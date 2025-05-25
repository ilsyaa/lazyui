import {
    registerPlugin,
    create
} from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

registerPlugin(FilePondPluginImagePreview);
registerPlugin(FilePondPluginFileValidateType);

function joinPath(...parts) {
    return parts.map((part, i) => {
        if (i === 0) return part.trim().replace(/[/]*$/g, '');
        return part.trim().replace(/^[/]*|[/]*$/g, '');
    }).join('/');
}

async function initFilePond({
    selector,
    existingFiles = [],
    accept = null,
    base_url = '',
    max = null
}) {
    const input = document.getElementById(selector);
    if (!input) return;

    const filePromises = existingFiles.map(async (path) => {
        try {
            const response = await fetch(joinPath(base_url, path));
            const blob = await response.blob();
            const filename = path.split('/').pop();
            return new File([blob], filename, { type: blob.type });
        } catch (err) {
            console.error(`Failed to load file ${path}`, err);
            return null;
        }
    });

    const files = (await Promise.all(filePromises)).filter(Boolean);
    create(input, {
        allowReorder: true,
        storeAsFile: true,
        ...(accept && { acceptedFileTypes: accept.split(',') }),
        maxFiles: max,
        files: files,
    });
}

window.initFilePond = initFilePond;
