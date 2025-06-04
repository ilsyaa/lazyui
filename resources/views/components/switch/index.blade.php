@props([
    'size' => 'base',
    'class' => null,
    'variant' => 'default',
])

<label role="switch" class="inline-block cursor-pointer">
    <input type="checkbox" {{ $attributes }} class="sr-only peer" />
    <div
        @class([
            "relative peer-focus:outline-none peer-focus:ring-1 peer-focus:ring-black/10 peer-focus:dark:ring-white/10 rounded-full peer-checked:after:translate-x-full after:content-[''] after:absolute after:rounded-full after:transition-all peer-disabled:opacity-80 peer-disabled:cursor-default",
            // size
            'min-w-[26px] min-h-[15px] after:size-[11px] after:top-[2px] after:start-[2px]' => ($size === 'xxs'),
            "min-w-7 min-h-4 after:size-3 after:top-[2px] after:start-[2px]" => ($size === 'xs'),
            "min-w-8 min-h-[18px] after:size-3.5 after:top-[2px] after:start-[2px]" => ($size === 'sm'),
            "min-w-9 min-h-5 after:size-4 after:top-[2px] after:start-[2px]" => ($size === 'base'),
            "min-w-11 min-h-6 after:size-5 after:top-[2px] after:start-[2px]" => ($size === 'lg'),

            // color
            "bg-cat-300 dark:bg-cat-700 peer-checked:after:bg-white peer-checked:dark:after:bg-cat-800 after:bg-white dark:after:bg-white",
            "peer-checked:bg-cat-800 dark:peer-checked:bg-white" => ($variant === 'default'),
            "peer-checked:bg-accent-500 dark:peer-checked:bg-accent-500 after:!bg-white" => ($variant === 'accent'),
            $class,
        ])
    ></div>
</label>
