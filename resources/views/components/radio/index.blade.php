@props([
    'variant' => 'default',
])

@php
    $variants = [
        'default' => 'checked:border-cat-800 dark:checked:border-white checked:hover:border-cat-800 dark:checked:hover:border-white checked:focus:border-cat-800 dark:checked:focus:border-white hover:before:bg-cat-800/10 dark:hover:before:bg-cat-500/5 before:focus-visible:bg-cat-800/10 dark:before:focus-visible:bg-cat-500/5 after:bg-cat-800 dark:after:bg-white',
        'accent' => 'checked:border-accent-500 checked:hover:border-accent-500 checked:focus:border-accent-500 hover:before:bg-accent-500/10 before:focus-visible:bg-accent-500/10 after:bg-accent-500',
        'custom' => '',
    ];
@endphp

<input
    type="radio"
    {{
        $attributes->twMerge([
                'relative appearance-none aspect-square h-4 w-4 rounded-full',
                'border border-cat-500 bg-transparent !ring-offset-transparent focus:ring-transparent',
                'disabled:cursor-not-allowed disabled:opacity-50',
                'before:absolute before:left-1/2 before:top-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:size-6 disabled:before:bg-transparent',
                'after:absolute after:left-1/2 after:top-1/2 after:-translate-x-1/2 after:-translate-y-1/2 after:size-2.5 after:rounded-full after:opacity-0 after:transition-opacity after:duration-200',
                'checked:after:opacity-100 disabled:after:!bg-cat-500/50 checked:bg-[image:none] checked:text-transparent disabled:checked:hover:border-cat-500/50',
                $variants[$variant] ?? $variants['default']
            ])
    }}
/>
