@props([
    'disabled' => false,
    'inset' => false,
])
<div
    role="menuitem"
    aria-haspopup="true"
    aria-disabled="{{ $disabled ? 'true' : 'false' }}"
    x-dropdown-menu:subbutton
    tabindex="-1"
    {{ $attributes->except(['x-on:click', '@click', 'wire:click'])->twMerge([
            'hover:bg-cat-200 dark:hover:bg-cat-750 focus:bg-cat-200 focus:dark:bg-cat-750',
            'relative flex w-full cursor-pointer select-none items-center',
            'rounded-lg px-2 py-1.5 text-sm outline-none transition-colors',
            'opacity-50 cursor-not-allowed' => $disabled,
            'pl-8' => $inset,
        ]) }}
>
    <span>
        {{ $slot }}
    </span>
    <svg class="ml-auto size-2.5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>
</div>
