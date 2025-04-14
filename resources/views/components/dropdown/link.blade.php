@props([
    'disabled' => false,
    'inset' => false,
])

<li>
    <a
        role="menuitem"
        aria-disabled="{{ $disabled ? 'true' : 'false' }}"
        x-dropdown-menu:item
        tabindex="-1"
        {{ $attributes->when($disabled, function ($attributes) {
                return $attributes->except(['x-on:click', '@click', 'wire:click']);
            })->twMerge([
                'hover:bg-cat-200 dark:hover:bg-cat-750 focus:bg-cat-200 focus:dark:bg-cat-750',
                'relative flex w-full cursor-pointer select-none items-center',
                'rounded-lg px-2 py-1.5 text-sm outline-none transition-colors',
                'opacity-50 cursor-not-allowed' => $disabled,
                'pl-8' => $inset,
            ]) }}
    >
        {{ $slot }}
    </a>
</li>
