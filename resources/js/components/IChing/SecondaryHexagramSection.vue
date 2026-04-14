<script setup lang="ts">
import HexagramCard from '@/components/IChing/HexagramCard.vue';
import TransformationDivider from '@/components/IChing/TransformationDivider.vue';
import { Reading } from '@/types/iching';
import { Sparkles } from 'lucide-vue-next';

defineProps<{
  reading: Reading;
}>();
</script>

<template>
  <div class="my-20 flex flex-col items-center">
    <TransformationDivider />
  </div>
  <div
    class="rounded-3xl border border-dashed border-slate-300 bg-slate-50/50 p-8 dark:border-slate-700 dark:bg-slate-900/30"
  >
    <HexagramCard
      :hexagram="reading.secondary_hexagram"
      :coin_results="
        reading.coin_results.map((v: number) => (v === 6 ? 7 : v === 9 ? 8 : v))
      "
      is_secondary
    />
  </div>

  <div
    class="rounded-2xl bg-amber-500 p-8 text-center text-white shadow-xl shadow-amber-900/20"
  >
    <Sparkles class="mx-auto mb-4 size-8" />
    <p class="font-serif text-xl italic">
      {{
        __(
          'Focus on the changing lines as the key to your inquiry. They represent the path from where you are to where you are going.',
        )
      }}
    </p>
  </div>
</template>
