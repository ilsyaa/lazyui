<aside class="lazy-sidebar" x-data="{}">
    <div class="lazy-sidebar-ghost-layout"></div>
    <div
        x-on:click="$appearance.setAside('toggle', true, false)"
        class="lazy-sidebar-toggle"
    >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg>
    </div>
    <div
        x-trap.inert.noscroll="$store.sidebar.isOpen"
        @keydown.escape.window="$store.sidebar.close()"
        :class="{'translate-x-0': $store.sidebar.isOpen}"
        class="lazy-sidebar-wrapper lazy-gradient"
    >
        <div class="lazy-sidebar-body">
            <div class="lazy-sidebar-header">
                <a class="lazy-sidebar-brand" href="/" aria-label="Brand">{{ config('app.name') }}</a>
            </div>
            <div class="lazy-sidebar-scroll-content" data-simplebar>
                <nav>
                    @include('[path].layouts.menu')
                </nav>
            </div>
        </div>
    </div>
    <div
        x-show="$store.sidebar.isOpen"
        x-transition.opacity
        x-cloak
        @click="$store.sidebar.close()"
        class="lazy-sidebar-backdrop"
    ></div>
</aside>
