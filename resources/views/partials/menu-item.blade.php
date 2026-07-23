@if (empty($item['children']))
    <a href="{{ $item['url'] }}" target="{{ $item['target'] }}"
       class="hover:text-gray-600 dark:hover:text-gray-300 {{ $item['is_current'] ? 'text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
        {{ $item['title'] }}
    </a>
@else
    <div class="relative group">
        <a href="{{ $item['url'] ?: '#' }}" target="{{ $item['target'] }}"
           class="hover:text-gray-600 dark:hover:text-gray-300 inline-flex items-center gap-1 {{ $item['is_current'] ? 'text-indigo-600 dark:text-indigo-400 font-medium' : '' }}">
            {{ $item['title'] }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </a>
        <div class="absolute left-0 top-full hidden group-hover:block bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-md shadow-lg min-w-[160px] py-1 z-50">
            @foreach ($item['children'] as $child)
                <div class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                    @include('partials.menu-item', ['item' => $child])
                </div>
            @endforeach
        </div>
    </div>
@endif
