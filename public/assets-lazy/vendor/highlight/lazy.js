class HljsLazyPlugin {
    constructor(options = {}) {
        this.hook = options.hook;
        this.callback = options.callback;
        this.lang = options.lang || document.documentElement.lang || "en";
    }

    "after:highlightElement"({ el, text }) {
        const wrapper = document.createElement("div");
        wrapper.classList.add("hljs");
        wrapper.style.borderRadius = "0.75rem";
        wrapper.style.overflow = "hidden";

        wrapper.insertAdjacentHTML("beforeend", `<div style="position: relative; display: flex; justify-content: space-between; align-items: center; padding: 1rem; padding-bottom: 0rem; padding-top: 0.500rem; margin-bottom: -0.5rem;">
            <div class="absolute top-2 right-2">
                <button type="button" id="copy" style="width: 1.75rem; height: 1.75rem; border-radius: 50%; cursor: pointer;">
                    <svg class="size-4" data-clipboard-clone xmlns="http://www.w3.org/2000/svg" viewbox="0,0,384,512"><path d="M280 64l40 0c35.3 0 64 28.7 64 64l0 320c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 128C0 92.7 28.7 64 64 64l40 0 9.6 0C121 27.5 153.3 0 192 0s71 27.5 78.4 64l9.6 0zM64 112c-8.8 0-16 7.2-16 16l0 320c0 8.8 7.2 16 16 16l256 0c8.8 0 16-7.2 16-16l0-320c0-8.8-7.2-16-16-16l-16 0 0 24c0 13.3-10.7 24-24 24l-88 0-88 0c-13.3 0-24-10.7-24-24l0-24-16 0zm128-8a24 24 0 1 0 0-48 24 24 0 1 0 0 48z" fill="currentColor" style="opacity: 1;"></path></svg>
                    <svg class="size-4" style="display: none;" data-clipboard-check xmlns="http://www.w3.org/2000/svg" viewbox="0,0,448,512"><path d="M441 103c9.4 9.4 9.4 24.6 0 33.9L177 401c-9.4 9.4-24.6 9.4-33.9 0L7 265c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l119 119L407 103c9.4-9.4 24.6-9.4 33.9 0z" fill="currentColor" style="opacity: 1;"></path></svg>
                </button>
            </div>
        </div>`);

        const preElement = el.closest("pre");
        preElement.parentNode.insertBefore(wrapper, preElement);
        wrapper.appendChild(preElement);

        const button = wrapper.querySelector("button#copy");
        let timer;
        button.addEventListener("click", () => {
            const text = el.textContent;
            navigator.clipboard.writeText(text)
            button.querySelector("[data-clipboard-clone]").style.display = "none";
            button.querySelector("[data-clipboard-check]").style.display = "block";
            clearTimeout(timer);
            timer = setTimeout(() => {
                button.querySelector("[data-clipboard-check]").style.display = "none";
                button.querySelector("[data-clipboard-clone]").style.display = "block";
            }, 1000);
        });
    }
}

const blade = function (hljs) {
    const TAG_INTERNALS = {
        endsWithParent: true,
        illegal: /</,
        relevance: 0,
        contains: [
            {
                className: 'attr',
                begin: '[A-Za-z0-9\\._:-]+',
                relevance: 0
            },
            {
                begin: /=\s*/,
                relevance: 0,
                contains: [
                    {
                        className: 'string',
                        endsParent: true,
                        variants: [
                            { begin: /"/, end: /"/ },
                            { begin: /'/, end: /'/ },
                            { begin: /[^\s"'=<>`]+/ }
                        ]
                    }
                ]
            }
        ]
    };

    const CUSTOM_COMPONENTS = {
        begin: '</?x-[a-zA-Z0-9:-]+',
        end: '/?>|>',
        returnBegin: true,
        subLanguage: 'xml',
        contains: [
            {
                className: 'name',
                begin: 'x-[a-zA-Z0-9:-]+',
                end: '>',
                contains: [TAG_INTERNALS]
            }
        ]
    };

    const BLADE_DIRECTIVES = {
        className: 'meta',
        variants: [
            { begin: '@php(?=[^a-zA-Z])', end: '@endphp' },
            { begin: '@[a-zA-Z]+' }
        ],
        contains: [
            {
                begin: '@php',
                end: '@endphp',
                subLanguage: 'php',
                excludeBegin: true,
                excludeEnd: true
            }
        ]
    };

    const BLADE_EXPRESSION = {
        className: 'template-variable',
        begin: '{{',
        end: '}}',
        contains: [
            {
                begin: '\\s*\\(?\\s*\\$[A-Za-z0-9_]+',
                end: '\\s*\\)?',
                className: 'variable',
                relevance: 0
            },
            {
                begin: '->',
                contains: [
                    {
                        className: 'keyword',
                        begin: '[a-zA-Z_]\\w*'
                    }
                ]
            },
            {
                subLanguage: 'php',
                begin: '\\s*',
                end: '\\s*'
            }
        ]
    };

    const PHP_INLINE = {
        className: 'meta',
        begin: '<\\?php',
        end: '\\?>',
        subLanguage: 'php',
        relevance: 10
    };

    return {
        name: 'Blade',
        aliases: ['blade'],
        case_insensitive: true,
        subLanguage: 'xml',
        contains: [
            CUSTOM_COMPONENTS,
            hljs.COMMENT('{{--', '--}}'),
            BLADE_EXPRESSION,
            BLADE_DIRECTIVES,
            PHP_INLINE,
            {
                className: 'meta',
                begin: '<\\?[=]?',
                end: '\\?>',
                subLanguage: 'php',
                contains: [
                    { begin: '/\\*', end: '\\*/', skip: true },
                    { begin: 'b"', end: '"', skip: true },
                    { begin: 'b\'', end: '\'', skip: true },
                    hljs.inherit(hljs.APOS_STRING_MODE, { illegal: null, className: null, contains: null, skip: true }),
                    hljs.inherit(hljs.QUOTE_STRING_MODE, { illegal: null, className: null, contains: null, skip: true })
                ]
            }
        ]
    };
}

hljs.registerLanguage('blade', blade);
hljs.addPlugin(new HljsLazyPlugin());
hljs.highlightAll();
