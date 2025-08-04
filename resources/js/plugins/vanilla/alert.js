class Alert {
    constructor() {
        this.activeAlerts = new Map();
        this.wrapperElement = null;
        this.isTransitioning = false;
        this.boundEscHandler = this.#onEsc.bind(this);
        this.nextZIndex = 1000;
        this.focusedElementBeforeAlert = null;
    }

    #createWrapper() {
        if (this.wrapperElement) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'fixed inset-0 z-[99] p-4 flex items-center justify-center bg-black/10 backdrop-blur-[1px] opacity-0 transition-opacity duration-200 ease-out';
        wrapper.addEventListener('click', (e) => {
            if (e.target === wrapper && this.activeAlerts.size > 0 && !this.isTransitioning) {
                let topAlertId = null;
                let maxZIndex = -1;
                for (const [id, entry] of this.activeAlerts.entries()) {
                    if (entry.element && parseInt(entry.element.style.zIndex || 0) > maxZIndex) {
                        maxZIndex = parseInt(entry.element.style.zIndex || 0);
                        topAlertId = id;
                    }
                }
                if (topAlertId) {
                    this.#remove(topAlertId, { confirmed: false, dismissedBy: 'overlay' });
                }
            }
        });

        this.wrapperElement = wrapper;
        document.body.appendChild(wrapper);

        requestAnimationFrame(() => {
            if (this.wrapperElement) {
                this.wrapperElement.classList.replace('opacity-0', 'opacity-100');
            }
        });

        this.#lockScroll(true);
        this.#bindEscHandler();

        // Simpan elemen yang sedang fokus sebelum alert muncul
        this.focusedElementBeforeAlert = document.activeElement;
    }

    async #destroyWrapper() {
        if (!this.wrapperElement || this.activeAlerts.size > 0) return;

        this.isTransitioning = true;
        this.wrapperElement.classList.replace('opacity-100', 'opacity-0');

        await new Promise(resolve => {
            const handleTransitionEnd = () => {
                this.wrapperElement?.removeEventListener('transitionend', handleTransitionEnd);
                resolve();
            };
            this.wrapperElement?.addEventListener('transitionend', handleTransitionEnd, { once: true });
            setTimeout(resolve, 300);
        });

        this.#cleanupWrapper();
        this.#unbindEscHandler();
        this.isTransitioning = false;
        this.nextZIndex = 1000;

        // Kembalikan fokus ke elemen yang sebelumnya aktif
        if (this.focusedElementBeforeAlert && typeof this.focusedElementBeforeAlert.focus === 'function') {
            this.focusedElementBeforeAlert.focus();
            this.focusedElementBeforeAlert = null; // Bersihkan referensi
        }
    }

    #cleanupWrapper() {
        if (this.wrapperElement?.parentNode) {
            this.wrapperElement.remove();
            this.wrapperElement = null;
            this.#lockScroll(false);
        }
    }

    #lockScroll(lock) {
        if (lock) {
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = `${scrollbarWidth}px`;
        } else {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }
    }

    #bindEscHandler() {
        document.addEventListener('keydown', this.boundEscHandler);
    }

    #unbindEscHandler() {
        document.removeEventListener('keydown', this.boundEscHandler);
    }

    #onEsc(e) {
        if (e.key === 'Escape' && this.activeAlerts.size > 0 && !this.isTransitioning) {
            let topAlertId = null;
            let maxZIndex = -1;
            for (const [id, entry] of this.activeAlerts.entries()) {
                if (entry.element && parseInt(entry.element.style.zIndex || 0) > maxZIndex) {
                    maxZIndex = parseInt(entry.element.style.zIndex || 0);
                    topAlertId = id;
                }
            }
            if (topAlertId) {
                this.#remove(topAlertId, { confirmed: false, dismissedBy: 'escape' });
            }
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
        return new Promise(async (resolve) => {
            const id = `zalert-${Math.random().toString(36).substring(2, 11)}`;
            this.#createWrapper();

            this.isTransitioning = true;
            this.nextZIndex++;

            const alertElement = this.#createAlertElement({
                id, type, title, text, confirmText, confirmClass,
                cancelText, cancelClass,
                onConfirm: () => this.#remove(id, { confirmed: true, dismissedBy: 'confirm' }),
                onCancel: () => this.#remove(id, { confirmed: false, dismissedBy: 'cancel' }),
            });

            alertElement.style.zIndex = this.nextZIndex;
            alertElement.style.position = 'absolute';
            alertElement.style.transform = 'scale(0.9)';
            alertElement.style.opacity = '0';

            this.activeAlerts.set(id, { element: alertElement, resolve });
            this.wrapperElement?.appendChild(alertElement);

            await new Promise(resolveTransition => {
                const handleTransitionEnd = (e) => {
                    if (e.target === alertElement && e.propertyName === 'opacity') {
                        alertElement.removeEventListener('transitionend', handleTransitionEnd);
                        resolveTransition();
                    }
                };
                alertElement.addEventListener('transitionend', handleTransitionEnd);
                requestAnimationFrame(() => {
                    alertElement.style.transform = 'scale(1)';
                    alertElement.style.opacity = '1';
                    alertElement.style.transition = 'transform 0.15s ease-out, opacity 0.15s ease-out';
                });
                setTimeout(resolveTransition, 160);
            });

            this.isTransitioning = false;

            const confirmButton = alertElement.querySelector(`#${id} .${confirmClass.split(' ')[0]}`); // Ambil tombol konfirmasi
            if (confirmButton && typeof confirmButton.focus === 'function') {
                confirmButton.focus();
            } else {
                alertElement.setAttribute('tabindex', '-1');
                alertElement.focus();
            }


            if (duration) {
                setTimeout(() => {
                    if (this.activeAlerts.has(id)) {
                        this.#remove(id, { confirmed: false, dismissedBy: 'timeout' });
                    }
                }, duration);
            }
        });
    }

    async #remove(id, result) {
        if (this.isTransitioning) return;

        const alertEntry = this.activeAlerts.get(id);
        if (!alertEntry || !alertEntry.element) return;

        this.isTransitioning = true;
        const { element: el, resolve } = alertEntry;

        el.style.transform = 'scale(0.9)';
        el.style.opacity = '0';
        el.style.transition = 'transform 0.15s ease-out, opacity 0.15s ease-out';


        await new Promise(resolveTransition => {
            const handleTransitionEnd = (e) => {
                if (e.target === el && e.propertyName === 'opacity') {
                    el.removeEventListener('transitionend', handleTransitionEnd);
                    resolveTransition();
                }
            };
            el.addEventListener('transitionend', handleTransitionEnd);
            setTimeout(resolveTransition, 160);
        });

        el.remove();
        this.activeAlerts.delete(id);
        resolve(result);

        this.isTransitioning = false;

        if (this.activeAlerts.size === 0) {
            this.#destroyWrapper();
        }
    }

    #createAlertElement({
        id, type, title, text, confirmText, confirmClass,
        cancelText, cancelClass, onConfirm, onCancel
    }) {
        const div = document.createElement('div');
        div.id = id;
        div.className = 'w-full max-w-md';

        div.setAttribute('tabindex', '-1');
        div.setAttribute('role', 'dialog');
        div.setAttribute('aria-modal', 'true');

        const iconHtml = this.#getIconHtml(type);
        const isCenter = iconHtml !== '';
        const titleAlignClass = isCenter ? 'text-center mx-3' : 'mt-0 text-left';
        const buttonJustifyClass = isCenter ? 'justify-center' : 'justify-end';
        const iconContainerClass = isCenter ? 'flex-col items-center' : 'flex-row items-start';

        div.innerHTML = `
            <div class="bg-white dark:bg-cat-800 rounded-2xl p-5 w-full motion-duration-300 motion-preset-pop">
                <div class="flex flex-col w-full">
                    <div class="flex gap-3 ${iconContainerClass}">
                        ${iconHtml}
                        <div class="${titleAlignClass}">
                            <h3 class="text-lg font-semibold leading-6">${title}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300">${text}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex mt-5 gap-3 ${buttonJustifyClass}" id="button-wrapper-${id}"></div>
                </div>
            </div>
        `;

        const wrapper = div.querySelector(`#button-wrapper-${id}`);

        let confirmBtn;

        if (cancelText) {
            const cancelBtn = this.#createButton(cancelClass, cancelText, onCancel);
            wrapper.appendChild(cancelBtn);
        }

        confirmBtn = this.#createButton(confirmClass, confirmText, onConfirm);
        wrapper.appendChild(confirmBtn);

        confirmBtn.setAttribute('data-alert-action', 'confirm');

        return div;
    }

    #getIconHtml(type) {
        switch (type) {
            case 'info':
                return `<div class="w-16 h-16 rounded-full bg-sky-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-10 text-sky-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M12 17v-6"/><circle cx="1" cy="1" r="1" fill="currentColor" transform="matrix(1 0 0 -1 11 9)"/><path opacity="0.5" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M7 3.338A9.95 9.95 0 0 1 12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12c0-1.821.487-3.53 1.338-5"/></g></svg></div>`;
            case 'success':
                return `<div class="w-16 h-16 rounded-full bg-emerald-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-10 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10" opacity="0.5"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.5 12.5l2 2l5-5"/></g></svg></div>`;
            case 'error':
                return `<div class="w-16 h-16 rounded-full bg-orange-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10" opacity="0.5"/><path stroke-linecap="round" d="m14.5 9.5l-5 5m0-5l5 5"/></g></svg></div>`;
            case 'warning':
                return `<div class="w-16 h-16 rounded-full bg-yellow-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-10 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" opacity="0.5"/><path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M12 7v6"/><circle cx="12" cy="16" r="1" fill="currentColor"/></g></svg></div>`;
            case 'loading':
                return `<div class="w-16 h-16 rounded-full bg-cat-500/10 flex justify-center items-center flex-shrink-0"><svg class="size-10 text-cat-500 animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.4}</styledefs><path fill="currentColor" class="fa-secondary" d="M256 64C150 64 64 150 64 256s86 192 192 192c70.1 0 131.3-37.5 164.9-93.6l.1 .1c-6.9 14.9-1.5 32.8 13 41.2c15.3 8.9 34.9 3.6 43.7-11.7c.2-.3 .4-.6 .5-.9l0 0C434.1 460.1 351.1 512 256 512C114.6 512 0 397.4 0 256S114.6 0 256 0c-17.7 0-32 14.3-32 32s14.3 32 32 32z"/><path fill="currentColor" class="fa-primary" d="M224 32c0-17.7 14.3-32 32-32C397.4 0 512 114.6 512 256c0 46.6-12.5 90.4-34.3 128c-8.8 15.3-28.4 20.5-43.7 11.7s-20.5-28.4-11.7-43.7c16.3-28.2 25.7-61 25.7-96c0-106-86-192-192-192c-17.7 0-32-14.3-32-32z"/></svg></div>`;
            default:
                return ``;
        }
    }

    #createButton(className, text, onClick) {
        const base = 'relative inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors cursor-pointer focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2';
        const btn = document.createElement('button');
        btn.className = base + ' ' + className;
        btn.textContent = text;
        btn.onclick = onClick;
        return btn;
    }
}

const alertInstance = new Alert();
window.zalert = (options) => alertInstance.show(options);
