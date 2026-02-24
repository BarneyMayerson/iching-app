<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/cabinet/divinations';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Coins } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
  result?: {
    coin_results: number[];
    hexagram: any;
  };
}>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Divinations', href: index().url },
  { title: 'New Casting', href: '#' },
];

const isCasting = ref(false);

const form = useForm({
  question: '',
});

const cast = () => {
  isCasting.value = true;

  setTimeout(() => {
    form.post(store().url, {
      onFinish: () => (isCasting.value = false),
      preserveState: true,
    });
  }, 2000);
};
</script>

<template>
  <Head title="Consult the Oracle" />

  <AppLayout :breadcrumbs>
    <div
      class="flex h-full flex-1 items-center justify-center p-4 transition-colors duration-500"
    >
      <div
        v-if="result"
        class="animate-in text-center duration-1000 fade-in zoom-in"
      >
        <HexagramView
          :coin-results="result.coin_results"
          :hexagram="result.hexagram"
        />
        <div class="mt-12">
          <button
            @click="router.get(index().url)"
            class="text-sm font-medium text-amber-600 transition-colors hover:text-amber-500 dark:text-amber-500 dark:hover:text-amber-400"
          >
            ← Back to History
          </button>
        </div>
      </div>

      <div v-else class="w-full max-w-md text-center">
        <div v-if="isCasting" class="flex flex-col items-center gap-8">
          <div class="flex gap-4">
            <div
              v-for="i in 3"
              :key="i"
              class="flex size-16 animate-bounce items-center justify-center rounded-full border-4 border-amber-600 bg-amber-500 text-2xl font-bold text-amber-900 shadow-xl"
              :style="{ animationDelay: `${i * 150}ms` }"
            >
              卌
            </div>
          </div>
          <p
            class="animate-pulse font-serif text-2xl text-amber-600 italic dark:text-amber-500"
          >
            The Oracle is thinking...
          </p>
        </div>

        <div
          v-else
          class="animate-in space-y-8 duration-700 fade-in slide-in-from-bottom-4"
        >
          <div
            class="mx-auto flex size-24 items-center justify-center rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-500"
          >
            <Coins class="size-12" />
          </div>

          <div class="space-y-2">
            <h2
              class="font-serif text-3xl font-bold text-slate-900 dark:text-slate-100"
            >
              Consult the Oracle
            </h2>
            <p
              class="font-serif text-sm text-slate-500 italic dark:text-slate-400"
            >
              "Focus on your question, and the answer will follow."
            </p>
          </div>

          <div class="space-y-4 text-left">
            <div>
              <label
                for="question"
                class="mb-2 ml-1 block text-sm font-medium text-slate-700 dark:text-slate-300"
              >
                What is your question?
              </label>
              <textarea
                id="question"
                v-model="form.question"
                maxlength="255"
                rows="3"
                placeholder="e.g. What should I focus on in my career this month?"
                class="w-full resize-none rounded-xl border border-slate-200 bg-white p-4 text-slate-900 transition-all duration-300 outline-none placeholder:text-slate-400 focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-100 dark:placeholder:text-slate-600"
              ></textarea>
              <div class="mt-2 flex items-start justify-between px-1">
                <div class="min-h-5 pr-4">
                  <span
                    v-if="form.errors.question"
                    class="animate-in text-xs font-medium text-red-600 fade-in slide-in-from-left-1 dark:text-red-400"
                  >
                    {{ form.errors.question }}
                  </span>
                </div>

                <div
                  class="text-xs font-medium whitespace-nowrap transition-colors"
                  :class="
                    form.question.length > 236
                      ? 'text-amber-500'
                      : 'text-slate-400 dark:text-slate-500'
                  "
                >
                  {{ form.question.length }}&nbsp;/&nbsp;255
                </div>
              </div>
            </div>

            <button
              @click="cast"
              :disabled="form.processing || form.question.length < 3"
              class="w-full rounded-xl bg-amber-600 py-4 text-lg font-bold text-white shadow-lg shadow-amber-900/20 transition-all hover:bg-amber-500 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50 disabled:grayscale-[0.5] dark:shadow-amber-900/40"
            >
              Cast Coins
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
