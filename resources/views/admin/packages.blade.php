@extends('layouts.admin')

@section('page_title', 'Packages')
@section('page_subtitle', 'Log new packages and review the full package list.')

@section('content')
    <div class="stack">
        <section class="panel" id="add-package">
            <div class="panel-head">
                <div>
                    <h2>Log Incoming Package</h2>
                    <p>Add a new package and assign it to a client.</p>
                </div>
            </div>
            <form method="post" action="{{ route('admin.packages.store') }}" class="form form-inline">
                @csrf

                <div class="fi-client">
                    <label>Client</label>
                    <div class="combobox" data-combobox>
                        <div class="combobox-input-wrap">
                            <svg class="cb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                            <input type="text" data-combobox-search placeholder="Search by name, email, or suite..." autocomplete="off" />
                            <button type="button" class="combobox-clear" data-combobox-clear aria-label="Clear">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <input type="hidden" name="client_id" data-combobox-value value="{{ old('client_id', $preselectClientId ?? '') }}" required />
                        <div class="combobox-list" data-combobox-list>
                            @foreach($clients as $client)
                                <div class="combobox-item"
                                     data-combobox-item
                                     data-value="{{ $client->id }}"
                                     data-label="{{ $client->name }} ({{ $client->suite_number }})"
                                     data-search="{{ strtolower($client->name.' '.$client->email.' '.$client->suite_number) }}">
                                    <span>{{ $client->name }}</span>
                                    <span class="cb-meta">{{ $client->suite_number }} · {{ $client->email }}</span>
                                </div>
                            @endforeach
                            <div class="combobox-empty" data-combobox-empty hidden>No clients match your search.</div>
                        </div>
                    </div>
                </div>

                <div class="fi-tracking">
                    <label>Tracking Number</label>
                    <input name="tracking_number" placeholder="e.g. S2A-PKG-0042" value="{{ old('tracking_number') }}" required />
                </div>

                <div class="fi-dim">
                    <label>Width <span class="fi-unit">cm</span></label>
                    <input name="width" type="number" step="0.01" value="{{ old('width') }}" required />
                </div>
                <div class="fi-dim">
                    <label>Height <span class="fi-unit">cm</span></label>
                    <input name="height" type="number" step="0.01" value="{{ old('height') }}" required />
                </div>
                <div class="fi-dim">
                    <label>Length <span class="fi-unit">cm</span></label>
                    <input name="length" type="number" step="0.01" value="{{ old('length') }}" required />
                </div>
                <div class="fi-dim">
                    <label>Weight <span class="fi-unit">kg</span></label>
                    <input name="weight" type="number" step="0.01" value="{{ old('weight') }}" required />
                </div>

                <div class="fi-desc">
                    <label>Contents Description</label>
                    <textarea name="contents_description" placeholder="Describe the contents..." required>{{ old('contents_description') }}</textarea>
                </div>

                <div class="fi-submit">
                    <button class="btn btn-primary" type="submit">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Save Package
                    </button>
                </div>
            </form>
        </section>

        <section class="panel">
            <div class="panel-head">
                <div>
                    <h2>All Packages</h2>
                    <p>Search by tracking, client, or description.</p>
                </div>
                <span class="admin-pill">{{ $packages->total() }} total</span>
            </div>

            <form method="get" action="{{ route('admin.packages') }}" class="admin-toolbar">
                <div class="search-field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                    <input type="search" name="q" value="{{ $search }}" placeholder="Search tracking, client, suite, or contents..." />
                </div>
                <select name="status" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach(\App\Models\Package::labels() as $code => $label)
                        <option value="{{ $code }}" {{ $statusFilter === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div style="display:flex; gap:8px; align-items:center;">
                    <button class="btn btn-primary btn-sm" type="submit">Search</button>
                    @if($search !== '' || $statusFilter)
                        <a class="clear-link" href="{{ route('admin.packages') }}">Clear</a>
                    @endif
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td><strong>{{ $package->tracking_number }}</strong></td>
                                <td>
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-weight:600;">{{ $package->client->name }}</span>
                                        <span style="font-size:0.78rem; color:var(--muted);">{{ $package->client->suite_number }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                                        {{ $package->status_label }}
                                    </span>
                                </td>
                                <td>{{ optional($package->received_at)->format('M d, Y') }}</td>
                                <td>
                                    <a class="btn btn-light btn-sm" href="{{ route('admin.packages.show', $package) }}">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty">No packages match your filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('partials.pagination-bar', ['paginator' => $packages, 'perPage' => $perPage])
        </section>
    </div>
@endsection

