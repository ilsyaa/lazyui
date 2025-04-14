import 'simplebar';

document.addEventListener(
    "alpine:init",
    () => {
        const modules = import.meta.glob("./plugins/**/*.js", { eager: true });
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
