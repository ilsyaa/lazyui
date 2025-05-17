@props([
    'placement' => 'top-right',
    'max' => 4,
    'duration' => 3000
])

@php
    $classPlacement = [
        'top-right' => 'sm:right-4 sm:-translate-x-0 top-4 right-1/2 translate-x-1/2',
        'top-left' => 'sm:left-4 sm:-translate-x-0 top-4 left-1/2 -translate-x-1/2',
        'top-center' => 'left-1/2 -translate-x-1/2 top-5',
        'bottom-right' => 'sm:right-4 sm:-translate-x-0 bottom-4 right-1/2 translate-x-1/2',
        'bottom-left' => 'sm:left-4 sm:-translate-x-0 bottom-4 left-1/2 -translate-x-1/2',
        'bottom-center' => 'left-1/2 -translate-x-1/2 bottom-5',
    ]
@endphp

<div
    {{
        $attributes
            ->twMerge([ 'fixed w-full z-[99] max-w-[95%] sm:max-w-2xs', $classPlacement[$placement] ?? $classPlacement['top-right'] ])
    }}
    data-lazy-toast='@json(["placement" => $placement, "max" => $max, "duration" => $duration])'
    aria-label="LazyToast Notification"
    tabindex="-1"
></div>

<script> document.addEventListener('toast', ({ detail }) => { detail.forEach(d => { toast(d); }); }); </script>
@if (session('toast')) <script> document.addEventListener('DOMContentLoaded', () => { toast(@js(session('toast'))); }) </script> @endif
