<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    Airplay,
    BookText,
    BoxIcon,
    Building2,
    GroupIcon,
    History,
    Layers2,
    LayoutGrid,
    Presentation,
    Shield,
    ShieldCheck,
    User,
    User2,
} from 'lucide-vue-next';

import AppLogo from '@/components/AppLogo.vue';
import NavConfiguration from '@/components/NavConfiguration.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavManagementMeeting from '@/components/NavManagementMeeting.vue';
import NavMaster from '@/components/NavMaster.vue';
import NavReport from '@/components/NavReport.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

const masterNavItems: NavItem[] = [
    {
        title: 'Tipe Meeting',
        href: '/type-meetings',
        icon: BoxIcon,
    },
    {
        title: 'Kategori Meeting',
        href: '/categories',
        icon: Airplay,
    },
    {
        title: 'Topic Meeting',
        href: '/topic',
        icon: BookText,
    },
    {
        title: 'Place Meeting',
        href: '/place',
        icon: Building2,
    },
    {
        title: 'Grup Activity',
        href: '/grup-activity',
        icon: GroupIcon,
    },
    {
        title: 'Activity Meeting',
        href: '/activity',
        icon: Activity,
    },
    {
        title: 'Pic Meeting',
        href: '/pic',
        icon: User2,
    },
];

const managementMeetingNavItems: NavItem[] = [
    {
        title: 'Master Meeting',
        href: '#',
        icon: Presentation,
    },
    {
        title: 'Implementation Meeting',
        href: '#',
        icon: Layers2,
    },
    {
        title: 'History Meeting',
        href: '#',
        icon: History,
    },
];

const configNavItems: NavItem[] = [
    {
        title: 'Role',
        href: '#',
        icon: Shield,
    },
    {
        title: 'Permission',
        href: '#',
        icon: ShieldCheck,
    },
    {
        title: 'User',
        href: '#',
        icon: User,
    },
];

const reportNavItems: NavItem[] = [
    {
        title: 'Mom Meeting',
        href: '#',
        icon: Shield,
    },
    {
        title: 'Performance Employee',
        href: '#',
        icon: ShieldCheck,
    },
    {
        title: 'Performance Project',
        href: '#',
        icon: User,
    },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavMaster :items="masterNavItems" />
            <NavManagementMeeting :items="managementMeetingNavItems" />
            <NavReport :items="reportNavItems" />
            <NavConfiguration :items="configNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
