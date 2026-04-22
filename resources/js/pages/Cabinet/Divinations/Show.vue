<script setup lang="ts">
import OracleInterpretationModal from '@/components/IChing/OracleInterpretationModal.vue';
import PrimaryHexagramSection from '@/components/IChing/PrimaryHexagramSection.vue';
import ReadingActions from '@/components/IChing/ReadingActions.vue';
import ReadingHeader from '@/components/IChing/ReadingHeader.vue';
import SecondaryHexagramSection from '@/components/IChing/SecondaryHexagramSection.vue';
import StabilityMessageSection from '@/components/IChing/StabilityMessageSection.vue';
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  cancelInterpretation,
  index,
  interpret,
} from '@/routes/cabinet/divinations';
import { Reading } from '@/types/iching';
import { Head, router, useForm } from '@inertiajs/vue3';
import { onUnmounted, ref, watch } from 'vue';

const props = defineProps<{
  reading: Reading;
  changing_lines: number[];
}>();

const isModalOpen = ref(false);
const form = useForm({});

const showTimeoutWarning = ref(false);
let pollingInterval: ReturnType<typeof setInterval> | null = null;
let timeoutTimer: ReturnType<typeof setTimeout> | null = null;

const stopPolling = () => {
  if (pollingInterval) clearInterval(pollingInterval);
  if (timeoutTimer) clearTimeout(timeoutTimer);
  pollingInterval = null;
  timeoutTimer = null;
  showTimeoutWarning.value = false;
};

const handleCancel = () => {
  router.patch(
    cancelInterpretation(props.reading).url,
    {},
    {
      onSuccess: () => {
        stopPolling();
        isModalOpen.value = false;
      },
    },
  );
};

const startPolling = () => {
  if (pollingInterval) return;

  timeoutTimer = setTimeout(() => {
    showTimeoutWarning.value = true;
  }, 15000);

  pollingInterval = setInterval(() => {
    router.reload({
      only: ['reading'],
      onSuccess: (page) => {
        const status = (page.props.reading as Reading).interpretation_status;

        if (status === 'Completed' || status === 'Failed') {
          stopPolling();
        }
      },
    });
  }, 3000);
};

// const handleAiClick = () => {
//   isModalOpen.value = true;

//   const currentStatus = props.reading.interpretation_status;

//   const canStart = ['Not started', 'Failed', 'Cancelled'].includes(
//     currentStatus,
//   );

//   if (canStart && !props.reading.ai_interpretation) {
//     form.post(interpret(props.reading).url, {
//       preserveScroll: true,
//       onSuccess: () => startPolling(),
//       onError: (errors) => {
//         console.error(errors);
//       },
//     });
//   } else if (['Pending', 'Processing'].includes(currentStatus)) {
//     startPolling();
//   }
// };

const handleAiClick = () => {
  const currentStatus = props.reading.interpretation_status;

  const canStart = ['Not started', 'Failed', 'Cancelled'].includes(
    currentStatus,
  );

  if (canStart && !props.reading.ai_interpretation) {
    form.post(interpret(props.reading).url, {
      preserveScroll: true,
      onSuccess: () => {
        isModalOpen.value = true;
        startPolling();
      },
      onError: () => {
        isModalOpen.value = false;
        console.info('Interpretation error');
      },
    });
  } else if (['Pending', 'Processing'].includes(currentStatus)) {
    isModalOpen.value = true;
    startPolling();
  } else if (props.reading.ai_interpretation) {
    isModalOpen.value = true;
  }
};

watch(isModalOpen, (isOpen) => {
  if (!isOpen) {
    stopPolling();
  }
});

onUnmounted(() => stopPolling());

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
    <div class="mx-auto max-w-5xl flex-1 p-6 lg:p-12">
      <ReadingActions
        :reading
        :is-processing="form.processing"
        @open-ai="handleAiClick"
      />

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

      <OracleInterpretationModal
        :show="isModalOpen"
        :reading
        :is-processing="
          form.processing ||
          ['Not started', 'Pending', 'Processing'].includes(
            reading.interpretation_status,
          )
        "
        :show-timeout-warning="showTimeoutWarning"
        @close="isModalOpen = false"
        @cancel="handleCancel"
      />
    </div>
  </AppLayout>
</template>
