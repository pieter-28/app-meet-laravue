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
import ModalPlace from '@/pages/Place/Modal.vue';
import { dashboard } from '@/routes';
import { Paginated, Place } from '@/types/master';
import { Head, Link } from '@inertiajs/vue3';
import {
    MoreHorizontal,
    Search,
    SquarePen,
    Trash2,
    User,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    places: Paginated<Place>;
    filters: {
        search?: string;
    };
}>();

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Place',
        href: '/place',
    },
];

const searchQuery = ref('');
const clearSearch = () => (searchQuery.value = '');
const placeRef = computed(() => props.places);

const { filteredData, getRowNumber } = usePaginatedFilter(
    placeRef,
    searchQuery,
    ['name', 'address', 'description'], // bebas field apa saja
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
} = useOpenModalDialog<Place>({
    id: null,
    name: '',
    address: '',
    description: '',
});

const { store, update, destroy } = useCrudActions(formData, `/place`);

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
    <Head title="Place" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-12">
            <div>
                <h1 class="text-xl font-semibold">Place Meeting</h1>
                <p class="text-sm text-muted-foreground">
                    Manage and organize all place meetings here.
                </p>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <User class="h-4 w-4" />
                        <p class="font-semibold">List Place Meeting</p>
                    </div>

                    <p class="text-sm text-muted-foreground">
                        Here is the list of all place meetings.
                    </p>
                </div>

                <Button
                    v-if="props.places?.data?.length > 0"
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
                        v-if="props.places?.data?.length > 0"
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

                    <div class="rounded-lg border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Address</TableHead>
                                    <TableHead>Description</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="(place, index) in filteredData"
                                    :key="place.id"
                                >
                                    <TableCell>{{
                                        getRowNumber(index)
                                    }}</TableCell>
                                    <TableCell>{{ place.name }}</TableCell>
                                    <TableCell>{{ place.address }}</TableCell>
                                    <TableCell>{{
                                        place.description
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
                                                        openEditDialog(place)
                                                    "
                                                >
                                                    <SquarePen
                                                        class="mr-2 h-4 w-4"
                                                    />
                                                    Edit
                                                </DropdownMenuItem>
                                                <DropdownMenuItem
                                                    @click="
                                                        openDeleteDialog(place)
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
                    <div v-if="props.places.total === 0" class="py-12">
                        <Empty>
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <Shield class="h-12 w-12" />
                                </EmptyMedia>
                                <EmptyTitle>No Places Yet</EmptyTitle>
                                <EmptyDescription>
                                    You haven't created any place yet. Get
                                    started by creating your first place.
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
                            No places match your search query. Try adjusting
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
                            Page {{ props.places.current_page }} of
                            {{ props.places.last_page }} —
                            {{ props.places.total }} total data
                        </p>

                        <div class="flex items-center gap-1">
                            <Link
                                v-for="(link, i) in props.places.links"
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
        <ModalPlace
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
