<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/dashboard/divinations';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
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

const cast = () => {
  isCasting.value = true;

  setTimeout(() => {
    router.post(
      store(),
      {},
      {
        onFinish: () => (isCasting.value = false),
        preserveState: true,
      },
    );
  }, 2000);
};
</script>

<template>
  <Head title="Consult the Oracle" />

  <AppLayout :breadcrumbs>
    <div class="flex h-full flex-1 items-center justify-center p-4">
      <div v-if="result" class="animate-in duration-1000 fade-in zoom-in">
        <HexagramView
          :coin-results="result.coin_results"
          :hexagram="result.hexagram"
        />
        <div class="mt-12 flex flex-col items-center gap-4">
          <button
            @click="router.get(index())"
            class="text-sm font-medium text-amber-600 transition-colors hover:text-amber-500"
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
          <p class="animate-pulse font-serif text-2xl text-amber-500 italic">
            The Oracle is thinking...
          </p>
        </div>

        <div v-else class="space-y-8">
          <div
            class="mx-auto flex size-24 items-center justify-center rounded-full bg-amber-500/10 text-amber-500"
          >
            <Coins class="size-12" />
          </div>
          <div class="space-y-2">
            <h2 class="font-serif text-3xl font-bold text-slate-100">
              Consult the Oracle
            </h2>
            <p class="font-serif text-slate-400 italic">
              "The superior man promotes his cause by the use of the oracle."
            </p>
          </div>

          <button
            @click="cast"
            class="w-full rounded-xl bg-amber-600 py-4 text-lg font-bold text-white shadow-lg shadow-amber-900/30 transition-all hover:bg-amber-500 active:scale-95"
          >
            Cast Coins
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
