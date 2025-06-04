@props([
    'id',
    'isOpen' => false,
    'isFullscreen' => false,
    'class' => '',
    'backdropClass' => 'bg-black/30',
    'backdropStatic' => false,
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
    @if ($backdropStatic)
    x-on:keydown.window.escape="$refs.dialogCard.classList.add('scale-105'); setTimeout(() => $refs.dialogCard.classList.remove('scale-105'), 150);"
    @else
    x-on:keydown.window.escape="$dialog.isOpen('{{ $id }}') && $dialog.close('{{ $id }}')"
    @endif
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
        @if ($backdropStatic)
        x-on:click="$refs.dialogCard.classList.add('scale-105'); setTimeout(() => $refs.dialogCard.classList.remove('scale-105'), 150);"
        @else
        x-on:click="$dialog.close(@js($id))"
        @endif
        @class(['fixed inset-0', $backdropClass])
        x-transition.opacity.duration.300ms
    ></div>

    <div class="fixed inset-0 overflow-y-auto pointer-events-none" style="z-index: 2">
        <div class="flex min-h-full justify-center p-4 text-center items-center">
            <div
                x-show="$dialog.isOpen(@js($id))"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-ref="dialogCard"
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
