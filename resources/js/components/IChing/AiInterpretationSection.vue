<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import { interpret } from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { useForm } from '@inertiajs/vue3';
import MarkdownIt from 'markdown-it';
import { computed } from 'vue';

const props = defineProps<{
  reading: Reading;
}>();

const { __ } = useTranslate();
const form = useForm();

const generateAiInterpretation = () => {
  form.post(interpret(props.reading).url, {
    preserveScroll: true,
  });
};

const md = MarkdownIt({
  html: true,
  linkify: true,
  typographer: true,
});

const renderedMarkdown = computed(() => {
  return md.render(props.reading.ai_interpretation || '');
});
</script>

<template>
  <div
    v-if="!reading.ai_interpretation"
    class="prose mt-8 rounded-xl border border-slate-200 bg-slate-50 p-6 prose-invert dark:border-slate-800 dark:bg-slate-900/20"
  >
    <h3 class="mb-4 text-lg font-medium text-slate-900 dark:text-slate-100">
      {{ __('AI Analysis') }}
    </h3>
    <p class="mb-6 text-sm text-slate-900 dark:text-slate-100">
      {{
        __(
          'Get a deep analysis of your hexagram based on your specific question.',
        )
      }}
    </p>

    <button
      @click="generateAiInterpretation"
      :disabled="form.processing"
      class="inline-flex items-center rounded-md border border-transparent bg-slate-600 px-4 py-2 text-xs font-semibold transition duration-150 ease-in-out hover:bg-slate-700 focus:bg-slate-700 focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:outline-none active:bg-slate-900 disabled:opacity-50"
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

    <div
      v-if="form.processing"
      class="mt-8 animate-pulse rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-6"
    >
      <div class="flex items-center space-x-4">
        <div class="flex-1 space-y-4 py-1">
          <div class="h-4 w-3/4 rounded bg-slate-200"></div>
          <div class="space-y-2">
            <div class="h-4 rounded bg-slate-200"></div>
            <div class="h-4 w-5/6 rounded bg-slate-200"></div>
          </div>
          <div class="h-4 w-1/2 rounded bg-slate-200"></div>
        </div>
      </div>
      <p class="mt-4 text-center text-sm text-slate-400 italic">
        {{ __('The Oracle is analyzing your hexagrams...') }}
      </p>
    </div>
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
</template>
