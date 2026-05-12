@extends('layouts.admin')

@section('page_title', 'Package ' . $package->tracking_number)
@section('page_subtitle', 'Full details, invoice, and status history for this package.')

@section('content')
    <div class="admin-row">
        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Package Details</h2>
                    <p>Logged into the system on {{ optional($package->received_at)->format('M d, Y - g:ia') }}.</p>
                </div>
                <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                    {{ $package->status_label }}
                </span>
            </div>

            <div class="profile-grid">
                <div>
                    <span class="profile-label">Tracking Number</span>
                    <strong>{{ $package->tracking_number }}</strong>
                </div>
                <div>
                    <span class="profile-label">Client</span>
                    <strong>
                        <a href="{{ route('admin.clients.show', $package->client) }}">{{ $package->client->name }}</a>
                    </strong>
                </div>
                <div>
                    <span class="profile-label">Suite</span>
                    <strong>{{ $package->client->suite_number ?? '—' }}</strong>
                </div>
                <div>
                    <span class="profile-label">Weight</span>
                    <strong>{{ $package->weight }} kg</strong>
                </div>
                <div>
                    <span class="profile-label">Dimensions</span>
                    <strong>{{ $package->width }} × {{ $package->height }} × {{ $package->length }}</strong>
                </div>
                <div>
                    <span class="profile-label">Received</span>
                    <strong>{{ optional($package->received_at)->format('M d, Y') }}</strong>
                </div>
            </div>

            <div class="description-block">
                <span class="profile-label">Contents</span>
                <p>{{ $package->contents_description }}</p>
            </div>

            @if($package->invoice)
                <div class="description-block">
                    <span class="profile-label">Invoice</span>
                    <p>
                        Review status: <strong>{{ ucfirst(str_replace('_', ' ', $package->invoice->review_status)) }}</strong>
                        ·
                        <a href="{{ route('admin.invoice.file', $package->invoice) }}" target="_blank">Open invoice file</a>
                    </p>
                    @if($package->invoice->admin_note)
                        <div class="needs-review-note">
                            <strong>Admin note:</strong> {{ $package->invoice->admin_note }}
                        </div>
                    @endif
                </div>
            @endif

            @if($package->status === \App\Models\Package::STATUS_SHIPPED || $package->status === \App\Models\Package::STATUS_READY_FOR_PICKUP)
                <div class="description-block">
                    <span class="profile-label">Final Status Actions</span>
                    <div class="invoice-actions" style="grid-template-columns: auto auto; gap: 12px;">
                        @if($package->status === \App\Models\Package::STATUS_SHIPPED)
                            <form method="post" action="{{ route('admin.packages.mark', $package) }}">
                                @csrf
                                <input type="hidden" name="action" value="ready_for_pickup">
                                <button class="btn btn-primary" type="submit">Mark Ready for Pickup</button>
                            </form>
                        @endif
                        <form method="post" action="{{ route('admin.packages.mark', $package) }}">
                            @csrf
                            <input type="hidden" name="action" value="delivered">
                            <button class="btn btn-light" type="submit">Mark Delivered</button>
                        </form>
                    </div>
                </div>
            @endif
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Status History</h2>
                <p>Every change made to this package.</p>
            </div>
            @include('partials.status-history', ['history' => $package->statusHistory])
        </section>
    </div>
@endsection
