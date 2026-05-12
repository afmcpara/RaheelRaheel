@extends('layouts.client')

@section('page_title', 'Package ' . $package->tracking_number)
@section('page_subtitle', 'Full details and status history for this package.')

@section('content')
    <div class="admin-row">
        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>Package Details</h2>
                    <p>Received {{ optional($package->received_at)->format('M d, Y') }}.</p>
                </div>
                <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                    {{ $package->status_label }}
                </span>
            </div>

            <div class="profile-grid">
                <div>
                    <span class="profile-label">Tracking</span>
                    <strong>{{ $package->tracking_number }}</strong>
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

            @if(in_array($package->status, [\App\Models\Package::STATUS_READY_TO_SEND, \App\Models\Package::STATUS_NEEDS_REVIEW], true))
                @if($package->invoice?->admin_note)
                    <div class="needs-review-note">
                        <strong>Admin note:</strong> {{ $package->invoice->admin_note }}
                    </div>
                @endif
                <form method="post" action="{{ route('client.packages.invoice', $package) }}" enctype="multipart/form-data" class="upload-form" data-upload-form>
                    @csrf
                    <label class="upload-zone" data-upload-zone>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span data-upload-label>Click to upload invoice (PDF / JPG / PNG, max 2 MB)</span>
                        <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required data-upload-input />
                    </label>
                    <div class="upload-progress" data-upload-progress hidden>
                        <div class="bar"><span data-upload-bar></span></div>
                        <span class="pct" data-upload-pct>0%</span>
                    </div>
                    <p class="upload-msg" data-upload-msg hidden></p>
                    <button class="btn btn-primary" type="submit" data-upload-submit>Upload Invoice</button>
                </form>
            @endif

            @if($package->invoice && $package->invoice->review_status === 'approved')
                <div class="description-block">
                    <span class="profile-label">Invoice</span>
                    <p>Invoice approved on {{ optional($package->invoice->reviewed_at)->format('M d, Y') }}.</p>
                </div>
            @endif
        </section>

        <section class="panel">
            <div class="panel-head">
                <h2>Status History</h2>
                <p>Lifecycle updates for this package.</p>
            </div>
            @include('partials.status-history', ['history' => $package->statusHistory])
        </section>
    </div>

    <p style="margin-top: 18px;">
        <a class="btn btn-light btn-sm" href="{{ route('client.packages') }}">← Back to my packages</a>
    </p>
@endsection
