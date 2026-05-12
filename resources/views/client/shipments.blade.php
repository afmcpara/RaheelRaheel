@extends('layouts.client')

@section('page_title', 'Shipment Status')
@section('page_subtitle', 'Track packages that have been requested for shipping or already shipped.')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <h2>Active Shipments</h2>
            <p>Packages currently on their way or being processed for shipment.</p>
        </div>

        @if($packages->isEmpty())
            <div class="empty-state">
                <h2>No active shipments</h2>
                <p>You haven't submitted any ship requests yet. Visit "My Packages" to start one.</p>
            </div>
        @else
            <div class="shipment-grid">
                @php
                    $order = [
                        \App\Models\Package::STATUS_INVOICE_APPROVED => 1,
                        \App\Models\Package::STATUS_SHIP_REQUESTED => 2,
                        \App\Models\Package::STATUS_SHIPPED => 3,
                        \App\Models\Package::STATUS_READY_FOR_PICKUP => 4,
                        \App\Models\Package::STATUS_DELIVERED => 5,
                    ];
                @endphp
                @foreach($packages as $package)
                    @php $rank = $order[$package->status] ?? 0; @endphp
                    <article class="package-card">
                        <header>
                            <div>
                                <h3><a href="{{ route('client.packages.show', $package) }}">{{ $package->tracking_number }}</a></h3>
                                <p class="meta">{{ \Illuminate\Support\Str::limit($package->contents_description, 80) }}</p>
                            </div>
                            <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                                {{ $package->status_label }}
                            </span>
                        </header>
                        <div class="timeline">
                            <div class="tl-step done">
                                <span class="dot"></span>
                                <span>Ship Requested</span>
                            </div>
                            <div class="tl-step {{ $rank >= 3 ? 'done' : '' }}">
                                <span class="dot"></span>
                                <span>Shipped</span>
                            </div>
                            <div class="tl-step {{ $rank >= 4 ? 'done' : '' }}">
                                <span class="dot"></span>
                                <span>Ready for Pickup</span>
                            </div>
                            <div class="tl-step {{ $rank >= 5 ? 'done' : '' }}">
                                <span class="dot"></span>
                                <span>Delivered</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
