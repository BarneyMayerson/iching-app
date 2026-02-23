<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/dashboard/divinations';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
  reading: any;
  hexagram: any;
  secondary_hexagram?: any;
  changing_lines: number[];
}>();

const breadcrumbs = [
  { title: 'Divinations', href: index().url },
  { title: `#${props.reading.id}`, href: '#' },
];
</script>

<template>
  <Head :title="'Hexagram ' + hexagram.number" />
  <AppLayout :breadcrumbs>
    <div class="flex flex-1 flex-col items-center p-8">
      <div class="mb-8 flex w-full max-w-5xl justify-start">
        <Link
          :href="index().url"
          class="flex items-center gap-2 text-sm font-medium text-slate-400 transition-colors hover:text-amber-500"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="size-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 19l-7-7m0 0l7-7m-7 7h18"
            />
          </svg>
          Back to History
        </Link>
      </div>

      <div class="grid w-full max-w-5xl gap-8 lg:grid-cols-2">
        <div class="space-y-6">
          <HexagramView
            :coin-results="reading.coin_results"
            :hexagram="hexagram"
          />
          <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
            <h3 class="mb-2 text-lg font-bold text-amber-500">Judgment</h3>
            <p class="leading-relaxed text-slate-300">
              {{ hexagram.judgment || 'Logic for description coming soon...' }}
            </p>
          </div>
        </div>

        <div
          v-if="secondary_hexagram"
          class="animate-in space-y-6 duration-700 slide-in-from-right"
        >
          <!-- <div
            class="rounded-lg border border-amber-500/20 bg-amber-500/10 py-2 text-center font-serif text-sm text-amber-500 italic"
          >
            Changing lines: {{ changing_lines.join(', ') }}
          </div> -->
          <HexagramView
            :coin-results="
              reading.coin_results.map((v: number) =>
                v === 6 ? 7 : v === 9 ? 8 : v,
              )
            "
            :hexagram="secondary_hexagram"
          />
          <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
            <h3 class="mb-2 text-lg font-bold text-amber-600">
              Transformation
            </h3>
            <p class="leading-relaxed text-slate-300">
              {{ secondary_hexagram.judgment }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
