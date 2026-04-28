<script setup lang="ts">
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { login, register } from '@/routes';
import { dashboard } from '@/routes/cabinet';
import { Link } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { Moon, Sparkles, Sun } from 'lucide-vue-next';

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>

<template>
  <nav
    class="sticky top-0 z-50 flex items-center justify-between bg-slate-50/80 p-6 backdrop-blur-md transition-colors lg:px-12 dark:bg-slate-950/80"
  >
    <Link href="/" class="group flex items-center gap-2">
      <div
        class="flex size-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg transition-transform group-hover:scale-105"
      >
        <Sparkles class="size-6" />
      </div>
      <span class="font-serif text-xl font-bold tracking-tight dark:text-white">
        {{ __('I-Ching Cabinet') }}
      </span>
    </Link>

    <div class="flex items-center gap-2 sm:gap-4">
      <LanguageSwitcher :currentLocale="$page.props.locale" />
      <button
        @click="toggleDark()"
        class="rounded-full p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
      >
        <Sun v-if="isDark" class="size-5" />
        <Moon v-else class="size-5" />
      </button>
      <Link
        v-if="$page.props.auth.user"
        :href="dashboard().url"
        class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
      >
        {{ __('Go to Cabinet') }}
      </Link>

      <template v-else>
        <Link
          :href="login().url"
          class="hidden rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 sm:block dark:text-slate-300 dark:hover:bg-slate-800"
        >
          {{ __('Log In') }}
        </Link>

        <Link
          :href="register().url"
          class="hidden rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg active:scale-95 sm:block dark:bg-amber-600 dark:text-slate-950 dark:hover:bg-amber-500"
        >
          {{ __('Get started for free') }}
        </Link>
      </template>
    </div>
  </nav>
</template>
