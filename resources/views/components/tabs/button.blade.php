@props([
    'classActive' => '',
])

<button :id="$id(tabId)" @click="tabButtonClicked($el);" type="button" :class="{ '{{ $classActive ?? 'bg-white dark:bg-cat-800' }}' : tabButtonActive($el) }" {{ $attributes->twMerge('relative z-20 inline-flex items-center justify-center w-full h-8 px-3 text-sm font-medium transition-all rounded-md cursor-pointer whitespace-nowrap') }}>{{ $slot }}</button>
