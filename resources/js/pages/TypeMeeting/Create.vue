<script setup lang="ts">
import { Form, Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import { LucideCheckCircle2 } from 'lucide-vue-next';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Type Meeting',
        href: '/type-meetings',
    },
];
const isCreateDialogOpen = ref(false);

const form = useForm({
    type_meeting_code: '',
    type_meeting_name: '',
    type_meeting_description: '',
});

const storeTypeMeeting = () => {
    form.post('/type-meetings', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success('Type Meeting created successfully!');
        },
    });
};
</script>

<template>
    <Head title="Create Type Meeting" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="space-y-6 p-6">
            <Card class="border-b shadow-none">
                <CardHeader>
                    <CardTitle>Create Type Meeting</CardTitle>
                </CardHeader>
                <CardContent>
                    <Form
                        @submit.prevent="storeTypeMeeting"
                        class="space-y-6"
                        v-slot="{ errors, processing, recentlySuccessful }"
                    >
                        <div class="grid gap-2">
                            <Label>Type Meeting Code</Label>
                            <Input v-model="form.type_meeting_code" placeholder="Auto Generate" disabled readonly value="Auto Generate" />
                            <InputError
                                :message="form.errors.type_meeting_code"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label>Type Meeting Name</Label>
                            <Input v-model="form.type_meeting_name" placeholder="Type meeting name" />
                            <InputError
                                :message="form.errors.type_meeting_name"
                            />
                        </div>

                        <div class="grid gap-2">
                            <Label>Description</Label>
                            <Input v-model="form.type_meeting_description" placeholder="Type meeting description" />
                            <InputError
                                :message="form.errors.type_meeting_description"
                            />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button
                                type="button"
                                variant="outline"
                                :disabled="form.processing"
                                @click="$inertia.visit('/type-meetings')"
                            >
                                Cancel
                            </Button>

                            <Button type="submit" :disabled="form.processing">
                                <LucideCheckCircle2 class="mr-2 h-4 w-4" />
                                Save
                            </Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-if="form.recentlySuccessful"
                                    class="text-sm text-neutral-600"
                                >
                                    Saved Changes.
                                </p>
                            </Transition>
                        </div>
                    </Form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped></style>
