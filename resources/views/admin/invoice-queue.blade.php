@extends('layouts.admin')

@section('page_title', 'Invoice Queue')
@section('page_subtitle', 'Approve or flag uploaded invoices.')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Pending Invoices</h2>
                <p>Review uploaded invoices and approve or request a re-upload.</p>
            </div>
            <span class="admin-pill">{{ $invoices->total() }} pending</span>
        </div>

        <form method="get" action="{{ route('admin.invoices') }}" class="admin-toolbar">
            <div class="search-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search by tracking, client, or suite..." />
            </div>
            <span></span>
            <div style="display:flex; gap:8px; align-items:center;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
                @if($search !== '')
                    <a class="clear-link" href="{{ route('admin.invoices') }}">Clear</a>
                @endif
            </div>
        </form>

        @if($invoices->isEmpty())
            <div class="empty-state">
                <h2>Inbox zero</h2>
                <p>No invoices are pending review right now.</p>
            </div>
        @else
            <div class="queue-list">
                @foreach($invoices as $invoice)
                    <div class="queue-row" data-queue-row>
                        <div class="queue-main">
                            <div class="queue-cell">
                                <strong>
                                    <a href="{{ route('admin.packages.show', $invoice->package) }}" style="color:inherit; text-decoration:none;">
                                        {{ $invoice->package->tracking_number }}
                                    </a>
                                </strong>
                                <span>Tracking #</span>
                            </div>
                            <div class="queue-cell">
                                <strong>{{ $invoice->package->client->name }}</strong>
                                <span>{{ $invoice->package->client->suite_number }}</span>
                            </div>
                            <div class="queue-cell">
                                <strong>{{ $invoice->created_at->format('M d, Y') }}</strong>
                                <span>{{ $invoice->created_at->diffForHumans() }}</span>
                            </div>
                            <div>
                                <span class="status-chip status-pending-invoice-review">Pending review</span>
                            </div>
                        </div>

                        <div class="queue-actions">
                            <a href="{{ route('admin.invoice.file', $invoice) }}" target="_blank" class="btn btn-light btn-sm">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                Open
                            </a>
                            <form method="post" action="{{ route('admin.invoices.review', $invoice) }}">
                                @csrf
                                <input type="hidden" name="decision" value="approve">
                                <button class="btn btn-primary btn-sm" type="submit">Approve</button>
                            </form>
                            <button type="button" class="btn btn-light btn-sm" data-toggle-note>Flag</button>
                        </div>

                        <form method="post" action="{{ route('admin.invoices.review', $invoice) }}" class="note-form">
                            @csrf
                            <input type="hidden" name="decision" value="needs_review">
                            <textarea name="admin_note" placeholder="What does the client need to fix?" required></textarea>
                            <div class="note-actions">
                                <button type="button" class="btn btn-light btn-sm" data-toggle-note>Cancel</button>
                                <button class="btn btn-primary btn-sm" type="submit">Send back to client</button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            @include('partials.pagination-bar', ['paginator' => $invoices, 'perPage' => $perPage])
        @endif
    </section>
@endsection
