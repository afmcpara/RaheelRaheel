@extends('layouts.client')

@section('page_title', 'Welcome back, ' . explode(' ', auth()->user()->name)[0])
@section('page_subtitle', 'Here is the live status of your packages and shipments.')

@section('content')
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon ink">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="M3.3 7L12 12l8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div>
                <p class="kpi-label">Total Packages</p>
                <h3>{{ $totalPackages }}</h3>
                <span class="kpi-foot">In your account</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
            </div>
            <div>
                <p class="kpi-label">Needs Your Action</p>
                <h3>{{ $needsAction }}</h3>
                <span class="kpi-foot">Upload or re-upload invoice</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <p class="kpi-label">Ready to Ship</p>
                <h3>{{ $readyToShip }}</h3>
                <span class="kpi-foot">Invoice approved</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon plum">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            </div>
            <div>
                <p class="kpi-label">In Transit / Shipped</p>
                <h3>{{ ($counts[\App\Models\Package::STATUS_SHIP_REQUESTED] ?? 0) + ($counts[\App\Models\Package::STATUS_SHIPPED] ?? 0) }}</h3>
                <span class="kpi-foot">On the way to Aruba</span>
            </div>
        </div>
    </div>

    <div class="admin-row">
        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Recent Packages</h2>
                    <p>Your most recently received packages.</p>
                </div>
                <a class="btn btn-light" href="{{ route('client.packages') }}">View all</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Status</th>
                            <th>Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPackages as $package)
                            <tr>
                                <td><strong><a href="{{ route('client.packages.show', $package) }}">{{ $package->tracking_number }}</a></strong></td>
                                <td>
                                    <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                                        {{ $package->status_label }}
                                    </span>
                                </td>
                                <td>{{ optional($package->received_at)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">No packages yet. They will appear here when received.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Quick Actions</h2>
            </div>
            <div class="quick-actions">
                <a class="quick-action" href="{{ route('client.packages') }}">
                    <div class="qa-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <strong>Upload Invoices</strong>
                        <span>For packages ready to send</span>
                    </div>
                </a>
                <a class="quick-action" href="{{ route('client.packages') }}#ship-request">
                    <div class="qa-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </div>
                    <div>
                        <strong>Create Ship Request</strong>
                        <span>From approved packages</span>
                    </div>
                </a>
                <a class="quick-action" href="{{ route('client.shipments') }}">
                    <div class="qa-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    </div>
                    <div>
                        <strong>Track Shipments</strong>
                        <span>See real-time status</span>
                    </div>
                </a>
            </div>
        </section>
    </div>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Status Overview</h2>
                <p>How your packages are distributed across the workflow.</p>
            </div>
            <span class="admin-pill">{{ $totalPackages }} total</span>
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
                $chartValues[] = (int) ($counts[$statusCode] ?? 0);
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

        {{-- Status tiles --}}
        <div class="status-tile-grid" style="margin-top: 22px;">
            @foreach(\App\Models\Package::labels() as $statusCode => $label)
                @php
                    $count = $counts[$statusCode] ?? 0;
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
