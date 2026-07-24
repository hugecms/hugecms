@php
    $hasChildren = ! empty($item['children']);
    $isActive = request()->url() === $item['url'];
@endphp

@if ($hasChildren)
    <div class="nav-dropdown" x-data="{ open: false }">
        <button type="button"
                class="nav-dropdown-toggle {{ $isActive ? 'nav-item-active' : '' }}">
            <span>{{ $item['title'] }}</span>
            <svg class="nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="nav-sub-menu">
            @foreach ($item['children'] as $child)
                <a href="{{ $child['url'] }}"
                   target="{{ $child['target'] }}"
                   class="nav-sub-item">
                    {{ $child['title'] }}
                </a>
            @endforeach
        </div>
    </div>
@else
    <a href="{{ $item['url'] }}"
       target="{{ $item['target'] }}"
       class="nav-item {{ $isActive ? 'nav-item-active' : '' }}">
        {{ $item['title'] }}
    </a>
@endif
