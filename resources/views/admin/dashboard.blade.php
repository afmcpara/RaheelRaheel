@extends('layouts.admin')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Live overview of packages, invoices, and shipments.')

@section('content')
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon ink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7L12 12l8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div>
                <p class="kpi-label">Total Packages</p>
                <h3>{{ $totalPackages }}</h3>
                <span class="kpi-foot">Across all clients</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
                <p class="kpi-label">Pending Reviews</p>
                <h3>{{ $pendingInvoiceReviews }}</h3>
                <span class="kpi-foot">Invoices awaiting action</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            </div>
            <div>
                <p class="kpi-label">Shipped</p>
                <h3>{{ $shippedCount }}</h3>
                <span class="kpi-foot">Successfully shipped</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon plum">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1A4 4 0 0 1 16 11"/></svg>
            </div>
            <div>
                <p class="kpi-label">Clients</p>
                <h3>{{ $clientsCount }}</h3>
                <span class="kpi-foot">Active accounts</span>
            </div>
        </div>
    </div>

    <div class="admin-row">
        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Recent Packages</h2>
                    <p>Latest packages logged into the system.</p>
                </div>
                <a href="{{ route('admin.packages') }}" class="btn btn-light">View all</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPackages as $package)
                            <tr>
                                <td><strong><a href="{{ route('admin.packages.show', $package) }}">{{ $package->tracking_number }}</a></strong></td>
                                <td>{{ $package->client->name }}</td>
                                <td>
                                    <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                                        {{ $package->status_label }}
                                    </span>
                                </td>
                                <td>{{ optional($package->received_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty">No packages logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Invoice Queue</h2>
                    <p>Pending invoices ready for your review.</p>
                </div>
                <a href="{{ route('admin.invoices') }}" class="btn btn-light">Open queue</a>
            </div>
            @forelse($pendingInvoices as $invoice)
                <div class="queue-item">
                    <div>
                        <strong>{{ $invoice->package->tracking_number }}</strong>
                        <span>{{ $invoice->package->client->name }}</span>
                    </div>
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.invoices') }}">Review</a>
                </div>
            @empty
                <div class="queue-item empty">All invoices are reviewed. Great job!</div>
            @endforelse
        </section>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Status Distribution</h2>
                <p>Live count of packages across every stage of the workflow.</p>
            </div>
            <span class="admin-pill">{{ $totalPackages }} total packages</span>
        </div>

        @php
            $totalForPct = max(1, $totalPackages);
            $statusIcons = [
                'ready_to_send' => '<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7L12 12l8.7-5"/><path d="M12 22V12"/>',
                'pending_invoice_review' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="14" x2="15" y2="14"/><line x1="9" y1="18" x2="13" y2="18"/>',
                'needs_review' => '<circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="13"/><circle cx="12" cy="16" r="0.6" fill="currentColor"/>',
                'invoice_approved' => '<polyline points="20 6 9 17 4 12"/>',
                'ship_requested' => '<path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/>',
                'shipped' => '<path d="M3 7h11v10H3z"/><path d="M14 10h4l3 3v4h-7"/><circle cx="7" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/>',
                'ready_for_pickup' => '<path d="M3 7h18v13H3z"/><path d="M3 7l9-5 9 5"/><path d="M12 12v4"/>',
                'delivered' => '<polyline points="20 6 9 17 4 12"/><circle cx="12" cy="12" r="10"/>',
            ];
            $statusColors = [
                'ready_to_send' => '#4f6fb5',
                'pending_invoice_review' => '#c79956',
                'needs_review' => '#c0454f',
                'invoice_approved' => '#2f7a5f',
                'ship_requested' => '#5b3a8e',
                'shipped' => '#0a1832',
                'ready_for_pickup' => '#2f6fd5',
                'delivered' => '#1f5e44',
            ];
            $chartLabels = [];
            $chartValues = [];
            $chartColors = [];
            foreach (\App\Models\Package::labels() as $statusCode => $label) {
                $chartLabels[] = $label;
                $chartValues[] = (int) ($statusCounts[$statusCode] ?? 0);
                $chartColors[] = $statusColors[$statusCode] ?? '#0a1832';
            }
            $chartPayload = json_encode([
                'labels' => $chartLabels,
                'values' => $chartValues,
                'colors' => $chartColors,
            ]);
        @endphp

        <div class="chart-wrap">
            <canvas data-status-chart='{!! e($chartPayload) !!}' aria-label="Packages per status"></canvas>
        </div>

        <div class="status-tile-grid" style="margin-top: 22px;">
            @foreach(\App\Models\Package::labels() as $statusCode => $label)
                @php
                    $count = $statusCounts[$statusCode] ?? 0;
                    $pct = $totalPackages > 0 ? round(($count / $totalForPct) * 100, 1) : 0;
                @endphp
                <article class="status-tile status-tile-{{ str_replace('_', '-', $statusCode) }}">
                    <div class="status-tile-head">
                        <span class="status-tile-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                {!! $statusIcons[$statusCode] ?? '<circle cx="12" cy="12" r="9"/>' !!}
                            </svg>
                        </span>
                        <span class="status-chip status-{{ str_replace('_', '-', $statusCode) }}">{{ $label }}</span>
                    </div>
                    <div class="status-tile-count">
                        <strong>{{ $count }}</strong>
                        <span>{{ $pct }}% of total</span>
                    </div>
                    <div class="status-tile-bar">
                        <span class="fill status-fill-{{ str_replace('_', '-', $statusCode) }}" style="width: {{ $pct }}%"></span>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
