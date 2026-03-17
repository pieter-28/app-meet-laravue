import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

export function useOpenModalDialog<T extends { id: number }>(
    defaultForm: Record<string, any>,
) {
    const selectedItem = ref<T | null>(null);

    const isDialogOpen = ref(false);
    const isDeleteDialogOpen = ref(false);
    const mode = ref<'create' | 'edit'>('create');

    const form = useForm({ ...defaultForm });

    const openCreateDialog = () => {
        mode.value = 'create';
        form.reset();
        isDialogOpen.value = true;
    };

    const openEditDialog = (item: T) => {
        mode.value = 'edit';
        selectedItem.value = item;

        Object.keys(defaultForm).forEach((key) => {
            form[key] = item[key as keyof T] ?? defaultForm[key];
        });

        isDialogOpen.value = true;
    };

    const openDeleteDialog = (item: T) => {
        selectedItem.value = item;
        isDeleteDialogOpen.value = true;
    };

    const closeDialogs = () => {
        isDialogOpen.value = false;
        isDeleteDialogOpen.value = false;
    };

    return {
        form,
        selectedItem,
        mode,

        isDialogOpen,
        isDeleteDialogOpen,

        openCreateDialog,
        openEditDialog,
        openDeleteDialog,
        closeDialogs,
    };
}
