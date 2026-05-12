<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\ShipRequest;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function dashboard(Request $request)
    {
        $packages = Package::where('client_id', $request->user()->id)->get();

        return view('client.dashboard', [
            'counts' => $packages->groupBy('status')->map->count(),
            'totalPackages' => $packages->count(),
            'recentPackages' => Package::with('invoice')
                ->where('client_id', $request->user()->id)
                ->latest()
                ->limit(5)
                ->get(),
            'needsAction' => $packages->whereIn('status', [
                Package::STATUS_READY_TO_SEND,
                Package::STATUS_NEEDS_REVIEW,
            ])->count(),
            'readyToShip' => $packages->where('status', Package::STATUS_INVOICE_APPROVED)->count(),
        ]);
    }

    public function packages(Request $request)
    {
        $clientId = $request->user()->id;
        $search = trim((string) $request->query('q', ''));
        $statusFilter = $request->query('status');
        $allowedPerPage = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $allCounts = Package::where('client_id', $clientId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $packages = Package::with('invoice')
            ->where('client_id', $clientId)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('tracking_number', 'like', "%{$search}%")
                        ->orWhere('contents_description', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->orderByRaw("FIELD(status, 'needs_review', 'ready_to_send', 'pending_invoice_review', 'invoice_approved', 'ship_requested', 'shipped', 'ready_for_pickup', 'delivered')")
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $approvedPackages = Package::with('invoice')
            ->where('client_id', $clientId)
            ->where('status', Package::STATUS_INVOICE_APPROVED)
            ->latest()
            ->get();

        return view('client.packages', [
            'packages' => $packages,
            'approvedPackages' => $approvedPackages,
            'totalCount' => (int) $allCounts->sum(),
            'allCounts' => $allCounts,
            'search' => $search,
            'statusFilter' => $statusFilter,
            'perPage' => $perPage,
        ]);
    }

    public function uploadInvoice(Request $request, Package $package)
    {
        abort_unless($package->client_id === $request->user()->id, 403);

        if (! in_array($package->status, [Package::STATUS_READY_TO_SEND, Package::STATUS_NEEDS_REVIEW], true)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Invoice cannot be uploaded for this package status.'], 422);
            }
            abort(422, 'Invoice cannot be uploaded for this package status.');
        }

        $data = $request->validate([
            'invoice_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $path = $data['invoice_file']->store('invoices', 'public');

        Invoice::updateOrCreate(
            ['package_id' => $package->id],
            [
                'file_path' => $path,
                'review_status' => 'pending',
                'admin_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        $package->transitionTo(Package::STATUS_PENDING_INVOICE_REVIEW, $request->user()->id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Invoice uploaded successfully.',
                'redirect' => route('client.packages'),
            ]);
        }

        return back()->with('success', 'Invoice uploaded successfully.');
    }

    public function createShipRequest(Request $request)
    {
        $clientId = $request->user()->id;
        $packageIds = $request->validate([
            'package_ids' => ['required', 'array', 'min:1'],
            'package_ids.*' => ['required', 'integer', 'exists:packages,id'],
        ])['package_ids'];

        $packages = Package::where('client_id', $clientId)
            ->whereIn('id', $packageIds)
            ->get();

        if ($packages->count() !== count($packageIds)) {
            abort(403, 'Invalid package selection.');
        }

        if ($packages->contains(fn (Package $package) => $package->status !== Package::STATUS_INVOICE_APPROVED)) {
            abort(422, 'Only invoice-approved packages can be ship requested.');
        }

        DB::transaction(function () use ($clientId, $packages, $request) {
            $shipRequest = ShipRequest::create([
                'client_id' => $clientId,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);

            $shipRequest->packages()->attach($packages->pluck('id'));
            foreach ($packages as $package) {
                $package->transitionTo(Package::STATUS_SHIP_REQUESTED, $request->user()->id);
            }
        });

        return back()->with('success', 'Ship request submitted.');
    }

    public function shipmentStatus(Request $request)
    {
        return view('client.shipments', [
            'packages' => Package::where('client_id', $request->user()->id)
                ->whereIn('status', [
                    Package::STATUS_SHIP_REQUESTED,
                    Package::STATUS_SHIPPED,
                    Package::STATUS_READY_FOR_PICKUP,
                    Package::STATUS_DELIVERED,
                ])
                ->latest()
                ->get(),
        ]);
    }

    public function showPackage(Request $request, Package $package)
    {
        abort_unless($package->client_id === $request->user()->id, 403);

        $package->load(['invoice', 'statusHistory.changedBy']);

        return view('client.package-show', [
            'package' => $package,
        ]);
    }
}
