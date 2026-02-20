<script setup lang="ts">
import HexagramLine from '@/components/IChing/HexagramLine.vue';
import { computed } from 'vue';

interface Hexagram {
  number: number;
  name: string;
  chinese_name: string;
  pinyin_name: string;
  character: string;
}

interface Props {
  coinResults: number[];
  hexagram: Hexagram;
}

const props = defineProps<Props>();

const reversedCoinResults = computed(() => [...props.coinResults].reverse());
</script>

<template>
  <div
    class="flex flex-col items-center rounded-2xl border border-slate-800 bg-slate-900 p-8 shadow-xl"
  >
    <div class="mb-8 text-center">
      <div
        class="mb-1 text-sm font-medium tracking-widest text-amber-500 uppercase"
      >
        Hexagram {{ hexagram.number }}
      </div>
      <h2 class="text-3xl font-bold text-slate-100">
        {{ hexagram.name }}
      </h2>
      <div class="mt-2 text-lg text-slate-400">
        <span class="font-serif">{{ hexagram.chinese_name }}</span>
        <span class="mx-2 text-slate-600">|</span>
        <span class="italic">{{ hexagram.pinyin_name }}</span>
      </div>
    </div>

    <div class="relative w-full max-w-60">
      <div class="flex flex-col">
        <HexagramLine
          v-for="(result, index) in reversedCoinResults"
          :key="index"
          :value="result"
        />
      </div>

      <div
        class="pointer-events-none absolute -inset-x-12 -top-4 -bottom-4 flex items-center justify-center text-[12rem] text-white opacity-[0.01]"
      >
        {{ hexagram.character }}
      </div>
    </div>
  </div>
</template>
