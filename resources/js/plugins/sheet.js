export default function (Alpine) {
    Alpine.store('sheet', {
        instances: {},

        create({ id, isOpen = false, noscroll = true }) {
            if (this.instances[id]) {
                console.error(`Sheet with id "${id}" already exists.`);
                return false;
            }

            this.instances[id] = { isOpen, noscroll };
            return {
                toggle: () => this.toggle(id),
                open: () => this.open(id),
                close: () => this.close(id),
                isOpen: () => this.isOpen(id),
            };
        },

        toggle(id) {
            if (!this.instances[id]) {
                console.error(`Sheet with id "${id}" not found.`);
                return;
            }

            const newState = !this.instances[id].isOpen;
            this.instances[id].isOpen = newState;
            this._emitEvent(id, newState);
            this._handleScrollLock();
        },

        open(id) {
            if (!this.instances[id]) {
                console.error(`Sheet with id "${id}" not found.`);
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
                console.error(`Sheet with id "${id}" not found.`);
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

        _emitEvent(id, status) {
            window.dispatchEvent(
                new CustomEvent('overlay', {
                    detail:
                    {
                        'sheet': {
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

    Alpine.magic('sheet', () => Alpine.store('sheet'));

    Alpine.directive('sheet', (el, directive, { evaluate }) => {
        if (directive.value == 'toggle') {
            el.addEventListener('click', () => {
                if (directive.expression == 'x-sheet:toggle' || directive.expression == '') throw new Error('[LazyUI] Sheet requires an id. ex: x-sheet:toggle="id"');
                Alpine.store('sheet').toggle(directive.expression);
            });
        } else if (directive.value == 'close' || directive.value == 'open') {
            el.addEventListener('click', () => {
                let $id = directive.expression;
                if (directive.expression == 'x-sheet:close' || directive.expression == 'x-sheet:open' || directive.expression == '') {
                    $id = el.closest('.lazy-sheet-wrapper')?.getAttribute('id');
                    if (!$id) throw new Error('[LazyUI] Sheet requires an id. ex: x-sheet:close="id"');
                }
                if(directive.value == 'open') {
                    Alpine.store('sheet').open($id)
                } else {
                    Alpine.store('sheet').close($id);
                }
            });
        }
    }).before('bind');
}
