<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import MarkdownIt from 'markdown-it';
import { computed } from 'vue';

const props = defineProps<{
  page: {
    title: string;
    content: string;
  };
}>();

const md = new MarkdownIt({
  html: true,
  linkify: true,
  typographer: true,
});

const renderedContent = computed(() => {
  return md.render(props.page.content || '');
});
</script>

<template>
  <Head :title="page.title" />

  <GuestLayout>
    <div class="mx-auto max-w-3xl px-6">
      <article>
        <header class="mb-12">
          <h1
            class="font-serif text-4xl font-bold tracking-tight text-slate-900 lg:text-5xl dark:text-white"
          >
            {{ page.title }}
          </h1>
          <div class="mt-4 h-1 w-20 bg-amber-500"></div>
        </header>

        <div
          class="prose max-w-none prose-slate prose-amber lg:prose-lg dark:prose-invert prose-headings:font-serif"
          v-html="renderedContent"
        ></div>

        <div
          class="my-12 flex items-center justify-center gap-4 text-slate-500 dark:text-slate-400"
        >
          <p class="text-sm">
            {{ __('Last updated') }}: {{ new Date().toLocaleDateString() }}
          </p>
        </div>
      </article>
    </div>
  </GuestLayout>
</template>
