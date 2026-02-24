<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import InterpretationBlock from '@/components/IChing/InterpretationBlock.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/cabinet/divinations';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

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
          class="group flex items-center gap-2 text-sm font-medium text-slate-600 transition-colors hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-500"
        >
          <ArrowLeft
            class="size-4 transition-transform group-hover:-translate-x-1"
          />
          Back to History
        </Link>
      </div>

      <div class="grid w-full max-w-5xl gap-8 lg:grid-cols-2">
        <div class="space-y-6">
          <HexagramView
            :coin-results="reading.coin_results"
            :hexagram="hexagram"
          />
          <InterpretationBlock title="Judgment" :text="hexagram.judgment" />
        </div>

        <div
          v-if="secondary_hexagram"
          class="animate-in space-y-6 duration-700 slide-in-from-right"
        >
          <HexagramView
            :coin-results="
              reading.coin_results.map((v: number) =>
                v === 6 ? 7 : v === 9 ? 8 : v,
              )
            "
            :hexagram="secondary_hexagram"
          />
          <InterpretationBlock
            title="Transformation"
            :text="secondary_hexagram.judgment"
            variant="secondary"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
