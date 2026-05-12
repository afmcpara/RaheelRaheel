@php
    use App\Models\Package;
@endphp
<ol class="history-timeline">
    @forelse($history->sortByDesc('changed_at') as $entry)
        <li>
            <span class="dot"></span>
            <div>
                <strong>{{ Package::labels()[$entry->new_status] ?? $entry->new_status }}</strong>
                <span class="meta">
                    @if($entry->old_status)
                        from {{ Package::labels()[$entry->old_status] ?? $entry->old_status }} ·
                    @endif
                    {{ $entry->changed_at->format('M d, Y - g:ia') }}
                    @if($entry->changedBy)
                        · by {{ $entry->changedBy->name }}
                    @endif
                </span>
            </div>
        </li>
    @empty
        <li class="empty">No history yet.</li>
    @endforelse
</ol>
