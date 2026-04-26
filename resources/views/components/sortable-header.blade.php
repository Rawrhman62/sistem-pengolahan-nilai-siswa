@props(['column', 'label', 'currentSort' => null, 'currentDirection' => 'asc'])

@php
    $isSorted = $currentSort === $column;
    $newDirection = $isSorted && $currentDirection === 'asc' ? 'desc' : 'asc';
    
    // Build URL with sort parameters while preserving existing query params
    $queryParams = request()->except(['sort', 'direction']);
    $queryParams['sort'] = $column;
    $queryParams['direction'] = $newDirection;
    $sortUrl = request()->url() . '?' . http_build_query($queryParams);
@endphp

<th style="cursor: pointer; user-select: none;" onclick="window.location.href='{{ $sortUrl }}'">
    <div style="display: flex; align-items: center; gap: 6px;">
        <span>{{ $label }}</span>
        @if($isSorted)
            @if($currentDirection === 'asc')
                <span style="font-size: 10px; opacity: 0.7;">▲</span>
            @else
                <span style="font-size: 10px; opacity: 0.7;">▼</span>
            @endif
        @else
            <span style="font-size: 10px; opacity: 0.3;">⇅</span>
        @endif
    </div>
</th>
