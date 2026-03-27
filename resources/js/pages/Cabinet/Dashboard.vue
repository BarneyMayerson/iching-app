<script setup lang="ts">
import YinYangBalance from '@/components/UserDashboard/YinYangBalance.vue';
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard as cabinetDashboard } from '@/routes/cabinet';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Activity, BarChart3 } from 'lucide-vue-next';
import { computed } from 'vue';

const { __ } = useTranslate();

defineProps<{
  stats: {
    monthly: Array<{ month: number; count: number }>;
    balance: { yang: number; yin: number; total: number };
    top_hexagram: { count: number; hexagram: any } | null;
    total_readings: number;
  };
  filters: {
    year: number;
    available_years: number[];
  };
}>();

const changeYear = (year: number) => {
  router.get(
    cabinetDashboard().url,
    { year },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['stats', 'filters'],
    },
  );
};

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
  {
    title: __('Dashboard'),
    href: cabinetDashboard().url,
  },
]);
</script>

<template>
  <Head :title="__('Dashboard')" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-1 flex-col gap-6 p-6">
      <div class="grid gap-6 md:grid-cols-3">
        <div
          class="rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
        >
          <div class="flex items-center gap-4 p-6">
            <div
              class="flex size-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 dark:bg-amber-900/20"
            >
              <Activity class="size-6" />
            </div>
            <div>
              <p class="text-sm font-medium text-slate-500">
                {{ __('Total Consultations') }}
              </p>
              <h4 class="text-3xl font-bold">{{ stats.total_readings }}</h4>
            </div>
          </div>
        </div>

        <div
          class="rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
        >
          <YinYangBalance v-bind="stats.balance" />
        </div>

        <div
          class="rounded-3xl border border-slate-100 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
        >
          <div v-if="stats.top_hexagram" class="flex items-center gap-4 p-6">
            <div
              class="flex size-14 items-center justify-center rounded-2xl bg-slate-900 text-3xl text-white shadow-lg dark:bg-slate-800"
            >
              {{ stats.top_hexagram.hexagram.character }}
            </div>
            <div class="flex-1 overflow-hidden">
              <p
                class="text-[10px] font-bold tracking-widest text-amber-600 uppercase"
              >
                {{ __('Personal Totem') }}
              </p>
              <h4 class="truncate font-serif text-lg leading-tight font-bold">
                {{ stats.top_hexagram.hexagram.number }}.
                {{ stats.top_hexagram.hexagram.names[0] }}
              </h4>
              <p class="text-xs text-slate-400">
                {{
                  __('Appeared :count times').replace(
                    ':count',
                    stats.top_hexagram.count.toString(),
                  )
                }}
              </p>
            </div>
          </div>
          <div
            v-else
            class="flex h-full items-center justify-center p-6 text-center text-sm text-slate-400 italic"
          >
            {{ __('Consult the Oracle to reveal your totem...') }}
          </div>
        </div>
      </div>

      <div
        class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
      >
        <div class="mb-10 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <BarChart3 class="size-5 text-slate-400" />
            <h3
              class="font-serif text-xl font-bold text-slate-800 italic dark:text-slate-100"
            >
              {{ __('Inquiry Timeline') }}
            </h3>
          </div>

          <div class="flex items-center gap-2">
            <label
              class="text-[10px] font-black tracking-widest text-slate-400 uppercase"
            >
              {{ __('Year:') }}
            </label>
            <select
              :value="filters.year"
              @change="
                changeYear(Number(($event.target as HTMLSelectElement).value))
              "
              class="cursor-pointer rounded-lg border-slate-200 bg-white py-1 pr-8 pl-3 text-sm font-bold shadow-sm focus:border-amber-500 focus:ring-amber-500 dark:border-slate-700 dark:bg-slate-800"
            >
              <option v-for="y in filters.available_years" :key="y" :value="y">
                {{ y }}
              </option>
            </select>
          </div>
        </div>

        <div
          class="flex h-64 items-end justify-between gap-2 border-b border-slate-100 pb-2 dark:border-slate-800"
        >
          <div
            v-for="data in stats.monthly"
            :key="data.month"
            class="group relative flex h-full flex-1 flex-col items-center justify-end"
          >
            <div
              class="absolute -top-10 left-1/2 z-10 -translate-x-1/2 rounded bg-slate-900 px-2 py-1 text-[10px] text-white opacity-0 transition-opacity group-hover:opacity-100"
            >
              {{ data.count }} {{ __('readings') }}
            </div>

            <div
              class="w-full rounded-t-lg bg-amber-400/20 transition-all duration-500 hover:bg-amber-400 dark:bg-amber-500/10 dark:hover:bg-amber-500"
              :style="{
                height:
                  data.count > 0
                    ? Math.max(
                        (data.count /
                          (Math.max(...stats.monthly.map((m) => m.count)) ||
                            1)) *
                          100,
                        5,
                      ) + '%'
                    : '0%',
              }"
            ></div>

            <span
              class="absolute -bottom-8 text-[10px] font-bold text-slate-400 uppercase"
            >
              {{
                new Date(0, data.month - 1).toLocaleString($page.props.locale, {
                  month: 'short',
                })
              }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
