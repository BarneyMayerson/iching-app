<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';

const props = defineProps<{
  yang: number;
  yin: number;
  total: number;
}>();

const { __ } = useTranslate();

const yangPercent = Math.round((props.yang / props.total) * 100) || 0;
const yinPercent = 100 - yangPercent;
</script>

<template>
  <div class="space-y-4 p-6">
    <div class="flex items-center justify-between">
      <h3 class="text-sm font-bold tracking-wider text-slate-500 uppercase">
        {{ __('Energy Balance') }}
      </h3>
      <span class="text-xs font-medium text-slate-400">
        {{ __(':total lines total').replace(':total', total.toString()) }}
      </span>
    </div>

    <div
      class="relative flex h-4 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
    >
      <div
        class="h-full bg-amber-500 transition-all duration-1000 ease-out"
        :style="{ width: yangPercent + '%' }"
      ></div>
      <div
        class="h-full bg-slate-900 transition-all duration-1000 ease-out dark:bg-slate-700"
        :style="{ width: yinPercent + '%' }"
      ></div>
    </div>

    <div class="flex justify-between font-serif text-sm">
      <div class="flex flex-col">
        <span class="font-bold text-amber-600">
          {{
            __('Yang (:percent%)').replace(':percent', yangPercent.toString())
          }}
        </span>
        <span class="text-[10px] tracking-tighter text-slate-400 uppercase">
          {{ __('Active / Light') }}
        </span>
      </div>
      <div class="flex flex-col text-right">
        <span class="font-bold text-slate-800 dark:text-slate-200">
          {{ __('Yin (:percent%)').replace(':percent', yinPercent.toString()) }}
        </span>
        <span class="text-[10px] tracking-tighter text-slate-400 uppercase">
          {{ __('Receptive / Dark') }}
        </span>
      </div>
    </div>
  </div>
</template>
