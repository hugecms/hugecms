<x-filament::page>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($themes as $theme)
            <div class="fi-card relative flex flex-col overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="aspect-video w-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                    @if ($theme->preview)
                        <img src="{{ asset('themes/'.$theme->id.'/'.$theme->preview) }}" alt="{{ $theme->label }}" class="h-full w-full object-cover">
                    @else
                        <div class="text-center text-gray-400 dark:text-gray-500">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <p class="mt-2 text-sm">暂无预览</p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-1 flex-col p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $theme->label }}</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $theme->description ?? '无描述' }}</p>
                        </div>
                        @if ($theme->id === $activeId)
                            <span class="inline-flex items-center rounded-md bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400">
                                当前启用
                            </span>
                        @endif
                    </div>

                    <div class="mt-4 space-y-1 text-sm text-gray-500 dark:text-gray-400">
                        <p>版本：{{ $theme->version }}</p>
                        <p>作者：{{ $theme->author }}</p>
                        <p>
                            编译状态：
                            @if ($theme->isCompiled())
                                <span class="text-green-600 dark:text-green-400">已编译</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">未编译</span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-auto flex items-center gap-2 pt-4">
                        @if ($theme->isCompiled())
                            <x-filament::button
                                wire:click="activate('{{ $theme->id }}')"
                                :disabled="$theme->id === $activeId"
                                size="sm"
                            >
                                {{ $theme->id === $activeId ? '已启用' : '启用' }}
                            </x-filament::button>
                        @endif

                        <a
                            href="{{ route('home', ['theme' => $theme->id]) }}"
                            target="_blank"
                            class="fi-link text-sm"
                        >
                            预览
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::page>
