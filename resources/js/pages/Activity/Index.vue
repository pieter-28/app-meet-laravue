<script setup lang="ts">
import AlertDialog from '@/components/AlertDialog.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
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
import { useCrudActions } from '@/composables/useCrudAction';
import { useOpenModalDialog } from '@/composables/useOpenModalDialog';
import { usePaginatedFilter } from '@/composables/usePaginatedFilter';
import AppLayout from '@/layouts/AppLayout.vue';
import ModalActivity from '@/pages/Activity/Modal.vue';
import { dashboard } from '@/routes';
import { formatDateID } from '@/types/dateFormat';
import { Activity, Paginated } from '@/types/master';
import { Head, Link } from '@inertiajs/vue3';
import {
    List,
    MoreHorizontal,
    Plus,
    Search,
    SquarePen,
    Trash2,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    activities: Paginated<Activity>;
}>();

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Activity',
        href: '/activity',
    },
];

const searchQuery = ref('');
const clearSearch = () => (searchQuery.value = '');
const activityRef = computed(() => props.activities);

const { filteredData, getRowNumber } = usePaginatedFilter(
    activityRef,
    searchQuery,
    ['name', 'code', 'description'], // bebas field apa saja
);

const {
    form: formData,
    selectedItem,
    mode,
    isDialogOpen,
    isDeleteDialogOpen,
    openCreateDialog,
    openEditDialog,
    openDeleteDialog,
    closeDialogs,
} = useOpenModalDialog<Activity>({
    id: null,
    name: '',
    description: '',
});

const { store, update, destroy } = useCrudActions(formData, `/activity`);

const handleDestroy = () => {
    if (!selectedItem.value?.id) return;

    destroy(selectedItem.value.id, () => {
        isDeleteDialogOpen.value = false;
        closeDialogs();
    });
};

const handleSubmit = () => {
    const onSuccess = () => {
        closeDialogs();
        formData.reset();
    };

    if (mode.value === 'edit' && selectedItem.value) {
        update(selectedItem.value.id, onSuccess);
    } else {
        store(onSuccess);
    }
};
</script>

<template>
    <Head title="Activity" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-12">
            <div>
                <h1 class="text-xl font-semibold">Activity</h1>
                <p class="text-sm text-muted-foreground">
                    Manage and organize all activities here.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <User class="h-4 w-4" />
                        <p class="font-semibold">List Activity</p>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Here is the list of all activities.
                    </p>
                </div>

                <Button
                    v-if="props.activities?.data?.length > 0"
                    variant="default"
                    size="sm"
                    @click="openCreateDialog"
                    class="flex items-center gap-1"
                >
                    + Create
                </Button>
            </div>
            <Card>
                <CardContent>
                    <div
                        v-if="props.activities?.data?.length > 0"
                        class="mb-4 flex items-center justify-between gap-4"
                    >
                        <div class="relative w-64">
                            <Search
                                class="absolute top-2.5 left-2 h-4 w-4 text-muted-foreground"
                            />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Search type meetings..."
                                class="pl-8"
                            />
                        </div>
                    </div>

                    <div
                        class="rounded-lg border"
                        v-if="props.activities?.data?.length > 0"
                    >
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Created At</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="(activity, index) in filteredData"
                                    :key="activity.id"
                                >
                                    <TableCell>{{
                                        getRowNumber(index)
                                    }}</TableCell>
                                    <TableCell>{{ activity.name }}</TableCell>
                                    <TableCell>{{
                                        activity.description
                                    }}</TableCell>
                                    <TableCell>{{
                                        formatDateID(activity.created_at)
                                    }}</TableCell>
                                    <TableCell>
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
                                                    >Actions</DropdownMenuLabel
                                                >
                                                <DropdownMenuItem
                                                    @click="
                                                        openEditDialog(activity)
                                                    "
                                                >
                                                    <SquarePen
                                                        class="mr-2 h-4 w-4"
                                                    />
                                                    Edit
                                                </DropdownMenuItem>
                                                <DropdownMenuItem
                                                    @click="
                                                        openDeleteDialog(
                                                            activity,
                                                        )
                                                    "
                                                >
                                                    <Trash2
                                                        class="mr-2 h-4 w-4"
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

                    <!-- No Data State -->
                    <div v-if="props.activities.total === 0" class="py-12">
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <List class="h-12 w-12" />
                                </EmptyMedia>
                                <EmptyTitle>No Activity Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven't created any activity yet. Get
                                    started by creating your first activity.
                                </EmptyDescription>
                            </EmptyHeader>
                            <EmptyContent>
                                <Button @click="openCreateDialog">
                                    <Plus class="h-4 w-4" />
                                    Create
                                </Button>
                            </EmptyContent>
                        </Empty>
                    </div>

                    <!-- No Search Results -->
                    <div
                        v-else-if="filteredData.length === 0"
                        class="py-12 text-center"
                    >
                        <div class="mb-4 flex justify-center">
                            <Search
                                class="h-12 w-12 text-muted-foreground/50"
                            />
                        </div>
                        <h3 class="text-lg font-semibold">No results found</h3>
                        <p class="mt-1 text-sm text-muted-foreground">
                            No activities match your search query. Try adjusting
                            your filters.
                        </p>
                        <Button
                            variant="outline"
                            class="mt-4"
                            @click="clearSearch"
                        >
                            Clear Search
                        </Button>
                    </div>

                    <!-- Paginate -->
                    <div v-else class="mt-4 flex items-center justify-between">
                        <p class="text-sm text-muted-foreground">
                            Page {{ props.activities.current_page }} of
                            {{ props.activities.last_page }} —
                            {{ props.activities.total }} total data
                        </p>

                        <div class="flex items-center gap-1">
                            <Link
                                v-for="(link, i) in props.activities.links"
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
                </CardContent>
            </Card>
        </div>

        <!-- Modal Place -->
        <ModalActivity
            v-if="isDialogOpen"
            v-model:open="isDialogOpen"
            :form="formData"
            :mode="mode"
            @confirm="handleSubmit"
        />
        <!-- End Modal Place -->

        <!-- Delete Confirmation Dialog -->
        <AlertDialog
            v-if="isDeleteDialogOpen"
            :open="isDeleteDialogOpen"
            @update:open="isDeleteDialogOpen = false"
            @confirm="handleDestroy"
        ></AlertDialog>
    </AppLayout>
</template>
