@extends('layouts.admin')

@section('page_title', $client->name)
@section('page_subtitle', 'Client profile and complete package history.')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Profile</h2>
                <p>Account information and contact details.</p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a class="btn btn-primary btn-sm" href="{{ route('admin.packages', ['client_id' => $client->id]) }}#add-package">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Package
                </a>
                <a class="btn btn-light btn-sm" href="{{ route('admin.clients') }}">← Back to clients</a>
            </div>
        </div>

        <div class="profile-grid">
            <div>
                <span class="profile-label">Name</span>
                <strong>{{ $client->name }}</strong>
            </div>
            <div>
                <span class="profile-label">Email</span>
                <strong>{{ $client->email }}</strong>
            </div>
            <div>
                <span class="profile-label">Suite Number</span>
                <strong>{{ $client->suite_number ?? '—' }}</strong>
            </div>
            <div>
                <span class="profile-label">Member Since</span>
                <strong>{{ $client->created_at->format('M d, Y') }}</strong>
            </div>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Packages ({{ $packages->count() }})</h2>
                <p>Every package assigned to this client.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tracking</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td><strong>{{ $package->tracking_number }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($package->contents_description, 50) }}</td>
                            <td>
                                <span class="status-chip status-{{ str_replace('_', '-', $package->status) }}">
                                    {{ $package->status_label }}
                                </span>
                            </td>
                            <td>{{ optional($package->received_at)->format('M d, Y') }}</td>
                            <td>
                                <a class="btn btn-light btn-sm" href="{{ route('admin.packages.show', $package) }}">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">No packages for this client yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
