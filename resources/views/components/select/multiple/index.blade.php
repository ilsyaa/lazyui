@props([
    'placeholder' => '+',
    'max' => 0,
    'checkbox' => false
])

<div
    x-data="lazySelectMultiple({ max: @js($max) })"
    class="relative w-full"
    x-on:keydown.escape.prevent="close()"
    x-on:keydown.enter.prevent="toggle()"
>
    <select class="sr-only" tabindex="-1" x-ref="selectOrigin" {{ $attributes }} x-on:focus="open()" multiple>
        {{ $slot }}
    </select>

    <div class="w-full">
        <button
            type="button"
            class="relative px-1.5 py-1.5 rounded-md bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 inline-flex items-center w-full flex-wrap gap-1 cursor-text transition duration-150 ease-in-out focus-within:outline-transparent"
            :class="{ 'ring-[1.7px] ring-cat-700 dark:ring-cat-200': _isOpen }"
            x-on:click="open()"
            x-ref="toggle"
        >
            <template x-for="res in _selected" :key="res.value">
                <div class="inline-flex items-center gap-1 px-2 py-1 font-medium text-xs bg-cat-300/30 dark:bg-cat-700/30 text-cat-800 dark:text-white rounded-md select-none">
                    <span x-text="res.label"></span>
                    <button type="button" class="text-cat-500 cursor-pointer outline-transparent rounded-full" x-on:click="removeSelected(res)">
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/><path fill="currentColor" class="fa-primary" d="M209 175c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47z"/></svg>
                    </button>
                </div>
            </template>
            <input
                x-ref="search"
                x-on:input="_search = $event.target.value"
                type="text"
                class="flex-grow outline-none border-none ring-0 focus:ring-0 focus:border-none py-1 text-sm bg-transparent px-2 inline-block min-w-7 w-0 placeholder:text-cat-500"
                placeholder="{{ $placeholder }}"
                spellcheck="false" autocomplete="off" aria-autocomplete="none"
                x-on:keydown.backspace="removeLastSelected()"
            >
        </button>
        <div
            x-ref="dropdown"
            x-show="_isOpen"
            x-cloak
            x-anchor.bottom.offset.8="$refs.toggle"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            role="listbox"
            class="z-[61] bg-white/90 rounded-md dark:bg-cat-800/90 lazy-gradient backdrop-blur w-full shadow"
            x-on:click.outside="close()"
        >
            <ul class="[&::-webkit-scrollbar]:!w-1 overflow-y-auto max-h-60 flex flex-col gap-y-1 p-1">
                <template x-for="opt of getFilteredOptions()" :key="opt.value">
                    <li
                        :class="{ 'bg-cat-300/50 dark:bg-cat-700/30': opt.selected, 'pointer-events-none opacity-50': opt.disabled }"
                        class="px-2.5 py-2 rounded-lg text-sm w-full cursor-pointer hover:bg-cat-300/30 dark:hover:bg-cat-700/15 select-none"
                        x-on:click="toggleSelected(opt);"
                        role="option"
                    >
                        <div class="flex gap-2">
                            @if ($checkbox)
                            <input type="checkbox" x-bind:checked="opt.selected" class="peer block relative cursor-pointer size-4 m-0.5 bg-transparent border-cat-400 dark:border-cat-500 rounded !ring-offset-transparent focus:ring-transparent disabled:cursor-not-allowed disabled:opacity-50 before:absolute before:left-1/2 before:top-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:size-[1.9rem] disabled:before:bg-transparent checked:bg-accent-500 checked:border-accent-500 indeterminate:!border-accent-500 hover:before:bg-accent-500/10 before:focus-visible:bg-accent-500/10" />
                            @endif
                            <span x-text="opt.label"></span>
                        </div>
                    </li>
                </template>
                <template x-if="getFilteredOptions().length === 0">
                    <div class="px-2 py-10 text-sm text-center">
                        No options found
                    </div>
                </template>
            </ul>
        </div>
    </div>
</div>
