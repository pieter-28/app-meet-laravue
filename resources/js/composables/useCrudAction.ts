import { router, usePage } from '@inertiajs/vue3';
import { CheckCircle2, X } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

export function useCrudActions<T extends { id?: number | null }>(
    form: any,
    baseUrl: string,
) {
    const processing = ref(false);
    const page = usePage();

    const getReloadKeys = () => {
        return Object.keys(page.props).filter(
            (key) => !['auth', 'errors', 'flash', 'ziggy'].includes(key),
        );
    };

    const reloadData = () => {
        router.reload({
            only: getReloadKeys(),
        });
    };

    const store = (onSuccess?: () => void) => {
        form.post(baseUrl, {
            preserveScroll: true,
            preserveState: true,

            onStart: () => (processing.value = true),

            onSuccess: () => {
                reloadData();
                toast.success('Created successfully!', {
                    icon: CheckCircle2,
                });

                form.reset();
                onSuccess?.();
            },

            onError: () => {
                toast.error('Create failed!', { icon: X });
            },

            onFinish: () => (processing.value = false),
        });
    };

    const update = (id: number, onSuccess?: () => void) => {
        form.put(`${baseUrl}/${id}`, {
            preserveScroll: true,
            preserveState: true,

            onStart: () => (processing.value = true),

            onSuccess: () => {
                reloadData();
                toast.success('Updated successfully!', {
                    icon: CheckCircle2,
                });

                form.reset();
                onSuccess?.();
            },

            onError: () => {
                toast.error('Update failed!', { icon: X });
            },

            onFinish: () => (processing.value = false),
        });
    };

    const destroy = (id: number, onSuccess?: () => void) => {
        router.delete(`${baseUrl}/${id}`, {
            preserveScroll: true,
            preserveState: false,

            onSuccess: () => {
                reloadData();
                toast.success('Deleted successfully!', {
                    icon: CheckCircle2,
                });

                onSuccess?.();
            },

            onError: () => {
                toast.error('Delete failed!', { icon: X });
            },
        });
    };

    return {
        store,
        update,
        destroy,
        processing,
    };
}
