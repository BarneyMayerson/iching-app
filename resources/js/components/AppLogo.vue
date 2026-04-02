<script setup lang="ts">
import { Sparkles } from 'lucide-vue-next';
</script>

<template>
  <div
    class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
  >
    <Sparkles class="size-5 fill-current text-white dark:text-black" />
  </div>
  <div class="ml-1 grid flex-1 text-left text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">
      {{ __('I-Ching Cabinet') }}
    </span>
  </div>
</template>
