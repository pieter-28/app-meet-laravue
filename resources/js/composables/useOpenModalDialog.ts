import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

export function useOpenModalDialog<T extends { id: number }>(
    defaultForm: Record<string, any>,
) {
    const selectedItem = ref<T | null>(null);

    const isAddDialogOpen = ref(false);
    const isEditDialogOpen = ref(false);
    const isDeleteDialogOpen = ref(false);

    const form = useForm({ ...defaultForm });

    const openCreateDialog = () => {
        form.reset();
        isAddDialogOpen.value = true;
    };

    const openEditDialog = (item: T) => {
        selectedItem.value = item;

        Object.keys(defaultForm).forEach((key) => {
            form[key] = item[key as keyof T] ?? defaultForm[key];
        });

        isEditDialogOpen.value = true;
    };

    const openDeleteDialog = (item: T) => {
        selectedItem.value = item;
        isDeleteDialogOpen.value = true;
    };

    const closeDialogs = () => {
        isAddDialogOpen.value = false;
        isEditDialogOpen.value = false;
        isDeleteDialogOpen.value = false;
    };

    return {
        form,
        selectedItem,

        isAddDialogOpen,
        isEditDialogOpen,
        isDeleteDialogOpen,

        openCreateDialog,
        openEditDialog,
        openDeleteDialog,
        closeDialogs,
    };
}
