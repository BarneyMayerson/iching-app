<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  value: number; // 6: Старая Инь (меняется), 7: Молодой Ян, 8: Молодой Инь, 9: Старый Ян (меняется)
  position: number; //Номер линии
}

const props = defineProps<Props>();

const isYang = computed(() => [7, 9].includes(props.value));
const isChanging = computed(() => [6, 9].includes(props.value));

const lineBaseClass = computed(() => [
  'h-3 rounded-sm shadow-sm transition-colors duration-500',
  'bg-slate-600 dark:bg-slate-400',
]);
</script>

<template>
  <div class="relative flex h-8 w-full items-center justify-center px-4">
    <div
      v-if="isYang"
      data-test="solid-line"
      class="w-full"
      :class="lineBaseClass"
    >
      <div
        v-if="value === 9"
        class="absolute inset-0 flex items-center justify-center"
      >
        <div
          class="size-2.5 rounded-full bg-white shadow-sm dark:bg-slate-900"
        ></div>
      </div>
    </div>

    <div v-else data-test="broken-line" class="flex w-full justify-between">
      <div class="w-[45%]" :class="lineBaseClass"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div
          v-if="value === 6"
          class="text-sm leading-none font-black text-slate-600 drop-shadow-sm dark:text-slate-400"
        >
          ✕
        </div>
      </div>
      <div class="w-[45%]" :class="lineBaseClass"></div>
    </div>

    <div class="absolute -left-2 text-xs text-gray-400 dark:text-gray-500">
      #{{ position }}
    </div>

    <div
      v-if="isChanging"
      data-test="changing-marker"
      class="absolute -right-2 flex h-3 w-3"
    >
      <span
        class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"
      ></span>
      <span
        class="relative inline-flex h-3 w-3 rounded-full bg-amber-600 shadow-sm"
      ></span>
    </div>
  </div>
</template>
