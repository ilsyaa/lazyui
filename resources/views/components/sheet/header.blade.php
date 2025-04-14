@props([
    'title'
])

<div class="flex justify-between items-center py-2 px-4">
    <h3 class="font-semibold text-cat-800 dark:text-white py-2">
        {{ $title }}
    </h3>
    <div class="flex flex-row items-center">
        <x-button class="hover:bg-cat-300 dark:hover:bg-cat-750 rounded-full size-8" variant="ghost" size="icon" x-sheet:close>
            <svg class="size-4 text-cat-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M345 137c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-119 119L73 103c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l119 119L39 375c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l119-119L311 409c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-119-119L345 137z"/></svg>
        </x-button>
    </div>
</div>
