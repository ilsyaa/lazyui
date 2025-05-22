class Toast {
    constructor() {
        this.toasts = [];
        this.limit = 0;
        this.placement = 'top-right';
        this.duration = 0;
        this.#init();

        const handler = (options) => this.#show(options);
        handler.info = (options) => this.#showWithType('info', options);
        handler.success = (options) => this.#showWithType('success', options);
        handler.error = (options) => this.#showWithType('error', options);
        handler.warning = (options) => this.#showWithType('warning', options);
        handler.loading = (options) => this.#showWithType('loading', options);

        return handler;
    }

    #init() {
        const wrapper = document.querySelector('[data-lazy-toast]');
        if (!wrapper) return console.warn('[LazyToast] wrapper not found. please read the documentation.');
        const data = JSON.parse(wrapper.dataset.lazyToast);
        this.limit = data.max || 0;
        this.placement = data.placement || 'top-right';
        this.duration = data.duration || 3000;
        this.wrapper = wrapper;
    }

    #show(options) {
        const id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 5)}`;
        const div = document.createElement('div');
        div.id = id;
        div.className = 'absolute w-full break-words wrap-anywhere select-none';
        div.append(this.#renderToastHTML(options));
        this.wrapper.append(div);
        this.toasts.unshift({
            id,
            element: div,
            ...options
        });
        this.#updateToasts();

        if (options.type !== 'loading') {
            setTimeout(() => {
                this.#remove(id);
            }, this.duration);
        }

        const toastUpdate = (options) => {
            const toast = this.toasts.find((t) => t.id === id);
            if (!toast) return;
            toast.element.querySelector('div').innerHTML = `<div>${this.#style(options.type)}</div><div class="flex-auto flex flex-col justify-center mr-3">${options?.title ? `<div class="text-sm inherit">${options.title}</div>` : ''}${options?.message ? `<div class="text-xs text-cat-500">${options.message}</div>` : ''}</div>`;
            setTimeout(() => {
                this.#remove(id);
            }, this.duration);
        }

        return {
            toast: toastUpdate.bind(this),
            ...['info', 'success', 'warning', 'error'].reduce((acc, type) => {
                acc[type] = (options) => {
                    const payload = typeof options === 'string' ? { type, title: options } : { type, ...options };
                    toastUpdate(payload);
                };
                return acc;
            }, {}),
            remove: () => this.#remove(id)
        };
    }

    #remove(id) {
        const toast = this.toasts.find((t) => t.id === id);
        if (!toast) return;
        toast.element.querySelector('div').classList.remove('opacity-100', 'translate-y-0');
        if (this.placement.startsWith('bottom')) {
            toast.element.querySelector('div').classList.add('opacity-0', 'translate-y-15');
        } else {
            toast.element.querySelector('div').classList.add('opacity-0', '-translate-y-15');
        }
        toast.element.addEventListener('transitionend', () => {
            toast.element.remove();
            this.toasts.splice(this.toasts.indexOf(toast), 1);
            this.#updateToasts();
        }, { once: true });
    }

    #updateToasts() {
        const MAX_TOASTS = this.limit;
        const fromBottom = this.placement.startsWith('bottom');
        let offset = 0;

        this.toasts.slice(0, MAX_TOASTS).forEach((toast, index) => {
            toast.element.style.transition = 'all 200ms linear';
            toast.element.style.display = '';

            if (fromBottom) {
                toast.element.style.bottom = `${offset}px`;
                toast.element.style.top = '';
            } else {
                toast.element.style.top = `${offset}px`;
                toast.element.style.bottom = '';
            }

            const toastHeight = toast.element.offsetHeight || 65;
            offset += toastHeight + 8;
        });

        // Sembunyikan toast yang melewati limit
        this.toasts.slice(MAX_TOASTS).forEach((toast) => {
            toast.element.querySelector('div').classList.remove('opacity-100', 'translate-y-0');
            if (this.placement.startsWith('bottom')) {
                toast.element.querySelector('div').classList.add('opacity-0', '-translate-y-15');
            } else {
                toast.element.querySelector('div').classList.add('opacity-0', 'translate-y-15');
            }
            this.toasts.splice(this.toasts.indexOf(toast), 1);
            toast.element.addEventListener('transitionend', () => {
                toast.element.remove();
            }, { once: true });
        });
    }

    #renderToastHTML(options) {
        const toast = document.createElement('div')
        toast.className = `transition-all ease-in-out duration-300 opacity-0 ${this.placement.startsWith('bottom') ? 'translate-y-15' : '-translate-y-15'} bg-white dark:bg-cat-800 shadow p-1.5 rounded-xl flex gap-3`;
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                if (this.placement.startsWith('bottom')) {
                    toast.classList.remove('translate-y-15', 'opacity-0');
                } else {
                    toast.classList.remove('opacity-0', '-translate-y-15');
                }
                toast.classList.add('opacity-100', 'translate-y-0');
            });
        });
        toast.innerHTML = `${this.#style(options.type)}<div class="flex-auto flex flex-col justify-center mr-3">${options?.title ? `<div class="text-sm inherit">${options.title}</div>` : ''}${options?.message ? `<div class="text-xs text-cat-500">${options.message}</div>` : ''}</div>`;
        return toast;
    }

    #showWithType(type, options) {
        if (typeof options === 'string') {
            return this.#show({ type, title: options });
        } else {
            return this.#show({ type, ...options });
        }
    }

    #style(type) {
        switch (type) {
            case 'info':
                return `<div class="w-12 h-12 rounded-xl bg-sky-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-6 text-sky-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10m-10 5.75a.75.75 0 0 0 .75-.75v-6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75M12 7a1 1 0 1 1 0 2a1 1 0 0 1 0-2" clip-rule="evenodd"/></svg></div>`;
            case 'success':
                return `<div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-6 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10m-5.97-3.03a.75.75 0 0 1 0 1.06l-5 5a.75.75 0 0 1-1.06 0l-2-2a.75.75 0 1 1 1.06-1.06l1.47 1.47l2.235-2.235L14.97 8.97a.75.75 0 0 1 1.06 0" clip-rule="evenodd"/></svg></div>`;
            case 'error':
                return `<div class="w-12 h-12 rounded-xl bg-orange-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-6 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10M8.97 8.97a.75.75 0 0 1 1.06 0L12 10.94l1.97-1.97a.75.75 0 0 1 1.06 1.06L13.06 12l1.97 1.97a.75.75 0 0 1-1.06 1.06L12 13.06l-1.97 1.97a.75.75 0 0 1-1.06-1.06L10.94 12l-1.97-1.97a.75.75 0 0 1 0-1.06" clip-rule="evenodd"/></svg></div>`;
            case 'warning':
                return `<div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12s4.477 10 10 10s10-4.477 10-10M12 6.25a.75.75 0 0 1 .75.75v6a.75.75 0 0 1-1.5 0V7a.75.75 0 0 1 .75-.75M12 17a1 1 0 1 0 0-2a1 1 0 0 0 0 2" clip-rule="evenodd"/></svg></div>`;
            case 'loading':
                return `<div class="w-12 h-12 rounded-xl bg-cat-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-5 text-cat-500 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M256 64C150 64 64 150 64 256s86 192 192 192c70.1 0 131.3-37.5 164.9-93.6l.1 .1c-6.9 14.9-1.5 32.8 13 41.2c15.3 8.9 34.9 3.6 43.7-11.7c.2-.3 .4-.6 .5-.9l0 0C434.1 460.1 351.1 512 256 512C114.6 512 0 397.4 0 256S114.6 0 256 0c-17.7 0-32 14.3-32 32s14.3 32 32 32z"/><path fill="currentColor" class="fa-primary" d="M224 32c0-17.7 14.3-32 32-32C397.4 0 512 114.6 512 256c0 46.6-12.5 90.4-34.3 128c-8.8 15.3-28.4 20.5-43.7 11.7s-20.5-28.4-11.7-43.7c16.3-28.2 25.7-61 25.7-96c0-106-86-192-192-192c-17.7 0-32-14.3-32-32z"/></svg></div>`;
            default:
                return `<div class="w-12 h-12 rounded-xl bg-cat-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-5 text-cat-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/></svg></div>`;
        }
    }
}

const toast = new Toast();
window.toast = toast;
