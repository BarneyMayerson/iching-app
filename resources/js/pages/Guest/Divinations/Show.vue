<script setup lang="ts">
import PrimaryHexagramSection from '@/components/IChing/PrimaryHexagramSection.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import SecondaryHexagramSection from '@/components/IChing/SecondaryHexagramSection.vue';
import StabilityMessageSection from '@/components/IChing/StabilityMessageSection.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Reading } from '@/types/iching';
import { Head } from '@inertiajs/vue3';

defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();
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

  <GuestLayout>
    <div class="mx-auto max-w-5xl flex-1 p-6 lg:p-12">
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
    </div>
  </GuestLayout>
</template>
