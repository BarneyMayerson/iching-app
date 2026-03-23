<script setup lang="ts">
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import { useTranslate } from '@/composables/useTranslate';
import { login, register } from '@/routes';
import { dashboard } from '@/routes/cabinet';
import { Head, Link } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import {
  BarChart2,
  BookOpen,
  FileText,
  Moon,
  Sparkles,
  Sun,
} from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

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

const brokenLines = ref<number[]>([]);
const changingLines = ref<number[]>([]);

const generateRandomHexagram = () => {
  // Генерируем от 0 до 4 прерывистых линий (Инь)
  brokenLines.value = Array.from({ length: 6 }, (_, i) => i + 1)
    .sort(() => 0.5 - Math.random())
    .slice(0, Math.floor(Math.random() * 5));

  // Генерируем от 0 до 3 "активных" линий (Янтарных)
  changingLines.value = Array.from({ length: 6 }, (_, i) => i + 1)
    .sort(() => 0.5 - Math.random())
    .slice(0, Math.floor(Math.random() * 4));
};

const isVisible = ref(false);
const isFlipping = ref(false);

const ritualSection = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | null = null;

onMounted(() => {
  generateRandomHexagram(); // Первый запуск

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
          isFlipping.value = true;

          // Завершаем анимацию монет после отрисовки 6-й линии
          // (6 линий * 150ms задержки) + время самой анимации линии (~700ms)
          setTimeout(() => {
            isFlipping.value = false;
          }, 1600);
        } else {
          // Сбрасываем состояние
          isVisible.value = false;
          isFlipping.value = false;
          // Генерируем новую комбинацию для следующего раза,
          // когда пользователь вернется к блоку
          generateRandomHexagram();
        }
      });
    },
    { threshold: 0.3 },
  );

  if (ritualSection.value) observer.observe(ritualSection.value);
});

onUnmounted(() => observer?.disconnect());

const scrollToSacredProcess = () => {
  const element = document.getElementById('sacred');
  if (element) {
    element.scrollIntoView({ behavior: 'smooth' });
  }
};
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
        <div
          class="flex flex-col items-center justify-center gap-4 sm:flex-row"
        >
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

    <section
      id="features"
      class="bg-white px-6 py-24 transition-colors duration-500 lg:px-12 dark:bg-slate-900/30"
    >
      <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
          <h2
            class="font-serif text-4xl font-bold text-slate-950 dark:text-white"
          >
            {{ __('Everything you need for') }}
            <span class="text-amber-600">{{ __('deep reflection') }}</span>
          </h2>
          <p class="mt-4 text-slate-600 dark:text-slate-400">
            {{
              __('Combining millenia-old tradition with modern data analysis.')
            }}
          </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/50 dark:hover:border-amber-500/50 dark:hover:bg-slate-800"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
            >
              <BookOpen class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold dark:text-slate-100">
              {{ __('Personal Archives') }}
            </h3>
            <p
              class="text-sm leading-relaxed text-slate-500 dark:text-slate-400"
            >
              {{
                __(
                  'Keep a complete history of your inquiries. Every hexagram and line is saved with your personal context and dates.',
                )
              }}
            </p>
          </div>

          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/50 dark:hover:border-amber-500/50 dark:hover:bg-slate-800"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
            >
              <FileText class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold dark:text-slate-100">
              {{ __('Beautiful PDF Reports') }}
            </h3>
            <p
              class="text-sm leading-relaxed text-slate-500 dark:text-slate-400"
            >
              {{
                __(
                  'Generate professional, printable reports for your readings, including the visual hexagram and the Path of the Sage.',
                )
              }}
            </p>
          </div>

          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl dark:border-slate-800 dark:bg-slate-900/50 dark:hover:border-amber-500/50 dark:hover:bg-slate-800"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110 dark:bg-slate-900"
            >
              <BarChart2 class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold dark:text-slate-100">
              {{ __('Insightful Statistics') }}
            </h3>
            <p
              class="text-sm leading-relaxed text-slate-500 dark:text-slate-400"
            >
              {{
                __(
                  'Visualize your energy balance. Track which hexagrams appear most frequently in your life through the Inquiry Timeline.',
                )
              }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <section
      ref="ritualSection"
      id="sacred"
      class="overflow-hidden bg-slate-950 py-24 text-white"
    >
      <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid items-center gap-16 lg:grid-cols-2">
          <div class="max-w-xl">
            <span
              class="mb-4 inline-block text-xs font-black tracking-[0.3em] text-amber-500 uppercase"
            >
              {{ __('The Sacred Process') }}
            </span>
            <h2
              class="mb-8 font-serif text-4xl leading-tight font-bold lg:text-5xl"
            >
              {{ __('A bridge between') }} <br />
              <span class="text-amber-200 italic">
                {{ __('chance and destiny.') }}
              </span>
            </h2>
            <p class="mb-8 text-lg text-slate-400">
              {{
                __(
                  'The ritual begins with a simple question. As the virtual coins fall, the algorithm calculates the probabilities just as the ancient masters did with yarrow stalks for centuries.',
                )
              }}
            </p>

            <ul class="space-y-4 text-slate-300">
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                {{ __('Three coins cast six times') }}
              </li>
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                {{ __('Dynamic lines reveal the flow of energy') }}
              </li>
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                {{ __('Stable truths meet inevitable transformations') }}
              </li>
            </ul>
          </div>

          <div class="relative flex justify-center py-12">
            <div class="absolute inset-0 bg-amber-500/10 blur-[120px]"></div>

            <div class="relative z-10 flex flex-col items-center gap-12">
              <div class="flex gap-6">
                <div
                  v-for="i in 3"
                  :key="i"
                  class="flex size-16 items-center justify-center rounded-full border-2 border-amber-500/50 bg-slate-900 text-2xl font-bold text-amber-500 transition-all duration-700"
                  :class="{
                    'animate-flip': isFlipping,
                    'scale-90 opacity-40': !isFlipping,
                  }"
                  :style="{ animationDelay: i * 0.1 + 's' }"
                >
                  <span>乾</span>
                </div>
              </div>

              <div class="flex flex-col gap-4">
                <div
                  v-for="n in 6"
                  :key="n"
                  class="h-3 w-64 overflow-hidden transition-all duration-700 ease-out"
                  :style="{
                    opacity: isVisible ? 0.3 + n * 0.12 : 0,
                    transform: isVisible ? 'translateY(0)' : 'translateY(40px)',
                    transitionDelay: n * 150 + 'ms',
                  }"
                >
                  <div
                    v-if="!brokenLines.includes(n)"
                    class="h-full w-full rounded-sm shadow-[0_0_15px_rgba(245,158,11,0.1)] transition-colors duration-1000"
                    :class="
                      changingLines.includes(n)
                        ? 'bg-amber-500 shadow-amber-500/20'
                        : 'bg-slate-700'
                    "
                  ></div>

                  <div v-else class="flex h-full w-full justify-between">
                    <div
                      class="h-full w-[45%] rounded-sm transition-colors duration-1000"
                      :class="
                        changingLines.includes(n)
                          ? 'bg-amber-500 shadow-amber-500/20'
                          : 'bg-slate-700'
                      "
                    ></div>
                    <div
                      class="h-full w-[45%] rounded-sm transition-colors duration-1000"
                      :class="
                        changingLines.includes(n)
                          ? 'bg-amber-500 shadow-amber-500/20'
                          : 'bg-slate-700'
                      "
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center">
      <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <p class="text-sm text-slate-500">
          © 2026 I-Ching Cabinet.
          {{ __('Built with precision for the modern sage.') }}
        </p>
        <div
          class="mt-6 flex justify-center gap-6 text-xs font-bold tracking-widest text-slate-400 uppercase"
        >
          <a href="#" class="hover:text-amber-500">{{ __('Privacy') }}</a>
          <a href="#" class="hover:text-amber-500">{{ __('Terms') }}</a>
          <a href="#" class="hover:text-amber-500">{{ __('Guide') }}</a>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
@keyframes coin-flip {
  0% {
    transform: translateY(0) rotateY(0);
  }
  50% {
    transform: translateY(-30px) rotateY(180deg);
  }
  100% {
    transform: translateY(0) rotateY(360deg);
  }
}

.animate-flip {
  animation: coin-flip 0.8s ease-in-out infinite;
}

.line-draw {
  stroke-dasharray: 100;
  stroke-dashoffset: 100;
  animation: draw 1.5s forwards;
}

@keyframes draw {
  to {
    stroke-dashoffset: 0;
  }
}
</style>
