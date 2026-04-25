<script setup lang="ts">
import { type Component } from 'vue';

defineProps<{
  title: string;
  description: string;
  icon: Component;
}>();
</script>

<template>
  <div
    class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/50 dark:hover:border-amber-500/50 dark:hover:bg-slate-800"
  >
    <div
      class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
    >
      <component :is="icon" class="size-6 text-amber-600" />
    </div>
    <h3 class="mb-3 font-serif text-xl font-bold dark:text-slate-100">
      {{ title }}
    </h3>
    <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">
      {{ description }}
    </p>
  </div>
</template>
