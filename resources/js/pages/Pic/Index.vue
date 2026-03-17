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
import ModalPic from '@/pages/Pic/Modal.vue';
import { dashboard } from '@/routes';
import { User } from '@/types';
import { formatDateID } from '@/types/dateFormat';
import { Paginated, Pic } from '@/types/master';
import { Head, Link } from '@inertiajs/vue3';
import {
    List,
    MoreHorizontal,
    Plus,
    Search,
    SquarePen,
    Trash2,
    UserIcon,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    pics: Paginated<Pic>;
    users: User[];
}>();

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'PIC',
        href: '/pic',
    },
];

const searchQuery = ref('');
const clearSearch = () => (searchQuery.value = '');
const picRef = computed(() => props.pics);

const { filteredData, getRowNumber } = usePaginatedFilter(
    picRef,
    searchQuery,
    ['name', 'email', 'phone', 'status', 'archievement', 'description'], // bebas field apa saja
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
} = useOpenModalDialog<Pic>({
    id: null,
    name: '',
    email: '',
    phone: '',
    status: 'active',
    archievement: '',
    description: '',
    user_id: null,
});

const { store, update, destroy } = useCrudActions(formData, `/pic`);

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
    <Head title="PIC" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-12">
            <div>
                <h1 class="text-xl font-semibold">PIC</h1>
                <p class="text-sm text-muted-foreground">
                    Manage and organize all pic here.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <UserIcon class="h-4 w-4" />
                        <p class="font-semibold">List PIC</p>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Here is the list of all PIC.
                    </p>
                </div>

                <Button
                    v-if="props.pics?.data?.length > 0"
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
                        v-if="props.pics?.data?.length > 0"
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
                        v-if="props.pics?.data?.length > 0"
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
                                    v-for="(pic, index) in filteredData"
                                    :key="pic.id"
                                >
                                    <TableCell>{{
                                        getRowNumber(index)
                                    }}</TableCell>
                                    <TableCell>{{ pic.name }}</TableCell>
                                    <TableCell>{{ pic.description }}</TableCell>
                                    <TableCell>{{
                                        formatDateID(pic.created_at)
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
                                                    @click="openEditDialog(pic)"
                                                >
                                                    <SquarePen
                                                        class="mr-2 h-4 w-4"
                                                    />
                                                    Edit
                                                </DropdownMenuItem>
                                                <DropdownMenuItem
                                                    @click="
                                                        openDeleteDialog(pic)
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
                    <div v-if="props.pics.total === 0" class="py-12">
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <List class="h-12 w-12" />
                                </EmptyMedia>
                                <EmptyTitle>No PIC Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven't created any PIC yet. Get started
                                    by creating your first PIC.
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
                            No PIC match your search query. Try adjusting your
                            filters.
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
                            Page {{ props.pics.current_page }} of
                            {{ props.pics.last_page }} —
                            {{ props.pics.total }} total data
                        </p>

                        <div class="flex items-center gap-1">
                            <Link
                                v-for="(link, i) in props.pics.links"
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
        <ModalPic
            v-model:open="isDialogOpen"
            :form="formData"
            :mode="mode"
            :users="props.users"
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
