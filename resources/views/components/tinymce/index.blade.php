@props([
    'id',
    'options' => [
        'menubar' => false,
        'relative_urls' => false,
        'remove_script_host' => false,
        'convert_urls' => true,
    ],
    'toolbar' => [ 'aidialog styles | bold italic underline strikethrough | bullist numlist | alignleft aligncenter alignright alignjustify | link image codesample | code fullscreen' ],
    'plugins' => [
        'lists',
        'autolink',
        'fullscreen',
        'link',
        'image',
        'code',
        'table',
        'help',
        'codesample',
    ]
])

<textarea id="{{ $id }}" {{ $attributes }}></textarea>

@pushOnce('body')
    <script src="{{ asset('assets/lazy/vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        window.tinymceInstances = [];

        function __loadTinyMCE({ id, theme = 'light', options = {} }) {
            const selector = `#${id}`;

            return new Promise((resolve) => {
                if (tinymceInstances.find((item) => item?.id === id)) {
                    tinymce.get(id).destroy();
                } else {
                    tinymceInstances.push({ id, options });
                }

                @if (Illuminate\Support\Facades\Route::has('ai'))
                options.plugins = options.plugins.concat('ai');
                options = {
                    ...options,
                    ai_request : function (request, respondWith) {
                        respondWith.string((signal) => window.fetch(@js(route('ai')), { signal, ...{
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': @js(csrf_token())
                            },
                            body: JSON.stringify({ request })
                        } })
                        .then(async (response) => {
                                if (!response.ok) throw new Error('Failed to communicate with the AI');
                                const data = await response.json();
                                if(data.error) throw new Error(data.error);
                                return data.response.trim().replace(/^```html\n/, "").replace(/\n```$/, "");
                            })
                        );
                    },
                };
                @endif

                tinymce.init({
                    selector,
                    license_key: 'gpl',
                    skin: `oxide${theme === 'dark' ? '-dark' : ''}`,
                    content_css: theme === 'dark' ? 'dark' : 'default',
                    ...options,
                    setup(editor) {
                        editor.on('change', function () {
                            tinymce.triggerSave();
                        });

                        editor.on('init', function () {
                            resolve(editor);
                        })
                    }
                });
            })
        }

        window.addEventListener(window.eventKey, (event) => {
            let theme = document.documentElement.getAttribute('theme-mode') || 'light';
            tinymceInstances.forEach((item) => {
                const { id, options } = item;
                __loadTinyMCE({ id, theme, options });
            })
        });
    </script>
@endPushOnce

@push('body')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let theme = document.documentElement.getAttribute('theme-mode') || 'light';
            __loadTinyMCE({
                id: @js($id),
                theme,
                options: {
                    ...@json($options),
                    toolbar: @json($toolbar),
                    plugins: @json($plugins),
                }
            }).then((editor) => {
                editor.setContent(`{{ $slot }}`)
            })
        });
    </script>
@endpush
