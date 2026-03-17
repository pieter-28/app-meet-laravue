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
import { Loader2 } from 'lucide-vue-next';

defineProps<{
    form: any;
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'confirm'): void;
}>();

const open = defineModel<boolean>('open');
</script>

<template>
    <!-- Start Edit Task Dialog -->
    <Dialog v-model:open="open">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>
                    {{
                        mode === 'create'
                            ? 'Add Place Meeting'
                            : 'Edit Place Meeting'
                    }}
                </DialogTitle>
                <DialogDescription>
                    {{
                        mode === 'create'
                            ? 'Add a new place meeting.'
                            : 'Update place meeting.'
                    }}
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="$emit('confirm')" class="space-y-4">
                <div class="space-y-2">
                    <Label for="add-place_name">Place Name</Label>
                    <Input
                        id="add-place_name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="Place meeting name"
                    />
                    <InputError :message="form.errors?.name" />
                </div>
                <div class="space-y-2">
                    <Label for="add-form-address">Address</Label>
                    <Input
                        id="add-form-address"
                        v-model="form.address"
                        type="text"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        placeholder="Address"
                    />
                    <InputError :message="form.errors?.address" />
                </div>
                <div class="space-y-2">
                    <Label for="add-form-description">Description</Label>
                    <Input
                        id="add-form-description"
                        v-model="form.description"
                        type="text"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        placeholder="Description"
                    />
                    <InputError :message="form.errors?.description" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button type="submit" :disabled="form.processing">
                        <Loader2
                            v-if="form.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{
                            form.processing
                                ? mode === 'create'
                                    ? 'creating...'
                                    : 'updating...'
                                : mode === 'create'
                                  ? 'Create'
                                  : 'Update'
                        }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
    <!-- End Edit Task Dialog -->
</template>
