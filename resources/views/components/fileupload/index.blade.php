@props([
    'name' => 'attachment',
    'existingFiles' => [],
    'base_url' => '',
    'id' => 'lazy-filepond-' . \Illuminate\Support\Str::uuid(),
])

<div class="w-full">
    <input
        type="file"
        id="{{ $id }}"
        name="{{ $name }}{{ $attributes->has('multiple') ? '[]' : '' }}"
        {{ $attributes->only(['multiple', 'accept']) }}
    />
</div>

@push('body')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            window.initFilePond({
                selector: @js($id),
                existingFiles: @json($existingFiles),
                base_url: @js($base_url),
                accept: @js($attributes->get('accept')),
                max: @js($attributes->get('max'))
            });
        })
    </script>
@endpush
