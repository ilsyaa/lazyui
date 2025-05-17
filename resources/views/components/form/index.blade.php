@props([
    'id' => 'lazy',
    'method' => 'POST',
    'action' => '',
])

<form {{ $attributes }} x-on:submit.prevent="lazySubmit($event, @js($id))">
    {{ $slot }}
</form>

<script>
    async function lazySubmit($event, $id) {
        const formData = new FormData($event.target);
        let toastl = null;
        if (window.toast) {
            toastl = toast.loading('Loading...');
        }
        $event.target.classList.add('pointer-events-none')

        try {
            let response = await fetch(@js($action), {
                method: @js($method),
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const contentType = response.headers.get("content-type") || "";
            const isJson = contentType.includes("application/json");
            const data = isJson ? await response.json() : {};
            document.dispatchEvent(new CustomEvent('form', { detail: { id: $id, ok: response.ok, element: $event, data } }));

            if (!response.ok) {
                if (toastl) toastl.error(data.message || 'Failed to submit.');
                $event.target.classList.remove('pointer-events-none');
                return;
            }

            if (toastl) toastl.success(data.message || 'Success!');
            $event.target.classList.remove('pointer-events-none');
        } catch (error) {
            document.dispatchEvent(new CustomEvent('form', { detail: { id: $id, ok: false, element: $event, data: error } }));
            if (toastl) toastl.error(error.message || 'Failed to submit.');
            $event.target.classList.remove('pointer-events-none');
        }
    }
</script>
