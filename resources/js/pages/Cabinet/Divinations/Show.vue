<script setup lang="ts">
import HexagramView from '@/components/IChing/HexagramView.vue';
import InterpretationBlock from '@/components/IChing/InterpretationBlock.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import TransformationDivider from '@/components/IChing/TransformationDivider.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/cabinet/divinations';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Sparkles, Zap } from 'lucide-vue-next';

interface Line {
  position: number;
  meaning: string;
}

interface Hexagram {
  binary: string;
  number: number;
  character: string;
  names: string[];
  origins: {
    chinese: string;
    pinyin: string;
  };
  judgment: string;
  image?: string;
  lines: Line[];
}

interface Reading {
  id: number;
  question: string;
  date: string;
  time: string;
  binary: string;
  hexagram: Hexagram;
  relative_date: string;
  coin_results: number[];
}

const props = defineProps<{
  reading: Reading;
  hexagram: Hexagram;
  secondary_hexagram?: Hexagram;
  changing_lines: number[];
}>();

const breadcrumbs = [
  { title: 'Divinations', href: index().url },
  { title: `Reading #${props.reading.id}`, href: '#' },
];
</script>

<template>
  <Head :title="'Hexagram ' + hexagram.number + ' - Interpretation'" />

  <AppLayout :breadcrumbs>
    <div class="mx-auto max-w-4xl flex-1 p-6 lg:p-12">
      <div class="mb-8">
        <Link
          :href="index().url"
          class="group inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition-colors hover:text-amber-600 dark:text-slate-400 dark:hover:text-amber-500"
        >
          <ArrowLeft
            class="size-4 transition-transform group-hover:-translate-x-1"
          />
          Back to History
        </Link>
      </div>

      <ReadingHeader
        class="mb-16 text-center"
        :question="reading.question"
        :date="reading.date"
        :time="reading.time"
        :relative_date="reading.relative_date"
      />

      <section class="space-y-10">
        <div
          class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900/50"
        >
          <div
            class="mb-6 grid gap-8 border-b border-slate-50 lg:mb-8 lg:grid-cols-2 dark:border-slate-800"
          >
            <div class="text-center sm:text-left">
              <span
                class="mb-2 inline-block rounded-full bg-amber-100 px-3 py-1 text-[10px] font-bold tracking-wider text-amber-700 uppercase dark:bg-amber-900/30 dark:text-amber-400"
              >
                Current Situation
              </span>
              <h2
                class="font-serif text-4xl font-bold text-slate-900 dark:text-slate-100"
              >
                {{ hexagram.number }}. {{ hexagram.names[0] }}
              </h2>
              <div class="mt-3 space-y-1">
                <p
                  v-for="(name, index) in hexagram.names.slice(1)"
                  :key="'alt-name-' + index"
                  class="font-serif text-lg text-slate-500 dark:text-slate-400"
                >
                  {{ name }}
                </p>

                <div
                  class="flex flex-wrap justify-center gap-x-4 gap-y-1 sm:justify-start"
                >
                  <p
                    v-if="hexagram.origins.chinese"
                    class="text-2xl text-slate-400 dark:text-slate-500"
                  >
                    {{ hexagram.origins.chinese }}
                  </p>
                  <p
                    v-if="hexagram.origins.pinyin"
                    class="font-serif text-lg text-slate-400 italic dark:text-slate-500"
                  >
                    {{ hexagram.origins.pinyin }}
                  </p>
                </div>
              </div>
            </div>
            <HexagramView
              :coin-results="reading.coin_results"
              :hexagram="hexagram"
            />
          </div>

          <div class="grid gap-8 lg:grid-cols-2">
            <InterpretationBlock
              title="The Judgment"
              :text="hexagram.judgment"
            />
            <InterpretationBlock
              title="The Image"
              :text="hexagram.image ?? ''"
            />
          </div>
        </div>

        <div class="space-y-6">
          <div class="flex items-center gap-3 px-4">
            <Zap class="size-5 text-amber-500" />
            <h3 class="font-serif text-2xl font-bold">Line Guidance</h3>
          </div>

          <div class="space-y-4">
            <div
              v-for="line in hexagram.lines"
              :key="line.position"
              :class="[
                'group relative rounded-2xl p-6 transition-all duration-500',
                changing_lines.includes(line.position)
                  ? 'border-l-4 border-amber-500 bg-amber-50/50 shadow-sm dark:bg-amber-900/10'
                  : 'border border-slate-100 bg-white opacity-60 dark:border-slate-800 dark:bg-slate-900/20',
              ]"
            >
              <div class="mb-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                  <span
                    :class="[
                      'flex size-7 items-center justify-center rounded-full text-xs font-bold transition-colors',
                      changing_lines.includes(line.position)
                        ? 'bg-amber-600 text-white'
                        : 'bg-slate-200 text-slate-500 dark:bg-slate-800',
                    ]"
                  >
                    {{ line.position }}
                  </span>
                  <span
                    class="text-[10px] font-black tracking-[0.2em] uppercase"
                    :class="
                      changing_lines.includes(line.position)
                        ? 'text-amber-600'
                        : 'text-slate-400'
                    "
                  >
                    {{
                      changing_lines.includes(line.position)
                        ? 'Changing'
                        : 'Stable'
                    }}
                  </span>
                </div>
              </div>
              <p
                class="text-sm leading-relaxed text-slate-700 dark:text-slate-300"
              >
                {{ line.meaning }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <div v-if="secondary_hexagram" class="my-20 flex flex-col items-center">
        <TransformationDivider />
      </div>

      <section
        v-if="secondary_hexagram"
        class="animate-in space-y-8 duration-1000 slide-in-from-bottom-10 fade-in"
      >
        <div
          class="rounded-3xl border border-dashed border-slate-300 bg-slate-50/50 p-8 dark:border-slate-700 dark:bg-slate-900/30"
        >
          <div
            class="mb-6 grid gap-8 border-b border-slate-50 lg:mb-8 lg:grid-cols-2 dark:border-slate-800"
          >
            <div class="text-center sm:text-left">
              <span
                class="mb-2 inline-block rounded-full bg-slate-900 px-3 py-1 text-[10px] font-bold tracking-wider text-white uppercase dark:bg-slate-100 dark:text-slate-900"
              >
                Future Outcome
              </span>
              <h2
                class="font-serif text-4xl font-bold text-slate-900 dark:text-slate-100"
              >
                {{ secondary_hexagram.number }}.
                {{ secondary_hexagram.names[0] }}
              </h2>
              <div class="mt-3 space-y-1">
                <p
                  v-for="(name, index) in secondary_hexagram.names.slice(1)"
                  :key="'alt-name-' + index"
                  class="font-serif text-lg text-slate-500 dark:text-slate-400"
                >
                  {{ name }}
                </p>

                <div
                  class="flex flex-wrap justify-center gap-x-4 gap-y-1 sm:justify-start"
                >
                  <p
                    v-if="secondary_hexagram.origins.chinese"
                    class="text-2xl text-slate-400 dark:text-slate-500"
                  >
                    {{ secondary_hexagram.origins.chinese }}
                  </p>
                  <p
                    v-if="hexagram.origins.pinyin"
                    class="font-serif text-lg text-slate-400 italic dark:text-slate-500"
                  >
                    {{ secondary_hexagram.origins.pinyin }}
                  </p>
                </div>
              </div>
            </div>
            <HexagramView
              :coin-results="
                reading.coin_results.map((v: number) =>
                  v === 6 ? 7 : v === 9 ? 8 : v,
                )
              "
              :hexagram="secondary_hexagram"
            />
          </div>

          <div class="grid gap-8 lg:grid-cols-2">
            <InterpretationBlock
              title="The Transformation"
              :text="secondary_hexagram.judgment"
              variant="secondary"
            />
            <InterpretationBlock
              title="The Image"
              :text="secondary_hexagram.image ?? ''"
              variant="secondary"
            />
          </div>
        </div>

        <div
          class="rounded-2xl bg-amber-500 p-8 text-center text-white shadow-xl shadow-amber-900/20"
        >
          <Sparkles class="mx-auto mb-4 size-8" />
          <p class="font-serif text-xl italic">
            Focus on the changing lines as the key to your inquiry. They
            represent the path from where you are to where you are going.
          </p>
        </div>
      </section>

      <div
        v-else
        class="mt-20 rounded-3xl bg-slate-50 p-12 text-center dark:bg-slate-900/20"
      >
        <p class="font-serif text-xl text-slate-500">
          The Oracle indicates a period of stability. The pattern is firm,
          suggesting that the current essence of the hexagram is the full answer
          to your question.
        </p>
      </div>
    </div>
  </AppLayout>
</template>
