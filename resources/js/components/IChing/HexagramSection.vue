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
        </div>

        <!-- trigram block -->
        <div
          class="mt-8 border-t border-slate-100 pt-6 dark:border-slate-800/50"
        >
          <div
            class="flex flex-col gap-3 sm:flex-row sm:items-stretch lg:gap-4"
          >
            <div
              class="group flex flex-1 items-center gap-4 rounded-2xl border border-slate-50 bg-slate-50/30 p-4 transition-all hover:border-amber-200 hover:bg-amber-50/30 dark:border-slate-800/50 dark:bg-slate-800/20 dark:hover:border-amber-900/50 dark:hover:bg-amber-900/10"
            >
              <div
                class="flex size-12 items-center justify-center rounded-xl bg-white text-3xl text-amber-600 shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
              >
                {{ hexagram.top_trigram.symbol }}
              </div>
              <div class="flex flex-col self-stretch">
                <p
                  class="text-[9px] font-black tracking-widest text-slate-400 uppercase"
                >
                  Above
                </p>
                <div class="flex flex-wrap items-baseline gap-x-2">
                  <span
                    class="font-serif text-lg font-bold text-slate-700 dark:text-slate-200"
                  >
                    {{ hexagram.top_trigram.elements[0] }}
                  </span>
                  <span
                    v-if="hexagram.top_trigram.elements[1]"
                    class="font-serif text-lg font-bold text-slate-500 dark:text-slate-400"
                  >
                    {{ hexagram.top_trigram.elements[1] }}
                  </span>
                </div>
                <p
                  class="mt-2 text-sm text-slate-500 italic dark:text-slate-400"
                >
                  {{ hexagram.top_trigram.attribute }}
                </p>
              </div>
            </div>

            <div
              class="group flex flex-1 items-center gap-4 rounded-2xl border border-slate-50 bg-slate-50/30 p-4 transition-all hover:border-amber-200 hover:bg-amber-50/30 dark:border-slate-800/50 dark:bg-slate-800/20 dark:hover:border-amber-900/50 dark:hover:bg-amber-900/10"
            >
              <div
                class="flex size-12 items-center justify-center rounded-xl bg-white text-3xl text-amber-600 shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
              >
                {{ hexagram.bottom_trigram.symbol }}
              </div>
              <div class="flex flex-col self-stretch">
                <p
                  class="text-[9px] font-black tracking-widest text-slate-400 uppercase"
                >
                  Below
                </p>
                <div class="flex flex-wrap items-baseline gap-x-2">
                  <span
                    class="font-serif text-lg font-bold text-slate-700 dark:text-slate-200"
                  >
                    {{ hexagram.bottom_trigram.elements[0] }}
                  </span>
                  <span
                    v-if="hexagram.bottom_trigram.elements[1]"
                    class="font-serif text-lg font-bold text-slate-500 dark:text-slate-400"
                  >
                    {{ hexagram.bottom_trigram.elements[1] }}
                  </span>
                </div>
                <p
                  class="mt-2 text-sm text-slate-500 italic dark:text-slate-400"
                >
                  {{ hexagram.bottom_trigram.attribute }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <HexagramView :coin-results="coin_results" :hexagram="hexagram" />
    </div>

    <div class="mt-4 lg:mt-8">
      <InterpretationBlock title="The Judgment" :text="hexagram.judgment" />
      <!-- <InterpretationBlock title="The Image" :text="hexagram.image ?? ''" /> -->
    </div>
  </div>
</template>
