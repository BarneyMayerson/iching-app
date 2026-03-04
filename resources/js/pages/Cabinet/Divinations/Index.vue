<script setup lang="ts">
import ReadingCard from '@/components/IChing/ReadingCard.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { create } from '@/routes/cabinet/divinations';
import { PaginationMeta, Reading } from '@/types/iching';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

defineProps<{
  readings: {
    data: Reading[];
    links: any;
    meta: PaginationMeta;
  };
}>();
</script>

<template>
  <Head title="My Divinations" />

  <AppLayout>
    <div class="mx-auto flex w-full max-w-7xl flex-col gap-8 p-6 lg:p-8">
      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
      >
        <div>
          <h1
            class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100"
          >
            History of Divinations
          </h1>
          <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            A record of your past consultations with the Book of Changes.
          </p>
        </div>

        <Link
          :href="create().url"
          class="inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-amber-500 hover:shadow-md active:scale-95"
        >
          <Plus class="size-4" />
          New Divination
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
          No divinations found
        </h3>
        <p class="mt-2 text-sm text-slate-500">
          The Book of Changes is waiting for your questions.
        </p>
        <Link
          :href="create().url"
          class="mt-6 text-sm font-bold text-amber-600 hover:text-amber-500"
        >
          Start your first session &rarr;
        </Link>
      </div>

      <template v-else>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <ReadingCard
            v-for="reading in readings.data"
            :key="reading.id"
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
