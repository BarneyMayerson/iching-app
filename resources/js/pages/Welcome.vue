<script setup lang="ts">
import { login, register } from '@/routes';
import { dashboard } from '@/routes/cabinet';
import { Head, Link } from '@inertiajs/vue3';
import { BarChart2, BookOpen, FileText, Sparkles } from 'lucide-vue-next';
import { onMounted, onUnmounted, ref } from 'vue';

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

const isVisible = ref(false);
const isFlipping = ref(false);

const ritualSection = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | null = null;

onMounted(() => {
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
          // Сбрасываем состояние, чтобы при возврате к блоку всё повторилось
          isVisible.value = false;
          isFlipping.value = false;
        }
      });
    },
    { threshold: 0.3 },
  );

  if (ritualSection.value) observer.observe(ritualSection.value);
});

onUnmounted(() => observer?.disconnect());
</script>

<template>
  <Head title="I-Ching Cabinet — Your Digital Oracle" />

  <div class="min-h-screen bg-slate-50 text-slate-900 selection:bg-amber-200">
    <nav class="flex items-center justify-between p-6 lg:px-12">
      <div class="flex items-center gap-2">
        <div
          class="flex size-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg"
        >
          <Sparkles class="size-6" />
        </div>
        <span class="font-serif text-xl font-bold tracking-tight"
          >I-Ching Cabinet</span
        >
      </div>

      <div v-if="canLogin" class="flex items-center gap-2 sm:gap-4">
        <Link
          v-if="$page.props.auth.user"
          :href="dashboard().url"
          class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100"
        >
          Go to Cabinet
        </Link>
        <template v-else>
          <Link
            :href="login().url"
            class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100"
          >
            Sign In
          </Link>
          <Link
            :href="register().url"
            class="rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:bg-slate-800 hover:shadow-lg active:scale-95"
          >
            Start for free
          </Link>
        </template>
      </div>
    </nav>

    <header class="relative px-6 pt-20 pb-32 text-center lg:px-12">
      <div class="mx-auto max-w-3xl">
        <span
          class="mb-4 inline-block rounded-full bg-amber-100 px-4 py-1.5 text-xs font-black tracking-[0.2em] text-amber-700 uppercase"
        >
          Ancient Wisdom • Modern Interface
        </span>
        <h1
          class="mb-8 font-serif text-6xl leading-[1.1] font-bold tracking-tight text-slate-950 lg:text-7xl"
        >
          Find clarity in the
          <span class="text-amber-600 italic">flow of change.</span>
        </h1>
        <p class="mb-10 text-lg leading-relaxed text-slate-600">
          A sophisticated digital space for I-Ching consultations. Track your
          journey, analyze patterns, and archive the wisdom of the Oracle in
          beautiful PDF reports.
        </p>
        <div
          class="flex flex-col items-center justify-center gap-4 sm:flex-row"
        >
          <Link
            :href="register().url"
            class="w-full rounded-2xl bg-slate-900 px-8 py-4 text-lg font-bold text-white shadow-xl shadow-slate-200 transition-all hover:bg-slate-800 sm:w-auto"
          >
            Begin Consultation
          </Link>
          <a
            href="#features"
            class="text-sm font-bold text-slate-500 hover:text-slate-900"
          >
            Explore Features ↓
          </a>
        </div>
      </div>
    </header>

    <section id="features" class="bg-white px-6 py-24 lg:px-12">
      <div class="mx-auto max-w-7xl">
        <div class="mb-16 text-center">
          <h2 class="font-serif text-4xl font-bold text-slate-950">
            Everything you need for
            <span class="text-amber-600">deep reflection</span>
          </h2>
          <p class="mt-4 text-slate-600">
            Combining millenia-old tradition with modern data analysis.
          </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110"
            >
              <BookOpen class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold">Personal Archives</h3>
            <p class="text-sm leading-relaxed text-slate-500">
              Keep a complete history of your inquiries. Every hexagram and line
              is saved with your personal context and dates.
            </p>
          </div>

          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110"
            >
              <FileText class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold">
              Beautiful PDF Reports
            </h3>
            <p class="text-sm leading-relaxed text-slate-500">
              Generate professional, printable reports for your readings,
              including the visual hexagram and the Path of the Sage.
            </p>
          </div>

          <div
            class="group rounded-3xl border border-slate-100 bg-slate-50/50 p-8 transition-all hover:border-amber-200 hover:bg-white hover:shadow-xl"
          >
            <div
              class="mb-6 flex size-12 items-center justify-center rounded-2xl bg-white shadow-sm transition-transform group-hover:scale-110"
            >
              <BarChart2 class="size-6 text-amber-600" />
            </div>
            <h3 class="mb-3 font-serif text-xl font-bold">
              Insightful Statistics
            </h3>
            <p class="text-sm leading-relaxed text-slate-500">
              Visualize your energy balance. Track which hexagrams appear most
              frequently in your life through the Inquiry Timeline.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section
      ref="ritualSection"
      class="overflow-hidden bg-slate-950 py-24 text-white"
    >
      <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid items-center gap-16 lg:grid-cols-2">
          <div class="max-w-xl">
            <span
              class="mb-4 inline-block text-xs font-black tracking-[0.3em] text-amber-500 uppercase"
            >
              The Sacred Process
            </span>
            <h2
              class="mb-8 font-serif text-4xl leading-tight font-bold lg:text-5xl"
            >
              A bridge between <br />
              <span class="text-amber-200 italic">chance and destiny.</span>
            </h2>
            <p class="mb-8 text-lg text-slate-400">
              The ritual begins with a simple question. As the virtual coins
              fall, the algorithm calculates the probabilities just as the
              ancient masters did with yarrow stalks for centuries.
            </p>

            <ul class="space-y-4 text-slate-300">
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                Three coins cast six times
              </li>
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                Dynamic lines reveal the flow of energy
              </li>
              <li class="flex items-center gap-3">
                <div class="size-1.5 rounded-full bg-amber-500"></div>
                Stable truths meet inevitable transformations
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
                    transform: isVisible ? 'translateY(0)' : 'translateY(20px)',
                    transitionDelay: n * 150 + 'ms',
                  }"
                >
                  <div
                    v-if="![2, 3, 5].includes(n)"
                    class="h-full w-full rounded-sm shadow-[0_0_15px_rgba(245,158,11,0.1)]"
                    :class="n > 3 ? 'bg-amber-500' : 'bg-slate-700'"
                  ></div>

                  <div v-else class="flex h-full w-full justify-between">
                    <div
                      class="h-full w-[45%] rounded-sm"
                      :class="n > 3 ? 'bg-amber-500' : 'bg-slate-700'"
                    ></div>
                    <div
                      class="h-full w-[45%] rounded-sm"
                      :class="n > 3 ? 'bg-amber-500' : 'bg-slate-700'"
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
          © 2026 I-Ching Cabinet. Built with precision for the modern sage.
        </p>
        <div
          class="mt-6 flex justify-center gap-6 text-xs font-bold tracking-widest text-slate-400 uppercase"
        >
          <a href="#" class="hover:text-amber-500">Privacy</a>
          <a href="#" class="hover:text-amber-500">Terms</a>
          <a href="#" class="hover:text-amber-500">Guide</a>
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
