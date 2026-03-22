<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import { show } from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
  reading: Reading;
}>();

const { __ } = useTranslate();

const hasTransformation = computed(() => {
  return props.reading.coin_results.some(
    (result: number) => result === 6 || result === 9,
  );
});

const isYang = (value: number) => value === 7 || value === 9;
const isChanging = (value: number) => value === 6 || value === 9;
</script>

<template>
  <Link
    :href="show(props.reading.uuid).url"
    :title="reading.question"
    class="group relative flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 transition-all duration-300 hover:-translate-y-1 hover:border-amber-300 hover:shadow-xl hover:shadow-amber-900/5 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-amber-700"
  >
    <div class="mb-4 flex items-center justify-between">
      <div class="flex flex-col">
        <span
          class="text-[10px] font-bold tracking-widest text-slate-400 uppercase"
        >
          {{ __(reading.relative_date) }}
        </span>
        <span class="text-xs text-slate-500">
          {{ reading.date }} · {{ reading.time }}
        </span>
      </div>

      <div
        class="text-2xl text-slate-300 transition-colors duration-500 group-hover:text-amber-500 dark:text-slate-700"
      >
        {{ reading.hexagram.character }}
      </div>
    </div>

    <div class="mb-6 flex-1">
      <h3
        class="line-clamp-2 font-serif text-lg font-medium text-slate-800 transition-colors group-hover:text-slate-900 dark:text-slate-200 dark:group-hover:text-white"
      >
        "{{ reading.question }}"
      </h3>
    </div>

    <div
      class="mt-auto flex items-end justify-between border-t border-slate-50 pt-4 dark:border-slate-800/50"
    >
      <div class="flex w-10 shrink-0 flex-col-reverse gap-0.75 pb-1">
        <div
          v-for="(result, index) in reading.coin_results"
          :key="index"
          class="h-0.75 w-full"
        >
          <div
            v-if="isYang(result)"
            :class="[
              'h-full w-full rounded-full transition-colors duration-500',
              isChanging(result)
                ? 'bg-amber-500'
                : 'bg-slate-200 group-hover:bg-slate-300 dark:bg-slate-800 dark:group-hover:bg-slate-700',
            ]"
          ></div>

          <div v-else class="flex h-full w-full justify-between">
            <div
              :class="[
                'h-full w-[42%] rounded-full transition-colors duration-500',
                isChanging(result)
                  ? 'bg-amber-500'
                  : 'bg-slate-200 group-hover:bg-slate-300 dark:bg-slate-800 dark:group-hover:bg-slate-700',
              ]"
            ></div>
            <div
              :class="[
                'h-full w-[42%] rounded-full transition-colors duration-500',
                isChanging(result)
                  ? 'bg-amber-500'
                  : 'bg-slate-200 group-hover:bg-slate-300 dark:bg-slate-800 dark:group-hover:bg-slate-700',
              ]"
            ></div>
          </div>
        </div>
      </div>

      <div class="flex flex-col items-end text-right">
        <div class="flex flex-col">
          <span
            class="mb-1 text-[8px] leading-none font-black text-slate-400 uppercase"
          >
            {{ __('Hexagram') }}
          </span>
          <span
            class="font-serif text-sm font-bold text-slate-700 dark:text-slate-300"
          >
            {{ reading.hexagram.number }}. {{ reading.hexagram.names[0] }}
          </span>
        </div>

        <div
          v-if="hasTransformation"
          class="mt-1 rounded-full bg-amber-50 px-2 py-0.5 text-[8px] font-bold text-amber-600 dark:bg-amber-900/20 dark:text-amber-400"
        >
          {{ __('Transformed') }}
        </div>
      </div>
    </div>
  </Link>
</template>
