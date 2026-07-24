@php
    $hasChildren = ! empty($item['children']);
    $isActive = request()->url() === $item['url'];
@endphp

@if ($hasChildren)
    <div class="nav-item" x-data="{ open: false }">
        <button type="button"
                class="nav-link nav-dropdown-btn {{ $isActive ? 'active' : '' }}">
            <span>{{ $item['title'] }}</span>
            <svg class="icon-sm nav-caret" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div class="nav-dropdown">
            <div class="nav-dropdown-panel">
                @foreach ($item['children'] as $child)
                    <a href="{{ $child['url'] }}"
                       target="{{ $child['target'] }}"
                       class="nav-dropdown-link">
                        {{ $child['title'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@else
    <a href="{{ $item['url'] }}"
       target="{{ $item['target'] }}"
       class="nav-link {{ $isActive ? 'active' : '' }}">
        {{ $item['title'] }}
    </a>
@endif
