<script setup lang="ts">
interface Props {
  isSpinning: boolean;
  result: number | null; // 2 (Yin/Heads) или 3 (Yang/Tails)
}

defineProps<Props>();
</script>

<template>
  <div class="coin-container">
    <div
      class="coin"
      :class="{
        'is-spinning': isSpinning,
        'show-heads': result === 3,
        'show-tails': result === 2,
      }"
    >
      <div
        class="side tails flex items-center justify-center rounded-full border-4 border-slate-400 bg-slate-300 shadow-inner"
      >
        <span class="text-2xl font-bold text-slate-700 italic">2</span>
      </div>
      <div
        class="side heads flex items-center justify-center rounded-full border-4 border-amber-600 bg-amber-500 shadow-inner"
      >
        <span class="text-2xl font-bold text-amber-900 italic">3</span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.coin-container {
  width: 80px;
  height: 80px;
  perspective: 1000px;
}

.coin {
  width: 100%;
  height: 100%;
  position: relative;
  transition: transform 1s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transform-style: preserve-3d;
}

.side {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
}

.heads {
  transform: rotateY(180deg);
}

.tails {
  transform: rotateY(0deg);
}

.is-spinning {
  animation: spin 0.6s linear infinite;
}

.show-heads {
  transform: rotateY(180deg);
}

.show-tails {
  transform: rotateY(0deg);
}

@keyframes spin {
  0% {
    transform: rotateY(0deg);
  }
  100% {
    transform: rotateY(360deg);
  }
}
</style>
