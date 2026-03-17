<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group';
import { type User } from '@/types';
import { Loader2 } from 'lucide-vue-next';
import { watch } from 'vue';

const props = defineProps<{
    form: any;
    mode: 'create' | 'edit';
    users: User[];
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
}>();

watch(
    () => props.form.user_id,
    (userId) => {
        const selectedUser = props.users.find((u) => u.id === userId);

        props.form.name = selectedUser?.name ?? '';
        props.form.email = selectedUser?.email ?? '';
        props.form.phone = selectedUser?.phone ?? '';
    },
);

const open = defineModel<boolean>('open');
</script>

<template>
    <!-- Start Edit Task Dialog -->
    <Dialog v-model:open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{ props.mode === 'create' ? 'Add PIC' : 'Edit PIC' }}
                </DialogTitle>
                <DialogDescription>
                    {{
                        props.mode === 'create'
                            ? 'Add a new PIC.'
                            : 'Update PIC.'
                    }}
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="$emit('confirm')" class="space-y-6">
                <!-- USER SELECT -->
                <div class="space-y-3">
                    <Label for="add-user_id">User</Label>

                    <Select v-model="props.form.user_id">
                        <SelectTrigger class="w-full" required>
                            <SelectValue placeholder="Select a user" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>User</SelectLabel>
                                <SelectItem
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>
                    </Select>

                    <!-- Modern Info Alert -->
                    <div
                        class="flex items-start gap-2 rounded-lg border bg-muted/50 p-3 text-xs text-muted-foreground"
                    >
                        <span class="mt-[2px]">ℹ️</span>
                        <p>
                            Selecting a user will automatically populate the
                            name, email, and phone fields.
                        </p>
                    </div>

                    <InputError :message="props.form.errors?.user_id" />
                </div>

                <!-- AUTO FILLED FIELDS -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label>Name</Label>
                        <Input
                            v-model="props.form.name"
                            readonly
                            class="cursor-not-allowed bg-muted/40"
                            placeholder="Auto-filled"
                            required
                        />
                        <InputError :message="props.form.errors?.name" />
                    </div>

                    <div class="space-y-2">
                        <Label>Email</Label>
                        <Input
                            v-model="props.form.email"
                            readonly
                            class="cursor-not-allowed bg-muted/40"
                            placeholder="Auto-filled"
                            required
                        />
                        <InputError :message="props.form.errors?.email" />
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <Label>Phone</Label>
                        <Input
                            v-model="props.form.phone"
                            readonly
                            class="cursor-not-allowed bg-muted/40"
                            placeholder="Auto-filled"
                        />
                        <InputError :message="props.form.errors?.phone" />
                    </div>
                </div>

                <div class="space-y-3">
                    <Label>Status</Label>

                    <ToggleGroup
                        type="single"
                        v-model="props.form.status"
                        class="justify-start rounded"
                    >
                        <ToggleGroupItem value="active" class="px-4">
                            Active
                        </ToggleGroupItem>
                        <ToggleGroupItem value="inactive" class="px-4">
                            Inactive
                        </ToggleGroupItem>
                    </ToggleGroup>

                    <InputError :message="props.form.errors?.status" />
                </div>

                <!-- ACHIEVEMENT -->
                <div class="space-y-2">
                    <Label>Achievement</Label>
                    <textarea
                        v-model="props.form.achievement"
                        rows="3"
                        class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none"
                        placeholder="Write a short description..."
                    />
                    <InputError :message="props.form.errors?.achievement" />
                </div>

                <!-- DESCRIPTION -->
                <div class="space-y-2">
                    <Label>Description</Label>
                    <textarea
                        v-model="props.form.description"
                        rows="3"
                        class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-ring focus:outline-none"
                        placeholder="Write a short description..."
                    />
                    <InputError :message="props.form.errors?.description" />
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-2">
                    <Button
                        type="submit"
                        class="min-w-[120px]"
                        :disabled="props.form.processing"
                    >
                        <Loader2
                            v-if="props.form.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{
                            props.form.processing
                                ? props.mode === 'create'
                                    ? 'Creating...'
                                    : 'Updating...'
                                : props.mode === 'create'
                                  ? 'Create PIC'
                                  : 'Update PIC'
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
    <!-- End Edit Task Dialog -->
</template>
