<header class="lazy-header">
    <nav>
        <div class="lg:hidden mr-3">
            <x-button
                variant="ghost"
                size="icon"
                class="rounded-full"
                x-data
                x-on:click="$store.sidebar.toggle()"
            >
                <svg class="size-5 text-cat-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M64 256c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H96c-17.7 0-32-14.3-32-32z"/><path fill="currentColor" class="fa-primary" d="M448 96c0-17.7-14.3-32-32-32H32C14.3 64 0 78.3 0 96s14.3 32 32 32H416c17.7 0 32-14.3 32-32zm0 320c0-17.7-14.3-32-32-32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H416c17.7 0 32-14.3 32-32z"/></svg>
            </x-button>
        </div>

        <div class="lazy-header-icon">
            <div x-data>
                <x-button
                    variant="ghost"
                    size="icon"
                    class="rounded-full md:hidden"
                    x-on:click="$dialog.toggle('dialog-search')"
                >
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M208 64a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm0 352A208 208 0 1 0 208 0a208 208 0 1 0 0 416z"/><path fill="currentColor" class="fa-primary" d="M330.7 376L457.4 502.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L376 330.7C363.3 348 348 363.3 330.7 376z"/></svg>
                </x-button>
                <button type="button" x-on:click="$dialog.toggle('dialog-search')" class="lazy-header-search-button">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M208 64a144 144 0 1 1 0 288 144 144 0 1 1 0-288zm0 352A208 208 0 1 0 208 0a208 208 0 1 0 0 416z"/><path fill="currentColor" class="fa-primary" d="M330.7 376L457.4 502.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L376 330.7C363.3 348 348 363.3 330.7 376z"/></svg>
                    <span class="shortcut">⌘K</span>
                </button>
            </div>
            <div class="flex gap-1 items-center">
                <x-button
                    href="https://github.com/ilsyaa/lazyui"
                    variant="ghost"
                    size="icon"
                    class="rounded-full"
                >
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>
                </x-button>
                <x-button
                    variant="ghost"
                    size="icon"
                    class="rounded-full relative"
                    x-data
                    x-sheet:toggle="lazySettings"
                >
                    <svg class="size-5 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-primary" d="M192 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/><path fill="currentColor" class="fa-secondary" d="M489.6 191.2c6.9-6.2 9.6-15.9 6.4-24.6c-4.4-11.9-9.7-23.3-15.8-34.3l-4.7-8.1c-6.6-11-14-21.4-22.1-31.2c-5.9-7.2-15.7-9.6-24.5-6.8l-55.7 17.7C359.8 93.6 345 85 329.2 78.4L316.7 21.3c-2-9.1-9-16.3-18.2-17.8C284.7 1.2 270.5 0 256 0s-28.7 1.2-42.5 3.5c-9.2 1.5-16.2 8.7-18.2 17.8L182.8 78.4c-15.8 6.5-30.6 15.1-44 25.4L83.1 86.1c-8.8-2.8-18.6-.3-24.5 6.8c-8.1 9.8-15.5 20.2-22.1 31.2l-4.7 8.1c-6.1 11-11.4 22.4-15.8 34.3c-3.2 8.7-.5 18.4 6.4 24.6l43.3 39.4C64.6 238.9 64 247.4 64 256s.6 17.1 1.7 25.4L22.4 320.8c-6.9 6.2-9.6 15.9-6.4 24.6c4.4 11.9 9.7 23.3 15.8 34.3l4.7 8.1c6.6 11 14 21.4 22.1 31.2c5.9 7.2 15.7 9.6 24.5 6.8l55.7-17.7c13.4 10.3 28.2 18.9 44 25.4l12.5 57.1c2 9.1 9 16.3 18.2 17.8c13.8 2.3 28 3.5 42.5 3.5s28.7-1.2 42.5-3.5c9.2-1.5 16.2-8.7 18.2-17.8l12.5-57.1c15.8-6.5 30.6-15.1 44-25.4l55.7 17.7c8.8 2.8 18.6 .3 24.5-6.8c8.1-9.8 15.5-20.2 22.1-31.2l4.7-8.1c6.1-11 11.3-22.4 15.8-34.3c3.2-8.7 .5-18.4-6.4-24.6l-43.3-39.4c1.1-8.3 1.7-16.8 1.7-25.4s-.6-17.1-1.7-25.4l43.3-39.4zM256 160a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>
                    <span class="absolute top-2 end-2 inline-flex items-center size-2 rounded-full font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white dark:border-slate-900 animate-pulse"></span>
                </x-button>
                <x-dropdown class="ml-2">
                    <x-dropdown.trigger variant="outline">
                        <img class="cursor-pointer size-9 rounded-full overflow-hidden object-center object-cover" src="{{ asset('assets/lazy/image/avatar.webp') }}" alt="avatar">
                    </x-dropdown.trigger>
                    <x-dropdown.content class="w-40" align="end">
                        <x-dropdown.label>My Account</x-dropdown.label>
                        <x-dropdown.separator />
                        <x-dropdown.link href="">
                            Profile
                        </x-dropdown.link>
                        <x-dropdown.link href="">
                            Settings
                        </x-dropdown.link>
                        <x-dropdown.separator />
                        <x-dropdown.link href="">
                            Log out
                        </x-dropdown.link>
                    </x-dropdown.content>
                </x-dropdown>
            </div>
        </div>
    </nav>
</header>
