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

        wrapper.insertAdjacentHTML("beforeend", `<div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; padding-bottom: 0rem; padding-top: 0.500rem; margin-bottom: -0.5rem;">
            <div class="flex gap-x-1">
                <span style="display: block; height: 0.75rem; width: 0.75rem; border-radius: 50%; background-color: #ef4444;"></span>
                <span style="display: block; height: 0.75rem; width: 0.75rem; border-radius: 50%; background-color: #f97316;"></span>
                <span style="display: block; height: 0.75rem; width: 0.75rem; border-radius: 50%; background-color: #10b981;"></span>
            </div>
            <div>
                <button type="button" id="copy" style="width: 1.75rem; height: 1.75rem; border-radius: 50%;" class="hover:bg-white/5 ripple:bg-white/10" data-ripple x-data x-tooltip="{ text: 'Copy' }">
                    <i class="fa-duotone fa-solid fa-clone"></i>
                    <i class="fa-duotone fa-solid fa-check" style="display: none;"></i>
                </button>
            </div>
        </div>`);

        const preElement = el.closest("pre");
        preElement.parentNode.insertBefore(wrapper, preElement);
        wrapper.appendChild(preElement);

        const button = wrapper.querySelector("button#copy");
        let timer;
        button.addEventListener("click", () => {
            navigator.clipboard.writeText(text).then(() => {
                button.querySelector("i.fa-duotone.fa-clone").style.display = "none";
                button.querySelector("i.fa-duotone.fa-check").style.display = "inline-block";
                if (typeof callback === "function") return callback(text, el);
            });

            clearTimeout(timer);
            timer = setTimeout(() => {
                button.querySelector("i.fa-duotone.fa-check").style.display = "none";
                button.querySelector("i.fa-duotone.fa-clone").style.display = "inline-block";
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
