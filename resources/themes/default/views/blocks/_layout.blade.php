@php
    $backgrounds = [
        'white' => 'bg-white',
        'gray' => 'bg-gray-100',
        'blue' => 'bg-blue-50',
        'green' => 'bg-green-50',
        'dark' => 'bg-gray-900 text-white',
    ];

    $paddings = [
        'sm' => 'py-4',
        'md' => 'py-8',
        'lg' => 'py-12',
        'xl' => 'py-16',
    ];

    $background = $backgrounds[$data['background_color'] ?? 'white'] ?? $backgrounds['white'];
    $paddingTop = $paddings[$data['padding_top'] ?? 'md'] ?? $paddings['md'];
    $paddingBottom = $paddings[$data['padding_bottom'] ?? 'md'] ?? $paddings['md'];
    $useContainer = ($data['container'] ?? true);
@endphp

<section class="{{ $background }} {{ $paddingTop }} {{ $paddingBottom }}">
    @if ($useContainer)
        <div class="mx-auto max-w-6xl px-4 sm:px-6">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</section>
