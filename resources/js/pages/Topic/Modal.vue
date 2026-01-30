<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Loader2 } from 'lucide-vue-next';

defineProps<{
    editingTask: boolean;
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
                <DialogTitle>Edit Topic Meeting</DialogTitle>
                <DialogDescription>
                    Edit the topic meeting details below.
                </DialogDescription>
            </DialogHeader>
            <form
                v-if="editingTask"
                @submit.prevent="$emit('confirm')"
                class="space-y-4"
            >
                <div class="space-y-2">
                    <Label for="edit-topic_code">Topic Code</Label>
                    <Input
                        id="edit-topic_code"
                        v-model="editForm.topic_code"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="Type meeting code"
                        readonly
                        disabled
                    />
                    <InputError :message="editForm.errors?.topic_code" />
                </div>
                <div class="space-y-2">
                    <Label for="edit-topic_name">Topic Name</Label>
                    <Input
                        id="edit-topic_name"
                        v-model="editForm.topic_name"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="Topic meeting name"
                    />
                    <InputError :message="editForm.errors?.topic_name" />
                </div>
                <div class="space-y-2">
                    <Label for="edit-topic_description"
                        >Type Meeting Description</Label
                    >
                    <Input
                        id="edit-topic_description"
                        v-model="editForm.topic_description"
                        type="text"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        placeholder="Topic meeting description"
                    />
                    <InputError :message="editForm.errors?.topic_description" />
                </div>
                <div class="flex justify-end gap-2">
                    <Button type="submit" :disabled="editForm.processing">
                        <Loader2
                            v-if="editForm.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{ editForm.processing ? 'Updating...' : 'Update' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
    <!-- End Edit Task Dialog -->
</template>
