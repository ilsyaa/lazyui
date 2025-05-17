@props([
    'placeholder' => '+',
    'max' => 0,
    'suggestions' => [],
    'checkbox' => false
])

<div
    x-data="{
        _isOpen: false,
        _suggestions: @js($suggestions),
        _max: @js($max),
        _input: '',
        _focus: '',
        _selected: [],

        init() {
            this.$nextTick(() => {
                if(this.$refs.originalInput.value) {
                    this._selected = this.$refs.originalInput.value.split(',');
                }
            })

            this.$watch('_selected', () => {
                this.$refs.originalInput.value = this._selected.join(', ');
                this.$refs.originalInput.dispatchEvent(new Event('input'));
            });
        },

        open() {
            this._isOpen = true;
        },

        toggle() {
            this._isOpen = !this._isOpen;
            this._input = '';
            this._focus = '';
        },

        close() {
            this._isOpen = false;
            this._input = '';
            this._focus = '';
        },

        getFilteredOptions() {
            if (this._input.trim() === '') {
                return this._suggestions;
            }
            return this._suggestions.filter(option => option.toLowerCase().includes(this._input.toLowerCase()));
        },

        removeSelected(value) {
            this._selected = this._selected.filter(val => val !== value);
        },

        removeLastSelected() {
            if (this._input === '' && this._selected.length > 0) {
                this._selected.pop();
            }
        },

        setSelected(value) {
            if (!value || value.replace(/\s/g, '') === '') return;
            if(this._selected.find(val => val === value)) return;
            if(this._max !=0 && this._selected.length >= this._max) return;
            this._selected.push(value);
            this._input = '';
            this._focus = '';
            this.$refs.input.focus();
        },

        toggleSelected(value) {
            if (!value || value.replace(/\s/g, '') === '') return;
            if(this._selected.find(val => val === value)) {
                this.removeSelected(value);
            } else {
                if(this._max !=0 && this._selected.length >= this._max) return;
                this._selected.push(value);
            }
            this._input = '';
            this._focus = '';
            this.$refs.input.focus();
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
        <input type="hidden" x-ref="originalInput" {{ $attributes->only(['x-model', 'wire:model', 'name', 'value']) }}>
        <button
            type="button"
            class="relative px-1.5 py-1.5 rounded-md bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 inline-flex items-center w-full flex-wrap gap-1 cursor-text transition duration-150 ease-in-out focus-within:outline-transparent"
            :class="{ 'ring-[1.7px] ring-cat-700 dark:ring-cat-200': _isOpen }"
            x-on:click="open(); $refs.input.focus()"
            x-ref="toggle"
            x-on:keydown.down.prevent="focusNextOption"
            x-on:keydown.up.prevent="focusPreviousOption"
            x-on:keydown.enter.prevent="_focus ? toggleSelected(_focus) : setSelected(_input) ; close();"
            {{ $attributes->except(['x-model', 'wire:model', 'name', 'value']) }}
        >
            <template x-for="value in _selected" :key="index">
                <div class="inline-flex items-center gap-1 px-2 py-1 font-medium text-xs bg-cat-300/30 dark:bg-cat-700/30 text-cat-800 dark:text-white rounded-md select-none">
                    <span x-text="value"></span>
                    <button type="button" class="text-cat-500 cursor-pointer outline-transparent rounded-full" x-on:click="removeSelected(value)">
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/><path fill="currentColor" class="fa-primary" d="M209 175c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47z"/></svg>
                    </button>
                </div>
            </template>
            <input
                x-ref="input"
                x-on:input="open()"
                x-model="_input"
                type="text"
                class="flex-grow outline-none border-none ring-0 focus:ring-0 focus:border-none py-1 text-sm bg-transparent px-2 inline-block min-w-7 w-0 placeholder:text-cat-500"
                placeholder="{{ $placeholder }}"
                spellcheck="false" autocomplete="off" aria-autocomplete="none"
                x-on:keydown.backspace="removeLastSelected()"
            >
        </button>
        <div
            x-ref="dropdown"
            x-show="_isOpen && getFilteredOptions().length > 0"
            x-cloak
            x-anchor.bottom.offset.8="$refs.toggle"
            role="listbox"
            class="z-[61] bg-white/90 rounded-md dark:bg-cat-800/90 lazy-gradient backdrop-blur w-full shadow"
        >
            <ul class="[&::-webkit-scrollbar]:!w-1 overflow-y-auto max-h-60 flex flex-col gap-y-1 p-1">
                <template x-for="value of getFilteredOptions()" :key="index">
                    <li
                        :class="{ 'bg-cat-300/50 dark:bg-cat-700/30': _focus === value, 'bg-cat-300/30 dark:bg-cat-700/15': _selected.includes(value) }"
                        class="px-2.5 py-2 rounded-lg text-sm w-full cursor-pointer hover:bg-cat-300/30 dark:hover:bg-cat-700/15 select-none"
                        x-on:click="toggleSelected(value);"
                        role="option"
                    >
                        <div class="flex gap-2">
                            @if ($checkbox)
                            <input type="checkbox" x-bind:checked="_selected.includes(value)" class="peer block relative cursor-pointer size-4 m-0.5 bg-transparent border-cat-400 dark:border-cat-500 rounded !ring-offset-transparent focus:ring-transparent disabled:cursor-not-allowed disabled:opacity-50 before:absolute before:left-1/2 before:top-1/2 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:size-[1.9rem] disabled:before:bg-transparent checked:bg-accent-500 checked:border-accent-500 indeterminate:!border-accent-500 hover:before:bg-accent-500/10 before:focus-visible:bg-accent-500/10" />
                            @endif
                            <span x-text="value"></span>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
