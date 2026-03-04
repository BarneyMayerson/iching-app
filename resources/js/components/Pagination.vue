<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  links: Array<{ url: string | null; label: string; active: boolean }>;
}>();
</script>

<template>
  <nav v-if="links.length > 3" class="flex flex-wrap gap-1">
    <template v-for="(link, key) in links" :key="key">
      <div
        v-if="link.url === null"
        class="border border-transparent px-4 py-2 text-sm text-slate-400"
        v-html="link.label"
      />
      <Link
        v-else
        :href="link.url"
        class="rounded-lg border px-4 py-2 text-sm transition-all"
        :class="[
          link.active
            ? 'border-amber-600 bg-amber-600 text-white shadow-sm'
            : 'border-slate-200 bg-white text-slate-600 hover:border-amber-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400',
        ]"
      >
        <span v-html="link.label" />
      </Link>
    </template>
  </nav>
</template>
