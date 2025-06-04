@props([
    'classActive' => '',
    'isActive' => false
])

<button
    :id="$id(tabId)"
    @if ($isActive)
        x-init="tabButtonClicked($el);"
    @endif
    x-on:click="tabButtonClicked($el);"
    type="button"
    :class="{ '{{ $classActive ?? 'bg-white dark:bg-cat-800' }}' : tabButtonActive($el) }"
    {{
        $attributes->twMerge('relative z-20 inline-flex items-center justify-center w-full h-full px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap focus:outline-none')
    }}
>{{ $slot }}</button>
