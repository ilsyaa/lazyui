<div x-data="{ menuOpen: false }" {{ $attributes }} x-dropdown-menu x-model="menuOpen" class="inline-block">
    {{ $slot }}
</div>
