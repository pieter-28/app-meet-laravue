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
    editForm: any;
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
                <DialogTitle>Add Category</DialogTitle>
                <DialogDescription> Add a new category. </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="$emit('confirm')" class="space-y-4">
                <div class="space-y-2">
                    <Label for="add-code">Code</Label>
                    <Input
                        id="add-code"
                        v-model="editForm.code"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="code"
                        readonly
                        disabled
                    />
                    <InputError :message="editForm.errors?.code" />
                </div>
                <div class="space-y-2">
                    <Label for="add-name">Name</Label>
                    <Input
                        id="add-name"
                        v-model="editForm.name"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="name"
                    />
                    <InputError :message="editForm.errors?.name" />
                </div>
                <div class="space-y-2">
                    <Label for="add-description">Description</Label>
                    <Input
                        id="add-description"
                        v-model="editForm.description"
                        type="text"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        placeholder="Description"
                    />
                    <InputError :message="editForm.errors?.description" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button type="submit" :disabled="editForm.processing">
                        <Loader2
                            v-if="editForm.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{ editForm.processing ? 'creating...' : 'Create' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
    <!-- End Edit Task Dialog -->
</template>
