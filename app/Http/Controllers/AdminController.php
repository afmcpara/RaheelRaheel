<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\ShipRequest;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $statusCounts = Package::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'clientsCount' => User::where('role', 'client')->count(),
            'totalPackages' => Package::count(),
            'statusCounts' => $statusCounts,
            'pendingInvoiceReviews' => Invoice::where('review_status', 'pending')->count(),
            'shippedCount' => $statusCounts[Package::STATUS_SHIPPED] ?? 0,
            'recentPackages' => Package::with('client')->latest()->limit(6)->get(),
            'pendingInvoices' => Invoice::with('package.client')
                ->where('review_status', 'pending')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }

    public function packages(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status');
        $perPage = $this->resolvePerPage($request);

        $packages = Package::with('client')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tracking_number', 'like', "%{$search}%")
                        ->orWhere('contents_description', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('suite_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.packages', [
            'packages' => $packages,
            'clients' => User::where('role', 'client')->orderBy('name')->get(['id', 'name', 'email', 'suite_number']),
            'search' => $search,
            'statusFilter' => $statusFilter,
            'preselectClientId' => $request->query('client_id'),
            'perPage' => $perPage,
        ]);
    }

    protected function resolvePerPage(Request $request, int $default = 10): int
    {
        $allowed = [10, 25, 50, 100];
        $value = (int) $request->query('per_page', $default);
        return in_array($value, $allowed, true) ? $value : $default;
    }

    public function storePackage(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:users,id'],
            'tracking_number' => ['required', 'string', 'max:255', 'unique:packages,tracking_number'],
            'width' => ['required', 'numeric', 'min:0.1'],
            'height' => ['required', 'numeric', 'min:0.1'],
            'length' => ['required', 'numeric', 'min:0.1'],
            'weight' => ['required', 'numeric', 'min:0.1'],
            'contents_description' => ['required', 'string', 'max:1000'],
        ]);

        $package = Package::create($data + [
            'status' => Package::STATUS_READY_TO_SEND,
            'received_at' => now(),
        ]);

        $package->statusHistory()->create([
            'old_status' => null,
            'new_status' => Package::STATUS_READY_TO_SEND,
            'changed_by' => $request->user()->id,
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Package logged successfully.');
    }

    public function invoiceQueue(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $invoices = Invoice::with(['package.client'])
            ->where('review_status', 'pending')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('package', function ($q) use ($search) {
                    $q->where('tracking_number', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%")
                                ->orWhere('suite_number', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($this->resolvePerPage($request))
            ->withQueryString();

        return view('admin.invoice-queue', [
            'invoices' => $invoices,
            'search' => $search,
            'perPage' => $invoices->perPage(),
        ]);
    }

    public function reviewInvoice(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'decision' => ['required', 'in:approve,needs_review'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($data['decision'] === 'approve') {
            $invoice->update([
                'review_status' => 'approved',
                'admin_note' => null,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
            $invoice->package->transitionTo(Package::STATUS_INVOICE_APPROVED, $request->user()->id);
        } else {
            $invoice->update([
                'review_status' => 'needs_review',
                'admin_note' => $data['admin_note'] ?? 'Please upload a clearer invoice.',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
            $invoice->package->transitionTo(Package::STATUS_NEEDS_REVIEW, $request->user()->id);
        }

        return back()->with('success', 'Invoice review updated.');
    }

    public function shipRequests(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status');

        $shipRequests = ShipRequest::with(['client', 'packages'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('client', function ($c) use ($search) {
                        $c->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('suite_number', 'like', "%{$search}%");
                    })->orWhereHas('packages', function ($p) use ($search) {
                        $p->where('tracking_number', 'like', "%{$search}%");
                    });
                });
            })
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->latest()
            ->paginate($this->resolvePerPage($request))
            ->withQueryString();

        return view('admin.ship-requests', [
            'shipRequests' => $shipRequests,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'perPage' => $shipRequests->perPage(),
        ]);
    }

    public function processShipRequest(Request $request, ShipRequest $shipRequest)
    {
        if ($shipRequest->status === 'processed') {
            return back()->with('success', 'Ship request is already processed.');
        }

        $shipRequest->packages->each(function (Package $package) use ($request) {
            $package->transitionTo(Package::STATUS_SHIPPED, $request->user()->id);
        });

        $shipRequest->update([
            'status' => 'processed',
            'processed_by' => $request->user()->id,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Ship request processed and marked as shipped.');
    }

    public function clients(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $perPage = $this->resolvePerPage($request);

        $clients = User::where('role', 'client')
            ->withCount('packages')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('suite_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.clients', [
            'clients' => $clients,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function showClient(User $client)
    {
        abort_unless($client->role === 'client', 404);

        return view('admin.client-show', [
            'client' => $client,
            'packages' => Package::with('invoice')
                ->where('client_id', $client->id)
                ->latest()
                ->get(),
        ]);
    }

    public function showPackage(Package $package)
    {
        $package->load(['client', 'invoice', 'statusHistory.changedBy']);

        return view('admin.package-show', [
            'package' => $package,
        ]);
    }

    public function markPackage(Request $request, Package $package)
    {
        $data = $request->validate([
            'action' => ['required', 'in:ready_for_pickup,delivered'],
        ]);

        $target = $data['action'] === 'ready_for_pickup'
            ? Package::STATUS_READY_FOR_PICKUP
            : Package::STATUS_DELIVERED;

        $package->transitionTo($target, $request->user()->id);

        return back()->with('success', 'Package status updated to '.Package::labels()[$target].'.');
    }
}
