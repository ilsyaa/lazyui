<div :id="$id(tabId + '-content')" x-show="tabContentActive($el)" x-cloak {{ $attributes->twMerge('relative') }}>
    {{ $slot }}
</div>
