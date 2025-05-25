@props([
    'name' => 'attachment',
    'multiple' => false,
    'max' => null,
    'accept' => null,
    'existingFiles' => [],
    'base_url' => ''
])

<div
    x-data="{
        _existingFiles: @js($existingFiles),
        _newFiles: [],
        _maxFiles: @js($max),
        _isDragging: false,
        _allowedTypes: @js(explode(',', $accept ?? '*/*')),
        _base_url: @js($base_url),

        _handleFileSelect(event) {
            let selectedFiles = Array.from(event.target.files);
            this._addFiles(selectedFiles);
            this.$refs.fileInput.value = null;
        },

        _addFiles(files) {
            for (const file of files) {
                if(this._newFiles.length >= this._maxFiles) {
                    if (window.toast) {
                        window.toast.warning('You can only upload a maximum of ' + this._maxFiles + ' files.');
                    }
                    break;
                }
                if(!this._isValidFileType(file)) {
                    if (window.toast) {
                        window.toast.warning('Invalid file type.');
                    }
                    continue;
                }
                this._newFiles.push(file);
            }
        },

        _remove(files) {
            this._newFiles = this._newFiles.filter(file => !files.includes(file));
        },

        _isValidFileType(file) {
            if (this._allowedTypes.includes('*/*')) {
                return true;
            }
            return this._allowedTypes.some(type => {
                if (type.endsWith('/*')) {
                    const baseType = type.slice(0, -2);
                    return file.type.startsWith(baseType);
                }
                return file.type === type;
            });
        },

        _detailFile(file) {
            if (file instanceof File) {
                return {
                    name: file.name,
                    size: this._getFileSize(file),
                    type: file.type,
                    url: URL.createObjectURL(file)
                };
            }

            console.log(this._base_url + file);

            return {
                name: file.slice(file.lastIndexOf('/') + 1),
                size: '-',
                type: file.slice(file.lastIndexOf('.') + 1),
                url: file
            };
        },

        _getFileSize(file) {
            if (file instanceof File) {
                const size = file.size;
                if (size < 1024) return size + ' B';
                if (size < 1024 * 1024) return Math.round(size / 1024) + ' KB';
                return Math.round(size / (1024 * 1024)) + ' MB';
            }
            return '-';
        },

        _getFiles() {
            return [...this._existingFiles, ...this._newFiles];
        }
    }"
    class="relative"
>
    <input
        type="file"
        @if($multiple) multiple @endif
        @if($accept) accept="{{ $accept }}" @endif
        x-ref="fileInput"
        class="sr-only"
        x-on:change="_handleFileSelect"
        {{ $attributes }}
    >

     <template x-for="(file, index) in _existingFiles" :key="'existing-' + index">
        <input type="hidden" :name="'old_' + '{{ $name }}' + '[' + index + ']'" :value="file">
    </template>

    <div class="flex flex-col items-center justify-center gap-y-2 bg-white dark:bg-cat-700/10 border border-cat-300 dark:border-cat-700/50 p-3 rounded-md">
        <p
            class="text-cat-500 text-sm font-medium select-none py-3"
            x-on:click="$refs.fileInput.click()"
            x-show="(_maxFiles === null || _getFiles().length < _maxFiles)"
        >
            <span>Drag and drop</span> or
            <span class="font-semibold text-cat-900 dark:text-white cursor-pointer">browse</span>
        </p>
        <div class="w-full" x-show="_getFiles().length > 0" x-cloak>
            <ul class="flex flex-col gap-y-2">
                <template x-for="(file, index) of _getFiles()" :key="index">
                    <li class="overflow-hidden motion-preset-expand" >
                        <div class="relative w-full h-32 overflow-hidden rounded-md bg-cat-800 dark:bg-cat-900 flex justify-center items-center">
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-black via-transparent to-transparent"></div>
                            <div class="absolute top-0 left-0 w-full flex items-center justify-between gap-x-3 px-3 py-2 z-10">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm line-clamp-1 text-white" x-text="_detailFile(file).name"></div>
                                    <div class="text-xs text-cat-500" x-text="_detailFile(file).size"></div>
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" class="size-6 text-cat-500 cursor-pointer" x-on:click="_remove([file])">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="m14.5 9.5l-5 5m0-5l5 5"/></g></svg>
                                    </button>
                                </div>
                            </div>
                            <template x-if="_detailFile(file).type.startsWith('image/')">
                                <img class="w-full h-full object-contain object-center" x-bind:src="_detailFile(file).url" loading="lazy" alt="lazy">
                            </template>
                            <template x-if="_detailFile(file).type.startsWith('video/')">
                                <video class="w-full h-full object-contain object-center" x-bind:src="_detailFile(file).url" controls></video>
                            </template>
                            <template x-if="!_detailFile(file).type.startsWith('image/') && !_detailFile(file).type.startsWith('video/')">
                                <svg class="size-12 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M14 22h-4c-3.771 0-5.657 0-6.828-1.172S2 17.771 2 14v-4c0-3.771 0-5.657 1.172-6.828S6.239 2 10.03 2c.606 0 1.091 0 1.5.017q-.02.12-.02.244l-.01 2.834c0 1.097 0 2.067.105 2.848c.114.847.375 1.694 1.067 2.386c.69.69 1.538.952 2.385 1.066c.781.105 1.751.105 2.848.105h4.052c.043.534.043 1.19.043 2.063V14c0 3.771 0 5.657-1.172 6.828S17.771 22 14 22" clip-rule="evenodd" opacity="0.5"/><path fill="currentColor" d="M6 13.75a.75.75 0 0 0 0 1.5h8a.75.75 0 0 0 0-1.5zm0 3.5a.75.75 0 0 0 0 1.5h5.5a.75.75 0 0 0 0-1.5zm5.51-14.99l-.01 2.835c0 1.097 0 2.066.105 2.848c.114.847.375 1.694 1.067 2.385c.69.691 1.538.953 2.385 1.067c.781.105 1.751.105 2.848.105h4.052q.02.232.028.5H22c0-.268 0-.402-.01-.56a5.3 5.3 0 0 0-.958-2.641c-.094-.128-.158-.204-.285-.357C19.954 7.494 18.91 6.312 18 5.5c-.81-.724-1.921-1.515-2.89-2.161c-.832-.556-1.248-.834-1.819-1.04a6 6 0 0 0-.506-.154c-.384-.095-.758-.128-1.285-.14z"/></svg>
                            </template>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
