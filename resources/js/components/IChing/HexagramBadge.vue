<script setup lang="ts">
defineProps<{
  is_secondary: boolean;
}>();
</script>

<template>
  <span
    :class="[
      'mb-2 inline-block rounded-full px-3 py-1 text-[10px] font-bold tracking-wider uppercase',
      is_secondary
        ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900'
        : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
    ]"
  >
    {{ is_secondary ? __('Future Outcome') : __('Current Situation') }}
  </span>
</template>
