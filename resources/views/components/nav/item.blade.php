@props([
  'isActive' => false,
  'hideIf' => false
])

<li
    @class([
        'is-active active' => $isActive,
        'hidden' => $hideIf
    ])
>
    <a
        {{
            $attributes
                ->twMerge('relative flex justify-start items-center py-[11.5px] px-3.5 text-sm font-medium rounded-lg focus:outline-none w-full text-cat-600 dark:text-cat-500 hover:bg-cat-300/20 dark:hover:bg-cat-600/10 transition-colors ease-in-out [.active_&]:!text-accent-500 [.active_&]:!dark:text-accent-300 [.active_&]:!bg-accent-500/10 [.active_&]:!dark:bg-accent-400/10 [.active_&]:!hover:bg-accent-400/20 [&[disabled]]:!select-none [&[disabled]]:!text-cat-500 [&[disabled]]:dark:!text-cat-600 [&[disabled]]:pointer-events-none');
        }}
    >
        <div class="mr-3 [&>svg]:size-5.5 [&>svg]:text-center flex-shrink-0">
            {{ $icon }}
        </div>
        @isset ($description)
        <div class="line-clamp-1 -my-2">
            <div class="line-clamp-1 flex-auto min-w-0 text-start">{{ $title }}</div>
            <div
                class="text-xs text-cat-500 font-light"
                x-data
                x-tooltip="{ text : '{{ $description }}' }"
            >{{ $description }}</div>
        </div>
        @else
        <div class="line-clamp-1 flex-auto min-w-0 text-start">{{ $title }}</div>
        @endisset
        {{ $span ?? '' }}
    </a>
</li>
