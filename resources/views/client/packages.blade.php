@extends('layouts.client')

@section('page_title', 'My Packages')
@section('page_subtitle', 'Upload invoices, submit ship requests, and track every package.')

@section('content')
    @php
        $needsActionCount = ($allCounts[\App\Models\Package::STATUS_READY_TO_SEND] ?? 0)
                          + ($allCounts[\App\Models\Package::STATUS_NEEDS_REVIEW] ?? 0);
        $inTransitCount = ($allCounts[\App\Models\Package::STATUS_SHIP_REQUESTED] ?? 0)
                        + ($allCounts[\App\Models\Package::STATUS_SHIPPED] ?? 0)
                        + ($allCounts[\App\Models\Package::STATUS_READY_FOR_PICKUP] ?? 0);

        $filters = [
            ['key' => null,                                          'label' => 'All',            'count' => $totalCount],
            ['key' => \App\Models\Package::STATUS_READY_TO_SEND,     'label' => 'Action needed',  'count' => $needsActionCount, 'urgent' => true],
            ['key' => \App\Models\Package::STATUS_PENDING_INVOICE_REVIEW, 'label' => 'In review', 'count' => $allCounts[\App\Models\Package::STATUS_PENDING_INVOICE_REVIEW] ?? 0],
            ['key' => \App\Models\Package::STATUS_INVOICE_APPROVED,  'label' => 'Ready to ship',  'count' => $allCounts[\App\Models\Package::STATUS_INVOICE_APPROVED] ?? 0],
            ['key' => \App\Models\Package::STATUS_SHIP_REQUESTED,    'label' => 'In transit',     'count' => $inTransitCount],
            ['key' => \App\Models\Package::STATUS_DELIVERED,         'label' => 'Delivered',      'count' => $allCounts[\App\Models\Package::STATUS_DELIVERED] ?? 0],
        ];
    @endphp

    @if($approvedPackages->isNotEmpty())
        <section class="panel ship-panel" id="ship-request">
            <div class="panel-head">
                <div>
                    <h2>Create a Ship Request</h2>
                    <p>Select one or more approved packages to ship to Aruba.</p>
                </div>
                <span class="admin-pill accent-pill">{{ $approvedPackages->count() }} ready</span>
            </div>

            <form method="post" action="{{ route('client.ship-requests.store') }}" class="ship-form" data-ship-form>
                @csrf
                <div class="ship-toolbar">
                    <label class="ship-select-all">
                        <input type="checkbox" data-ship-select-all />
                        <span>Select all</span>
                    </label>
                    <span class="ship-summary" data-ship-summary>
                        <span data-ship-count>0</span> selected ·
                        <strong data-ship-weight>0</strong> kg
                    </span>
                </div>

                <div class="ship-select-grid">
                    @foreach($approvedPackages as $package)
                        <label class="ship-option">
                            <input type="checkbox" name="package_ids[]"
                                   value="{{ $package->id }}"
                                   data-ship-checkbox
                                   data-weight="{{ $package->weight }}" />
                            <span class="ship-info">
                                <strong>{{ $package->tracking_number }}</strong>
                                <em>{{ \Illuminate\Support\Str::limit($package->contents_description, 64) }}</em>
                                <span class="ship-meta">{{ $package->weight }} kg · {{ $package->width }}×{{ $package->height }}×{{ $package->length }} cm</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <button class="btn btn-primary" type="submit" data-ship-submit disabled>
                    Submit Ship Request
                    <svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h12"/><path d="M11 5l5 5-5 5"/></svg>
                </button>
            </form>
        </section>
    @endif

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>My Packages</h2>
                <p>Search, filter, and manage every package in your account.</p>
            </div>
            <span class="admin-pill">{{ $packages->total() }} {{ \Illuminate\Support\Str::plural('result', $packages->total()) }}</span>
        </div>

        {{-- Filter chips --}}
        <div class="filter-chips">
            @foreach($filters as $filter)
                @php
                    $isActive = $statusFilter === $filter['key']
                        || ($filter['key'] === null && !$statusFilter);
                    $url = route('client.packages', array_filter([
                        'status' => $filter['key'],
                        'q' => $search ?: null,
                    ]));
                @endphp
                <a href="{{ $url }}"
                   class="filter-chip {{ $isActive ? 'is-active' : '' }} {{ ($filter['urgent'] ?? false) && $filter['count'] > 0 ? 'is-urgent' : '' }}">
                    {{ $filter['label'] }}
                    <span class="chip-count">{{ $filter['count'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search bar --}}
        <form method="get" action="{{ route('client.packages') }}" class="admin-toolbar">
            @if($statusFilter)
                <input type="hidden" name="status" value="{{ $statusFilter }}" />
            @endif
            <div class="search-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search tracking number or description..." />
            </div>
            <span></span>
            <div style="display:flex; gap:8px; align-items:center;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
                @if($search !== '' || $statusFilter)
                    <a class="clear-link" href="{{ route('client.packages') }}">Clear</a>
                @endif
            </div>
        </form>

        @if($packages->isEmpty())
            <div class="empty-state">
                <h2>No packages found</h2>
                <p>
                    @if($search !== '' || $statusFilter)
                        Try clearing your filters or searching for something else.
                    @else
                        When your packages arrive at the warehouse, they will appear here.
                    @endif
                </p>
            </div>
        @else
            <div class="package-list">
                @foreach($packages as $package)
                    @php
                        $canUpload = in_array($package->status, [
                            \App\Models\Package::STATUS_READY_TO_SEND,
                            \App\Models\Package::STATUS_NEEDS_REVIEW,
                        ], true);
                        $statusClass = str_replace('_', '-', $package->status);
                    @endphp
                    <article class="pkg-row pkg-status-{{ $statusClass }} {{ $canUpload ? 'has-action' : '' }}">
                        <div class="pkg-row-main">
                            <div class="pkg-id">
                                <a href="{{ route('client.packages.show', $package) }}" class="pkg-link">
                                    <strong>{{ $package->tracking_number }}</strong>
                                </a>
                                <span class="status-chip status-{{ $statusClass }}">{{ $package->status_label }}</span>
                            </div>
                            <p class="pkg-desc">{{ $package->contents_description }}</p>
                            <div class="pkg-meta-row">
                                <span class="pkg-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                    {{ $package->width }} × {{ $package->height }} × {{ $package->length }} cm
                                </span>
                                <span class="pkg-meta-item">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h12l-1 14H7L6 6z"/><path d="M9 6V4a3 3 0 0 1 6 0v2"/></svg>
                                    {{ $package->weight }} kg
                                </span>
                                @if($package->received_at)
                                    <span class="pkg-meta-item">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 14"/></svg>
                                        {{ $package->received_at->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($canUpload)
                            <div class="pkg-row-action">
                                @if($package->invoice?->admin_note)
                                    <div class="needs-review-note">
                                        <strong>Admin note:</strong> {{ $package->invoice->admin_note }}
                                    </div>
                                @endif
                                <form method="post" action="{{ route('client.packages.invoice', $package) }}" enctype="multipart/form-data" class="upload-form upload-form-slim" data-upload-form>
                                    @csrf
                                    <label class="upload-zone upload-zone-slim" data-upload-zone>
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        <span data-upload-label>Click to upload invoice (PDF / JPG / PNG, max 2 MB)</span>
                                        <input type="file" name="invoice_file" accept=".pdf,.jpg,.jpeg,.png" required data-upload-input />
                                    </label>
                                    <div class="upload-progress" data-upload-progress hidden>
                                        <div class="bar"><span data-upload-bar></span></div>
                                        <span class="pct" data-upload-pct>0%</span>
                                    </div>
                                    <p class="upload-msg" data-upload-msg hidden></p>
                                    <button class="btn btn-primary btn-sm" type="submit" data-upload-submit>Upload Invoice</button>
                                </form>
                            </div>
                        @else
                            <div class="pkg-row-action pkg-row-action-passive">
                                <a class="btn btn-light btn-sm" href="{{ route('client.packages.show', $package) }}">
                                    View details
                                </a>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            @include('partials.pagination-bar', ['paginator' => $packages, 'perPage' => $perPage])
        @endif
    </section>
@endsection
