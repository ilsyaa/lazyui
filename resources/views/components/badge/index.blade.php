@props([
    'variant' => 'default',
])

@php
    $base = 'inline-flex items-center rounded-md px-2.5 py-1 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2';

    $variantClasses = [
        'default' => 'bg-cat-900 dark:bg-white text-white dark:text-cat-900',
        'destructive' => 'bg-red-600 dark:bg-red-700 text-white',
        'outline' => 'border border-cat-300 dark:border-cat-700',
        'secondary' => 'bg-cat-300 dark:bg-cat-700 text-cat-900 dark:text-white',
    ];
@endphp

<span
    {{
        $attributes->twMerge($base, $variantClasses[$variant] ?? $variantClasses['default'])
    }}
>
    {{ $slot }}
</span>
