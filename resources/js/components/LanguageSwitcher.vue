<script setup lang="ts">
import { update as languageUpdate } from '@/routes/language';
import { router } from '@inertiajs/vue3';

const languages = {
  en: 'ENG',
  ru: 'RUS',
};

defineProps<{
  currentLocale: string;
}>();

const changeLanguage = (lang: string) => {
  router.post(
    languageUpdate().url,
    { language: lang },
    {
      preserveScroll: true,
    },
  );
};
</script>

<template>
  <div
    class="inline-flex items-center gap-1 rounded-full bg-slate-100 p-1 dark:bg-slate-800/50"
  >
    <button
      v-for="(label, code) in languages"
      :key="code"
      @click="changeLanguage(code)"
      class="rounded-full px-3 py-1.5 text-[10px] font-black tracking-widest uppercase transition-all"
      :class="
        currentLocale === code
          ? 'bg-white text-slate-900 shadow-sm dark:bg-slate-700 dark:text-white'
          : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-200'
      "
    >
      {{ label }}
    </button>
  </div>
</template>
