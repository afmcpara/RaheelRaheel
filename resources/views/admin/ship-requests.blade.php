@extends('layouts.admin')

@section('page_title', 'Ship Requests')
@section('page_subtitle', 'Process client ship requests and mark packages as shipped.')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>All Ship Requests</h2>
                <p>Search, filter, and process client shipment requests.</p>
            </div>
            <span class="admin-pill">{{ $shipRequests->total() }} total</span>
        </div>

        <form method="get" action="{{ route('admin.ship-requests') }}" class="admin-toolbar">
            <div class="search-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search by client, suite, or tracking number..." />
            </div>
            <select name="status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="processed" {{ $statusFilter === 'processed' ? 'selected' : '' }}>Processed</option>
            </select>
            <div style="display:flex; gap:8px; align-items:center;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
                @if($search !== '' || $statusFilter)
                    <a class="clear-link" href="{{ route('admin.ship-requests') }}">Clear</a>
                @endif
            </div>
        </form>

        @if($shipRequests->isEmpty())
            <div class="empty-state">
                <h2>No ship requests</h2>
                <p>Nothing matches your filters right now.</p>
            </div>
        @else
            <div class="queue-list">
                @foreach($shipRequests as $shipRequest)
                    <div class="queue-row ship-row">
                        <div class="queue-main">
                            <div class="queue-cell">
                                <strong>Request #{{ $shipRequest->id }}</strong>
                                <span>{{ $shipRequest->submitted_at->format('M d, Y · g:i A') }}</span>
                            </div>
                            <div class="queue-cell">
                                <strong>{{ $shipRequest->client->name }}</strong>
                                <span>{{ $shipRequest->client->suite_number }}</span>
                            </div>
                            <div class="queue-cell">
                                <strong>{{ $shipRequest->packages->count() }} {{ \Illuminate\Support\Str::plural('package', $shipRequest->packages->count()) }}</strong>
                                <div class="packages-line">
                                    @foreach($shipRequest->packages->take(3) as $package)
                                        <span class="tag">{{ $package->tracking_number }}</span>
                                    @endforeach
                                    @if($shipRequest->packages->count() > 3)
                                        <span class="tag">+{{ $shipRequest->packages->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="status-chip status-{{ $shipRequest->status === 'processed' ? 'shipped' : 'ship-requested' }}">
                                    {{ ucfirst($shipRequest->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="queue-actions">
                            @if($shipRequest->status === 'submitted')
                                <form method="post" action="{{ route('admin.ship-requests.process', $shipRequest) }}">
                                    @csrf
                                    <button class="btn btn-primary btn-sm" type="submit">Mark as Shipped</button>
                                </form>
                            @else
                                <span style="font-size:0.8rem; color:var(--muted); font-weight:600;">
                                    Processed {{ optional($shipRequest->processed_at)->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @include('partials.pagination-bar', ['paginator' => $shipRequests, 'perPage' => $perPage])
        @endif
    </section>
@endsection
