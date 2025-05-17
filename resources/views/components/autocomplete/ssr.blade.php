@props([
    'url' => ''
])

<div
    x-data="{
        _isOpen: false,
        _suggestions: [],
        _input: '',
        _focus: '',
        _debounceTimer: null,
        _isLoading: false,

        init() {
            this.$watch('_input', (value) => {
                if (this._input.trim() === '') {
                    this._suggestions = [];
                    return;
                }

                clearTimeout(this._debounceTimer);
                this._debounceTimer = setTimeout(() => {
                        this._isLoading = true;
                        fetch(@js($url) + '?search=' + value)
                            .then(response => response.json())
                            .then(data => {
                                this._isLoading = false;
                                this._suggestions = data
                            }).catch(error => {
                                this._isLoading = false;
                                console.error('Error fetching data:', error);
                                this._suggestions = [];
                            });
                    }, 500);
            });
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
            const index = this._suggestions.indexOf(this._focus);
            this._focus = this._suggestions[(index + 1) % this._suggestions.length];
        },

        focusPreviousOption() {
            const index = this._suggestions.indexOf(this._focus);
            this._focus = this._suggestions[(index - 1 + this._suggestions.length) % this._suggestions.length];
        },
    }"
    class="relative w-full"
    x-on:click.outside="close()"
    x-on:keydown.escape.prevent="close()"
>
    <div class="w-full">
        <div class="relative">
            <input
                class="cursor-auto appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none scheme-light dark:scheme-dark block w-full px-3 py-2.5 text-sm rounded-md placeholder:text-cat-500 focus:ring-[1.7px] focus:outline-none focus:ring-cat-700 dark:focus:ring-cat-200 focus:border-transparent transition duration-150 ease-in-out bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 disabled:cursor-not-allowed disabled:opacity-50"
                x-on:input="_input = $event.target.value"
                x-model="_input"
                x-ref="input"
                x-on:focus="open()"
                x-on:keydown.down.prevent="focusNextOption"
                x-on:keydown.up.prevent="focusPreviousOption"
                x-on:keydown.enter.prevent="_focus ? (_input = _focus) : false ; close()"
                x-on:keydown.tab.prevent="close()"
                {{ $attributes }}
            />
            <div class="absolute top-1/2 right-3 transform -translate-y-1/2 pointer-events-none" x-cloak x-show="_isLoading">
                <svg class="size-4 animate-spin text-cat-500/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M477.7 384c21.8-37.7 34.3-81.4 34.3-128C512 114.6 397.4 0 256 0V64c106 0 192 86 192 192c0 35-9.4 67.8-25.7 96l55.4 32z"/></svg>
            </div>
        </div>
        <div
            x-ref="dropdown"
            x-show="_isOpen && _input.length > 0 && _suggestions.length > 0 && !_isLoading"
            x-cloak
            x-anchor.bottom.offset.8="$refs.input"
            role="listbox"
            class="z-[61] bg-white/90 rounded-md dark:bg-cat-800/90 lazy-gradient backdrop-blur w-full shadow"
        >
            <ul class="[&::-webkit-scrollbar]:!w-1 overflow-y-auto max-h-60 flex flex-col gap-y-1 p-1">
                <template x-for="value of _suggestions" :key="index">
                    <li
                        :class="{ 'bg-cat-300/50 dark:bg-cat-700/30': _focus === value }"
                        class="px-2.5 py-2 rounded-lg text-sm w-full cursor-pointer hover:bg-cat-300/30 dark:hover:bg-cat-700/15 select-none"
                        x-on:click="_input = value; close()"
                        role="option"
                    >
                        <span x-text="value"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
