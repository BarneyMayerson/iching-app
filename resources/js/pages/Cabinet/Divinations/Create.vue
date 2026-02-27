<script setup lang="ts">
import Coin from '@/components/IChing/Coin.vue';
import HexagramLine from '@/components/IChing/HexagramLine.vue'; // Твой обновленный компонент
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/cabinet/divinations';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Coins, Loader2, Sparkles, Zap } from 'lucide-vue-next';
import { ref } from 'vue';

// Состояния режимов
type Mode = 'classic' | 'quick';
const mode = ref<Mode>('classic');

enum CastingPhase {
  Initial = 'initial',
  CastingSingleCoin = 'casting_single_coin',
  ShowingSingleCoin = 'showing_single_coin',
  CastingThreeCoins = 'casting_three_coins',
  ShowingThreeCoins = 'showing_three_coins',
  Finished = 'finished',
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Divinations', href: index().url },
  { title: 'New Casting', href: '#' },
];

const castingPhase = ref<CastingPhase>(CastingPhase.Initial);
const currentLineCoinResults = ref<number[]>([]);
const hexagramCoinResults = ref<number[]>([]);
const lineCount = ref(0);
const singleCoinResult = ref<number | null>(null);
const isCasting = ref(false);
const isReadyToSave = ref(false);

const form = useForm({
  question: '',
  coin_results: [] as number[],
});

const delay = (ms: number) => new Promise((res) => setTimeout(res, ms));

const castQuick = async () => {
  if (isCasting.value || form.question.length < 3) return;
  isCasting.value = true;

  await delay(1500);

  const results = [];
  for (let i = 0; i < 6; i++) {
    // Имитируем 1-й этап
    const s1 = Math.random() > 0.5 ? 2 : 3;
    // Имитируем 2-й этап
    const s2 = Array.from({ length: 3 }, () => (Math.random() > 0.5 ? 2 : 3));

    results.push(calculateLineValue(s1, s2));
  }

  hexagramCoinResults.value = results;
  lineCount.value = 6;
  form.coin_results = results;
  isReadyToSave.value = true;
};

const castPerLine = async () => {
  if (isCasting.value || form.question.length < 3) return;
  isCasting.value = true;

  // --- ЭТАП 1: Полярность ---
  singleCoinResult.value = null;
  castingPhase.value = CastingPhase.CastingSingleCoin;
  await delay(1000);

  const stage1 = Math.random() > 0.5 ? 2 : 3;
  singleCoinResult.value = stage1;
  castingPhase.value = CastingPhase.ShowingSingleCoin;
  await delay(1000);

  // --- ЭТАП 2: Возраст ---
  castingPhase.value = CastingPhase.CastingThreeCoins;
  await delay(1000);

  const stage2 = Array.from({ length: 3 }, () => (Math.random() > 0.5 ? 2 : 3));
  currentLineCoinResults.value = stage2;
  castingPhase.value = CastingPhase.ShowingThreeCoins;
  await delay(1500);

  // ВЫЧИСЛЕНИЕ по новому алгоритму
  const lineValue = calculateLineValue(stage1, stage2);

  hexagramCoinResults.value.push(lineValue);
  lineCount.value++;

  if (lineCount.value === 6) {
    form.coin_results = hexagramCoinResults.value;
    isReadyToSave.value = true;
  } else {
    castingPhase.value = CastingPhase.Initial;
    isCasting.value = false;
  }
};

const calculateLineValue = (
  firstStage: number,
  threeCoins: number[],
): number => {
  console.log('Calculating line value with:', { firstStage, threeCoins });
  // Считаем количество решек (значение 2) во втором броске
  const tailsCount = threeCoins.filter((v: number) => v === 2).length;

  if (firstStage === 2) {
    // Была Инь на 1-м этапе
    // Старый Инь (6) только если три решки (2, 2, 2)
    return tailsCount === 3 ? 6 : 8; // Иначе Молодой Инь (8)
  } else {
    // Был Ян на 1-м этапе
    // Старый Ян (9) только если ровно две решки (например 2, 2, 3)
    return tailsCount === 2 ? 9 : 7; // Иначе Молодой Ян (7)
  }
};

const saveDivination = () => {
  form.coin_results = hexagramCoinResults.value;
  form.post(store().url);
};

const resetCasting = () => {
  lineCount.value = 0;
  hexagramCoinResults.value = [];
  isReadyToSave.value = false;
  isCasting.value = false;
  castingPhase.value = CastingPhase.Initial;
};
</script>

<template>
  <Head title="Consult the Oracle" />

  <AppLayout :breadcrumbs>
    <div
      class="mx-auto grid flex-1 grid-cols-1 gap-8 p-4 lg:grid-cols-3 lg:p-8"
    >
      <div class="space-y-6 lg:col-span-2">
        <div
          class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:p-10 dark:border-slate-800 dark:bg-slate-900/50"
        >
          <div class="mb-10 space-y-4">
            <div class="flex items-center justify-between">
              <h2
                class="font-serif text-2xl font-bold text-slate-900 dark:text-slate-100"
              >
                Your Inquiry
              </h2>

              <div
                v-if="lineCount === 0 && !isCasting"
                class="flex rounded-lg bg-slate-100 p-1 dark:bg-slate-800"
              >
                <button
                  @click="mode = 'classic'"
                  :class="
                    mode === 'classic'
                      ? 'bg-white shadow dark:bg-slate-700'
                      : 'text-slate-500'
                  "
                  class="flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                >
                  <Sparkles class="size-3" /> Classic
                </button>
                <button
                  @click="mode = 'quick'"
                  :class="
                    mode === 'quick'
                      ? 'bg-white shadow dark:bg-slate-700'
                      : 'text-slate-500'
                  "
                  class="flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-medium transition-all"
                >
                  <Zap class="size-3" /> Quick
                </button>
              </div>
            </div>

            <div
              v-if="isReadyToSave"
              class="relative animate-in duration-700 fade-in slide-in-from-left-4"
            >
              <div
                class="absolute top-0 -left-2 font-serif text-4xl text-amber-500/20"
              >
                “
              </div>
              <p
                class="px-4 py-2 font-serif text-xl text-slate-700 italic dark:text-slate-300"
              >
                {{ form.question }}
              </p>
            </div>

            <textarea
              v-else
              v-model="form.question"
              :disabled="lineCount > 0 || isCasting"
              placeholder="Enter your question to the Book of Changes..."
              class="w-full resize-none rounded-2xl border border-slate-200 bg-slate-50/50 p-5 text-lg transition-all outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-slate-800 dark:bg-slate-950/50 dark:text-slate-100"
              rows="3"
            ></textarea>
          </div>

          <div class="transition-all duration-500">
            <div
              v-if="isReadyToSave"
              class="flex animate-in flex-col items-center space-y-8 border-t border-slate-100 pt-10 duration-500 zoom-in dark:border-slate-800"
            >
              <div
                class="flex size-20 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30"
              >
                <Sparkles class="size-10" />
              </div>

              <div class="space-y-2 text-center">
                <h3 class="font-serif text-2xl font-bold">
                  The pattern is complete
                </h3>
                <p class="text-slate-500">
                  Would you like to record this divination in your history?
                </p>
              </div>

              <div
                class="flex w-full flex-col justify-center gap-4 sm:flex-row"
              >
                <button
                  @click="saveDivination"
                  :disabled="form.processing"
                  class="flex items-center justify-center gap-2 rounded-full bg-amber-600 px-10 py-4 text-lg font-bold text-white shadow-lg shadow-amber-900/20 transition-all hover:bg-amber-500 active:scale-95 disabled:opacity-50 dark:bg-amber-500 dark:hover:bg-amber-600"
                >
                  <Loader2 v-if="form.processing" class="size-5 animate-spin" />
                  Save & Interpret
                </button>

                <button
                  @click="resetCasting"
                  :disabled="form.processing"
                  class="rounded-full bg-slate-200 px-10 py-4 text-lg font-bold text-slate-700 transition-all hover:bg-slate-300 active:scale-95 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                  Start Over
                </button>
              </div>
            </div>

            <div
              v-else
              class="flex min-h-80 flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 dark:border-slate-800 dark:bg-slate-900/80"
            >
              <div v-if="mode === 'quick'" class="space-y-6 text-center">
                <div v-if="isCasting" class="space-y-4">
                  <Loader2
                    class="mx-auto size-12 animate-spin text-amber-500"
                  />
                  <p
                    class="animate-pulse font-serif text-xl text-amber-600 italic"
                  >
                    The Oracle is synthesizing the patterns...
                  </p>
                </div>
                <div v-else class="space-y-4">
                  <div
                    class="mx-auto flex size-20 items-center justify-center rounded-full bg-amber-600 text-amber-50 dark:bg-amber-500"
                  >
                    <Coins class="size-10" />
                  </div>
                  <p class="font-serif text-slate-500 italic">
                    All six lines will be cast simultaneously.
                  </p>
                  <button
                    @click="castQuick"
                    :disabled="form.question.length < 3"
                    class="rounded-full bg-amber-600 px-10 py-4 font-bold text-white shadow-lg shadow-amber-900/20 transition-all hover:bg-amber-500 active:scale-95 disabled:opacity-30 dark:bg-amber-500 dark:hover:bg-amber-600"
                  >
                    Instant Casting
                  </button>
                </div>
              </div>

              <div v-else class="w-full max-w-md text-center">
                <div class="flex flex-col items-center">
                  <p
                    v-if="castingPhase !== CastingPhase.Initial"
                    class="mb-8 text-xs font-bold tracking-widest text-slate-600 uppercase dark:text-slate-400"
                  >
                    {{
                      castingPhase.includes('single')
                        ? 'Phase I: Polarity'
                        : 'Phase II: Maturity'
                    }}
                  </p>

                  <div
                    v-if="castingPhase.includes('single')"
                    class="flex flex-col items-center"
                  >
                    <Coin
                      :is-spinning="
                        castingPhase === CastingPhase.CastingSingleCoin
                      "
                      :result="singleCoinResult"
                    />
                  </div>

                  <div
                    v-else-if="castingPhase.includes('three')"
                    class="flex justify-center gap-6"
                  >
                    <Coin
                      v-for="(res, idx) in castingPhase ===
                      CastingPhase.CastingThreeCoins
                        ? [null, null, null]
                        : currentLineCoinResults"
                      :key="idx"
                      :is-spinning="
                        castingPhase === CastingPhase.CastingThreeCoins
                      "
                      :result="res"
                    />
                  </div>

                  <div v-else class="h-0"></div>

                  <div
                    class="mt-4 h-6 font-bold text-slate-600 transition-opacity duration-300 dark:text-slate-400"
                    :class="singleCoinResult ? 'opacity-100' : 'opacity-0'"
                  >
                    {{
                      singleCoinResult === 3
                        ? 'YANG'
                        : singleCoinResult === 2
                          ? 'YIN'
                          : ''
                    }}
                  </div>
                </div>

                <div
                  v-if="castingPhase === CastingPhase.Initial"
                  class="mt-8 space-y-6"
                >
                  <div
                    class="mx-auto flex size-16 items-center justify-center rounded-full bg-slate-200 text-slate-400 dark:bg-slate-800"
                  >
                    <Coins class="size-8" />
                  </div>
                  <p class="text-slate-600 dark:text-slate-400">
                    Line {{ lineCount + 1 }} of 6
                  </p>
                  <button
                    @click="castPerLine"
                    :disabled="form.question.length < 3 || isCasting"
                    class="rounded-full bg-slate-900 px-10 py-4 font-bold text-white shadow-lg transition-all hover:bg-slate-800 active:scale-95 disabled:opacity-30 dark:bg-amber-500 dark:hover:bg-amber-600"
                  >
                    Cast Line {{ lineCount + 1 }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        class="flex h-fit flex-col items-center rounded-3xl border border-slate-200 bg-white p-8 shadow-sm lg:sticky lg:top-8 dark:border-slate-800 dark:bg-slate-900/50"
      >
        <h3
          class="mb-10 text-xs font-bold tracking-[0.2em] text-slate-400 uppercase"
        >
          Emerging Hexagram
        </h3>

        <div class="flex w-full max-w-45 flex-col-reverse gap-4">
          <HexagramLine
            v-for="(val, idx) in hexagramCoinResults"
            :key="idx"
            :value="val"
            class="animate-in duration-700 fade-in slide-in-from-bottom-4"
          />

          <div
            v-for="i in 6 - hexagramCoinResults.length"
            :key="'placeholder-' + i"
            class="h-8 w-full border-b border-slate-100 dark:border-slate-800/30"
          ></div>
        </div>

        <div class="mt-12 text-center">
          <div class="text-4xl font-light text-slate-300 dark:text-slate-700">
            {{ lineCount }}<span class="text-xl">/6</span>
          </div>
          <p
            class="mt-2 text-[10px] font-bold tracking-widest text-slate-400 uppercase dark:text-slate-500"
          >
            Lines Completed
          </p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
