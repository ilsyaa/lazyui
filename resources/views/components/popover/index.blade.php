@props([
    'placement' => 'top',
    'offset' => 8,
    'trigger' => 'click',
    'class' => ''
])

<div x-data="{
    _isOpen: false,
    _delay: 200,
    _openTimeout: null,
    _closeTimeout: null,

    open() {
        clearTimeout(this._closeTimeout);
        this._openTimeout = setTimeout(() => {
            this._isOpen = true;
        }, this._delay);
    },

    close() {
        clearTimeout(this._openTimeout);
        this._closeTimeout = setTimeout(() => {
            this._isOpen = false;
        }, this._delay);
    },

    toggle() {
        this._isOpen = !this._isOpen;
    }
}">
    <div
        x-ref="button"
        @if ($trigger == 'hover')
            x-on:mouseenter="open()"
            x-on:mouseleave="close()"
        @else
            x-on:click="toggle()"
        @endif
    >
        {{ $toggle }}
    </div>

    <div
        x-show="_isOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @if ($trigger === 'hover')
            x-on:mouseenter="open()"
            x-on:mouseleave="close()"
        @else
            x-on:click.outside="_isOpen = false"
        @endif
        @keydown.escape.window="_isOpen = false"
        x-anchor.{{ $placement }}.offset.{{ $offset }}.ref="$refs.button"
        @class([
            'z-[61] bg-white/90 lazy-shadow-overlay rounded-xl dark:bg-cat-800/90 backdrop-blur lazy-gradient',
            'origin-top-left' => $placement === 'bottom-start',
            'origin-top-right' => $placement === 'bottom-end',
            'origin-bottom-left' => $placement === 'top-start',
            'origin-bottom-right' => $placement === 'top-end',
            $class
        ])
        x-cloak
    >
        {{ $content }}
    </div>
</div>
