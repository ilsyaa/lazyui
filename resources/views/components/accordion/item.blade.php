@props(['value'])

<div
    x-accordion:item
    {{ $attributes->twMerge('border-b border-cat-300 dark:border-cat-700') }}
    x-data="{ item: '{{ $value }}' }"
    :data-state="__getDataState(item)"
>
    {{ $slot }}
</div>
