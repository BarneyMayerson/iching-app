<script setup lang="ts">
import ForgotPasswordForm from '@/components/auth/ForgotPasswordForm.vue';
import LoginForm from '@/components/auth/LoginForm.vue';
import RegisterForm from '@/components/auth/RegisterForm.vue';
import ResetPasswordForm from '@/components/auth/ResetPasswordForm.vue';
import LanguageSwitcher from '@/components/LanguageSwitcher.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import { useTranslate } from '@/composables/useTranslate';
import AuthModal from '@/pages/auth/AuthModal.vue';
import { dashboard } from '@/routes/cabinet';
import { Link, usePage } from '@inertiajs/vue3';
import { Sparkles } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

withDefaults(
  defineProps<{
    canResetPassword?: boolean;
    canRegister?: boolean;
  }>(),
  {
    canResetPassword: true,
    canRegister: true,
  },
);

const page = usePage();

const { __ } = useTranslate();

const isAuthOpen = ref(false);
const authMode = ref<
  'login' | 'register' | 'forgot-password' | 'reset-password' | 'check-email'
>('login');

const modalConfig = computed(() => {
  const configs = {
    login: {
      title: __('Welcome back'),
      description: __('Continue your journey with the Oracle'),
    },
    register: {
      title: __('Join the Circle'),
      description: __('Create your Master account today'),
    },
    'forgot-password': {
      title: __('Reset password'),
      description: __('Enter your email to receive a recovery link'),
    },
    'reset-password': {
      title: __('New Password'),
      description: __('Set a strong password for your account'),
    },
    'check-email': {
      title: __('Check your inbox'),
      description: __('We have emailed your password reset link!'),
    },
  };

  return configs[authMode.value];
});

const openLogin = () => {
  authMode.value = 'login';
  isAuthOpen.value = true;
};
const openRegister = () => {
  authMode.value = 'register';
  isAuthOpen.value = true;
};

const resetData = ref({
  token: '',
  email: '',
});

const clearAuthQueryParams = () => {
  const url = new URL(window.location.href);

  ['modal', 'token', 'email'].forEach((param) => {
    url.searchParams.delete(param);
  });

  const query = url.searchParams.toString();

  window.history.replaceState(
    {},
    '',
    query ? `${url.pathname}?${query}` : url.pathname,
  );
};

const checkUrlParams = () => {
  const params = new URLSearchParams(window.location.search);

  const modal = params.get('modal');

  if (modal === 'reset-password' && params.get('token')) {
    resetData.value = {
      token: params.get('token') || '',
      email: params.get('email') || '',
    };

    authMode.value = 'reset-password';
    isAuthOpen.value = true;

    return;
  }

  if (modal === 'login') {
    authMode.value = 'login';
    isAuthOpen.value = true;
  }
};

watch(isAuthOpen, (isOpen, wasOpen) => {
  if (wasOpen && !isOpen) {
    clearAuthQueryParams();
  }
});

watch(
  () => page.url,
  () => checkUrlParams(),
  { immediate: true },
);
</script>

<template>
  <nav
    class="sticky top-0 z-50 flex items-center justify-between bg-slate-50/80 p-6 backdrop-blur-md transition-colors lg:px-12 dark:bg-slate-950/80"
  >
    <Link href="/" class="group flex items-center gap-2">
      <div
        class="flex size-10 items-center justify-center rounded-xl bg-slate-900 text-white shadow-lg transition-transform group-hover:scale-105"
      >
        <Sparkles class="size-6" />
      </div>
      <span class="font-serif text-xl font-bold tracking-tight dark:text-white">
        {{ __('I-Ching Cabinet') }}
      </span>
    </Link>

    <div class="flex items-center gap-2 sm:gap-4">
      <LanguageSwitcher :currentLocale="$page.props.locale" />
      <ThemeSwitcher />
      <Link
        v-if="$page.props.auth.user"
        :href="dashboard().url"
        class="rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
      >
        {{ __('Go to Cabinet') }}
      </Link>

      <template v-else>
        <button
          @click="openLogin"
          class="hidden rounded-full px-5 py-2 text-sm font-bold text-slate-700 transition-colors hover:bg-slate-100 sm:block dark:text-slate-300 dark:hover:bg-slate-800"
        >
          {{ __('Log in') }}
        </button>

        <button
          @click="openRegister"
          class="hidden rounded-full bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-md transition-all hover:shadow-lg active:scale-95 sm:block dark:bg-amber-600 dark:text-slate-950 dark:hover:bg-amber-500"
        >
          {{ __('Get started for free') }}
        </button>
      </template>
    </div>
  </nav>

  <AuthModal
    v-model:open="isAuthOpen"
    :title="modalConfig.title"
    :description="modalConfig.description"
    :mode="authMode"
  >
    <template #content>
      <LoginForm
        v-if="authMode === 'login'"
        @forgot-password="authMode = 'forgot-password'"
      />
      <RegisterForm v-if="authMode === 'register'" />
      <ForgotPasswordForm
        v-if="authMode === 'forgot-password'"
        @success="authMode = 'check-email'"
      />
      <ResetPasswordForm
        v-if="authMode === 'reset-password'"
        :token="resetData.token"
        :email="resetData.email"
      />
    </template>

    <template #footer>
      <div class="mt-4 text-sm">
        <div
          v-if="authMode === 'login'"
          class="flex items-center justify-center gap-6 text-center"
        >
          <button
            v-if="canResetPassword"
            @click="authMode = 'forgot-password'"
            class="font-bold text-amber-600 hover:underline"
          >
            {{ __('Forgot password?') }}
          </button>

          <button
            v-if="canRegister"
            @click="authMode = 'register'"
            class="font-bold text-amber-600 hover:underline"
          >
            {{ __('Sign up') }}
          </button>
        </div>
      </div>

      <div v-if="authMode === 'register' || authMode === 'forgot-password'">
        <button
          @click="authMode = 'login'"
          class="font-bold text-amber-600 hover:underline"
        >
          {{ __('Log in') }}
        </button>
      </div>

      <div v-if="authMode === 'check-email'">
        <button
          @click="isAuthOpen = false"
          class="font-bold text-amber-600 hover:underline"
        >
          {{ __('Close') }}
        </button>
      </div>
    </template>
  </AuthModal>
</template>
