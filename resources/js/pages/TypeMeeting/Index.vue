<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import ModalAdd from '@/pages/TypeMeeting/Add.vue';
import Modal from '@/pages/TypeMeeting/Modal.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import axios from 'axios';
import {
    FolderCode,
    Loader2,
    LucidePencil,
    Search,
    Trash,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import Alert from '../alert.vue';

interface TypeMeeting {
    id: number;
    type_meeting_code: string;
    type_meeting_name: string;
    type_meeting_description?: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedTypeMeetings {
    data: TypeMeeting[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    filters: {
        search?: string;
    };
}

const props = defineProps<{
    typeMeetings: PaginatedTypeMeetings; // typeMeetings is the name of the prop same as the name of the data in the controller
    filters: {
        search?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Type Meeting',
        href: '/type-meetings',
    },
];

const isDeleteDialogOpen = ref(false);
const typeIdToDelete = ref<number | null>(null);
const deleteTypeId = ref<number | null>(null);

const isEditDialogOpen = ref(false);
const isAddDialogOpen = ref(false);
const editingTask = ref<TypeMeeting | null>(null);

const deleteType = (id: number) => {
    typeIdToDelete.value = id;
    isDeleteDialogOpen.value = true;
};

const confirmDeleteType = () => {
    if (!typeIdToDelete.value) return;
    deleteTypeId.value = typeIdToDelete.value;
    router.delete(`/type-meetings/${typeIdToDelete.value}`, {
        preserveScroll: true,
        onFinish: () => {
            deleteTypeId.value = null;
            typeIdToDelete.value = null;
            isDeleteDialogOpen.value = false;
            toast.success('Type Meeting deleted successfully!');
        },
    });
};

const addForm = useForm({
    type_meeting_code: '',
    type_meeting_name: '',
    type_meeting_description: '',
});

const editForm = useForm({
    type_meeting_code: '',
    type_meeting_name: '',
    type_meeting_description: '',
});

const generateCode = async () => {
    try {
        const res = await axios.get('/type-meetings/code');
        addForm.type_meeting_code = res.data.code;
    } catch (error) {
        console.error(error);
    }
};

const storeData = async () => {
    await addForm.post('/type-meetings', {
        preserveScroll: true,
        onSuccess: () => {
            isAddDialogOpen.value = false;
            addForm.reset();
            toast.success('Type Meeting created successfully!');
        },
    });
};

const updateTypeMeeting = () => {
    if (!editingTask.value) return;

    editForm.put(`/type-meetings/${editingTask.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isEditDialogOpen.value = false;
            editForm.reset();
            toast.success('Task updated successfully!');
        },
    });
};

const openAddDialog = () => {
    isAddDialogOpen.value = true;
    addForm.reset();
    generateCode();
};
const openEditDialog = (item: TypeMeeting) => {
    editingTask.value = { ...item };
    editForm.type_meeting_code = item.type_meeting_code;
    editForm.type_meeting_name = item.type_meeting_name || '';
    editForm.type_meeting_description = item.type_meeting_description || '';
    isEditDialogOpen.value = true;
};

const search = ref(props.filters.search ?? '');

watchDebounced(
    search,
    (value) => {
        router.get(
            '/type-meetings',
            {
                search: value || undefined,
                page: 1,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    },
    { debounce: 300 },
);
</script>

<template>
    <Head title="Type Meeting" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="mb-4">
                <h5>Master Type Meetings</h5>
                <p class="text-sm text-muted-foreground">
                    Manage your type meetings here. You can create, edit, and
                    delete type meetings as needed.
                </p>
            </div>

            <Button
                v-if="props.typeMeetings?.data?.length > 0"
                variant="default"
                size="sm"
                @click="openAddDialog"
                >+ Create</Button
            >

            <Card>
                <CardContent>
                    <!-- search -->
                    <div
                        v-if="props.typeMeetings?.data?.length > 0"
                        class="mb-4 grid gap-4 md:grid-cols-3"
                    >
                        <div class="space-y-2">
                            <div class="relative w-64">
                                <Search
                                    class="absolute top-2.5 left-2 h-4 w-4 text-muted-foreground"
                                />
                                <Input
                                    v-model="search"
                                    type="search"
                                    placeholder="Search type meetings..."
                                    class="pl-8"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- end search -->

                    <!-- table -->
                    <div
                        v-if="props.typeMeetings?.data?.length > 0"
                        class="space-y-4"
                    >
                        <div class="rounded-md border">
                            <table class="w-full caption-bottom text-sm">
                                <thead class="text-center [&_tr]:border-b">
                                    <tr
                                        class="border-b transition-colors hover:bg-muted"
                                    >
                                        <th
                                            class="px-4 py-2 text-left font-semibold"
                                        >
                                            No
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left font-semibold"
                                        >
                                            Code
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left font-semibold"
                                        >
                                            Name
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left font-semibold"
                                        >
                                            Description
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left font-semibold"
                                        >
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="[&_tr:last-child]:border-0">
                                    <tr
                                        v-for="(type, index) in props
                                            .typeMeetings.data"
                                        :key="type.id"
                                        class="border-b transition-colors hover:bg-muted/50"
                                    >
                                        <td
                                            class="w-2 p-2 text-center align-middle"
                                        >
                                            {{
                                                (props.typeMeetings
                                                    .current_page -
                                                    1) *
                                                    props.typeMeetings
                                                        .per_page +
                                                index +
                                                1
                                            }}
                                        </td>

                                        <td class="p-2 align-middle">
                                            {{ type.type_meeting_code }}
                                        </td>

                                        <td class="p-2 align-middle">
                                            {{ type.type_meeting_name }}
                                        </td>

                                        <td class="p-2 align-middle">
                                            {{
                                                type.type_meeting_description ??
                                                '-'
                                            }}
                                        </td>

                                        <td class="space-x-4 p-2 align-middle">
                                            <Button
                                                size="sm"
                                                @click="openEditDialog(type)"
                                                variant="secondary"
                                            >
                                                <LucidePencil class="h-4 w-4" />
                                            </Button>

                                            <Button
                                                size="sm"
                                                variant="destructive"
                                                @click="deleteType(type.id)"
                                                :disabled="
                                                    deleteTypeId === type.id
                                                "
                                            >
                                                <Loader2
                                                    v-if="
                                                        deleteTypeId === type.id
                                                    "
                                                    class="h-4 w-4 animate-spin"
                                                />
                                                <Trash v-else class="h-4 w-4" />
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginate -->
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-muted-foreground">
                                Page {{ props.typeMeetings.current_page }} of
                                {{ props.typeMeetings.last_page }} —
                                {{ props.typeMeetings.total }} total data
                            </p>

                            <div class="flex items-center gap-1">
                                <Link
                                    v-for="(link, i) in props.typeMeetings
                                        .links"
                                    :key="i"
                                    :href="link.url ?? ''"
                                    preserve-state
                                    preserve-scroll
                                    v-html="link.label"
                                    class="rounded-md px-3 py-1 text-sm"
                                    :class="{
                                        'bg-primary text-primary-foreground':
                                            link.active,
                                        'pointer-events-none opacity-50':
                                            !link.url,
                                        'hover:bg-muted':
                                            link.url && !link.active,
                                    }"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- end table -->

                    <!-- no data -->
                    <div v-else class="py-12 text-center text-muted-foreground">
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <FolderCode />
                                </EmptyMedia>
                                <EmptyTitle>No Data Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven't created any data yet. Get
                                    started by creating your first data.
                                </EmptyDescription>
                            </EmptyHeader>
                            <EmptyContent>
                                <div class="flex gap-2">
                                    <Button @click="openAddDialog"
                                        >+ Create</Button
                                    >
                                </div>
                            </EmptyContent>
                        </Empty>
                    </div>
                </CardContent>
            </Card>
            <!-- end card -->

            <!-- Start Add Task Dialog -->
            <ModalAdd
                :add-form="addForm"
                v-model:open="isAddDialogOpen"
                @confirm="storeData"
            />
            <!-- End Add Task Dialog -->

            <!-- Start Edit Task Dialog -->
            <Modal
                :edit-form="editForm"
                v-model:open="isEditDialogOpen"
                :editing-task="!!editingTask"
                @confirm="updateTypeMeeting"
            />
            <!-- End Edit Task Dialog -->

            <Alert
                v-model:open="isDeleteDialogOpen"
                @confirm="confirmDeleteType"
            />
        </div>
    </AppLayout>
</template>
