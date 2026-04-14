<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import {
  deleteMethod,
  exportMethod,
  index,
} from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Link, router } from '@inertiajs/vue3';
import { ArrowLeft, FileDown, Lock, Share2, Trash2 } from 'lucide-vue-next';

const props = defineProps<{
  reading: Reading;
}>();

const { __ } = useTranslate();

const downloadPdf = () => {
  window.location.href = exportMethod(props.reading.uuid).url;
};

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
</script>

<template>
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
</template>
