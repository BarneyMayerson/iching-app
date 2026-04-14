<script setup lang="ts">
import AiInterpretationSection from '@/components/IChing/AiInterpretationSection.vue';
import PrimaryHexagramSection from '@/components/IChing/PrimaryHexagramSection.vue';
import ReadingActions from '@/components/IChing/ReadingActions.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import SecondaryHexagramSection from '@/components/IChing/SecondaryHexagramSection.vue';
import StabilityMessageSection from '@/components/IChing/StabilityMessageSection.vue';
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();

const { __ } = useTranslate();

const breadcrumbs = [
  { title: __('Divinations'), href: index().url },
  {
    title:
      props.reading.question.length > 50
        ? props.reading.question.substring(0, 50) + '...'
        : props.reading.question,
    href: '#',
  },
];
</script>

<template>
  <Head
    :title="
      __('Hexagram :number - Interpretation').replace(
        ':number',
        reading.hexagram.number.toString(),
      )
    "
  />

  <AppLayout :breadcrumbs>
    <div class="mx-auto max-w-4xl flex-1 p-6 lg:p-12">
      <ReadingActions :reading />

      <ReadingHeader
        class="mb-16 text-center"
        :question="reading.question"
        :date="reading.date"
        :time="reading.time"
        :relative_date="reading.relative_date"
      />

      <section class="space-y-10">
        <PrimaryHexagramSection :reading :changing_lines />
      </section>

      <section
        v-if="reading.secondary_hexagram"
        class="animate-in space-y-8 duration-1000 slide-in-from-bottom-10 fade-in"
      >
        <SecondaryHexagramSection :reading />
      </section>

      <section v-else>
        <StabilityMessageSection />
      </section>

      <AiInterpretationSection :reading />
    </div>
  </AppLayout>
</template>
