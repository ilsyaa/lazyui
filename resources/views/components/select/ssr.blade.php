@props([
    'url' => null,
    'displayIcon' => false
])

<div
    x-data="lazySelectSsr({ url: @js($url) })"
    class="relative w-full"
    x-on:keydown.escape.prevent="close()"
    x-on:keydown.enter.prevent="toggle()"
>
    <select class="sr-only" tabindex="-1" aria-hidden="true" x-ref="selectOrigin" {{ $attributes->only(['name']) }}>
        <option value="" selected></option>
    </select>

    <div class="w-full">
        <div class="relative">
            <input
                x-bind:class="{'ring-2 ring-cat-700 dark:ring-cat-200': _isOpen, 'pl-9' : (@js($displayIcon) && getSelected('icon'))}"
                x-bind:value="getSelected('label')"
                x-ref="toggle"
                class="cursor-auto appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none scheme-light dark:scheme-dark block w-full px-3 py-2.5 text-sm rounded-md placeholder:text-cat-500 focus:ring-[1.7px] focus:outline-none focus:ring-cat-700 dark:focus:ring-cat-200 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 file:border-0 file:bg-transparent file:px-1 file:rounded file:text-sm file:font-medium disabled:cursor-not-allowed disabled:opacity-50"
                readonly
                x-on:focus="$nextTick(() => {
                    const el = $event.target;
                    const val = el.value;
                    el.value = '';
                    el.value = val;
                })"
                {{ $attributes->except(['name']) }}
                x-on:click="toggle()"
            />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none {{ $displayIcon ? '' : 'hidden' }}">
                <span x-html="getSelected('icon') || ''" class="[&_svg]:size-4 [&_img]:w-5 [&_img]:object-cover [&_img]:object-center"></span>
            </div>
        </div>
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
            class="z-[61] bg-white/90 rounded-md dark:bg-cat-800/90 backdrop-blur w-full shadow lazy-gradient"
            x-on:click.outside="close()"
        >
            <div class="p-1 pb-0">
                <input type="text" x-ref="search" placeholder="Start typing to search..." class="px-2 w-full rounded-md border-transparent bg-transparent focus:ring-0 focus-within:ring-0 text-sm" x-model="_search" />
            </div>
            <ul class="[&::-webkit-scrollbar]:!w-1 overflow-y-auto max-h-60 flex flex-col gap-y-1 p-1">
                <template x-for="opt of getFilteredOptions()" :key="opt.value">
                    <li
                        :class="{ 'bg-cat-300/50 dark:bg-cat-700/30': getSelected('value') === opt.value, 'pointer-events-none opacity-50': opt.disabled }"
                        class="px-2.5 py-2 rounded-lg text-sm w-full cursor-pointer hover:bg-cat-300/30 dark:hover:bg-cat-700/15 select-none"
                        x-on:click="setSelected(opt); close();"
                        role="option"
                    >
                        <div class="flex items-center gap-x-2">
                            <template x-if="opt.icon">
                                <span x-html="opt.icon" class="[&_svg]:size-4 [&_img]:w-5 [&_img]:object-cover [&_img]:object-center"></span>
                            </template>
                            <span x-text="opt.label" class="flex-1"></span>
                        </div>
                        <template x-if="opt.description">
                            <span class="text-xs text-cat-500" x-text="opt.description"></span>
                        </template>
                    </li>
                </template>
                <template x-if="_isLoading">
                    <div class="px-2 py-1 flex justify-center">
                        <svg class="size-4 animate-spin text-cat-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M477.7 384c21.8-37.7 34.3-81.4 34.3-128C512 114.6 397.4 0 256 0V64c106 0 192 86 192 192c0 35-9.4 67.8-25.7 96l55.4 32z"/></svg>
                    </div>
                </template>
                <template x-if="getFilteredOptions().length === 0 && _search.length > 0 && !_isLoading">
                    <div class="px-2 py-10 text-sm text-center">
                        No options found
                    </div>
                </template>
            </ul>
        </div>
    </div>
</div>
