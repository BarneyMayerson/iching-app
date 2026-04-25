<script setup lang="ts">
import FeatureCard from '@/components/landing/FeatureCard.vue';
import { BarChart2, BookOpen, FileText } from 'lucide-vue-next';
</script>
<template>
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
        <FeatureCard
          :title="__('Personal Archives')"
          :description="
            __(
              'Keep a complete history of your inquiries. Every hexagram and line is saved with your personal context and dates.',
            )
          "
          :icon="BookOpen"
        />
        <FeatureCard
          :title="__('Beautiful PDF Reports')"
          :description="
            __(
              'Generate professional, printable reports for your readings, including the visual hexagram and the Path of the Sage.',
            )
          "
          :icon="FileText"
        />
        <FeatureCard
          :title="__('Insightful Statistics')"
          :description="
            __(
              'Visualize your energy balance. Track which hexagrams appear most frequently in your life through the Inquiry Timeline.',
            )
          "
          :icon="BarChart2"
        />
      </div>
    </div>
  </section>
</template>
