<script setup lang="ts">
import { Zap } from 'lucide-vue-next';

interface Line {
  position: number;
  meaning: string;
}

defineProps<{
  lines: Line[];
  changing_lines: number[];
}>();
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center gap-3 px-4">
      <Zap class="size-5 text-amber-500" />
      <h3 class="font-serif text-2xl font-bold">Line Guidance</h3>
    </div>

    <div class="space-y-4">
      <div
        v-for="line in lines"
        :key="line.position"
        :class="[
          'group relative rounded-2xl p-6 transition-all duration-500',
          changing_lines.includes(line.position)
            ? 'border-l-4 border-amber-500 bg-amber-50/50 shadow-sm dark:bg-amber-900/10'
            : 'border border-slate-100 bg-white opacity-60 dark:border-slate-800 dark:bg-slate-900/20',
        ]"
      >
        <div class="mb-3 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <span
              :class="[
                'flex size-7 items-center justify-center rounded-full text-xs font-bold transition-colors',
                changing_lines.includes(line.position)
                  ? 'bg-amber-600 text-white'
                  : 'bg-slate-200 text-slate-500 dark:bg-slate-800',
              ]"
            >
              {{ line.position }}
            </span>
            <span
              class="text-[10px] font-black tracking-[0.2em] uppercase"
              :class="
                changing_lines.includes(line.position)
                  ? 'text-amber-600'
                  : 'text-slate-400'
              "
            >
              {{
                changing_lines.includes(line.position) ? 'Changing' : 'Stable'
              }}
            </span>
          </div>
        </div>
        <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">
          {{ line.meaning }}
        </p>
      </div>
    </div>
  </div>
</template>
