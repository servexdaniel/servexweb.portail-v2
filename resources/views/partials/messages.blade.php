@php
    // Configuration des types de messages flash (équivalent du {% set %} en Twig)
    $flashTypes = [
        'success' => [
            'bg' => 'green-500',
            'border' => 'green-700',
            'icon' => 'green-500',
            'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'error' => [
            'bg' => 'red-500',
            'border' => 'red-700',
            'icon' => 'red-500',
            'path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'warning' => [
            'bg' => 'yellow-500',
            'border' => 'yellow-700',
            'icon' => 'yellow-500',
            'path' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        'info' => [
            'bg' => 'blue-500',
            'border' => 'blue-700',
            'icon' => 'blue-500',
            'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
    ];
@endphp

{{-- Boucle sur les types de messages flash (équivalent de {% for type, config in flash_types %}) --}}
@foreach ($flashTypes as $type => $config)
    @if (session()->has($type))
        <div
            class="w-full flex items-center bg-{{ $config['bg'] }} border-l-8 border-{{ $config['border'] }} py-2 px-3 shadow-md mb-2 rounded-lg">
            <div class="text-{{ $config['icon'] }} rounded-full bg-white mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['path'] }}" />
                </svg>
            </div>
            <div class="text-white max-w-xs">
                {{ session($type) }}
            </div>
        </div>
    @endif
@endforeach

{{-- Erreurs de validation Laravel (équivalent de {% if errors.any() %}) --}}
@if ($errors->any())
    <div class="w-full flex items-center bg-red-500 border-l-8 border-red-700 py-2 px-3 shadow-md mb-2 rounded-lg">
        <div class="text-red-500 rounded-full bg-white mr-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-white">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
