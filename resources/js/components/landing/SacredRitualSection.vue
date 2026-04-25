<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import CoinsVisual from './CoinsVisual.vue';
import HexagramVisual from './HexagramVisual.vue';

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
</script>

<template>
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
            <CoinsVisual :is-flipping />
            <HexagramVisual :brokenLines :changingLines :is-visible />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
