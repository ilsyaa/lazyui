<option {{ $attributes }}>
    @isset($icon)
    <div data-icon>{{ $icon }}</div>
    @endif
    @isset ($label)
    <div data-label>{{ $label }}</div>
    @endif
    @isset ($description)
    <div data-description>{{ $description }}</div>
    @endif
</option>
