<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

type ToastType = 'success' | 'error' | 'warning' | 'info';

interface Toast {
  id: number;
  message: string;
  type: ToastType;
  visible: boolean;
  paused: boolean;
  remaining: number;
  startedAt: number;
  timer: ReturnType<typeof setTimeout> | null;
  isClosing: boolean;
}

interface Props {
  timeout?: number;
  position?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left';
}

const props = withDefaults(defineProps<Props>(), {
  timeout: 4000,
  position: 'bottom-right',
});

const page = usePage();
const toasts = ref<Toast[]>([]);

const stackDirection = computed(() =>
  props.position.includes('top') ? 'flex-col' : 'flex-col-reverse',
);

const positionClass = computed(() => {
  const map: Record<string, string> = {
    'top-left': 'top-4 left-4',
    'top-right': 'top-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'bottom-right': 'bottom-4 right-4',
  };
  return map[props.position];
});

const isLeft = computed(() => props.position.includes('left'));
const translateClass = computed(() =>
  isLeft.value ? '-translate-x-8' : 'translate-x-8',
);

function addToast(message: string, type: ToastType = 'success') {
  if (!message) return;

  const id = Date.now();
  const newToast: Toast = {
    id,
    message,
    type,
    visible: true,
    paused: false,
    remaining: props.timeout,
    startedAt: Date.now(),
    timer: null,
    isClosing: false,
  };

  toasts.value.push(newToast);

  nextTick(() => {
    startTimer(id);
  });
}

function startTimer(id: number) {
  const toast = toasts.value.find((t) => t.id === id);
  if (!toast || props.timeout <= 0) return;

  toast.paused = false;
  toast.timer = setTimeout(() => removeToast(id), toast.remaining);
  toast.startedAt = Date.now();
}

function pauseTimer(id: number) {
  const toast = toasts.value.find((t) => t.id === id);
  if (!toast || !toast.timer) return;

  toast.paused = true;
  const elapsed = Date.now() - toast.startedAt;
  toast.remaining -= elapsed;
  clearTimeout(toast.timer);
  toast.timer = null;
}

function removeToast(id: number) {
  const toast = toasts.value.find((t) => t.id === id);
  if (toast) {
    toast.isClosing = true;
    toast.visible = false;
    if (toast.timer) clearTimeout(toast.timer);
    toast.timer = null;
  }

  setTimeout(() => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  }, 300);
}

function getProgressStyle(toast: Toast) {
  if (!toast || props.timeout <= 0) {
    return { transform: 'scaleX(1)', transitionDuration: '0s' };
  }
  if (toast.isClosing) {
    return { transform: 'scaleX(0)', transitionDuration: '0s' };
  }
  if (toast.paused) {
    const progress = toast.remaining / props.timeout;
    return { transform: `scaleX(${progress})`, transitionDuration: '0s' };
  }
  if (toast.timer) {
    return {
      transform: 'scaleX(0)',
      transitionDuration: `${toast.remaining}ms`,
    };
  }
  return { transform: 'scaleX(1)', transitionDuration: '0s' };
}

// Следим за flash из бекенда
watch(
  () =>
    page.props.flash as {
      error?: string;
      success?: string;
      warning?: string;
      info?: string;
    },
  (flash) => {
    if (flash?.error) addToast(flash.error, 'error');
    if (flash?.success) addToast(flash.success, 'success');
    if (flash?.warning) addToast(flash.warning, 'warning');
    if (flash?.info) addToast(flash.info, 'info');
  },
  { deep: true },
);

// Следим за ошибками валидации (ValidationException)
watch(
  () => page.props.errors as Record<string, string>,
  (errors) => {
    const errorValues = Object.values(errors);
    if (errorValues.length > 0) {
      addToast(errorValues[0], 'error');
    }
  },
  { deep: true },
);

const bgClass = (type: ToastType) =>
  ({
    success: 'bg-emerald-600',
    error: 'bg-red-600',
    warning: 'bg-amber-600',
    info: 'bg-blue-600',
  })[type] || 'bg-emerald-600';

const checkInitialFlash = () => {
  const flash = page.props.flash as {
    error?: string;
    success?: string;
    warning?: string;
    info?: string;
  };

  if (flash?.error) addToast(flash.error, 'error');
  if (flash?.success) addToast(flash.success, 'success');
  if (flash?.warning) addToast(flash.warning, 'warning');
  if (flash?.info) addToast(flash.info, 'info');
};

onMounted(() => {
  checkInitialFlash();
});

onUnmounted(() => {
  toasts.value.forEach((t) => t.timer && clearTimeout(t.timer));
});
</script>

<template>
  <Teleport to="body">
    <div
      :class="[
        positionClass,
        stackDirection,
        'fixed z-100 flex w-full max-w-fit gap-2',
      ]"
    >
      <TransitionGroup
        enter-active-class="transition-all duration-300 ease-out"
        :enter-from-class="`opacity-0 scale-95 ${translateClass}`"
        enter-to-class="opacity-100 scale-100 translate-x-0"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 scale-100 translate-x-0"
        :leave-to-class="`opacity-0 scale-95 ${translateClass}`"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          v-show="toast.visible"
          @mouseenter="pauseTimer(toast.id)"
          @mouseleave="startTimer(toast.id)"
          :class="[
            bgClass(toast.type),
            'relative max-w-xs min-w-70 overflow-hidden rounded-xl px-4 py-3 text-white shadow-xl sm:max-w-md',
            'group flex items-start gap-3',
          ]"
        >
          <div class="flex-1 pt-0.5 text-sm leading-snug font-medium">
            {{ toast.message }}
          </div>
          <button
            @click="removeToast(toast.id)"
            class="p-1 opacity-60 transition-opacity hover:opacity-100"
          >
            ✕
          </button>

          <div
            v-if="props.timeout > 0"
            class="absolute right-0 bottom-0 left-0 h-1 bg-black/20"
          >
            <div
              class="linear h-full w-full origin-left bg-white/60 transition-transform"
              :style="getProgressStyle(toast)"
            />
          </div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<style scoped>
.linear {
  transition-timing-function: linear;
}
</style>
