<x-nav.section label="Getting Started">

    <x-nav.item>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M313 7c-9.4-9.4-24.6-9.4-33.9 0L231 55c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l48-48c9.4-9.4 9.4-24.6 0-33.9zM505 199c-9.4-9.4-24.6-9.4-33.9 0l-48 48c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l48-48c9.4-9.4 9.4-24.6 0-33.9zM505 41c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0L327 151c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L505 41z"/><path fill="currentColor" class="fa-primary" d="M213.4 157.4c-8.8-17.9-34.3-17.9-43.1 0l-46.3 94L20.5 266.5C.9 269.3-7 293.5 7.2 307.4l74.9 73.2L64.5 483.9c-3.4 19.6 17.2 34.6 34.8 25.3l92.6-48.8 92.6 48.8c17.6 9.3 38.2-5.7 34.8-25.3L301.6 380.6l74.9-73.2c14.2-13.9 6.4-38.1-13.3-40.9L259.7 251.4l-46.3-94z"/></svg>
        </x-slot:icon>
        <x-slot:title>Intallation</x-slot:title>
    </x-nav.item>

</x-nav.section>

<x-nav.section label="Components">

    <x-nav.item-collapse
        is-active="{{ request()->routeIs('display*') }}"
    >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M64 64c0-17.7 14.3-32 32-32s32 14.3 32 32l0 288H64L64 64zm0 384V416h64l0 32c0 17.7-14.3 32-32 32s-32-14.3-32-32zm320 0c0 17.7-14.3 32-32 32s-32-14.3-32-32l0-288 64 0 0 288zm0-384V96l-64 0V64c0-17.7 14.3-32 32-32s32 14.3 32 32z"/><path fill="currentColor" class="fa-primary" d="M416 96c17.7 0 32 14.3 32 32s-14.3 32-32 32l-288 0V96H416zM32 96H64v64l-32 0c-17.7 0-32-14.3-32-32s14.3-32 32-32zm0 320c-17.7 0-32-14.3-32-32s14.3-32 32-32l288 0v64L32 416zm384 0H384V352h32c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
        </x-slot:icon>
        <x-slot:title>Display</x-slot:title>
        <x-slot:sub>
            <x-nav.sub-item
                is-active="{{ request()->routeIs('display.button') }}"
                href="{{ route('display.button') }}"
            >
                <x-slot:title>Button</x-slot:title>
            </x-nav.sub-item>
            <x-nav.sub-item
                is-active="{{ request()->routeIs('display.card') }}"
                href="{{ route('display.card') }}"
            >
                <x-slot:title>Card</x-slot:title>
            </x-nav.sub-item>
        </x-slot:sub>
    </x-nav.item-collapse>

    <x-nav.item-collapse
        is-active="{{ request()->routeIs('overlay*') }}"
    >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M0 64C0 28.7 28.7 0 64 0H448c35.3 0 64 28.7 64 64V352c0 35.3-28.7 64-64 64H309.3L185.6 508.8c-4.8 3.6-11.3 4.2-16.8 1.5s-8.8-8.2-8.8-14.3V416H64c-35.3 0-64-28.7-64-64V64zm152 80c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H152zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H152z"/><path fill="currentColor" class="fa-primary" d="M128 168c0-13.3 10.7-24 24-24H360c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24zm0 96c0-13.3 10.7-24 24-24H264c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24z"/></svg>
        </x-slot:icon>
        <x-slot:title>Overlay</x-slot:title>
        <x-slot:sub>
            <x-nav.sub-item
                is-active="{{ request()->routeIs('overlay.dropdown') }}"
                href="{{ route('overlay.dropdown') }}"
            >
                <x-slot:title>Dropdown</x-slot:title>
            </x-nav.sub-item>
            <x-nav.sub-item
                is-active="{{ request()->routeIs('overlay.dialog') }}"
                href="{{ route('overlay.dialog') }}"
            >
                <x-slot:title>Dialog</x-slot:title>
            </x-nav.sub-item>
            <x-nav.sub-item
                is-active="{{ request()->routeIs('overlay.sheet') }}"
                href="{{ route('overlay.sheet') }}"
            >
                <x-slot:title>Sheet</x-slot:title>
            </x-nav.sub-item>
        </x-slot:sub>
    </x-nav.item-collapse>

</x-nav.section>
