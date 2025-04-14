<div x-data="{ menuOpen: false }" {{ $attributes }}>
    <div x-dropdown-menu x-model="menuOpen" class="relative flex items-center">
        {{ $slot }}
    </div>
</div>
