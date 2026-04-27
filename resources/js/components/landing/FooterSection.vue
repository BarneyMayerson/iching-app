<script setup lang="ts">
import { page } from '@/routes/static';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
      <p class="text-sm text-slate-500">
        © 2026 I-Ching Cabinet.
        {{ __('Built with precision for the modern sage.') }}
      </p>
      <div
        class="mt-6 flex justify-center gap-6 text-xs font-bold tracking-widest text-slate-400 uppercase"
      >
        <Link :href="page('privacy').url" class="hover:text-amber-500">
          {{ __('Privacy') }}
        </Link>
        <Link :href="page('terms').url" class="hover:text-amber-500">
          {{ __('Terms') }}
        </Link>
        <Link :href="page('guide').url" class="hover:text-amber-500">
          {{ __('Guide') }}
        </Link>
      </div>
    </div>
  </footer>
</template>
