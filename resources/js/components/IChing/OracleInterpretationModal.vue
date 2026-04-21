<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Reading } from '@/types/iching';
import { Sparkles } from 'lucide-vue-next';
import MarkdownIt from 'markdown-it';
import { computed } from 'vue';

const props = defineProps<{
  show: boolean;
  reading: Reading;
  isProcessing: boolean;
  showTimeoutWarning: boolean;
}>();

const emit = defineEmits(['close', 'cancel']);

const md = new MarkdownIt({
  html: false,
  linkify: true,
  typographer: true,
});
const renderedMarkdown = computed(() =>
  md.render(props.reading.ai_interpretation || ''),
);
</script>

<template>
  <Dialog :open="show" @update:open="(val) => !val && emit('close')">
    <DialogContent class="sm:max-w-2xl">
      <DialogHeader>
        <DialogTitle class="flex items-center gap-2 text-2xl">
          <Sparkles class="size-5 text-indigo-400 dark:text-indigo-600" />
          {{ __('Oracle Interpretation') }}
        </DialogTitle>
        <DialogDescription>
          {{
            __(
              'Detailed analysis of the connections between your question and the I Ching symbols.',
            )
          }}
        </DialogDescription>
      </DialogHeader>

      <div class="max-h-[65vh] overflow-y-auto pr-2">
        <div v-if="isProcessing" class="space-y-4 py-8">
          <div class="animate-pulse space-y-3">
            <div class="h-4 w-3/4 rounded bg-muted"></div>
            <div class="h-4 w-full rounded bg-muted"></div>
            <div class="h-4 w-5/6 rounded bg-muted"></div>
          </div>
          <p
            class="animate-pulse text-center text-sm text-muted-foreground italic"
          >
            {{ __('The Oracle is weaving the interpretation...') }}
          </p>
        </div>

        <div
          v-if="showTimeoutWarning"
          class="mt-6 animate-in text-center duration-300 fade-in zoom-in"
        >
          <p class="mb-4 text-xs text-red-400">
            {{ __('The Oracle is taking longer than usual...') }}
          </p>
          <button
            @click="$emit('cancel')"
            class="text-sm font-medium text-primary hover:underline"
          >
            {{ __('Cancel and try later') }}
          </button>
        </div>

        <div
          v-if="reading.interpretation_status === 'Failed'"
          class="p-4 text-red-500"
        >
          {{
            __('The Oracle is temporarily unavailable. Please try again later.')
          }}
        </div>

        <div v-else-if="reading.ai_interpretation">
          <div
            class="prose max-w-none pb-4 prose-slate dark:prose-invert"
            v-html="renderedMarkdown"
          ></div>
          <div class="mt-6 text-center">
            <button
              @click="$emit('close')"
              class="text-sm font-medium text-primary hover:underline"
            >
              {{ __('Close') }}
            </button>
          </div>
        </div>
      </div>
    </DialogContent>
  </Dialog>
</template>
