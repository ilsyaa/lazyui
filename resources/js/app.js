import 'simplebar';

import.meta.glob("./plugins/vanilla/*.js", { eager: true });

document.addEventListener(
    "alpine:init",
    () => {
        const modules = import.meta.glob("./plugins/alpine/*.js", { eager: true });
        for (const path in modules) {
            window.Alpine.plugin(modules[path].default);
        }
    },
    { once: true },
);

Alpine.store('sidebar', {
    isOpen: false,
    toggle() {
        this.isOpen = !this.isOpen;
    },
    close() {
        this.isOpen = false;
    },
    open() {
        this.isOpen = true;
    }
});

window.LazyUI = {
    version: '1.0.5',
    name: 'LazyUI',
    author: 'Ilsya',
    license: 'MIT',
    github: 'https://github.com/ilsyaa/lazyui'
}
