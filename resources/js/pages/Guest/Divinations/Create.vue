<script setup lang="ts">
import DivinationForm from '@/components/IChing/form/DivinationForm.vue';
import HexagramPreview from '@/components/IChing/form/HexagramPreview.vue';
import { useTranslate } from '@/composables/useTranslate';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { index } from '@/routes/cabinet/divinations';
import { store } from '@/routes/divination';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const { __ } = useTranslate();

const breadcrumbs: BreadcrumbItem[] = [
  { title: __('Divinations'), href: index().url },
  { title: __('New Casting'), href: '#' },
];

const hexagramCoinResults = ref<number[]>([]);
const lineCount = ref(0);

const handleUpdate = (results: number[], count: number) => {
  hexagramCoinResults.value = results;
  lineCount.value = count;
};
</script>

<template>
  <Head :title="__('Consult the Oracle')" />

  <GuestLayout>
    <div
      class="mx-auto grid max-w-5xl flex-1 grid-cols-1 gap-8 p-4 lg:grid-cols-3 lg:p-8"
    >
      <DivinationForm
        :submit-url="store().url"
        :is-guest="true"
        @update="handleUpdate"
      />
      <HexagramPreview
        :hexagram-coin-results="hexagramCoinResults"
        :line-count="lineCount"
      />
    </div>
  </GuestLayout>
</template>
