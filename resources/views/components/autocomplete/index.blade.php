@props([
    'suggestions' => []
])

<div
    x-data="{
        _isOpen: false,
        _suggestions: @js($suggestions),
        _input: '',
        _focus: '',

        getFilteredOptions() {
            if (this._input.trim() === '') {
                return this._suggestions;
            }
            return this._suggestions.filter(option => option.toLowerCase().includes(this._input.toLowerCase()));
        },

        open() {
            this._isOpen = true;
        },

        toggle() {
            this._isOpen = !this._isOpen;
        },

        close() {
            this._isOpen = false;
            this.$refs.input.blur();
        },

        focusNextOption() {
            const index = this.getFilteredOptions().indexOf(this._focus);
            this._focus = this.getFilteredOptions()[(index + 1) % this.getFilteredOptions().length];
        },

        focusPreviousOption() {
            const index = this.getFilteredOptions().indexOf(this._focus);
            this._focus = this.getFilteredOptions()[(index - 1 + this.getFilteredOptions().length) % this.getFilteredOptions().length];
        },
    }"
    class="relative w-full"
    x-on:click.outside="close()"
    x-on:keydown.escape.prevent="close()"
>
    <div class="w-full">
        <input
            class="cursor-auto appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none scheme-light dark:scheme-dark block w-full px-3 py-2.5 text-sm rounded-md placeholder:text-cat-500 focus:ring-[1.7px] focus:outline-none focus:ring-cat-700 dark:focus:ring-cat-200 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 disabled:cursor-not-allowed disabled:opacity-50"
            x-on:input.debounce.250ms="_input = $event.target.value"
            x-bind:value="_input"
            x-ref="input"
            x-on:focus="open()"
            x-on:keydown.down.prevent="focusNextOption"
            x-on:keydown.up.prevent="focusPreviousOption"
            x-on:keydown.enter.prevent="_focus ? (_input = _focus) : false ; close()"
            x-on:keydown.tab.prevent="close()"
            {{ $attributes }}
        />
        <div
            x-ref="dropdown"
            x-show="_isOpen && _input.length > 0 && getFilteredOptions().length > 0"
            x-cloak
            x-anchor.bottom.offset.8="$refs.input"
            role="listbox"
            class="z-[61] bg-white/90 rounded-md dark:bg-cat-800/90 lazy-gradient backdrop-blur w-full shadow"
        >
            <ul class="[&::-webkit-scrollbar]:!w-1 overflow-y-auto max-h-60 flex flex-col gap-y-1 p-1">
                <template x-for="value of getFilteredOptions()" :key="index">
                    <li
                        :class="{ 'bg-cat-300/50 dark:bg-cat-700/30': _focus === value }"
                        class="px-2.5 py-2 rounded-lg text-sm w-full cursor-pointer hover:bg-cat-300/30 dark:hover:bg-cat-700/15 select-none"
                        x-on:click="_input = value; _isOpen = false"
                        role="option"
                    >
                        <span x-text="value"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
