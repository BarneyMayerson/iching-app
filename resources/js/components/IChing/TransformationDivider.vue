<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import { ChevronDown } from 'lucide-vue-next';

const { __ } = useTranslate();
</script>

<template>
  <div class="h-16 w-px bg-linear-to-b from-amber-500 to-transparent"></div>
  <div
    class="flex size-12 items-center justify-center rounded-full bg-amber-500 text-white shadow-lg shadow-amber-500/20"
  >
    <ChevronDown class="size-6 animate-bounce" />
  </div>
  <p class="mt-4 font-serif text-xl text-slate-400 italic">
    {{ __('Transforming into...') }}
  </p>
</template>
