@props(['paginator', 'perPage' => 10])
@php
    $queryParams = request()->query();
    unset($queryParams['per_page'], $queryParams['page']);
@endphp

<div class="pager-bar">
    <div class="pager-bar-left">
        <span class="pager-info">
            @if($paginator->total() === 0)
                No results
            @else
                Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
                of <strong>{{ $paginator->total() }}</strong>
            @endif
        </span>
        <form method="get" action="{{ url()->current() }}" class="per-page-form">
            @foreach($queryParams as $k => $v)
                @if(is_array($v))
                    @foreach($v as $vv)<input type="hidden" name="{{ $k }}[]" value="{{ $vv }}" />@endforeach
                @else
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                @endif
            @endforeach
            <label for="per-page-select">Per page</label>
            <select name="per_page" id="per-page-select" onchange="this.form.submit()">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if($paginator->hasPages())
        {!! $paginator->onEachSide(1)->links('partials.pagination') !!}
    @endif
</div>
