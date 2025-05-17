class Alert {
    constructor() {
        this.alerts = [];
        this.wrapperElement = null;
        this.isTransitioning = false;
        this.eventEsc = this.#_onEsc.bind(this);
    }

    #_createWrapper() {
        if (this.wrapperElement) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'fixed size-full z-[99] p-4 top-0 left-0 flex sm:items-center items-end justify-center bg-black/10 backdrop-blur-[1px] opacity-0 transition-opacity duration-200 ease-out';
        wrapper.onclick = (e) => {
            if (e.target === wrapper && this.alerts.length && !this.isTransitioning) {
                this.#_remove(this.alerts[0].id);
            }
        };

        this.wrapperElement = wrapper;
        document.body.appendChild(wrapper);
        requestAnimationFrame(() => wrapper.classList.replace('opacity-0', 'opacity-100'));
        this.#_lockScroll(true);
        this.#_bindEscHandler();
    }

    #_destroyWrapper() {
        if (!this.wrapperElement) return;

        this.isTransitioning = true;
        this.wrapperElement.classList.replace('opacity-100', 'opacity-0');

        const fallbackTimeout = setTimeout(() => this.#_cleanupWrapper(), 300);
        this.wrapperElement.addEventListener('transitionend', () => {
            clearTimeout(fallbackTimeout);
            this.#_cleanupWrapper();
        }, { once: true });

        this.#_unbindEscHandler();
    }

    #_cleanupWrapper() {
        if (this.wrapperElement?.parentNode) {
            document.body.removeChild(this.wrapperElement);
            this.wrapperElement = null;
            this.#_lockScroll(false);
        }
        this.isTransitioning = false;
    }

    #_lockScroll(lock) {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.overflow = lock ? 'hidden' : '';
        document.body.style.paddingRight = lock ? `${scrollbarWidth}px` : '';
    }

    #_bindEscHandler() {
        document.addEventListener('keydown', this.eventEsc);
    }

    #_unbindEscHandler() {
        document.removeEventListener('keydown', this.eventEsc);
    }

    #_onEsc(e) {
        if (e.key === 'Escape' && this.alerts.length && !this.isTransitioning) {
            this.#_remove(this.alerts[0].id);
        }
    }

    show({
        type = '',
        title = '',
        text = '',
        confirmText = 'Yes',
        confirmClass = 'bg-cat-900 dark:bg-white text-white dark:text-cat-900 shadow hover:bg-cat-800/90 dark:hover:bg-white/90',
        cancelText = false,
        cancelClass = 'bg-cat-300 dark:bg-cat-700 text-cat-900 dark:text-white shadow-sm',
        duration = false,
    } = {}) {
        return new Promise((resolve) => {
            if (this.isTransitioning) {
                setTimeout(() => this.show({ type, title, text, confirmText, confirmClass, cancelText, cancelClass, duration, style }).then(resolve), 50);
                return;
            }

            const id = `zalert-${Math.floor(Math.random() * 100000)}`;
            this.#_createWrapper();

            const alertElement = this.#_createAlertElement({
                id, type, title, text, confirmText, confirmClass,
                cancelText, cancelClass, duration, resolve
            });

            this.alerts.unshift({ id, element: alertElement });
            this.wrapperElement.appendChild(alertElement);

            if (duration) {
                setTimeout(() => !this.isTransitioning && this.#_remove(id), duration);
            }
        });
    }

    #_remove(id) {
        if (this.isTransitioning) return;

        const alert = this.alerts.find(a => a.id === id);
        if (!alert) return;

        this.isTransitioning = true;
        this.alerts = this.alerts.filter(a => a.id !== id);

        const el = alert.element;
        el.style.transform = 'scale(0.8)';
        el.style.opacity = '0';

        const fallbackTimeout = setTimeout(() => this.#_removeAlertElement(el), 300);
        el.addEventListener('transitionend', (e) => {
            if (e.propertyName === 'opacity') {
                clearTimeout(fallbackTimeout);
                this.#_removeAlertElement(el);
            }
        }, { once: true });
    }

    #_removeAlertElement(el) {
        el.remove();
        if (!this.alerts.length) {
            this.#_destroyWrapper();
        } else {
            this.isTransitioning = false;
        }
    }

    #_createAlertElement({
        id, type, title, text, confirmText, confirmClass,
        cancelText, cancelClass, resolve
    }) {
        const div = document.createElement('div');
        div.id = id;
        div.className = 'w-full max-w-md transition-[transform,opacity] ease-out duration-150';

        const isCenter = this.#_style(type);
        div.innerHTML = `<div class="bg-white dark:bg-cat-800 rounded-2xl p-5 w-full ${isCenter ? 'style-center' : ''} motion-duration-300 motion-preset-pop"><div class="flex flex-col w-full"><div class="flex gap-3 ${this.#_style(type) ? 'flex-col items-center' : 'flex-row items-start'}">${this.#_style(type)}<div class="${isCenter ? 'text-center mx-3' : 'mt-0 text-left'}"><h3 class="text-lg font-semibold leading-6">${title}</h3><div class="mt-2"><p class="text-sm">${text}</p></div></div></div><div class="flex mt-5 gap-3 ${isCenter ? 'justify-center' : 'justify-end'}" id="button-wrapper"></div></div></div>`;
        const wrapper = div.querySelector('#button-wrapper');

        if (cancelText) {
            const cancelBtn = this.#_createButton(cancelClass, cancelText, () => {
                this.#_remove(id);
                resolve({ confirmed: false });
            });
            wrapper.appendChild(cancelBtn);
        }

        const confirmBtn = this.#_createButton(confirmClass, confirmText, () => {
            this.#_remove(id);
            resolve({ confirmed: true });
        });
        wrapper.appendChild(confirmBtn);

        return div;
    }

    #_style(type) {
        switch (type) {
            case 'info':
                return `<div class="w-16 h-16 rounded-full bg-sky-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-7 text-sky-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg></div>`;
            case 'success':
                return `<div class="w-16 h-16 rounded-full bg-emerald-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-7 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM241 337l-17 17-17-17-80-80L161 223l63 63L351 159 385 193 241 337z"/></svg></div>`;
            case 'error':
                return `<div class="w-16 h-16 rounded-full bg-orange-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-7 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M0 256L128 32H384L512 256 384 480H128L0 256zM280 128H232v24V264v24h48V264 152 128zM232 320v48h48V320H232z"/></svg></div>`;
            case 'warning':
                return `<div class="w-16 h-16 rounded-full bg-yellow-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-7 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480H40c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24V296c0 13.3 10.7 24 24 24s24-10.7 24-24V184c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"/></svg></div>`;
            case 'loading':
                return `<div class="w-16 h-16 rounded-full bg-cat-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-7 text-cat-500 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</style></defs><path fill="currentColor" class="fa-secondary" d="M256 64C150 64 64 150 64 256s86 192 192 192c70.1 0 131.3-37.5 164.9-93.6l.1 .1c-6.9 14.9-1.5 32.8 13 41.2c15.3 8.9 34.9 3.6 43.7-11.7c.2-.3 .4-.6 .5-.9l0 0C434.1 460.1 351.1 512 256 512C114.6 512 0 397.4 0 256S114.6 0 256 0c-17.7 0-32 14.3-32 32s14.3 32 32 32z"/><path fill="currentColor" class="fa-primary" d="M224 32c0-17.7 14.3-32 32-32C397.4 0 512 114.6 512 256c0 46.6-12.5 90.4-34.3 128c-8.8 15.3-28.4 20.5-43.7 11.7s-20.5-28.4-11.7-43.7c16.3-28.2 25.7-61 25.7-96c0-106-86-192-192-192c-17.7 0-32-14.3-32-32z"/></svg></div>`;
            default:
                return ``;
        }
    }

    #_createButton(className, text, onClick) {
        const base = 'relative inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2';
        const btn = document.createElement('button');
        btn.className = base + ' ' + className;
        btn.textContent = text;
        btn.onclick = onClick;
        return btn;
    }
}

const alert = new Alert();
window.zalert = (options) => alert.show(options);
