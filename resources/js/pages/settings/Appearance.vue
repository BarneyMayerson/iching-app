<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { AppPageProps, type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/appearance';

const page = usePage<AppPageProps>();
const __ = (key: string): string => page.props.translations[key] || key;

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: __('Appearance settings'),
    href: edit().url,
  },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head :title="__('Appearance settings')" />

    <h1 class="sr-only">Appearance Settings</h1>

    <SettingsLayout>
      <div class="space-y-6">
        <HeadingSmall
          :title="__('Appearance settings')"
          :description="__('Update your account\'s appearance settings')"
        />
        <AppearanceTabs />
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
