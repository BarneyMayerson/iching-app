<script setup lang="ts">
defineProps<{
  brokenLines: number[];
  changingLines: number[];
  isVisible: boolean;
}>();
</script>

<template>
  <div class="flex flex-col gap-4">
    <div
      v-for="n in 6"
      :key="n"
      class="h-3 w-64 overflow-hidden transition-all duration-700 ease-out"
      :style="{
        opacity: isVisible ? 0.3 + n * 0.12 : 0,
        transform: isVisible ? 'translateY(0)' : 'translateY(40px)',
        transitionDelay: n * 150 + 'ms',
      }"
    >
      <div
        v-if="!brokenLines.includes(n)"
        class="h-full w-full rounded-sm transition-colors duration-1000"
        :class="
          changingLines.includes(n)
            ? 'bg-amber-500 shadow-[0_0_15px_rgba(245,158,11,0.4)]'
            : 'bg-slate-700'
        "
      ></div>

      <div v-else class="flex h-full w-full justify-between">
        <div
          class="h-full w-[45%] rounded-sm transition-colors duration-1000"
          :class="changingLines.includes(n) ? 'bg-amber-500' : 'bg-slate-700'"
        ></div>
        <div
          class="h-full w-[45%] rounded-sm transition-colors duration-1000"
          :class="changingLines.includes(n) ? 'bg-amber-500' : 'bg-slate-700'"
        ></div>
      </div>
    </div>
  </div>
</template>
