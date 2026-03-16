<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';

import { AlertTriangle } from 'lucide-vue-next';

const open = defineModel<boolean>('open');

const emit = defineEmits<{
    (e: 'confirm'): void;
}>();
</script>

<template>
    <AlertDialog v-model:open="open">
        <AlertDialogContent class="sm:max-w-[420px]">
            <!-- Icon -->
            <div class="mb-4 flex justify-center">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600"
                >
                    <AlertTriangle class="h-6 w-6" />
                </div>
            </div>

            <AlertDialogHeader class="space-y-2 text-center">
                <AlertDialogTitle class="text-lg text-center font-semibold">
                    Apakah Anda Yakin?
                </AlertDialogTitle>

                <AlertDialogDescription class="text-sm text-center text-muted-foreground">
                    Tindakan ini akan menghapus data secara permanen dan tidak
                    dapat dibatalkan.
                </AlertDialogDescription>
            </AlertDialogHeader>

            <AlertDialogFooter class="mt-6 flex gap-2 sm:justify-center">
                <AlertDialogCancel class="min-w-[100px]" @click="open = false">
                    Batal
                </AlertDialogCancel>

                <AlertDialogAction
                    variant="destructive"
                    class="min-w-[100px] bg-red-600 hover:bg-red-700 focus:ring-red-600"
                    @click="emit('confirm')"
                >
                    Hapus
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
