<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import LogoMotion from '@/components/logos/LogoMotion.vue';
import TextLink from '@/components/TextLink.vue';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';

import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form } from '@inertiajs/vue3';
import { X } from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';

const props = withDefaults(
  defineProps<{
    show: boolean;
    canResetPassword?: boolean;
    canRegister?: boolean;
  }>(),
  {
    canResetPassword: true,
    canRegister: true,
  },
);

const emit = defineEmits(['close']);

const handleEsc = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && props.show) emit('close');
};

onMounted(() => window.addEventListener('keydown', handleEsc));
onUnmounted(() => window.removeEventListener('keydown', handleEsc));
</script>

<template>
  <Teleport to="body">
    <Transition name="fade">
      <!-- Backdrop (Затемнение) -->
      <div
        v-if="show"
        class="fixed inset-0 z-50 bg-slate-900/30 backdrop-blur-sm"
      ></div>
    </Transition>
    <Transition name="slide-modal">
      <!-- Modal (Модальное окно) -->
      <div
        v-if="show"
        @click.self="emit('close')"
        class="fixed inset-0 z-50 flex items-center justify-center"
      >
        <div
          class="relative w-full max-w-md overflow-hidden rounded-3xl border border-slate-100 bg-white p-8 shadow-2xl dark:border-slate-800 dark:bg-slate-900"
        >
          <button
            @click="emit('close')"
            class="absolute top-4 right-4 text-slate-400 transition-colors hover:text-slate-600 dark:hover:text-slate-200"
          >
            <X class="size-6" />
          </button>

          <div class="mb-8 flex flex-col items-center">
            <LogoMotion class="mb-4 size-20" />
            <h1
              class="font-serif text-3xl font-bold text-slate-900 dark:text-white"
            >
              {{ __('Welcome Back') }}
            </h1>
            <p class="mt-2 text-sm text-slate-500">
              {{ __('Continue your journey with the Oracle') }}
            </p>
          </div>

          <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
          >
            <div class="grid gap-6">
              <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input
                  id="email"
                  type="email"
                  name="email"
                  required
                  autofocus
                  :tabindex="1"
                  autocomplete="email"
                  placeholder="email@example.com"
                />
                <InputError :message="errors.email" />
              </div>

              <div class="grid gap-2">
                <Label for="password">{{ __('Password') }}</Label>
                <Input
                  id="password"
                  type="password"
                  name="password"
                  required
                  :tabindex="2"
                  autocomplete="current-password"
                  placeholder="Password"
                />
                <InputError :message="errors.password" />
              </div>

              <div class="flex items-center justify-between">
                <Label for="remember" class="flex items-center space-x-3">
                  <Checkbox id="remember" name="remember" :tabindex="3" />
                  <span>{{ __('Remember me') }}</span>
                </Label>
              </div>

              <Button
                type="submit"
                class="mt-4 w-full"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
              >
                <Spinner v-if="processing" />
                {{ __('Log in') }}
              </Button>
            </div>
          </Form>

          <div
            v-if="canRegister"
            class="mt-8 flex items-center justify-center gap-6 text-center text-sm"
          >
            <TextLink
              v-if="canResetPassword"
              :href="request().url"
              :tabindex="5"
            >
              {{ __('Forgot password?') }}
            </TextLink>
            <TextLink :href="register().url" :tabindex="6">
              {{ __('Sign up') }}
            </TextLink>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition:
    backdrop-filter 0.3s ease,
    opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  backdrop-filter: blur(0px);
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  backdrop-filter: blur(8px);
  opacity: 1;
}

.slide-modal-enter-active {
  transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.slide-modal-leave-active {
  transition: all 0.2s cubic-bezier(0.7, 0, 0.84, 0);
}

.slide-modal-enter-from {
  opacity: 0;
  transform: translateX(100%) scale(0.9);
}

.slide-modal-leave-to {
  opacity: 0;
  transform: translateX(50%) scale(0.95);
}
</style>
