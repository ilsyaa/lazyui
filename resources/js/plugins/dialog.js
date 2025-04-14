export default function (Alpine) {
    Alpine.store('dialog', {
        instances: {},

        create({ id, isOpen = false, noscroll = true, fullscreen = false }) {
            if (this.instances[id]) {
                console.error(`Dialog with id "${id}" already exists.`);
                return false;
            }

            this.instances[id] = { isOpen, noscroll, fullscreen };
            return {
                toggle: () => this.toggle(id),
                open: () => this.open(id),
                close: () => this.close(id),
                isOpen: () => this.isOpen(id),
                toggleFullscreen: () => this.toggleFullscreen(id),
                isFullscreen: () => this.isFullscreen(id),
            };
        },

        toggle(id) {
            if (!this.instances[id]) {
                console.error(`Dialog with id "${id}" not found.`);
                return;
            }

            const newState = !this.instances[id].isOpen;
            this.instances[id].isOpen = newState;
            this._emitEvent(id, newState);
            this._handleScrollLock();
        },

        open(id) {
            if (!this.instances[id]) {
                console.error(`Dialog with id "${id}" not found.`);
                return;
            }

            if (!this.instances[id].isOpen) {
                this.instances[id].isOpen = true;
                this._emitEvent(id, true);
                this._handleScrollLock();
            }
        },

        close(id) {
            if (!this.instances[id]) {
                console.error(`Dialog with id "${id}" not found.`);
                return;
            }

            if (this.instances[id].isOpen) {
                this.instances[id].isOpen = false;
                this._emitEvent(id, false);
                this._handleScrollLock();
            }
        },

        isOpen(id) {
            return this.instances[id]?.isOpen || false;
        },

        toggleFullscreen(id) {
            this.close(id);
            setTimeout(() => {
                this.open(id)
                this.instances[id].fullscreen = !this.instances[id].fullscreen;
            }, 150);
        },

        isFullscreen(id = null) {
            return this.instances[id].fullscreen;
        },

        _emitEvent(id, status) {
            window.dispatchEvent(
                new CustomEvent('overlay', {
                    detail:
                    {
                        'dialog': {
                            id,
                            status: status ? 'open' : 'close'
                        }
                    }
                })
            );
        },

        _handleScrollLock() {
            const hasOpenSheetsWithScrollLock = Object.values(this.instances)
                .some(instance => instance.isOpen && instance.noscroll);

            if (hasOpenSheetsWithScrollLock) {
                this._lockScroll(true);
            } else {
                // Use setTimeout to ensure any transition/animation can complete
                setTimeout(() => {
                    if (!Object.values(this.instances).some(instance => instance.isOpen && instance.noscroll)) {
                        this._lockScroll(false);
                    }
                }, 50);
            }
        },

        _lockScroll(lock) {
            const widthScrollbar = window.innerWidth - document.documentElement.clientWidth;
            if (lock) {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = widthScrollbar + 'px';
            } else {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        }
    });

    Alpine.magic('dialog', () => Alpine.store('dialog'));

    Alpine.directive('dialog', (el, directive, { evaluate }) => {
        if (directive.value == 'toggle') {
            el.addEventListener('click', () => {
                if (directive.expression == 'x-dialog:toggle' || directive.expression == '') throw new Error('[LazyUI] Dialog requires an id. ex: x-sheet:toggle="id"');
                Alpine.store('dialog').toggle(directive.expression);
            });
        } else if (directive.value == 'close' || directive.value == 'open') {
            el.addEventListener('click', () => {
                let $id = directive.expression;
                if (directive.expression == 'x-dialog:close' || directive.expression == 'x-dialog:open' || directive.expression == '') {
                    $id = el.closest('.lazy-dialog-wrapper')?.getAttribute('id');
                    if (!$id) throw new Error('[LazyUI] Dialog requires an id. ex: x-dialog:close="id"');
                }
                if(directive.value == 'open') {
                    Alpine.store('dialog').open($id)
                } else {
                    Alpine.store('dialog').close($id);
                }
            });
        }
    }).before('bind');
}
