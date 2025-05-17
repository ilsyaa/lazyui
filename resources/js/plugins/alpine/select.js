export default function (Alpine) {
    Alpine.data('lazySelect', () => ({
        _isOpen: false,
        _options: [],
        _search: '',

        init() {
            this.$nextTick(() => {
                const currentValue = this.$refs.selectOrigin.value;
                const querySelectOptions = Array.from(this.$refs.selectOrigin.querySelectorAll('option'));
                const hasMatchingOption = querySelectOptions.some(option => option.value === currentValue);
                if (querySelectOptions.length > 0) {
                    let uniqueValues = [];
                    this._options = querySelectOptions.map((option) => {
                        const opt = {
                            value: option.getAttribute('value') || (option.querySelector('div[data-label]')?.innerText || option.text),
                            label: option.querySelector('div[data-label]')?.innerText || option.text,
                            icon: option.querySelector('div[data-icon]')?.innerHTML || '',
                            description: option.querySelector('div[data-description]')?.innerText || '',
                            selected: hasMatchingOption ? option.value === currentValue : option.hasAttribute('selected'),
                            disabled: option.hasAttribute('disabled')
                        };
                        if(!uniqueValues.includes(opt.value)) {
                            uniqueValues.push(opt.value);
                            return opt;
                        } else {
                            console.warn('[LazyComponent] duplicate option.\nSELECT[NAME]: ' + this.$refs.selectOrigin.name + '\nSELECT[VALUE]: ' + opt.value);
                            return null;
                        }
                    }).filter(option => option !== null);
                }
            });

            this.$watch('_options', (value) => {
                this.$refs.selectOrigin.value = value.find((option) => option.selected).value;
                this.$refs.selectOrigin.dispatchEvent(new Event('change', { bubbles: true }));
            });

            this.$watch('_isOpen', (value) => {
                if (value) {
                    if(window.matchMedia('(min-width: 768px)').matches) {
                        this.$nextTick(() => {
                            this.$refs.search?.focus();
                        });
                    }
                    this._search = '';
                }
            });
        },

        open() {
            this._isOpen = true;
        },

        toggle() {
            this._isOpen = !this._isOpen;
        },

        close() {
            this._isOpen = false;
        },

        getSelected(key = null) {
            const selectedOption = this._options.find(option => option.selected);
            if (key) {
                return selectedOption ? selectedOption[key] : '';
            } else {
                return selectedOption;
            }
        },

        setSelected(opt) {
            this._options = this._options.map((option) => ({
                ...option,
                selected: option.value === opt.value
            }));
        },

        getFilteredOptions() {
            if (this._search.trim() === '') {
                return this._options;
            }
            return this._options.filter(option => option.label.toLowerCase().includes(this._search.toLowerCase()));
        },
    }));

    Alpine.data('lazySelectSsr', ({ url }) => (
        {
            _isOpen: false,
            _isLoading: false,
            _options: [],
            _selected: [],
            _search: '',
            _debounceTimer: null,
            _url: url,

            init() {
                this.$watch('_isOpen', (value) => {
                    if (value) {
                        if(window.matchMedia('(min-width: 768px)').matches) {
                            this.$nextTick(() => {
                                this.$refs.search?.focus();
                            });
                        }
                        this._search = '';
                    }
                });

                this.$watch('_search', (value) => {
                    if (this._search.trim() === '') {
                        this._options = [];
                        return;
                    }
                    this._isLoading = true;
                    clearTimeout(this._debounceTimer);
                    this._debounceTimer = setTimeout(() => {
                        let uniqueValues = [];
                        fetch(this._url + '?search=' + value)
                            .then(response => response.json())
                            .then(data => {
                                this._isLoading = false;
                                this._options = data.map((option) => {
                                    if(!uniqueValues.includes(option.value)) {
                                        uniqueValues.push(option.value);
                                        return option;
                                    } else {
                                        console.warn('[LazyComponent] duplicate option.\nSELECT[NAME]: ' + this.$refs.selectOrigin.name + '\nSELECT[VALUE]: ' + option.value);
                                        return null;
                                    }
                                }).filter(option => option !== null);
                            }).catch(error => {
                                this._isLoading = false;
                                console.error('Error fetching data:', error);
                                this._options = [];
                            });
                    }, 500);
                });

                this.$watch('_selected', (e) => {
                    if (e) {
                        this.$refs.selectOrigin.querySelector('option:checked').value = e?.value || '';
                    }
                });
            },

            open() {
                this._isOpen = true;
            },

            toggle() {
                this._isOpen = !this._isOpen;
            },

            close() {
                this._isOpen = false;
            },

            getSelected(key = null) {
                if(!this._selected) return;
                return key ? this._selected[key] : this._selected;
            },

            setSelected(opt) {
                this._selected = opt
            },

            getFilteredOptions() {
                return this._options;
            },
        }
    ));

    Alpine.data('lazySelectMultiple', ({ max }) => (
        {
            _isOpen: false,
            _options: [],
            _selected: [],
            _search: '',
            _max: max,

            init() {
                this.$nextTick(() => {
                    const currentValues = Array.from(this.$refs.selectOrigin.selectedOptions).map(option => option.value);
                    const querySelectOptions = Array.from(this.$refs.selectOrigin.querySelectorAll('option'));
                    if (querySelectOptions.length > 0) {
                        let uniqueValues = [];
                        this._options = querySelectOptions.map((option, i) => {
                            if(!uniqueValues.includes(option.value)) {
                                uniqueValues.push(option.value);
                                if(option.hasAttribute('selected') || currentValues.includes(option.value)) {
                                    this._selected.push({ value: option.value, label: option.text });
                                }
                                return {
                                    index: i,
                                    value: option.value,
                                    label: option.text,
                                    disabled: option.hasAttribute('disabled')
                                }
                            } else {
                                console.warn('[LazyComponent] duplicate option.\nSELECT[NAME]: ' + this.$refs.selectOrigin.name + '\nSELECT[VALUE]: ' + option.value);
                                return null;
                            }
                        }).filter(option => option !== null);
                    }
                });

                this.$watch('_selected', (value) => {
                    Array.from(this.$refs.selectOrigin.options).map((option) => {
                        option.selected = this._selected.find(selected => selected.value === option.value) ? true : false
                    });
                    this.$refs.selectOrigin.dispatchEvent(new Event('change', { bubbles: true }));
                });
            },

            open() {
                this._isOpen = true;
                this.$refs.search.value = '';
                this.$refs.search.focus();
                this._search = '';
            },

            toggle() {
                this._isOpen = !this._isOpen;
                this.$refs.search.value = '';
                this.$refs.search.blur();
                this._search = '';
            },

            close() {
                this.$refs.search.value = '';
                this.$refs.search.blur();
                this._search = '';
                this._isOpen = false;
            },

            toggleSelected(opt) {
                if(this._selected.find(option => option.value === opt.value)) {
                    this.removeSelected(opt);
                } else {
                    if(this._max !=0 && this._selected.length >= this._max) return;
                    this._selected.push({ value: opt.value, label: opt.label });
                }
                this._search = '';
                this.$refs.search.value = '';
                this.$refs.search.focus();
            },

            removeSelected(opt) {
                this._selected = this._selected.filter(option => option.value !== opt.value);
            },

            removeLastSelected() {
                if (this._search === '' && this._selected.length > 0) {
                    this._selected.pop();
                }
            },

            getFilteredOptions() {
                let options = this._options.map((option) => ({
                    ...option,
                    selected: this._selected.find(selected => selected.value === option.value) ? true : false
                }));
                if (this._search.trim() === '') {
                    return options;
                }
                return options.filter(option => option.label.toLowerCase().includes(this._search.toLowerCase()));
            },
        }
    ));

    Alpine.data('lazySelectMultipleSsr', ({ max, url }) => (
        {
            _isOpen: false,
            _options: [],
            _selected: [],
            _search: '',
            _max: max,
            _url: url,
            _isLoading: false,

            init() {
                this.$watch('_selected', (value) => {
                    let optionElement = '';
                    value.map((option) => {
                        optionElement += `<option value="${option.value}" selected>${option.label}</option>`;
                    });
                    this.$refs.selectOrigin.innerHTML = optionElement;
                    this.$refs.selectOrigin.dispatchEvent(new Event('change', { bubbles: true }));
                });

                this.$watch('_search', (value) => {
                    if (this._search.trim() === '') {
                        this._options = [];
                        return;
                    }
                    this._isLoading = true;
                    clearTimeout(this._debounceTimer);
                    this._debounceTimer = setTimeout(() => {
                        let uniqueValues = [];
                        fetch(this._url + '?search=' + value)
                            .then(response => response.json())
                            .then(data => {
                                this._isLoading = false;
                                this._options = data.map((option) => {
                                    if(!uniqueValues.includes(option.value)) {
                                        uniqueValues.push(option.value);
                                        return option;
                                    } else {
                                        console.warn('[LazyComponent] duplicate option.\nSELECT[NAME]: ' + this.$refs.selectOrigin.name + '\nSELECT[VALUE]: ' + option.value);
                                        return null;
                                    }
                                }).filter(option => option !== null);
                            }).catch(error => {
                                this._isLoading = false;
                                console.error('Error fetching data:', error);
                                this._options = [];
                            });
                    }, 500);
                });
            },

            open() {
                this._isOpen = true;
                this.$refs.search.value = '';
                this.$refs.search.focus();
                this._search = '';
            },

            toggle() {
                this._isOpen = !this._isOpen;
                this.$refs.search.value = '';
                this.$refs.search.blur();
                this._search = '';
            },

            close() {
                this.$refs.search.value = '';
                this.$refs.search.blur();
                this._search = '';
                this._isOpen = false;
            },

            toggleSelected(opt) {
                if(this._selected.find(option => option.value === opt.value)) {
                    this.removeSelected(opt);
                } else {
                    if(this._max !=0 && this._selected.length >= this._max) return;
                    this._selected.push({ value: opt.value, label: opt.label });
                }
            },

            setSelected(opt) {
                this._selected = opt
            },

            removeSelected(opt) {
                this._selected = this._selected.filter(option => option.value !== opt.value);
            },

            removeLastSelected() {
                if (this._search === '' && this._selected.length > 0) {
                    this._selected.pop();
                }
            },

            getFilteredOptions() {
                let options = this._options.map((option) => ({
                    ...option,
                    selected: this._selected.find(selected => selected.value === option.value) ? true : false
                }));
                if (this._search.trim() === '') {
                    return options;
                }
                return options.filter(option => option.label.toLowerCase().includes(this._search.toLowerCase()));
            },
        }
    ));
}
