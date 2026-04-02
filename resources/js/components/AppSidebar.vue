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
import { useTranslate } from '@/composables/useTranslate';
import { dashboard as cabinetDashboard } from '@/routes/cabinet';
import { index as cabinetDivinationsIndex } from '@/routes/cabinet/divinations';
import { dashboard as admDashboard } from '@/routes/filament/adm/pages/index';
import { AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutDashboard, LayoutGrid, Scroll, Sparkles } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const { __, locale } = useTranslate();

const page = usePage<AppPageProps>();

const mainNavItems = computed<NavItem[]>(() => [
  {
    title: __('Overview'),
    href: cabinetDashboard().url,
    icon: LayoutGrid,
  },
  {
    title: __('Divinations'),
    href: cabinetDivinationsIndex().url,
    icon: Sparkles,
  },
]);

const footerNavItems = computed<NavItem[]>(() => {
  const items: NavItem[] = [
    {
      title: __('Book of Changes'),
      href:
        locale.value === 'ru'
          ? 'https://ru.wikipedia.org/wiki/Книга_Перемен'
          : 'https://en.wikipedia.org/wiki/I_Ching',
      icon: Scroll,
    },
  ];

  if (page.props.hasAdminAccess) {
    items.unshift({
      title: __('Administration Panel'),
      href: admDashboard({ query: {} }).url,
      target: '_self',
      icon: LayoutDashboard,
    });
  }

  return items;
});
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
        <LanguageSwitcher :current-locale="locale" />
      </div>
      <NavFooter :items="footerNavItems" />
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
