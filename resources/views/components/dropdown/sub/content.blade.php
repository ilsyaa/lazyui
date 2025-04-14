<ul
    x-dropdown-menu:subitems
    x-transition:enter.origin.top.right
    x-anchor.right-start="document.getElementById($id('alpine-dropdown-menu'))"
    x-cloak
    {{ $attributes->twMerge('z-[60] min-w-[8rem] bg-white dark:bg-cat-800 rounded-xl p-1 shadow-md') }}
>
    {{ $slot }}
</ul>
