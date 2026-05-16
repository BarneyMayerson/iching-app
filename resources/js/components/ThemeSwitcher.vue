<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core';
import { Moon, Sun } from 'lucide-vue-next';

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>
<template>
  <button
    @click="toggleDark()"
    class="rounded-full p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
  >
    <Sun v-if="isDark" class="size-5" />
    <Moon v-else class="size-5" />
  </button>
</template>
