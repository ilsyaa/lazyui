/*
 * Appearance
 * @author: Ilsya
 * @license: Licensed MIT
 * Copyright 2024 VELIXS
 */

const LazySettings = {
    Modes: ['dark', 'light', 'auto'],
    Accents: ['emerald', 'indigo', 'blue', 'pink', 'orange', 'purple'],
    Fonts: ['inter', 'sans', 'monospace', 'serif'],
    Asides: ['show', 'hide'],
    htmlElement: document.querySelector('html'),
    localStorageKey: window.localStorageKey,
    eventKey: window.eventKey,
    autoInit() {
        this.setTheme(this._get('themeMode') || this.Modes[0], false, false)
        this.setAccent(this._get('themeAccent' || this.Accents[0]), false, false)
        this.setFont(this._get('themeFont') || this.Fonts[0], false, false)
        this.setAside(this._get('themeAside') || this.Asides[0], false, false)
        this.sentEvent(dispatchEvent)
    },
    /*
        mode: 'light', 'dark', 'auto', 'toggle
    */
    resetAppearance() {
        this.setTheme(this.Modes[0], true, false)
        this.setFont(this.Fonts[0], true, false)
        this.setAside(this.Asides[0], true, false)
        this.sentEvent(dispatchEvent)
    },
    setTheme(mode = 'toggle', saveInStore = true, dispatchEvent = true) {
        const $resetStyles = document.createElement('style')
        $resetStyles.innerHTML = `*{ transition: unset !important; }`
        document.head.appendChild($resetStyles)
        setTimeout(() => { $resetStyles.remove() }, 50)

        let currentMode = this._get('themeMode') || this.Modes[0]
        let setToHTML = currentMode;
        if(mode == 'auto') {
            setToHTML = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
        };
        if(mode == 'toggle') {
            setToHTML = currentMode === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'light' : 'dark') : (currentMode == 'dark' ? 'light'  : 'dark')
        }
        if(mode != 'toggle' && mode !== 'auto' && this.Modes.includes(mode)) setToHTML = mode
        this.htmlElement.setAttribute('theme-mode', setToHTML)
        let original = mode === 'toggle' ? (currentMode === 'auto' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'light' : 'dark') : currentMode == 'dark' ? 'light'  : 'dark') : mode === 'auto' ? 'auto' : setToHTML
        this.DOMSettings({ themeMode: original })
        if(saveInStore) this._setLocalStorage('themeMode', original)
        this.sentEvent(dispatchEvent)
    },
    setAccent(accent, saveInStore = true, dispatchEvent = true) {
        this.htmlElement.setAttribute('theme-accent', this.Accents.includes(accent) ? accent : this.Accents[0])
        this.DOMSettings({ themeAccent: this.Accents.includes(accent) ? accent : this.Accents[0] })
        if(saveInStore) this._setLocalStorage('themeAccent', this.Accents.includes(accent) ? accent : this.Accents[0])
        this.sentEvent(dispatchEvent)
    },
    setFont(font, saveInStore = true, dispatchEvent = true) {
        let themeFont = this.Fonts.includes(font) ? font : this.Fonts[0];
        this.htmlElement.setAttribute('theme-font', themeFont)
        this.DOMSettings({ themeFont })
        if(saveInStore) this._setLocalStorage('themeFont', themeFont)
        this.sentEvent(dispatchEvent)
    },
    setAside(aside, saveInStore = true, dispatchEvent = true) {
        if(aside === 'toggle') aside = this._get('themeAside') === 'show' ? 'hide' : 'show'
        let themeAside = this.Asides.includes(aside) ? aside : this.Asides[0];
        this.htmlElement.setAttribute('theme-aside', themeAside)
        if(saveInStore) this._setLocalStorage('themeAside', themeAside)
        this.sentEvent(dispatchEvent)
    },
    DOMSettings(e) {
        const applySettings = () => {
            const setting = document.querySelector('#lazy-settings');
            if (!setting) return
            try {
                if (e?.themeMode) {
                    setting.querySelector('#appearance-toggle input[type="checkbox"]').checked = false;
                    setting.querySelector('#appearance-toggle').classList.remove('active')
                    setting.querySelector('#appearance-auto').classList.remove('active')
                    setting.querySelector('#appearance-auto input[type="checkbox"]').checked = false;
                    if (e.themeMode === 'auto') {
                        setting.querySelector('#appearance-auto').classList.add('active')
                    } else {
                        setting.querySelector('#appearance-toggle').classList.add('active')
                    }
                    setting.querySelector('#appearance-toggle input[type="checkbox"]').checked = e.themeMode === 'dark';
                    setting.querySelector('#appearance-auto input[type="checkbox"]').checked = e.themeMode === 'auto';
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

    // render accent buttons on settings
    const $accentContent = document.querySelector("#lazy-settings #accent-content");
    if($accentContent){
        for (const $accent of LazySettings.Accents) {
            const $accentItem = document.createElement('button')
            $accentItem.classList.add("h-16", "aspect-[1/1]", "hover:bg-gray-500/10", "[html[theme-accent='"+$accent+"']_&]:bg-accent-500/10" ,"rounded-xl", "flex", "justify-center", "items-center", "ripple:bg-accent-300/20")
            $accentItem.setAttribute('type', 'button')
            $accentItem.setAttribute('data-ripple', '')
            $accentItem.setAttribute('x-on:click', '$appearance.setAccent(\''+$accent+'\', true, false)')
            $accentItem.innerHTML = `<span class="size-4 rounded-full transition-transform duration-300 ease-in-out [html[theme-accent='${$accent}']_&]:scale-150 bg-${$accent}-300"></span>`
            $accentContent.appendChild($accentItem)
        }
    }

    const $fontContent = document.querySelector("#lazy-settings #font-content");
    if($fontContent){
        for (const $font of LazySettings.Fonts) {
            const $fontItem = document.createElement('button')
            $fontItem.classList.add($font ,"h-20","w-full", "hover:bg-gray-500/10" ,"rounded-xl", "text-gray-500" , "flex", "flex-col", "gap-y-3","justify-center", "items-center", "ripple:bg-gray-300/20")
            $fontItem.setAttribute('type', 'button')
            $fontItem.setAttribute('x-on:click', '$appearance.setFont(\''+$font+'\', true, false)')
            $fontItem.setAttribute('data-ripple', '')
            $fontItem.innerHTML = `<i class="fa-duotone fa-solid fa-font-case"></i> <span class="text-xs text-gray-500 dark:text-gray-500 font-medium">${$font}</span>`
            $fontContent.appendChild($fontItem)
        }
    }
})
