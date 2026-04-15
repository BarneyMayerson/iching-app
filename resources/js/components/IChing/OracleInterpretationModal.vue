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
}>();

const emit = defineEmits(['close']);

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
          v-else-if="reading.ai_interpretation"
          class="prose max-w-none pb-4 prose-slate dark:prose-invert"
          v-html="renderedMarkdown"
        ></div>
      </div>
    </DialogContent>
  </Dialog>
</template>
