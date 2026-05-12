@extends('layouts.admin')

@section('page_title', 'Clients')
@section('page_subtitle', 'All registered clients and their package counts.')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>All Clients</h2>
                <p>Search and manage every client account.</p>
            </div>
            <span class="admin-pill">{{ $clients->total() }} {{ \Illuminate\Support\Str::plural('client', $clients->total()) }}</span>
        </div>

        <form method="get" action="{{ route('admin.clients') }}" class="admin-toolbar">
            <div class="search-field">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search by name, email, or suite number..." />
            </div>
            <span></span>
            <div style="display:flex; gap:8px; align-items:center;">
                <button class="btn btn-primary btn-sm" type="submit">Search</button>
                @if($search !== '')
                    <a class="clear-link" href="{{ route('admin.clients') }}">Clear</a>
                @endif
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Suite</th>
                        <th>Packages</th>
                        <th>Joined</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td><strong>{{ $client->name }}</strong></td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->suite_number ?? '—' }}</td>
                            <td>{{ $client->packages_count }}</td>
                            <td>{{ $client->created_at->format('M d, Y') }}</td>
                            <td>
                                <div style="display:flex; gap:6px; justify-content:flex-end; flex-wrap:wrap;">
                                    <a class="btn btn-primary btn-sm"
                                       href="{{ route('admin.packages', ['client_id' => $client->id]) }}#add-package"
                                       title="Log a new package for this client">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Add Package
                                    </a>
                                    <a class="btn btn-light btn-sm" href="{{ route('admin.clients.show', $client) }}">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No clients match your search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.pagination-bar', ['paginator' => $clients, 'perPage' => $perPage])
    </section>
@endsection
