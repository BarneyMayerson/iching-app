<script setup lang="ts">
import { Trigram } from '@/types/iching';

defineProps<{
  trigram: Trigram;
  label: string;
}>();
</script>

<template>
  <div
    class="group flex flex-1 items-center gap-4 rounded-2xl border border-slate-50 bg-slate-50/30 p-4 transition-all hover:border-amber-200 hover:bg-amber-50/30 dark:border-slate-800/50 dark:bg-slate-800/20 dark:hover:border-amber-900/50 dark:hover:bg-amber-900/10"
  >
    <div
      class="flex items-center justify-center rounded-xl bg-white text-3xl text-amber-600 shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
    >
      {{ trigram.symbol }}
    </div>
    <div class="flex flex-col self-stretch">
      <p class="text-[9px] font-black tracking-widest text-slate-400 uppercase">
        {{ label }}
      </p>
      <div class="flex flex-wrap items-baseline gap-x-2">
        <span
          class="font-serif text-lg font-bold text-slate-700 dark:text-slate-200"
        >
          {{ trigram.elements[0] }}
        </span>
        <span
          v-if="trigram.elements[1]"
          class="font-serif text-lg font-bold text-slate-500 dark:text-slate-400"
        >
          {{ trigram.elements[1] }}
        </span>
      </div>
      <p class="mt-2 text-sm text-slate-500 italic dark:text-slate-400">
        {{ trigram.attribute }}
      </p>
    </div>
  </div>
</template>
