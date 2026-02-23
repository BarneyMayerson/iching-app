<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
  create as dashboardDivinationsCreate,
  index as dashboardDivinationsIndex,
  show as dashboardDivinationsShow,
} from '@/routes/dashboard/divinations';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Divinations',
    href: dashboardDivinationsIndex().url,
  },
];

defineProps<{
  divinations: any[];
}>();
</script>

<template>
  <Head title="My Divinations" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">History of Divinations</h1>

        <Link
          :href="dashboardDivinationsCreate().url"
          class="flex items-center gap-2 rounded-md bg-amber-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-amber-500"
        >
          <Plus class="size-4" />
          New Divination
        </Link>
      </div>

      <div
        class="relative flex min-h-100 flex-1 items-center justify-center rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
      >
        <div v-if="divinations.length === 0" class="text-center">
          <p class="text-muted-foreground">
            You haven't performed any divinations yet.
          </p>
        </div>

        <div v-else class="absolute inset-0 w-full p-4">
          <table class="min-w-full divide-y-2">
            <thead>
              <tr>
                <th
                  scope="col"
                  class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold sm:pl-0"
                >
                  Date
                </th>
                <th
                  scope="col"
                  class="hidden px-3 py-3.5 text-left text-sm font-semibold sm:table-cell"
                >
                  Question
                </th>
                <th
                  scope="col"
                  class="hidden px-3 py-3.5 text-left text-sm font-semibold lg:table-cell"
                >
                  Binary
                </th>
                <th scope="col" class="py-3.5 pr-4 pl-3 sm:pr-0">
                  <span class="sr-only">View</span>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="reading in divinations" :key="reading.id">
                <td
                  class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap sm:pl-0"
                >
                  {{ reading.created_at }}
                </td>
                <td
                  class="hidden px-3 py-4 text-sm whitespace-nowrap sm:table-cell"
                >
                  {{ reading.question }}
                </td>
                <td
                  class="hidden px-3 py-4 font-mono text-sm whitespace-nowrap lg:table-cell"
                >
                  {{ reading.binary }}
                </td>
                <td
                  class="py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-0"
                >
                  <Link
                    :href="dashboardDivinationsShow(reading.id).url"
                    class="rounded-md bg-amber-600 px-4 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-amber-500"
                  >
                    View
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
