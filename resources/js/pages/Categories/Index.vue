<script setup lang="ts">
import Alert from '@/components/AlertDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import ModalCreate from '@/pages/Categories/Add.vue';
import Modal from '@/pages/Categories/Modal.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import axios from 'axios';
import {
    FolderCode,
    Loader2,
    LucidePencil,
    MoreHorizontal,
    Search,
    Trash,
    User,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Categories {
    id: number;
    code: string;
    name: string;
    description?: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedCategories {
    data: Categories[];
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
    categories: PaginatedCategories; // categories is the name of the prop same as the name of the data in the controller
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
        title: 'Categories',
        href: '/categories',
    },
];

const isDeleteDialogOpen = ref(false);
const typeIdToDelete = ref<number | null>(null);
const deleteTypeId = ref<number | null>(null);

const isEditDialogOpen = ref(false);
const isAddDialogOpen = ref(false);
const editingTask = ref<Categories | null>(null);

const deleteCategory = (id: number) => {
    typeIdToDelete.value = id;
    isDeleteDialogOpen.value = true;
};

const confirmDeleteType = () => {
    if (!typeIdToDelete.value) return;
    deleteTypeId.value = typeIdToDelete.value;
    router.delete(`/categories/${typeIdToDelete.value}`, {
        preserveScroll: true,

        onFinish: () => {
            deleteTypeId.value = null;
            typeIdToDelete.value = null;
            isDeleteDialogOpen.value = false;
            toast.success('Category deleted successfully!');
        },
    });
};

const addForm = useForm({
    code: '',
    name: '',
    description: '',
});

const generateCode = async () => {
    try {
        const res = await axios.get('/categories/code');
        addForm.code = res.data.code;
    } catch (error) {
        console.error(error);
    }
};

const editForm = useForm({
    code: '',
    name: '',
    description: '',
});

const storeCategory = () => {
    addForm.post('/categories', {
        preserveScroll: true,
        onSuccess: () => {
            isAddDialogOpen.value = false;
            addForm.reset();
            toast.success('Category created successfully!');
        },
    });
};

const updateTypeMeeting = () => {
    if (!editingTask.value) return;

    editForm.put(`/categories/${editingTask.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isEditDialogOpen.value = false;
            editForm.reset();
            toast.success('Category updated successfully!');
        },
    });
};

const openAddDialog = () => {
    isAddDialogOpen.value = true;
    editForm.reset();
    generateCode();
};

const openEditDialog = (item: Categories) => {
    editingTask.value = { ...item };
    editForm.code = item.code;
    editForm.name = item.name || '';
    editForm.description = item.description || '';
    isEditDialogOpen.value = true;
};

const search = ref(props.filters.search ?? '');

watchDebounced(
    search,
    (value) => {
        router.get(
            '/categories',
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
    <Head title="Categories Meeting" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-12">
            <div>
                <h1 class="text-xl font-semibold">Categories Meeting</h1>
                <p class="text-sm text-muted-foreground">
                    Manage and organize all categories meetings here.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <User class="h-4 w-4" />
                        <p class="font-semibold">List Categori Meeting</p>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Here is the list of all Categori meetings.
                    </p>
                </div>
                <Button
                    v-if="props.categories?.data?.length > 0"
                    variant="default"
                    size="sm"
                    @click="openAddDialog"
                >
                    + Create
                </Button>
            </div>

        <!-- card -->
        <Card>
            <CardContent>
                <!-- search -->
                <div
                    v-if="props.categories?.data?.length > 0"
                    class="mb-4 flex items-center justify-between gap-4"
                >
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

                <!-- end search -->

                <!-- table -->
                <div
                    v-if="props.categories?.data?.length > 0"
                    class="space-y-4"
                >
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Code</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead class="text-center"
                                        >Action</TableHead
                                    >
                                </TableRow>
                            </TableHeader>
                            <TableBody class="max-h-96 overflow-y-auto">
                                <TableRow
                                    v-for="(type, index) in props.categories
                                        .data"
                                    :key="type.id"
                                >
                                    <TableCell>
                                        {{
                                            (props.categories.current_page -
                                                1) *
                                                props.categories.per_page +
                                            index +
                                            1
                                        }}
                                    </TableCell>
                                    <TableCell>{{ type.code }}</TableCell>
                                    <TableCell>{{ type.name }}</TableCell>
                                    <TableCell>{{
                                        type.description
                                    }}</TableCell>
                                    <TableCell class="space-x-2 text-center">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button
                                                    variant="ghost"
                                                    class="h-8 w-8 p-0"
                                                >
                                                    <span class="sr-only"
                                                        >Open menu</span
                                                    >
                                                    <MoreHorizontal
                                                        class="h-4 w-4"
                                                    />
                                                </Button>
                                            </DropdownMenuTrigger>

                                            <DropdownMenuContent align="end">
                                                <DropdownMenuLabel
                                                    >Action</DropdownMenuLabel
                                                >
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem
                                                    @click="
                                                        openEditDialog(type)
                                                    "
                                                >
                                                    <LucidePencil
                                                        class="h-4 w-4"
                                                    />
                                                    Edit
                                                </DropdownMenuItem>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem
                                                    @click="
                                                        deleteCategory(type.id)
                                                    "
                                                    :disabled="
                                                        deleteTypeId === type.id
                                                    "
                                                >
                                                    <Loader2
                                                        v-if="
                                                            deleteTypeId ===
                                                            type.id
                                                        "
                                                        class="h-4 w-4 animate-spin"
                                                    />
                                                    <Trash
                                                        v-else
                                                        class="h-4 w-4"
                                                    />
                                                    Delete
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Paginate -->
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Page {{ props.categories.current_page }} of
                            {{ props.categories.last_page }} —
                            {{ props.categories.total }} total data
                        </p>

                        <div class="flex items-center gap-1">
                            <Link
                                v-for="(link, i) in props.categories.links"
                                :key="i"
                                :href="link.url ?? ''"
                                preserve-state
                                preserve-scroll
                                v-html="link.label"
                                class="rounded-md px-3 py-1 text-sm"
                                :class="{
                                    'bg-primary text-primary-foreground':
                                        link.active,
                                    'pointer-events-none opacity-50': !link.url,
                                    'hover:bg-muted': link.url && !link.active,
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
                                You haven't created any categories yet. Get
                                started by creating your first category.
                            </EmptyDescription>
                        </EmptyHeader>
                        <EmptyContent>
                            <div class="flex gap-2">
                                <Button @click="openAddDialog">Create</Button>
                            </div>
                        </EmptyContent>
                    </Empty>
                </div>
            </CardContent>
        </Card>
        <!-- end card -->

        <!-- Start Add Task Dialog -->
        <ModalCreate
            :edit-form="addForm"
            v-model:open="isAddDialogOpen"
            @confirm="storeCategory"
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

        <Alert v-model:open="isDeleteDialogOpen" @confirm="confirmDeleteType" />
        </div>
    </AppLayout>
</template>
