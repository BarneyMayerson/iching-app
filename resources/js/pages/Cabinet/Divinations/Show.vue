<script setup lang="ts">
import HexagramSection from '@/components/IChing/HexagramSection.vue';
import LineGuidance from '@/components/IChing/LineGuidance.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import TransformationDivider from '@/components/IChing/TransformationDivider.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  deleteMethod,
  exportMethod,
  index,
} from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Head, Link, router } from '@inertiajs/vue3';
import {
  ArrowLeft,
  FileDown,
  Lock,
  Share2,
  Sparkles,
  Trash2,
} from 'lucide-vue-next';

const props = defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();

const breadcrumbs = [
  { title: 'Divinations', href: index().url },
  { title: `${props.reading.question}`, href: '#' },
];

const handleDelete = () => {
  if (
    confirm(
      'Вы уверены, что хотите удалить это гадание? Это действие необратимо.',
    )
  ) {
    router.delete(deleteMethod(props.reading.uuid).url);
  }
};

const downloadPdf = () => {
  window.location.href = exportMethod(props.reading.uuid).url;
};
</script>

<template>
  <Head :title="'Hexagram ' + reading.hexagram.number + ' - Interpretation'" />

  <AppLayout :breadcrumbs>
    <div class="mx-auto max-w-4xl flex-1 p-6 lg:p-12">
      <div
        class="mb-8 flex items-center justify-between border-b border-slate-100 pb-4 dark:border-slate-800"
      >
        <Link
          :href="index().url"
          class="group inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-500"
        >
          <ArrowLeft
            class="size-4 transition-transform group-hover:-translate-x-1"
          />
          Back to History
        </Link>

        <div class="flex items-center gap-2">
          <button
            @click="downloadPdf"
            class="flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition-all hover:bg-slate-200 active:scale-95 dark:bg-slate-800 dark:text-slate-200"
          >
            <FileDown class="size-4" />
            Export PDF
          </button>
          <div
            class="flex items-center rounded-xl bg-slate-50 p-1 dark:bg-slate-900"
          >
            <button
              class="flex cursor-not-allowed items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-400 opacity-50"
              title="Share link"
            >
              <Share2 class="size-3.5" />
              Share
            </button>
            <div class="mx-1 h-4 w-px bg-slate-200 dark:bg-slate-800"></div>
            <button
              class="flex cursor-not-allowed items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-400 opacity-50"
              title="Make private"
            >
              <Lock class="size-3.5" />
              Private
            </button>
          </div>

          <button
            @click="handleDelete"
            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-950/30"
          >
            <Trash2 class="size-4" />
            <span class="hidden sm:inline">Delete</span>
          </button>
        </div>
      </div>

      <ReadingHeader
        class="mb-16 text-center"
        :question="reading.question"
        :date="reading.date"
        :time="reading.time"
        :relative_date="reading.relative_date"
      />

      <section class="space-y-10">
        <div
          class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
        >
          <HexagramSection
            :hexagram="reading.hexagram"
            :coin_results="reading.coin_results"
          />
        </div>

        <LineGuidance
          :lines="reading.hexagram.lines"
          :changing_lines="changing_lines"
        />
      </section>

      <div
        v-if="reading.secondary_hexagram"
        class="my-20 flex flex-col items-center"
      >
        <TransformationDivider />
      </div>

      <section
        v-if="reading.secondary_hexagram"
        class="animate-in space-y-8 duration-1000 slide-in-from-bottom-10 fade-in"
      >
        <div
          class="rounded-3xl border border-dashed border-slate-300 bg-slate-50/50 p-8 dark:border-slate-700 dark:bg-slate-900/30"
        >
          <HexagramSection
            :hexagram="reading.secondary_hexagram"
            :coin_results="
              reading.coin_results.map((v: number) =>
                v === 6 ? 7 : v === 9 ? 8 : v,
              )
            "
            is_secondary
          />
        </div>

        <div
          class="rounded-2xl bg-amber-500 p-8 text-center text-white shadow-xl shadow-amber-900/20"
        >
          <Sparkles class="mx-auto mb-4 size-8" />
          <p class="font-serif text-xl italic">
            Focus on the changing lines as the key to your inquiry. They
            represent the path from where you are to where you are going.
          </p>
        </div>
      </section>

      <div
        v-else
        class="mt-20 rounded-3xl bg-slate-50 p-12 text-center dark:bg-slate-900/20"
      >
        <p class="font-serif text-xl text-slate-500">
          The Oracle indicates a period of stability. The pattern is firm,
          suggesting that the current essence of the hexagram is the full answer
          to your question.
        </p>
      </div>
    </div>
  </AppLayout>
</template>
