<script setup lang="ts">
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
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
import { dashboard as cabinetDashboard } from '@/routes/cabinet';
import { index as cabinetDivinationsIndex } from '@/routes/cabinet/divinations';
import { AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Scroll, Sparkles } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<AppPageProps>();

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: cabinetDashboard().url,
    icon: LayoutGrid,
  },
  {
    title: 'Divinations',
    href: cabinetDivinationsIndex().url,
    icon: Sparkles,
  },
];

const footerNavItems: NavItem[] = [
  {
    title: 'Book of Changes',
    href: 'https://en.wikipedia.org/wiki/I_Ching',
    icon: Scroll,
  },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="cabinetDashboard().url">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <div class="px-2 py-2 group-data-[collapsible=icon]:hidden">
        <LanguageSwitcher :current-locale="page.props.locale" />
      </div>
      <NavFooter :items="footerNavItems" />
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
