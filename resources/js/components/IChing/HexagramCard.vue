<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import InterpretationBlock from '@/components/IChing/InterpretationBlock.vue';
import { Hexagram } from '@/types/iching';
import HexagramBadge from './HexagramBadge.vue';
import TrigramCard from './TrigramCard.vue';

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
      class="mb-6 grid gap-8 border-b border-slate-50 lg:mb-8 lg:grid-cols-[1fr_auto] dark:border-slate-800"
    >
      <div class="text-center sm:text-left">
        <HexagramBadge :is_secondary />
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
        </div>

        <!-- trigram block -->
        <div
          class="mt-8 border-t border-slate-100 pt-6 dark:border-slate-800/50"
        >
          <div
            class="flex flex-col gap-3 sm:flex-row sm:items-stretch lg:gap-4"
          >
            <TrigramCard :trigram="hexagram.top_trigram" :label="__('Above')" />
            <TrigramCard
              :trigram="hexagram.bottom_trigram"
              :label="__('Below')"
            />
          </div>
        </div>
      </div>
      <HexagramView :coin-results="coin_results" :hexagram />
    </div>

    <div class="mt-4 lg:mt-8">
      <InterpretationBlock
        :title="__('The Judgment')"
        :text="hexagram.judgment"
      />
    </div>
  </div>
</template>
