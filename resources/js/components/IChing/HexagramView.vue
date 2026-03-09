<script setup lang="ts">
import HexagramLine from '@/components/IChing/HexagramLine.vue';
import { Hexagram } from '@/types/iching';
import { computed } from 'vue';

const props = defineProps<{
  coinResults: number[];
  hexagram: Hexagram;
}>();

const reversedCoinResults = computed(() => [...props.coinResults].reverse());
</script>

<template>
  <div
    class="flex flex-col items-center rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-colors duration-500 dark:border-slate-800 dark:bg-slate-900"
  >
    <div class="mb-8 text-center">
      <div
        class="mb-1 text-xs font-bold tracking-[0.4em] text-amber-600 uppercase dark:text-amber-500"
      >
        Hexagram {{ hexagram.number }}
      </div>
      <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-50">
        {{ hexagram.names[0] }}
      </h2>
      <div class="mt-2 text-lg text-slate-400">
        <span class="font-serif">{{ hexagram.origins.chinese }}</span>
        <span class="mx-2 text-slate-300 dark:text-slate-700">|</span>
        <span class="italic">{{ hexagram.origins.pinyin }}</span>
      </div>
    </div>

    <div class="relative w-full max-w-60">
      <div class="flex flex-col">
        <HexagramLine
          v-for="(result, index) in reversedCoinResults"
          :key="6 - index"
          :position="6 - index"
          :value="result"
          class="animate-reveal"
          :style="{
            animationDelay: `${(5 - index) * 200}ms`,
            animationFillMode: 'backwards',
          }"
        />
      </div>

      <div
        class="pointer-events-none absolute -inset-x-12 -top-4 -bottom-4 flex items-center justify-center text-[12rem] text-slate-900 opacity-[0.03] transition-colors duration-500 dark:text-white dark:opacity-[0.02]"
      >
        {{ hexagram.character }}
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes revealLine {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-reveal {
  animation-name: revealLine;
  animation-duration: 600ms;
  animation-timing-function: ease-out;
}
</style>
