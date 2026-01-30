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
    addForm: any;
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
                <DialogTitle>Add Topic Meeting</DialogTitle>
                <DialogDescription>
                    Add a new topic meeting.
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="$emit('confirm')" class="space-y-4">
                <div class="space-y-2">
                    <Label for="add-topic_code">Code</Label>
                    <Input
                        id="add-topic_code"
                        v-model="addForm.topic_code"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="Topic meeting code"
                        readonly
                        disabled
                    />
                    <InputError :message="addForm.errors?.topic_code" />
                </div>
                <div class="space-y-2">
                    <Label for="add-topic_name">Topic Name</Label>
                    <Input
                        id="add-type_meeting_name"
                        v-model="addForm.topic_name"
                        type="text"
                        class="mt-1 w-full"
                        placeholder="Topic meeting name"
                    />
                    <InputError :message="addForm.errors?.topic_name" />
                </div>
                <div class="space-y-2">
                    <Label for="add-topic_description"
                        >Description</Label
                    >
                    <Input
                        id="add-topic_description"
                        v-model="addForm.topic_description"
                        type="text"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        placeholder="Description"
                    />
                    <InputError
                        :message="addForm.errors?.topic_description"
                    />
                </div>
                <div class="flex justify-end gap-2">
                    <Button type="submit" :disabled="addForm.processing">
                        <Loader2
                            v-if="addForm.processing"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        {{ addForm.processing ? 'creating...' : 'Create' }}
                    </Button>
                </div>
            </form>
        </DialogContent>
    </Dialog>
    <!-- End Edit Task Dialog -->
</template>
