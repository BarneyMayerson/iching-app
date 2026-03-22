<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import { Crosshair, ScrollText, Zap } from 'lucide-vue-next';
import { ref } from 'vue';

interface Line {
  position: number;
  meaning: string;
}

const props = defineProps<{
  lines: Line[];
  changing_lines: number[];
}>();

const { __ } = useTranslate();

const mode = ref<'path' | 'focus'>('path');

const isChanging = (pos: number) => props.changing_lines.includes(pos - 1);
</script>

<template>
  <div class="space-y-8">
    <div
      class="flex flex-col gap-6 px-4 sm:flex-row sm:items-center sm:justify-between"
    >
      <div class="flex items-center gap-3">
        <Zap class="size-6 text-amber-500" />
        <h3
          class="font-serif text-2xl font-bold text-slate-900 dark:text-white"
        >
          {{ __('Line Guidance') }}
        </h3>
      </div>

      <div class="flex rounded-full bg-slate-100 p-1 dark:bg-slate-800/50">
        <button
          @click="mode = 'path'"
          :class="[
            'flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold transition-all',
            mode === 'path'
              ? 'bg-white text-amber-600 shadow-sm dark:bg-slate-700'
              : 'text-slate-500 hover:text-slate-700',
          ]"
        >
          <ScrollText class="size-3.5" />
          {{ __('The Path of the Sage') }}
        </button>
        <button
          @click="mode = 'focus'"
          :class="[
            'flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-bold transition-all',
            mode === 'focus'
              ? 'bg-white text-amber-600 shadow-sm dark:bg-slate-700'
              : 'text-slate-500 hover:text-slate-700',
          ]"
        >
          <Crosshair class="size-3.5" />
          {{ __("The Oracle's Focus") }}
        </button>
      </div>
    </div>

    <div class="relative min-h-100">
      <TransitionGroup
        enter-active-class="transition duration-500 ease-out"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-300 ease-in absolute w-full"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div v-if="mode === 'path'" key="path" class="space-y-4">
          <div
            v-for="line in lines"
            :key="line.position"
            :class="[
              'group relative rounded-2xl border p-6 transition-all duration-300',
              isChanging(line.position)
                ? 'border-amber-200 bg-amber-50/30 dark:border-amber-900/30 dark:bg-amber-900/10'
                : 'border-slate-100 bg-white dark:border-slate-800 dark:bg-slate-900/20',
            ]"
          >
            <div class="mb-3 flex items-center gap-3">
              <span
                :class="[
                  'text-xs font-black tracking-widest uppercase',
                  isChanging(line.position)
                    ? 'text-amber-600'
                    : 'text-slate-400',
                ]"
              >
                {{
                  __('Line #:position — :status')
                    .replace(':position', line.position.toString())
                    .replace(
                      ':status',
                      isChanging(line.position) ? __('Changing') : __('Stable'),
                    )
                }}
              </span>
            </div>
            <p
              class="text-sm leading-relaxed text-slate-700 dark:text-slate-300"
            >
              {{ line.meaning }}
            </p>
          </div>
        </div>

        <div v-else key="focus" class="space-y-6">
          <div
            v-if="changing_lines.length === 0"
            class="flex flex-col items-center justify-center py-20 text-center"
          >
            <div class="mb-4 rounded-full bg-slate-50 p-4 dark:bg-slate-800">
              <ScrollText class="size-8 text-slate-300" />
            </div>
            <p class="font-serif text-lg text-slate-500 italic">
              {{
                __(
                  'The lines remain stable. No transformation is sought at this time.',
                )
              }}
            </p>
          </div>

          <div
            v-for="line in lines.filter((l) => isChanging(l.position))"
            :key="line.position"
            class="relative rounded-3xl border-2 border-amber-200 bg-white p-8 shadow-sm dark:border-amber-900/30 dark:bg-slate-900"
          >
            <div
              class="absolute top-8 -left-3 flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-white shadow-lg"
            >
              <Zap class="size-5" />
            </div>
            <div class="ml-6">
              <h4
                class="mb-2 text-xs font-black tracking-[0.3em] text-amber-600 uppercase"
              >
                {{
                  __('Active Transformation at Line :position').replace(
                    ':position',
                    line.position.toString(),
                  )
                }}
              </h4>
              <p
                class="font-serif text-lg leading-relaxed text-slate-800 dark:text-slate-200"
              >
                {{ line.meaning }}
              </p>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>
