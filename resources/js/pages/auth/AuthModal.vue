<script setup lang="ts">
import LogoMotion from '@/components/logos/LogoMotion.vue';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';

interface Props {
  open: boolean;
  title: string;
  mode: 'login' | 'register' | 'forgot-password';
  description?: string;
}

defineProps<Props>();
const emit = defineEmits(['update:open']);
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent
      class="rounded-3xl border-none bg-white shadow-2xl sm:max-w-md dark:bg-slate-900"
    >
      <Transition name="modal-content" mode="out-in" appear>
        <div :key="mode" class="w-full">
          <DialogHeader class="flex flex-col items-center justify-center pt-4">
            <LogoMotion class="mb-4 size-20" />
            <DialogTitle
              class="font-serif text-3xl font-bold text-slate-900 dark:text-white"
            >
              {{ title }}
            </DialogTitle>
            <DialogDescription
              v-if="description"
              class="mt-2 text-center text-sm text-slate-500"
            >
              {{ description }}
            </DialogDescription>
          </DialogHeader>

          <slot name="content" />

          <div
            class="flex flex-col items-center border-t border-slate-200 dark:border-slate-700"
          >
            <slot name="footer" />
          </div>
        </div>
      </Transition>
    </DialogContent>
  </Dialog>
</template>

<style scoped>
.modal-content-enter-active,
.modal-content-leave-active {
  transition: all 0.45s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-content-enter-from {
  opacity: 0;
  transform: scale(0.94) translateY(20px);
}

.modal-content-leave-to {
  opacity: 0;
  transform: scale(0.97) translateY(15px);
}

.modal-content-leave-from,
.modal-content-enter-to {
  opacity: 1;
  transform: scale(1) translateY(0);
}
</style>
