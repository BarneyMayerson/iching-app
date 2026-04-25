<script setup lang="ts">
import { register } from '@/routes';
import { Link } from '@inertiajs/vue3';

const scrollToSacredProcess = () => {
  const element = document.getElementById('sacred');
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
};
</script>
<template>
  <header class="relative px-6 pt-20 pb-32 text-center lg:px-12">
    <div class="mx-auto max-w-4xl">
      <span
        class="mb-4 inline-block rounded-full bg-amber-100 px-4 py-1.5 text-xs font-black tracking-[0.2em] text-amber-700 uppercase"
      >
        {{ __('Ancient Wisdom • Modern Interface') }}
      </span>
      <h1
        class="mb-8 font-serif text-6xl leading-[1.1] font-bold tracking-tight text-slate-950 lg:text-7xl dark:text-white"
      >
        {{ __('Find clarity in the') }}
        <span class="text-amber-600 italic">{{ __('flow of change.') }}</span>
      </h1>
      <p class="mb-10 text-lg leading-relaxed text-slate-600">
        {{
          __(
            'A sophisticated digital space for I-Ching consultations. Track your journey, analyze patterns, and archive the wisdom of the Oracle in beautiful PDF reports.',
          )
        }}
      </p>
      <div class="flex flex-col items-center justify-center gap-4 sm:flex-row">
        <Link
          :href="register().url"
          class="w-full rounded-2xl bg-slate-900 px-8 py-4 text-lg font-bold text-white shadow-xl shadow-slate-200 transition-all hover:bg-slate-800 active:scale-95 sm:w-auto dark:bg-amber-600 dark:text-slate-950 dark:shadow-amber-900/20 dark:hover:bg-amber-500"
        >
          {{ __('Begin Consultation') }}
        </Link>

        <a
          href="#sacred"
          @click.prevent="scrollToSacredProcess"
          class="text-sm font-bold text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100"
        >
          {{ __('The Sacred Process') }} ↓
        </a>
      </div>
    </div>
  </header>
</template>
