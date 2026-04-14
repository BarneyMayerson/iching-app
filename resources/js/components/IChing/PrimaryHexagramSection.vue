<script setup lang="ts">
import HexagramCard from '@/components/IChing/HexagramCard.vue';
import LineGuidance from '@/components/IChing/LineGuidance.vue';
import { Reading } from '@/types/iching';

defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();
</script>

<template>
  <div
    class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
  >
    <HexagramCard
      :hexagram="reading.hexagram"
      :coin_results="reading.coin_results"
    />
  </div>

  <LineGuidance
    :lines="reading.hexagram.lines"
    :changing_lines="changing_lines"
  />
</template>
