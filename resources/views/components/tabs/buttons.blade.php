@props([
    'classMarker' => ''
])

<div x-ref="tabButtons" {{ $attributes->twMerge('relative inline-flex items-center justify-center w-full h-10 p-1 bg-cat-100 dark:bg-cat-900 rounded-lg select-none') }} class="">
    {{ $slot }}
    <div x-ref="tabMarker" class="absolute left-0 z-10 w-1/2 h-full duration-300 ease-out" x-cloak><div class="w-full h-full bg-white dark:bg-cat-800 rounded-md shadow-sm {{ $classMarker }}"></div></div>
</div>
