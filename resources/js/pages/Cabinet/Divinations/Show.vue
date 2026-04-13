<script setup lang="ts">
import HexagramSection from '@/components/IChing/HexagramSection.vue';
import LineGuidance from '@/components/IChing/LineGuidance.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import TransformationDivider from '@/components/IChing/TransformationDivider.vue';
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  deleteMethod,
  exportMethod,
  index,
  interpret,
} from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
  ArrowLeft,
  FileDown,
  Lock,
  Share2,
  Sparkles,
  Trash2,
} from 'lucide-vue-next';
import markdownit from 'markdown-it';
import { computed } from 'vue';

const props = defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();

const { __ } = useTranslate();

const breadcrumbs = [
  { title: __('Divinations'), href: index().url },
  { title: `${props.reading.question}`, href: '#' },
];

const handleDelete = () => {
  if (
    confirm(
      __(
        'Are you sure you want to delete this divination? This action cannot be undone!',
      ),
    )
  ) {
    router.delete(deleteMethod(props.reading.uuid).url);
  }
};

const downloadPdf = () => {
  window.location.href = exportMethod(props.reading.uuid).url;
};

const form = useForm();

const generateAiInterpretation = () => {
  form.post(interpret(props.reading).url, {
    preserveScroll: true,
  });
};

const md = markdownit({
  html: false,
  linkify: true,
  typographer: true,
});

const renderedMarkdown = computed(() => {
  return md.render(props.reading.ai_interpretation || '');
});
</script>

<template>
  <Head
    :title="
      __('Hexagram :number - Interpretation').replace(
        ':number',
        reading.hexagram.number.toString(),
      )
    "
  />

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
          {{ __('Back to History') }}
        </Link>

        <div class="flex items-center gap-2">
          <button
            @click="downloadPdf"
            class="flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition-all hover:bg-slate-200 active:scale-95 dark:bg-slate-800 dark:text-slate-200"
          >
            <FileDown class="size-4" />
            {{ __('Export PDF') }}
          </button>
          <div
            class="flex items-center rounded-xl bg-slate-50 p-1 dark:bg-slate-900"
          >
            <button
              class="flex cursor-not-allowed items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-400 opacity-50"
              title="Share link"
            >
              <Share2 class="size-3.5" />
              {{ __('Share') }}
            </button>
            <div class="mx-1 h-4 w-px bg-slate-200 dark:bg-slate-800"></div>
            <button
              class="flex cursor-not-allowed items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-400 opacity-50"
              title="Make private"
            >
              <Lock class="size-3.5" />
              {{ __('Private') }}
            </button>
          </div>

          <button
            @click="handleDelete"
            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:hover:bg-red-950/30"
          >
            <Trash2 class="size-4" />
            <span class="hidden sm:inline">{{ __('Delete') }}</span>
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
            {{
              __(
                'Focus on the changing lines as the key to your inquiry. They represent the path from where you are to where you are going.',
              )
            }}
          </p>
        </div>
      </section>

      <div
        v-else
        class="mt-20 rounded-3xl bg-slate-50 p-12 text-center dark:bg-slate-900/20"
      >
        <p class="font-serif text-xl text-slate-500">
          {{
            __(
              'The Oracle indicates a period of stability. The pattern is firm, suggesting that the current essence of the hexagram is the full answer to your question.',
            )
          }}
        </p>
      </div>
      <div
        v-if="!reading.ai_interpretation"
        class="mt-8 rounded-xl border border-indigo-100 bg-indigo-50 p-6"
      >
        <h3 class="mb-4 text-lg font-medium text-indigo-900">
          {{ __('AI Analysis') }}
        </h3>
        <p class="mb-6 text-sm text-indigo-700">
          {{
            __(
              'Get a deep analysis of your hexagrams based on your specific question.',
            )
          }}
        </p>

        <button
          @click="generateAiInterpretation"
          :disabled="form.processing"
          class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out hover:bg-indigo-700 focus:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none active:bg-indigo-900 disabled:opacity-50"
        >
          <span
            v-if="form.processing"
            class="mr-2 tracking-normal lowercase italic"
          >
            {{ __('Consulting the oracle...') }}
          </span>
          <span v-else>
            {{ __('Generate AI Interpretation') }}
          </span>
        </button>
      </div>

      <div
        v-else
        class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-slate-900/20"
      >
        <h3
          class="border-b pb-2 text-xl font-bold text-slate-900 dark:text-slate-100"
        >
          {{ __('AI Interpretation') }}
        </h3>
        <div
          class="prose max-w-none prose-slate dark:prose-invert"
          v-html="renderedMarkdown"
        ></div>
      </div>
    </div>
  </AppLayout>
</template>
