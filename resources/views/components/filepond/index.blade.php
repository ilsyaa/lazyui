@props([
    'name' => 'attachment',
    'existingFiles' => [],
    'base_url' => '',
    'id' => 'lazy-filepond-' . \Illuminate\Support\Str::uuid(),
    'maxPreviewSize' => 1024
])

<div class="w-full">
    <input
        type="file"
        id="{{ $id }}"
        {{ $attributes->only(['multiple', 'accept']) }}
    />
    <div id="{{ $id }}-existing"></div>
    <div id="{{ $id }}-new"></div>
</div>

@pushOnce('body')
    <script src="{{ asset('assets/lazy/vendor/filepond/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('assets/lazy/vendor/filepond/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/lazy/vendor/filepond/filepond.min.js') }}"></script>
    <script>
        window.filepondInstances = [];
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);

        function joinPath(...parts) {
            return parts.map((part, i) => {
                if (i === 0) return part.trim().replace(/[/]*$/g, '');
                return part.trim().replace(/^[/]*|[/]*$/g, '');
            }).join('/');
        }

        function initFilePond({
            selector,
            existingFiles = [],
            accept = null,
            base_url = '',
            maxPreviewSize = 1024,
            max = null,
            name,
            multiple = false
        }) {
            const input = document.getElementById(selector);
            if (!input) return;

            if(filepondInstances.find((item) => item.id == selector)) {
                console.error('Filepond with id "' + selector + '" already initialized.');
                return;
            }

            const pond = FilePond.create(input, {
                allowReorder: true,
                ...(accept && { acceptedFileTypes: accept.split(',') }),
                maxFiles: max,
                files: (typeof existingFiles == 'string'
                    ?
                        [{ source: existingFiles, options: { type: 'local' } }]
                    :
                        existingFiles.map(path => ({
                            source: path,
                            options: {
                                type: 'local',
                            }
                        }))
                ),
                server: {
                    load: (source, load, error, progress, abort, headers) => {
                        const fileUrl = joinPath(base_url, source);

                        const headRequest = new XMLHttpRequest();
                        headRequest.open('HEAD', fileUrl, true);

                        headRequest.onload = function () {
                            if (headRequest.status >= 200 && headRequest.status < 300) {
                                const contentLength = headRequest.getResponseHeader('Content-Length');
                                const fileSize = contentLength ? parseInt(contentLength, 10) : 0;
                                const sizeInKB = fileSize / 1024;
                                if (sizeInKB > maxPreviewSize) {
                                    load();
                                } else {
                                    const getRequest = new XMLHttpRequest();
                                    getRequest.open('GET', fileUrl);
                                    getRequest.responseType = 'blob';

                                    getRequest.onload = function () {
                                        if (getRequest.status >= 200 && getRequest.status < 300) {
                                            load(getRequest.response);
                                        } else {
                                            error('Failed to load file.');
                                        }
                                    };

                                    getRequest.onerror = () => error('Failed to load file.');
                                    getRequest.onprogress = (e) => {
                                        if (e.lengthComputable) {
                                            progress(e.lengthComputable, e.loaded, e.total);
                                        }
                                    };

                                    getRequest.send();
                                }
                            } else {
                                error('Failed to check file size.');
                            }
                        };

                        headRequest.onerror = () => error('Failed to check file size.');
                        headRequest.send();

                        return {
                            abort: () => {
                                headRequest.abort();
                                abort();
                            }
                        };
                    },
                    revert: (uniqueFileId, load, error) => {
                        load();
                    },
                },
                onupdatefiles: (files) => {
                    const wrapperInputsExisting = document.getElementById(selector + '-existing');
                    wrapperInputsExisting.innerHTML = '';
                    const wrapperInputsNew = document.getElementById(selector + '-new');
                    wrapperInputsNew.innerHTML = '';

                    for (const file of files) {
                        if (FilePond.FileOrigin.LOCAL === file.origin) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'existing_' + name + (multiple ? '[]' : '');
                            input.value = file.source;
                            wrapperInputsExisting.appendChild(input);
                        } else if(FilePond.FileOrigin.INPUT === file.origin) {
                            const input = document.createElement('input');
                            input.type = 'file';
                            input.name = name + (multiple ? '[]' : '');
                            input.style.display = 'none';

                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file.file);
                            input.files = dataTransfer.files;

                            wrapperInputsNew.appendChild(input);
                        }
                    }
                },

                onreorderfiles: (files) => {
                    const wrapperInputsExisting = document.getElementById(selector + '-existing');
                    wrapperInputsExisting.innerHTML = '';

                    for (const file of files) {
                        if (FilePond.FileOrigin.LOCAL === file.origin) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'existing_' + name + (multiple ? '[]' : '');
                            input.value = file.source;
                            wrapperInputsExisting.appendChild(input);
                        }
                    }
                }
            });

            filepondInstances.push({
                id: selector,
                ...pond,
                syncExistingFiles: (newExistingImage) => {
                    pond.setOptions({
                        files: (typeof newExistingImage == 'string'
                            ?
                            [
                                {
                                    source: newExistingImage,
                                    options: {
                                        type: 'local'
                                    }
                                }
                            ]
                            :
                            newExistingImage.map(image => ({
                                source: image,
                                options: {
                                    type: 'local'
                                }
                            }))
                        )
                    });
                }
            });
        }
    </script>
@endPushOnce

@push('body')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            initFilePond({
                selector: @js($id),
                existingFiles: @json($existingFiles),
                base_url: @js($base_url),
                accept: @js($attributes->get('accept')),
                max: @js($attributes->get('max')),
                multiple: @js($attributes->get('multiple')),
                maxPreviewSize: @js($maxPreviewSize),
                name: @js($name)
            })
        })
    </script>
@endpush
