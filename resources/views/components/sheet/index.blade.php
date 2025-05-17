@props([
    'id',
    'isOpen' => false,
    'placement' => 'right',
    'size' => '350px',
    'backdropClass' => '',
    'scrollbar' => false,
    'class' => ''
])

@php
    $transitionClasses = [
        'right' => [
            'enter' => 'translate-x-full',
            'enterTo' => 'translate-x-0',
            'leave' => 'translate-x-0',
            'leaveTo' => 'translate-x-full',
        ],
        'left' => [
            'enter' => '-translate-x-full',
            'enterTo' => 'translate-x-0',
            'leave' => 'translate-x-0',
            'leaveTo' => '-translate-x-full',
        ],
        'top' => [
            'enter' => '-translate-y-full',
            'enterTo' => 'translate-y-0',
            'leave' => 'translate-y-0',
            'leaveTo' => '-translate-y-full',
        ],
        'bottom' => [
            'enter' => 'translate-y-full',
            'enterTo' => 'translate-y-0',
            'leave' => 'translate-y-0',
            'leaveTo' => 'translate-y-full',
        ],
    ];

    $placements = [
        'right' => 'top-0 right-0 h-full',
        'left' => 'top-0 left-0 h-full',
        'top' => 'top-0 left-0 w-full',
        'bottom' => 'bottom-0 left-0 w-full',
    ]
@endphp

<div
    x-data="$store.sheet.create({
        id: @js($id),
        isOpen: @js($isOpen),
        noscroll: @js(!$scrollbar)
    })"
    id="{{ $id }}"
    x-cloak
    x-show="$store.sheet.isOpen(@js($id))"
    x-on:keydown.window.escape="$store.sheet.isOpen(@js($id)) && $store.sheet.close(@js($id))"
    class="lazy-sheet-wrapper fixed inset-0 z-[61]"
>
    <div
        class="@twMerge([
            'fixed inset-0 bg-black/50',
            $backdropClass,
        ])"
        x-on:click="$store.sheet.close(@js($id))"
        x-show="$store.sheet.isOpen(@js($id))"
        x-transition.opacity.duration.600ms
    ></div>

    <div
        x-show="$store.sheet.isOpen(@js($id))"
        x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="{{ $transitionClasses[$placement]['enter'] }}"
        x-transition:enter-end="{{ $transitionClasses[$placement]['enterTo'] }}"
        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="{{ $transitionClasses[$placement]['leave'] }}"
        x-transition:leave-end="{{ $transitionClasses[$placement]['leaveTo'] }}"
        class="@twMerge(['absolute max-w-full transform', 'bg-white/90 dark:bg-cat-800/95 backdrop-blur', 'flex flex-col overflow-hidden', $class, $placements[$placement]])"
        style="{{ in_array($placement, ['left', 'right']) ? "width: $size;" : "height: $size;" }}"
        role="dialog"
        data-dialog-id="{{ $id }}"
    >
        {{ $slot }}
    </div>
</div>
