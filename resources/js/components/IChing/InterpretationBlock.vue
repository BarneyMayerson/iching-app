<script setup lang="ts">
defineProps<{
  title: string;
  text: string;
  variant?: 'primary' | 'secondary';
}>();
</script>

<template>
  <div
    class="rounded-xl border border-slate-200 bg-white/80 p-6 shadow-sm transition-all duration-500 dark:border-slate-800 dark:bg-slate-900/50"
  >
    <h3
      class="mb-3 text-lg font-bold tracking-wider uppercase"
      :class="
        variant === 'secondary'
          ? 'text-amber-600 dark:text-amber-500'
          : 'text-amber-500 dark:text-amber-600'
      "
    >
      {{ title }}
    </h3>
    <p class="leading-relaxed text-slate-700 dark:text-slate-300">
      {{ text || 'The Oracle is silent on this matter...' }}
    </p>
  </div>
</template>
