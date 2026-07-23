<x-filament-widgets::widget class="fi-wi-stats-overview">
    <x-slot name="heading">
        {{ $this->heading ?? '快捷操作' }}
    </x-slot>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->getActions() as $action)
            <a
                href="{{ $action['url'] }}"
                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-{{ $action['color'] }} fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm ring-1 bg-white dark:bg-gray-900"
            >
                <x-icon :name="'heroicon-' . $action['icon']->value" class="h-5 w-5" />
                <span>{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
