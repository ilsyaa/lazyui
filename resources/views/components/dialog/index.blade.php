@props([
    'id',
    'isOpen' => false,
    'isFullscreen' => false,
    'class' => '',
    'backdropClass' => 'bg-black/30',
    'scrollbar' => false,
    'level' => 0,
])

<div
    x-data="$dialog.create({
        id: @js($id),
        isOpen: @js($isOpen),
        fullscreen: @js($isFullscreen),
        noscroll: @js(!$scrollbar)
    })"
    x-on:keydown.window.escape="$dialog.isOpen('{{ $id }}') && $dialog.close('{{ $id }}')"
    x-cloak
    x-show="$dialog.isOpen('{{ $id }}')"
    class="fixed inset-0 z-[61] lazy-dialog-wrapper"
    style="z-index: {{ (61 + $level) }}"
    id="{{ $id }}"
    role="dialog"
    x-bind:class="{ 'fullscreen' : $dialog.isFullscreen(@js($id)), 'open' : $dialog.isOpen(@js($id)) }"
>

    <div
        x-show="$dialog.isOpen(@js($id))"
        style="z-index: 1"
        :class="{ 'hidden' : $dialog.isFullscreen(@js($id)) }"
        x-on:click="$dialog.close(@js($id))"
        @class(['fixed inset-0', $backdropClass])
        x-transition.opacity
    ></div>

    <div class="fixed inset-0 overflow-y-auto pointer-events-none" style="z-index: 2">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center">
            <div
                x-show="$dialog.isOpen(@js($id))"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                :class="{
                    'fixed inset-0 h-full !max-w-full !rounded-none': $dialog.isFullscreen(@js($id)),
                    'relative': !$dialog.isFullscreen(@js($id))
                }"
                @class([
                    'bg-white dark:bg-cat-800 text-cat-900 dark:text-white text-left lazy-shadow-overlay transition-all w-full max-w-xl rounded-xl pointer-events-auto',
                    $class
                ])
            >
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
