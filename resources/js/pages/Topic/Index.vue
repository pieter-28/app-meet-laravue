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
import ModalAdd from '@/pages/Topic/Add.vue';
import Modal from '@/pages/Topic/Modal.vue';
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
    User,
    MoreHorizontal,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Topic {
    id: number;
    topic_code: string;
    topic_name: string;
    topic_description?: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedTopic {
    data: Topic[];
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
    topics: PaginatedTopic; // topics is the name of the prop same as the name of the data in the controller
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
        title: 'Topic Meeting',
        href: '/topic',
    },
];

const isDeleteDialogOpen = ref(false);
const topicIdToDelete = ref<number | null>(null);
const deleteId = ref<number | null>(null);

const isEditDialogOpen = ref(false);
const isAddDialogOpen = ref(false);
const editingTopic = ref<Topic | null>(null);

const deleteType = (id: number) => {
    topicIdToDelete.value = id;
    isDeleteDialogOpen.value = true;
};

const confirmDeleteType = () => {
    if (!topicIdToDelete.value) return;
    deleteId.value = topicIdToDelete.value;
    router.delete(`/topic/${topicIdToDelete.value}`, {
        preserveScroll: true,
        onFinish: () => {
            deleteId.value = null;
            topicIdToDelete.value = null;
            isDeleteDialogOpen.value = false;
            toast.success('Type Meeting deleted successfully!');
        },
    });
};

const form = useForm({
    topic_code: '',
    topic_name: '',
    topic_description: '',
});

const generateCode = async () => {
    try {
        const res = await axios.get('/topic/code');
        form.topic_code = res.data.code;
    } catch (error) {
        console.error(error);
    }
};

const storeData = async () => {
    await form.post('/topic', {
        preserveScroll: true,
        onSuccess: () => {
            isAddDialogOpen.value = false;
            form.reset();
            toast.success('Topic created successfully!');
        },
    });
};

const updateTopicMeeting = () => {
    if (!editingTopic.value) return;

    form.put(`/topic/${editingTopic.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            isEditDialogOpen.value = false;
            form.reset();
            toast.success('Task updated successfully!');
        },
    });
};

const openAddDialog = () => {
    isAddDialogOpen.value = true;
    form.reset();
    generateCode();
};

const openEditDialog = (item: Topic) => {
    editingTopic.value = { ...item };
    form.topic_code = item.topic_code;
    form.topic_name = item.topic_name || '';
    form.topic_description = item.topic_description || '';
    isEditDialogOpen.value = true;
};

const search = ref(props.filters.search ?? '');

watchDebounced(
    search,
    (value) => {
        router.get(
            '/topic',
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
    <Head title="Topic Meeting" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-12">
            <div>
                <h1 class="text-xl font-semibold">Topic Meeting</h1>
                <p class="text-sm text-muted-foreground">
                    Manage and organize all topic meetings here.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <User class="h-4 w-4" />
                        <p class="font-semibold">List Topic Meeting</p>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Here is the list of all topic meetings.
                    </p>
                </div>

                <Button
                    v-if="props.topics?.data?.length > 0"
                    variant="default"
                    size="sm"
                    @click="openAddDialog"
                    class="flex items-center gap-1"
                >
                    + Create
                </Button>
            </div>

            <Card>
                <CardContent>
                    <!-- search -->
                    <div
                        v-if="props.topics?.data?.length > 0"
                        class="mb-4 flex items-center justify-between gap-4"
                    >
                        <div class="relative w-64">
                            <Search
                                class="absolute top-2.5 left-2 h-4 w-4 text-muted-foreground"
                            />
                            <Input
                                v-model="search"
                                type="search"
                                placeholder="Search topic..."
                                class="pl-8"
                            />
                        </div>
                    </div>
                    <!-- end search -->

                    <!-- table -->
                    <div
                        v-if="props.topics?.data?.length > 0"
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
                                        v-for="(type, index) in props.topics
                                            .data"
                                        :key="type.id"
                                    >
                                        <TableCell>
                                            {{
                                                (props.topics.current_page -
                                                    1) *
                                                    props.topics.per_page +
                                                index +
                                                1
                                            }}
                                        </TableCell>
                                        <TableCell>{{
                                            type.topic_code
                                        }}</TableCell>
                                        <TableCell>{{
                                            type.topic_name
                                        }}</TableCell>
                                        <TableCell>{{
                                            type.topic_description ?? '-'
                                        }}</TableCell>
                                        <TableCell
                                            class="space-x-2 text-center"
                                        >
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
                                                
                                                <DropdownMenuContent
                                                    align="end"
                                                >
                                                    <DropdownMenuLabel>Action</DropdownMenuLabel>
                                                    <DropdownMenuSeparator/>
                                                    <DropdownMenuItem
                                                        @click="
                                                            openEditDialog(type)
                                                        "
                                                    >
                                                    <LucidePencil class="h-4 w-4" />
                                                        Edit
                                                    </DropdownMenuItem>
                                                    <DropdownMenuSeparator />
                                                    <DropdownMenuItem
                                                        @click="
                                                            deleteType(type.id)
                                                        "
                                                        :disabled="
                                                            deleteId === type.id
                                                        "
                                                    >
                                                        <Loader2
                                                            v-if="
                                                                deleteId ===
                                                                type.id
                                                            "
                                                            class="h-4 w-4 animate-spin"
                                                        />
                                                        <Trash v-else class="h-4 w-4" />
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
                                Page {{ props.topics.current_page }} of
                                {{ props.topics.last_page }} —
                                {{ props.topics.total }} total data
                            </p>

                            <div class="flex items-center gap-1">
                                <Link
                                    v-for="(link, i) in props.topics.links"
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
                :add-form="form"
                v-model:open="isAddDialogOpen"
                @confirm="storeData"
            />
            <!-- End Add Task Dialog -->

            <!-- Start Edit Task Dialog -->
            <Modal
                :edit-form="form"
                v-model:open="isEditDialogOpen"
                :editing-task="!!editingTopic"
                @confirm="updateTopicMeeting"
            />
            <!-- End Edit Task Dialog -->

            <Alert
                v-model:open="isDeleteDialogOpen"
                @confirm="confirmDeleteType"
            />
        </div>
    </AppLayout>
</template>
