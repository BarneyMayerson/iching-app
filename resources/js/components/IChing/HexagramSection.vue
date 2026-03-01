<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import InterpretationBlock from '@/components/IChing/InterpretationBlock.vue';
import { Hexagram } from '@/types/iching';

withDefaults(
  defineProps<{
    hexagram: Hexagram;
    coin_results: number[];
    is_secondary?: boolean;
  }>(),
  {
    is_secondary: false,
  },
);
</script>

<template>
  <div>
    <div
      class="mb-6 grid gap-8 border-b border-slate-50 lg:mb-8 lg:grid-cols-2 dark:border-slate-800"
    >
      <div class="text-center sm:text-left">
        <span
          :class="[
            'mb-2 inline-block rounded-full px-3 py-1 text-[10px] font-bold tracking-wider uppercase',
            is_secondary
              ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900'
              : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
          ]"
        >
          {{ is_secondary ? 'Future Outcome' : 'Current Situation' }}
        </span>
        <h2
          class="font-serif text-4xl font-bold text-slate-900 dark:text-slate-100"
        >
          {{ hexagram.number }}. {{ hexagram.names[0] }}
        </h2>
        <div class="mt-3 space-y-1">
          <p
            v-for="(name, index) in hexagram.names.slice(1)"
            :key="'alt-name-' + index"
            class="font-serif text-lg text-slate-500 dark:text-slate-400"
          >
            {{ name }}
          </p>

          <div
            class="flex flex-wrap justify-center gap-x-4 gap-y-1 sm:justify-start"
          >
            <p
              v-if="hexagram.origins.chinese"
              class="text-2xl text-slate-400 dark:text-slate-500"
            >
              {{ hexagram.origins.chinese }}
            </p>
            <p
              v-if="hexagram.origins.pinyin"
              class="font-serif text-lg text-slate-400 italic dark:text-slate-500"
            >
              {{ hexagram.origins.pinyin }}
            </p>
          </div>
        </div>
      </div>
      <HexagramView :coin-results="coin_results" :hexagram="hexagram" />
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
      <InterpretationBlock title="The Judgment" :text="hexagram.judgment" />
      <InterpretationBlock title="The Image" :text="hexagram.image ?? ''" />
    </div>
  </div>
</template>
