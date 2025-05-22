<x-nav.section label="GET STARTED">

    <x-nav.item-collapse>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M3 10c0-3.771 0-5.657 1.172-6.828S7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172S21 6.229 21 10v4c0 3.771 0 5.657-1.172 6.828S16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172S3 17.771 3 14z" opacity="0.5"/><path fill="currentColor" fill-rule="evenodd" d="M7.25 12a.75.75 0 0 1 .75-.75h8a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1-.75-.75m0-4A.75.75 0 0 1 8 7.25h8a.75.75 0 0 1 0 1.5H8A.75.75 0 0 1 7.25 8m0 8a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1-.75-.75" clip-rule="evenodd"/></svg>
        </x-slot:icon>
        <x-slot:title>Documentation</x-slot:title>
        <x-slot:sub>
            <x-nav.sub-item
                target="_blank"
                href="https://lazyui.koding.in/layout"
            >
                <x-slot:title>Generate Dashboard</x-slot:title>
            </x-nav.sub-item>
            <x-nav.sub-item
                target="_blank"
                href="https://lazyui.koding.in/nav"
            >
                <x-slot:title>Nav Components</x-slot:title>
            </x-nav.sub-item>
        </x-slot:sub>
    </x-nav.item-collapse>

</x-nav.section>

<x-nav.section label="OTHER">

    <x-nav.item
        target="_blank"
        href="https://icon-sets.iconify.design/solar"
    >
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" d="M3.464 20.536C4.93 22 7.286 22 12 22s7.071 0 8.535-1.465C22 19.072 22 16.714 22 12s0-7.071-1.465-8.536C19.072 2 16.714 2 12 2S4.929 2 3.464 3.464C2 4.93 2 7.286 2 12s0 7.071 1.464 8.535" opacity="0.5"/><path fill="currentColor" d="M17 12.667C17 16.933 13.444 18 11.667 18C10.11 18 7 16.933 7 12.667C7 10.81 8.063 9.633 8.956 9.04c.408-.271.916-.098.942.391c.058 1.071.883 1.931 1.523 1.07c.584-.788.873-1.858.873-2.501c0-.947.958-1.548 1.707-.968C15.459 8.162 17 10.056 17 12.667"/></svg>
        </x-slot:icon>
        <x-slot:title>Icons</x-slot:title>
    </x-nav.item>

</x-nav.section>
