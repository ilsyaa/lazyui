@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'default',
    'size' => 'default',
])

@php
    $baseClasses = 'relative inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50';

    $variantClasses = [
        'default' => 'bg-cat-900 dark:bg-white text-white dark:text-cat-900 shadow hover:bg-cat-800/90 dark:hover:bg-white/90',
        'destructive' => 'bg-red-600 dark:bg-red-700 text-white shadow-sm hover:bg-red-700 dark:hover:bg-red-800',
        'outline' => 'border shadow-xs border-cat-300 dark:border-cat-700 shadow-sm',
        'secondary' => 'bg-cat-300 dark:bg-cat-700 text-cat-900 dark:text-white shadow-sm',
        'ghost' => 'hover:bg-cat-300/20 dark:hover:bg-cat-700/20 text-cat-900 dark:text-white',
        'link' => 'text-cat-900 dark:text-white underline-offset-4 hover:underline',
    ];

    $sizeClasses = [
        'default' => 'h-9 px-4 py-2',
        'sm' => 'h-8 rounded-md px-3 text-xs',
        'lg' => 'h-10 rounded-md px-8',
        'icon' => 'h-9 w-9',
    ];

    $classes = [
        $baseClasses,
        $variantClasses[$variant] ?? $variantClasses['default'],
        $sizeClasses[$size] ?? $sizeClasses['default'],
    ];
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->twMerge(['class' => $classes]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->twMerge(['class' => $classes]) }}
    >
        {{ $slot }}
    </button>
@endif
