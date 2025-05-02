@props([
    'isActive' => false,
    'hideIf' => false
])

<li
    @class([ 'hidden' => $hideIf ])
    x-data="{ subitem: @js($isActive) }"
>
    <a
        {{
            $attributes
                ->twMerge("flex items-center py-[9px] px-3.5 text-sm font-normal rounded-lg focus:outline-none text-cat-600 dark:text-cat-500 hover:bg-cat-300/20 dark:hover:bg-cat-600/15 before:content-[''] before:block before:w-1 before:h-1 before:mx-2 before:mr-3 before:rounded-full before:bg-cat-300 dark:before:bg-cat-600 [&[disabled]]:!select-none [&[disabled]]:!text-cat-500 [&[disabled]]:dark:!text-cat-600 [&[disabled]]:pointer-events-none")
        }}
        x-bind:class="{
            'bg-cat-300/20 dark:bg-cat-600/15 text-cat-800 dark:text-white before:!bg-accent-400 font-medium dark:before:!bg-accent-400' : subitem,
        }"
    >
        <div class="block w-[0.8em]"></div>
        <div class="line-clamp-1 flex-auto min-w-0 text-start">{{ $title }}</div>
    </a>
</li>
