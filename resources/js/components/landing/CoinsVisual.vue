<script setup lang="ts">
defineProps<{
  isFlipping: boolean;
}>();
</script>

<template>
  <div class="flex gap-6">
    <div
      v-for="i in 3"
      :key="i"
      class="flex size-16 items-center justify-center rounded-full border-2 border-amber-500/50 bg-slate-900 text-2xl font-bold text-amber-500 transition-all duration-700"
      :class="{
        'animate-flip': isFlipping,
        'scale-90 opacity-40': !isFlipping,
      }"
      :style="{ animationDelay: i * 0.1 + 's' }"
    >
      <span>乾</span>
    </div>
  </div>
</template>

<style scoped>
@keyframes coin-flip {
  0% {
    transform: translateY(0) rotateY(0);
  }
  50% {
    transform: translateY(-30px) rotateY(180deg);
  }
  100% {
    transform: translateY(0) rotateY(360deg);
  }
}
.animate-flip {
  animation: coin-flip 0.8s ease-in-out infinite;
}
</style>
