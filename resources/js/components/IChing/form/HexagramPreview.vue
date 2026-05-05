<script setup lang="ts">
import HexagramLine from '@/components/IChing/HexagramLine.vue';

defineProps<{
  hexagramCoinResults: number[];
  lineCount: number;
}>();
</script>

<template>
  <div
    class="flex h-fit flex-col items-center rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:sticky lg:top-8 dark:border-slate-800 dark:bg-slate-900/50"
  >
    <h3
      class="mb-10 text-xs font-bold tracking-[0.2em] text-slate-400 uppercase"
    >
      {{ __('Emerging Hexagram') }}
    </h3>

    <div class="flex w-full max-w-45 flex-col-reverse gap-4">
      <HexagramLine
        v-for="(val, idx) in hexagramCoinResults"
        :key="idx"
        :value="val"
        :position="idx + 1"
        class="animate-in duration-700 fade-in slide-in-from-bottom-4"
      />

      <div
        v-for="i in 6 - hexagramCoinResults.length"
        :key="'placeholder-' + i"
        class="h-8 w-full border-b border-slate-100 dark:border-slate-800/30"
      ></div>
    </div>

    <div class="mt-12 text-center">
      <div class="text-4xl font-light text-slate-300 dark:text-slate-700">
        {{ lineCount }}<span class="text-xl">/6</span>
      </div>
      <p
        class="mt-2 text-[10px] font-bold tracking-widest text-slate-400 uppercase dark:text-slate-500"
      >
        {{ __('Lines Completed') }}
      </p>
    </div>
  </div>
</template>
