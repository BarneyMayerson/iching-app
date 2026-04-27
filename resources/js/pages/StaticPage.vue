<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
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

  <div class="min-h-screen bg-slate-50 py-12 dark:bg-slate-950">
    <div class="mx-auto max-w-3xl px-6">
      <Link
        href="/"
        class="mb-8 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
      >
        <ChevronLeft class="size-4" />
        {{ __('Back to home') }}
      </Link>

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
      </article>

      <footer
        class="mt-16 border-t border-slate-200 pt-8 text-center dark:border-slate-800"
      >
        <p class="text-sm text-slate-500">
          © 2026 I-Ching Cabinet. {{ __('Last updated') }}:
          {{ new Date().toLocaleDateString() }}
        </p>
      </footer>
    </div>
  </div>
</template>
