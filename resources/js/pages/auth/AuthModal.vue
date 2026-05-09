<script setup lang="ts">
import LogoMotion from '@/components/logos/LogoMotion.vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

interface Props {
  open: boolean;
  title: string;
  description?: string;
}

defineProps<Props>();
const emit = defineEmits(['update:open']);
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent
      class="rounded-3xl border-none bg-white shadow-2xl sm:max-w-md dark:bg-slate-900"
    >
      <DialogHeader class="flex flex-col items-center justify-center pt-4">
        <LogoMotion class="mb-4 size-20" />
        <DialogTitle
          class="font-serif text-3xl font-bold text-slate-900 dark:text-white"
        >
          {{ title }}
        </DialogTitle>
        <DialogDescription
          v-if="description"
          class="mt-2 text-center text-sm text-slate-500"
        >
          {{ description }}
        </DialogDescription>
      </DialogHeader>

      <div class="py-2">
        <slot name="content" />
      </div>

      <div
        class="flex flex-col items-center border-t border-slate-200 dark:border-slate-700"
      >
        <slot name="footer" />
      </div>
    </DialogContent>
  </Dialog>
</template>
