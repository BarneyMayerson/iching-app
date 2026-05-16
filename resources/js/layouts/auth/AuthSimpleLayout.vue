<script setup lang="ts">
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import LogoStatic from '@/components/logos/LogoStatic.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import { home } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
  title?: string;
  description?: string;
}>();

const page = usePage();
</script>

<template>
  <div
    class="absolute top-4 right-4 flex items-center gap-2 sm:top-8 sm:right-8"
  >
    <LanguageSwitcher :currentLocale="page.props.locale" />
    <ThemeSwitcher />
  </div>
  <div
    class="flex min-h-svh flex-col items-center justify-center gap-6 bg-slate-50 p-6 md:p-10 dark:bg-slate-950"
  >
    <div class="w-full max-w-sm">
      <div class="flex flex-col gap-8">
        <div class="flex flex-col items-center gap-4">
          <Link
            :href="home()"
            class="group flex flex-col items-center gap-3 font-medium"
          >
            <div
              class="flex size-20 items-center justify-center rounded-xl transition-transform group-hover:scale-105"
            >
              <LogoStatic class="size-16" />
            </div>

            <span
              class="font-serif text-2xl font-bold tracking-tight text-slate-900 dark:text-white"
            >
              {{ __('I-Ching Cabinet') }}
            </span>
            <span class="sr-only">{{ title }}</span>
          </Link>

          <div class="space-y-2 text-center">
            <h1
              class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white"
            >
              {{ title }}
            </h1>
            <p
              class="text-center text-sm text-balance text-slate-500 dark:text-slate-400"
            >
              {{ description }}
            </p>
          </div>
        </div>

        <div
          class="p rounded-2xl bg-white px-4 py-8 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-800"
        >
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>
