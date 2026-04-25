<script setup lang="ts">
import FeatureSection from '@/components/landing/FeatureSection.vue';
import FooterSection from '@/components/landing/FooterSection.vue';
import HeroHeader from '@/components/landing/HeroHeader.vue';
import SacredRitualSection from '@/components/landing/SacredRitualSection.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { useTranslate } from '@/composables/useTranslate';
import { login, register } from '@/routes';
import { dashboard } from '@/routes/cabinet';
import { Head, Link } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { Moon, Sparkles, Sun } from 'lucide-vue-next';

const { __ } = useTranslate();

const isDark = useDark();
const toggleDark = useToggle(isDark);

withDefaults(
  defineProps<{
    canRegister: boolean;
    canLogin: boolean;
  }>(),
  {
    canRegister: true,
    canLogin: true,
  },
);
</script>

<template>
  <Head :title="__('I-Ching Cabinet — Your Digital Oracle')" />

  <div
    class="min-h-screen bg-slate-50 text-slate-900 selection:bg-amber-200 dark:bg-slate-950 dark:text-slate-100"
  >
    <nav class="flex items-center justify-between p-6 lg:px-12">
      <div class="flex items-center gap-2">
        <div
          class="flex size-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg"
        >
          <Sparkles class="size-6" />
        </div>
        <span class="font-serif text-xl font-bold tracking-tight">
          {{ __('I-Ching Cabinet') }}
        </span>
      </div>

      <div v-if="canLogin" class="flex items-center gap-2 sm:gap-4">
        <Link
          v-if="$page.props.auth.user"
          :href="dashboard().url"
          class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100"
        >
          {{ __('Go to Cabinet') }}
        </Link>
        <template v-else>
          <LanguageSwitcher :currentLocale="$page.props.locale" />
          <button
            @click="toggleDark()"
            class="rounded-full p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
          >
            <Sun v-if="isDark" class="size-5" />
            <Moon v-else class="size-5" />
          </button>
          <Link
            :href="login().url"
            class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
          >
            {{ __('Log In') }}
          </Link>
          <Link
            :href="register().url"
            class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-slate-800 hover:shadow-lg active:scale-95"
          >
            {{ __('Start for free') }}
          </Link>
        </template>
      </div>
    </nav>

    <HeroHeader />
    <FeatureSection />
    <SacredRitualSection />
    <FooterSection />
  </div>
</template>
