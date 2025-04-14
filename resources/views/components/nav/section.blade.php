@props([
    'label' => '',
    'isOpen' => true,
    'hideIf' => false,
])

<div
    x-data="{ expanded: {{ $isOpen }} }"
    @class([
        'hidden' => $hideIf,
        'mt-3'
    ])
>
    <button
        @click="expanded = ! expanded"
        type="button"
        class="group text-[11px] font-semibold text-cat-500/70 hover:text-cat-800 dark:hover:text-cat-50 select-none mb-3 transition-colors duration-300 ease-out inline-flex items-center px-1  -translate-x-1.5"
        :aria-expanded="expanded"
    >
        <svg class="size-3 ms-auto transition-transform ease-in-out opacity-0 group-hover:opacity-100" x-bind:class="expanded ? 'rotate-90' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M289.9 256l-17 17L113 433l-17 17L62.1 416l17-17 143-143L79 113l-17-17L96 62.1l17 17L273 239l17 17z"/></svg>
        <span class="transition-transform ease-in-out duration-300 hover:translate-x-0.5 px-1 uppercase">{{ $label }}</span>
    </button>
    <div
        x-show="expanded"
        x-collapse
        class="w-full"
    >
        <ul class="space-y-1">
            {{ $slot }}
        </ul>
    </div>
</div>
