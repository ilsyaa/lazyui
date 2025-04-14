<div
    {{
        $attributes->twMerge();
    }}
    x-trap="isOpen()"
    :class="{'h-full pb-11 overflow-y-auto' : isFullscreen()}"
>
    {{ $slot }}
</div>
