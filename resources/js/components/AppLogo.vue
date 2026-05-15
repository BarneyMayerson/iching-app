<script setup lang="ts">
import LogoStatic from './logos/LogoStatic.vue';
</script>

<template>
  <div
    class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground"
  >
    <LogoStatic class="text-white dark:text-black" />
  </div>
  <div class="ml-1 grid flex-1 text-left text-sm">
    <span class="mb-0.5 truncate leading-tight font-semibold">
      {{ __('I-Ching Cabinet') }}
    </span>
  </div>
</template>
