<script setup lang="ts">
import ReadingCard from '@/components/IChing/ReadingCard.vue';
import Pagination from '@/components/Pagination.vue';
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/cabinet/divinations';
import { BreadcrumbItem } from '@/types';
import { PaginationMeta, Reading } from '@/types/iching';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { Inbox, Plus, Search, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
  readings: {
    data: Reading[];
    links: any;
    meta: PaginationMeta;
  };
  filters: {
    search?: string;
    hexagram?: string;
  };
  hexagramList: Array<{
    value: number;
    label: string;
  }>;
}>();

const { __ } = useTranslate();

const search = ref(props.filters.search || '');
const hexagram = ref(props.filters.hexagram || '');
const isSearching = computed(() => search.value || hexagram.value);

const updateFilters = debounce(() => {
  router.get(
    index().url,
    {
      search: search.value,
      hexagram: hexagram.value,
    },
    { preserveState: true, replace: true },
  );
}, 300);

watch([search, hexagram], () => {
  updateFilters();
});

const clearSearch = () => {
  search.value = '';
};

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
  {
    title: __('My Divinations'),
    href: index().url,
  },
]);
</script>

<template>
  <Head :title="__('My Divinations')" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div
      class="mx-auto mt-8 flex max-w-7xl flex-col gap-4 px-6 sm:flex-row sm:items-center lg:px-8"
    >
      <div class="relative flex-1">
        <div
          class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400"
        >
          <Search class="size-4" />
        </div>
        <input
          id="question"
          v-model="search"
          type="text"
          :placeholder="__('Search by question...')"
          class="w-full rounded-2xl border-slate-200 bg-white py-2 pr-10 pl-10 text-sm dark:border-slate-800 dark:bg-slate-900"
        />
        <button
          v-if="search"
          @click="clearSearch"
          class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600"
        >
          <X class="size-4" />
        </button>
      </div>

      <div class="relative w-full sm:w-48">
        <select
          id="hexagram-list"
          v-model="hexagram"
          class="w-full appearance-none rounded-2xl border-slate-200 bg-white py-2 pr-10 pl-4 font-serif text-sm text-slate-400 dark:border-slate-800 dark:bg-slate-900"
        >
          <option value="" class="py-2">{{ __('All Hexagrams') }}</option>
          <option
            v-for="item in hexagramList"
            :key="item.value"
            :value="item.value"
            class="py-2 text-slate-900 dark:text-slate-100"
          >
            {{ item.label }}
          </option>
        </select>
      </div>
    </div>
    <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 p-6 lg:p-8">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
      >
        <div>
          <h1
            class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100"
          >
            {{ __('History of Divinations') }}
          </h1>
          <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{
              __(
                'A record of your past consultations with the Book of Changes.',
              )
            }}
          </p>
        </div>

        <Link
          :href="create().url"
          class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-amber-500 hover:shadow-md active:scale-95"
        >
          <Plus class="size-4" />
          {{ __('New Divination') }}
        </Link>
      </div>

      <div
        v-if="readings.data.length === 0"
        class="flex min-h-100 flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 p-12 text-center dark:border-slate-800"
      >
        <div class="rounded-full bg-slate-50 p-4 dark:bg-slate-900">
          <Inbox class="size-8 text-slate-400" />
        </div>
        <h3 class="mt-4 text-lg font-medium text-slate-900 dark:text-white">
          {{ __('No divinations found') }}
        </h3>
        <div v-if="!isSearching">
          <p class="mt-2 text-sm text-slate-500">
            {{ __('The Book of Changes is waiting for your questions.') }}
          </p>
          <Link
            :href="create().url"
            class="mt-6 block text-sm font-bold text-amber-600 hover:text-amber-500"
          >
            {{ __('Start your first session') }} &rarr;
          </Link>
        </div>
      </div>

      <template v-else>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <ReadingCard
            v-for="reading in readings.data"
            :key="reading.uuid"
            :reading="reading"
          />
        </div>

        <div
          class="flex items-center justify-center border-t border-slate-100 pt-6 lg:pt-8 dark:border-slate-800"
        >
          <Pagination :links="readings.meta.links" />
        </div>
      </template>
    </div>
  </AppLayout>
</template>
