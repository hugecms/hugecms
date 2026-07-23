<x-filament-panels::page>
    <div class="flex gap-2">
        @foreach (\App\Filament\Admin\Pages\RecycleBin::TABS as $tab => $config)
            @if (\App\Filament\Admin\Pages\RecycleBin::canViewTab($tab))
                <x-filament::button
                    wire:click="setTab('{{ $tab }}')"
                    :color="$activeTab === $tab ? 'primary' : 'gray'"
                >
                    {{ $config['label'] }}
                </x-filament::button>
            @endif
        @endforeach
    </div>

    {{ $this->table }}
</x-filament-panels::page>
