@props([
    'align' => 'center',
    'side' => 'bottom',
    'sideOffset' => 4,
])
@php
    $alignment = $side . ['center' => '', 'end' => '-end', 'start' => '-start'][$align];
@endphp
<ul
    x-dropdown-menu:items
    x-transition:enter.origin.top.right
    x-anchor.{{ $alignment }}.offset.{{ $sideOffset }}="document.getElementById($id('alpine-dropdown-menu-button'))"
    x-cloak
    {{ $attributes->twMerge('z-[60] min-w-[8rem] bg-white dark:bg-cat-800 rounded-xl p-1 shadow-md') }}
>
    {{ $slot }}
</ul>
