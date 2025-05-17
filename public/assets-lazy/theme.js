/*
 * Appearance
 * @author: Ilsya
 * @license: Licensed MIT
 * Copyright 2024 VELIXS
 */

const LazySettings = {
    defaultMode: window.defaultMode || 'auto',
    defaultAccent: window.defaultAccent || 'emerald',
    defaultFont: window.defaultFont || 'inter',
    defaultAside: window.defaultAside || 'show',
    htmlElement: document.querySelector('html'),
    localStorageKey: window.localStorageKey,
    eventKey: window.eventKey,
    autoInit() {
        this.setTheme(this._get('themeMode') || this.defaultMode, false, false)
        this.setAccent(this._get('themeAccent' || this.defaultAccent), false, false)
        this.setFont(this._get('themeFont') || this.defaultFont, false, false)
        this.setAside(this._get('themeAside') || this.defaultAside, false, false)
        this.sentEvent(dispatchEvent)
    },
    /*
        mode: 'light', 'dark', 'auto', 'toggle
    */
    resetAppearance() {
        this.setTheme(this.defaultMode, true, false)
        this.setFont(this.defaultFont, true, false)
        this.setAccent(this.defaultAccent, true, false)
        this.setAside(this.defaultAside, true, false)
        this.sentEvent(dispatchEvent)
    },
    setTheme(mode = 'toggle', saveInStore = true, dispatchEvent = true) {
        const $resetStyles = document.createElement('style')
        $resetStyles.innerHTML = `*{ transition: unset !important; }`
        document.head.appendChild($resetStyles)
        setTimeout(() => { $resetStyles.remove() }, 50)

        let currentMode = this._get('themeMode') || this.defaultMode
        let setToHTML = currentMode;
        if(mode == 'auto') {
            setToHTML = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        };
        if(mode == 'toggle') {
            setToHTML = currentMode === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'light' : 'dark') : (currentMode == 'dark' ? 'light'  : 'dark')
        }
        if(mode != 'toggle' && mode !== 'auto') setToHTML = mode
        this.htmlElement.setAttribute('theme-mode', setToHTML)
        let original = mode === 'toggle' ? (currentMode === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'light' : 'dark') : currentMode == 'dark' ? 'light'  : 'dark') : mode === 'auto' ? 'auto' : setToHTML
        this.DOMSettings({ themeMode: original })
        if(saveInStore) this._setLocalStorage('themeMode', original)
        this.sentEvent(dispatchEvent)
    },
    setAccent(accent, saveInStore = true, dispatchEvent = true) {
        let themeAccent = accent;
        this.htmlElement.setAttribute('theme-accent', themeAccent)
        this.DOMSettings({ themeAccent: themeAccent })
        if(saveInStore) this._setLocalStorage('themeAccent', themeAccent)
        this.sentEvent(dispatchEvent)
    },
    setFont(font, saveInStore = true, dispatchEvent = true) {
        let themeFont = font;
        this.htmlElement.setAttribute('theme-font', themeFont)
        this.DOMSettings({ themeFont })
        if(saveInStore) this._setLocalStorage('themeFont', themeFont)
        this.sentEvent(dispatchEvent)
    },
    setAside(aside, saveInStore = true, dispatchEvent = true) {
        if(aside === 'toggle') aside = this._get('themeAside') === 'show' ? 'hide' : 'show'
        let themeAside = aside;
        this.htmlElement.setAttribute('theme-aside', themeAside)
        if(saveInStore) this._setLocalStorage('themeAside', themeAside)
        this.sentEvent(dispatchEvent)
    },
    DOMSettings(e) {
        const applySettings = () => {
            const setting = document.querySelector('#lazySettings');
            if (!setting) return
            try {
                if (e?.themeMode) {
                    setting.querySelector('#appearance-toggle input[type="checkbox"]').checked = false;
                    setting.querySelector('#appearance-auto input[type="checkbox"]').checked = false;
                    setting.querySelector('#appearance-toggle input[type="checkbox"]').checked = e.themeMode === 'dark';
                    setting.querySelector('#appearance-auto input[type="checkbox"]').checked = e.themeMode === 'auto';
                }

                if (e?.themeAccent) {
                    setting.querySelectorAll('[data-accents] [data-accent]').forEach(el => el.classList.remove('active'))
                    setting.querySelector('[data-accents] [data-accent="' + e.themeAccent + '"]').classList.add('active')
                }

                if (e?.themeFont) {
                    setting.querySelectorAll('[data-fonts] [data-font]').forEach(el => el.classList.remove('active'))
                    setting.querySelector('[data-fonts] [data-font="' + e.themeFont + '"]').classList.add('active')
                }
            } catch (e) {
                console.error('DOM Settings Error:', e)
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', applySettings);
        } else {
            applySettings();
        }
    },
    // get local storage data
    _get(key = null) {
        let value;
        value = JSON.parse(localStorage.getItem(this.localStorageKey) || '{}')
        if (key) return value[key]
        return value
    },
    _setLocalStorage(key, value) {
        localStorage.setItem(this.localStorageKey, JSON.stringify({
            ...this._get(),
            [key]: value
        }))
    },
    sentEvent(dispatchEvent) {
        if (dispatchEvent) window.dispatchEvent(new CustomEvent(this.eventKey, { detail: this._get() }))
    },
}

LazySettings.autoInit()

document.addEventListener('alpine:init', () => {
    Alpine.store('lazy-settings', LazySettings)
    Alpine.magic('appearance', () => Alpine.store('lazy-settings'))

    // OS Theme on Change
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if(Alpine.store('lazy-settings')._get('themeMode').includes('auto')) {
            Alpine.store('lazy-settings').setTheme('auto')
        }
    })

    // Keyboard Shortcuts Ctrl + D
    document.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault()
            Alpine.store('lazy-settings').setTheme('toggle')
        }
    })
})
