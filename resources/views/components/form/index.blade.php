@props([
    'id' => 'lazy',
    'method' => 'POST',
    'action' => '',
    'toast' => true,
    'toastErrors' => 'summary' // summary, detailed
])

<form {{ $attributes }} x-data="{ toast: @js($toast), toastErrors: @js($toastErrors) }" x-on:submit.prevent="__lazyForm($event, @js($id))">
    {{ $slot }}
</form>

@pushOnce('body')
    <script>
        async function __lazyForm($event, $id) {
            document.dispatchEvent(new CustomEvent('form', { detail: { id: $id, loading: true, element: $event } }));
            const Form = $event.target;
            const xdata = Alpine.$data(Form);
            const formData = new FormData(Form);
            let toastl = null;
            if (window.toast && toast) {
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
                document.dispatchEvent(new CustomEvent('form', { detail: { id: $id, ok: response.ok, data, element: $event } }));

                if (!response.ok) {
                    if(xdata.toastErrors == 'detailed') {
                        toastl.remove();
                        Object.values(data.errors).forEach((value, index) => {
                            setTimeout(() => {
                                toast.warning(value[0]);
                            }, index * 200);
                        });
                    } else {
                        if (toastl) toastl.error(data.message || 'Failed to submit.');
                    }
                    $event.target.classList.remove('pointer-events-none');
                    return;
                }

                if (toastl) toastl.success(data.message || 'Success!');
                $event.target.classList.remove('pointer-events-none');
            } catch (error) {
                document.dispatchEvent(new CustomEvent('form', { detail: { id: $id, ok: false, data: error, element: $event } }));
                if (toastl) toastl.error(error.message || 'Failed to submit.');
                $event.target.classList.remove('pointer-events-none');
            }
        }
    </script>
@endPushOnce
