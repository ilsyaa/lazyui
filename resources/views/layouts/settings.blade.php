<x-sheet class="bg-white dark:bg-cat-900/95 lazy-gradient shadow" backdropClass="bg-transparent" id="lazySettings">
    <x-sheet.header title="Settings" />
    <x-sheet.body class="p-5">
        <div class="grid grid-cols-2 gap-3">
            <button
                type="button"
                class="block aspect-[7/5] rounded-xl w-full h-full px-5 py-6 bg-white dark:bg-cat-800 shadow"
                x-on:click="$appearance.setTheme('toggle')"
                id="appearance-toggle"
            >
                <div class="flex flex-col justify-between h-full w-full text-start pointer-events-none">
                    <div class="flex flex-row justify-between items-center">
                        <svg class="size-7 text-cat-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,640,512"><path d="M256.5 240.3C264.5 124 361.2 32 479.5 32c6.4 0 12.7 .3 19 .8c7 .6 12.8 5.7 14.3 12.5s-1.6 13.9-7.7 17.3c-53.3 30.2-89.3 87.6-89.3 153.3c0 97.2 78.6 176 175.5 176c10.3 0 20.3-.9 30.1-2.6c6.9-1.2 13.8 2.2 17 8.5c1.2 2.3 1.8 4.8 1.8 7.3c0 4.2-1.7 8.4-4.8 11.5C595 455.8 540 480 479.5 480c-60.9 0-116.1-24.4-156.4-64c17.9-18.7 29-44.1 29-72c0-54.6-42-99.3-95.5-103.7z" fill="currentColor" style="opacity: 0.4;"></path><path d="M0 352c0 35.3 28.7 64 64 64l184 0c39.8 0 72-32.2 72-72s-32.2-72-72-72c-10.1 0-19.7 2.1-28.4 5.8C208.8 246.5 179 224 144 224c-38.7 0-71 27.5-78.4 64c-.5 0-1.1 0-1.6 0c-35.3 0-64 28.7-64 64z" fill="currentColor" style="opacity: 1;"></path></svg>
                        <x-switch size="xxs" />
                    </div>
                    <span class="text-xs font-semibold">Dark mode</span>
                </div>
            </button>
            <button
                type="button"
                class="block aspect-[7/5] rounded-xl w-full h-full px-5 py-6 bg-white dark:bg-cat-800 shadow"
                id="appearance-auto"
                x-on:click="$appearance.setTheme('auto')"
            >
                <div class="flex flex-col justify-between h-full w-full text-start pointer-events-none">
                    <div class="flex flex-row justify-between items-center">
                        <svg class="size-5 text-cat-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,512,512"><path d="M0 224l512 0 0 192c0 35.3-28.7 64-64 64L64 480c-35.3 0-64-28.7-64-64L0 224zm352-96a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zm96 0a32 32 0 1 1 -64 0 32 32 0 1 1 64 0z" fill="currentColor" style="opacity: 0.4;"></path><path d="M448 32c35.3 0 64 28.7 64 64l0 128L0 224 0 96C0 60.7 28.7 32 64 32l384 0zM416 160a32 32 0 1 0 0-64 32 32 0 1 0 0 64zM288 128a32 32 0 1 0 64 0 32 32 0 1 0 -64 0zm-64 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z" fill="currentColor" style="opacity: 1;"></path></svg>
                        <x-switch size="xxs" />
                    </div>
                    <span class="text-xs font-semibold">Auto mode</span>
                </div>
            </button>
        </div>
        <div class="relative border border-cat-300/50 dark:border-cat-750 rounded-xl mt-12">
            <div class="absolute -top-3 left-4 text-xs font-semibold px-3 py-1 rounded-xl bg-cat-800 dark:bg-white text-white dark:text-cat-800 z-[1]">Accent</div>
            <div class="grid grid-cols-3 gap-3 pt-7 p-5 justify-items-center" x-data data-accents>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="emerald"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-emerald-300 transition-transform duration-300 ease-in-out"></span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="indigo"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-indigo-300 transition-transform duration-300 ease-in-out"></span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="blue"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-blue-300 transition-transform duration-300 ease-in-out"></span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="pink"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-pink-300 transition-transform duration-300 ease-in-out"></span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="orange"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-orange-300 transition-transform duration-300 ease-in-out"></span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex justify-center items-center h-16 aspect-[1/1] hover:bg-cat-500/10 [&.active]:bg-accent-500/10 rounded-xl"
                    data-accent="purple"
                    x-on:click="$appearance.setAccent($event.currentTarget.dataset.accent, true, false)"
                >
                    <span class="size-4 [.active_&]:scale-150 rounded-full bg-purple-300 transition-transform duration-300 ease-in-out"></span>
                </button>
            </div>
        </div>

        <div class="relative border border-cat-300/50 dark:border-cat-750 rounded-xl mt-12">
            <div class="absolute -top-3 left-4 text-xs font-semibold px-3 py-1 rounded-xl bg-cat-800 dark:bg-white text-white dark:text-cat-800 z-[1]">Font</div>
            <div class="grid grid-cols-2 gap-3 pt-7 p-5 justify-items-center" x-data data-fonts>
                <button
                    type="button"
                    class="active cursor-pointer flex flex-col gap-y-3 justify-center items-center h-20 w-full text-cat-500 hover:bg-cat-500/10 rounded-xl [&.active]:shadow [&.active]:bg-white dark:[&.active]:bg-cat-500/10 [&.active]:text-cat-900 dark:[&.active]:text-white"
                    data-font="inter"
                    x-on:click="$appearance.setFont($event.currentTarget.dataset.font, true, false)"
                >
                    <svg class="size-5 [.active_&]:text-accent-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,640,512"><path d="M384 288l0 64c0 70.7 57.3 128 128 128c24.5 0 47.4-6.9 66.8-18.8c5 11.1 16.2 18.8 29.2 18.8c17.7 0 32-14.3 32-32l0-96 0-64 0-96c0-17.7-14.3-32-32-32c-13 0-24.1 7.7-29.2 18.8C559.4 166.9 536.5 160 512 160c-70.7 0-128 57.3-128 128zm64 0c0-35.3 28.7-64 64-64s64 28.7 64 64l0 64c0 35.3-28.7 64-64 64s-64-28.7-64-64l0-64z" fill="currentColor" style="opacity: 0.4;"></path><path d="M206 52.8C201.3 40.3 189.3 32 176 32s-25.3 8.3-30 20.8L2 436.8c-6.2 16.5 2.2 35 18.7 41.2s35-2.2 41.2-18.7L96.2 368l159.6 0L290 459.2c6.2 16.5 24.7 24.9 41.2 18.7s24.9-24.6 18.7-41.2L206 52.8zM231.8 304l-111.6 0L176 155.1 231.8 304z" fill="currentColor" style="opacity: 1;"></path></svg>
                    <span class="text-xs font-medium">Inter</span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex flex-col gap-y-3 justify-center items-center h-20 w-full text-cat-500 hover:bg-cat-500/10 rounded-xl [&.active]:shadow [&.active]:bg-white dark:[&.active]:bg-cat-500/10 [&.active]:text-cat-900 dark:[&.active]:text-white"
                    data-font="sans"
                    x-on:click="$appearance.setFont($event.currentTarget.dataset.font, true, false)"
                >
                    <svg class="size-5 [.active_&]:text-accent-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,640,512"><path d="M384 288l0 64c0 70.7 57.3 128 128 128c24.5 0 47.4-6.9 66.8-18.8c5 11.1 16.2 18.8 29.2 18.8c17.7 0 32-14.3 32-32l0-96 0-64 0-96c0-17.7-14.3-32-32-32c-13 0-24.1 7.7-29.2 18.8C559.4 166.9 536.5 160 512 160c-70.7 0-128 57.3-128 128zm64 0c0-35.3 28.7-64 64-64s64 28.7 64 64l0 64c0 35.3-28.7 64-64 64s-64-28.7-64-64l0-64z" fill="currentColor" style="opacity: 0.4;"></path><path d="M206 52.8C201.3 40.3 189.3 32 176 32s-25.3 8.3-30 20.8L2 436.8c-6.2 16.5 2.2 35 18.7 41.2s35-2.2 41.2-18.7L96.2 368l159.6 0L290 459.2c6.2 16.5 24.7 24.9 41.2 18.7s24.9-24.6 18.7-41.2L206 52.8zM231.8 304l-111.6 0L176 155.1 231.8 304z" fill="currentColor" style="opacity: 1;"></path></svg>
                    <span class="text-xs font-medium">Sans</span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex flex-col gap-y-3 justify-center items-center h-20 w-full text-cat-500 hover:bg-cat-500/10 rounded-xl [&.active]:shadow [&.active]:bg-white dark:[&.active]:bg-cat-500/10 [&.active]:text-cat-900 dark:[&.active]:text-white"
                    data-font="monospace"
                    x-on:click="$appearance.setFont($event.currentTarget.dataset.font, true, false)"
                >
                    <svg class="size-5 [.active_&]:text-accent-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,640,512"><path d="M384 288l0 64c0 70.7 57.3 128 128 128c24.5 0 47.4-6.9 66.8-18.8c5 11.1 16.2 18.8 29.2 18.8c17.7 0 32-14.3 32-32l0-96 0-64 0-96c0-17.7-14.3-32-32-32c-13 0-24.1 7.7-29.2 18.8C559.4 166.9 536.5 160 512 160c-70.7 0-128 57.3-128 128zm64 0c0-35.3 28.7-64 64-64s64 28.7 64 64l0 64c0 35.3-28.7 64-64 64s-64-28.7-64-64l0-64z" fill="currentColor" style="opacity: 0.4;"></path><path d="M206 52.8C201.3 40.3 189.3 32 176 32s-25.3 8.3-30 20.8L2 436.8c-6.2 16.5 2.2 35 18.7 41.2s35-2.2 41.2-18.7L96.2 368l159.6 0L290 459.2c6.2 16.5 24.7 24.9 41.2 18.7s24.9-24.6 18.7-41.2L206 52.8zM231.8 304l-111.6 0L176 155.1 231.8 304z" fill="currentColor" style="opacity: 1;"></path></svg>
                    <span class="text-xs font-medium">Monospace</span>
                </button>
                <button
                    type="button"
                    class="cursor-pointer flex flex-col gap-y-3 justify-center items-center h-20 w-full text-cat-500 hover:bg-cat-500/10 rounded-xl [&.active]:shadow [&.active]:bg-white dark:[&.active]:bg-cat-500/10 [&.active]:text-cat-900 dark:[&.active]:text-white"
                    data-font="serif"
                    x-on:click="$appearance.setFont($event.currentTarget.dataset.font, true, false)"
                >
                    <svg class="size-5 [.active_&]:text-accent-500" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,640,512"><path d="M384 288l0 64c0 70.7 57.3 128 128 128c24.5 0 47.4-6.9 66.8-18.8c5 11.1 16.2 18.8 29.2 18.8c17.7 0 32-14.3 32-32l0-96 0-64 0-96c0-17.7-14.3-32-32-32c-13 0-24.1 7.7-29.2 18.8C559.4 166.9 536.5 160 512 160c-70.7 0-128 57.3-128 128zm64 0c0-35.3 28.7-64 64-64s64 28.7 64 64l0 64c0 35.3-28.7 64-64 64s-64-28.7-64-64l0-64z" fill="currentColor" style="opacity: 0.4;"></path><path d="M206 52.8C201.3 40.3 189.3 32 176 32s-25.3 8.3-30 20.8L2 436.8c-6.2 16.5 2.2 35 18.7 41.2s35-2.2 41.2-18.7L96.2 368l159.6 0L290 459.2c6.2 16.5 24.7 24.9 41.2 18.7s24.9-24.6 18.7-41.2L206 52.8zM231.8 304l-111.6 0L176 155.1 231.8 304z" fill="currentColor" style="opacity: 1;"></path></svg>
                    <span class="text-xs font-medium">Serif</span>
                </button>
            </div>
        </div>
    </x-sheet.body>
    <x-sheet.footer class="p-3">
        <x-button class="w-full gap-1.5" x-on:click="$appearance.resetAppearance('auto')">
            <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" viewbox="0,0,512,512"><path d="M472 224c13.3 0 24-10.7 24-24l0-144c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 80.1-20-23.5C387 63.4 325.1 32 256 32C132.3 32 32 132.3 32 256s100.3 224 224 224c50.4 0 97-16.7 134.4-44.8c10.6-8 12.7-23 4.8-33.6s-23-12.7-33.6-4.8C332.2 418.9 295.7 432 256 432c-97.2 0-176-78.8-176-176s78.8-176 176-176c54.3 0 102.9 24.6 135.2 63.4l.1 .2s0 0 0 0L418.9 176 328 176c-13.3 0-24 10.7-24 24s10.7 24 24 24l144 0z" fill="currentColor" style="opacity: 1;"></path></svg>
            Reset settings
        </x-button>
    </x-sheet.footer>
</x-sheet>
