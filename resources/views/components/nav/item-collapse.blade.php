@props([
    'isActive' => false,
    'hideIf' => false,
])

<li
    x-data="{
        isOpen : @js($isActive),
        isActive : @js($isActive),
    }"
    @class([
        'hidden' => $hideIf,
        'w-full overflow-hidden transition-[height] duration-300'
    ])
>
    <button
        @click="isOpen = ! isOpen"
        type="button"
        {{
            $attributes
                ->twMerge('relative flex justify-start items-center py-[11.5px] px-3.5 text-sm font-medium rounded-lg focus:outline-none w-full text-cat-600 dark:text-cat-500 hover:bg-cat-300/20 dark:hover:bg-cat-600/10 transition-colors ease-in-out [&[disabled]]:!select-none [&[disabled]]:!text-cat-500 [&[disabled]]:dark:!text-cat-600 [&[disabled]]:pointer-events-none cursor-pointer')
        }}
        :class="{
            '!text-accent-500 dark:!text-accent-300 !bg-accent-500/10 dark:!bg-accent-400/10 hover:!bg-accent-400/20' : isActive,
            'bg-cat-300/30 dark:bg-cat-600/10 text-cat-800 dark:text-cat-50' : isOpen
        }"
    >
        <div class="mr-3 [&>svg]:size-4.5 [&>svg]:text-center flex-shrink-0">
            {{ $icon }}
        </div>
        <div class="line-clamp-1 flex-auto min-w-0 text-start">{{ $title }}</div>
        <svg class="size-3 ms-auto transition-transform ease-in-out duration-300" x-bind:class="isOpen ? 'rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg>
    </button>

    <div
        x-show="isOpen"
        x-cloak
        x-collapse
        class="w-full"
    >
        <ul class="space-y-1 pt-1">
            {{ $sub ?? '' }}
        </ul>
    </div>
</li>
