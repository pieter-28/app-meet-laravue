<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

import {
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

import { useActiveUrl } from '@/composables/useActiveUrl';
import { type NavItem } from '@/types';
import { ChevronDown, ChevronRight, Layers } from 'lucide-vue-next';

const props = defineProps<{
    items: NavItem[];
}>();

const { urlIsActive } = useActiveUrl();
const open = ref(false);

watchEffect(() => {
    open.value = props.items.some((item) => urlIsActive(item.href));
});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarMenu>
            <!-- MASTER HEADER (style sama kayak Dashboard) -->
            <SidebarMenuItem>
                <SidebarMenuButton
                    @click="open = !open"
                    :is-active="open"
                    class="justify-between"
                >
                    <div class="flex items-center gap-2">
                        <Layers class="h-4 w-4" />
                        <span>Master</span>
                    </div>

                    <component
                        :is="open ? ChevronDown : ChevronRight"
                        class="h-4 w-4"
                    />
                </SidebarMenuButton>
            </SidebarMenuItem>

            <!-- DROPDOWN ITEMS -->
            <SidebarMenuItem
                v-for="item in items"
                :key="item.title"
                v-show="open"
                class="pl-6 transition-all duration-200"
            >
                <SidebarMenuButton as-child :is-active="urlIsActive(item.href)">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
