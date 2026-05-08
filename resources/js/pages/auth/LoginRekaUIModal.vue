<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import LogoMotion from '@/components/logos/LogoMotion.vue';
import TextLink from '@/components/TextLink.vue';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form } from '@inertiajs/vue3';

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
</script>

<template>
  <Dialog>
    <DialogTrigger as-child>
      <Button variant="ghost">{{ __('Log in') }}</Button>
    </DialogTrigger>

    <DialogContent class="dark:bg-slate-900">
      <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="flex flex-col gap-6"
      >
        <DialogHeader>
          <DialogTitle class="flex flex-col items-center justify-center">
            <LogoMotion class="mb-4 size-20" />
            <h1
              class="font-serif text-3xl font-bold text-slate-900 dark:text-white"
            >
              {{ __('Welcome Back') }}
            </h1>
            <p class="mt-2 text-sm text-slate-500">
              {{ __('Continue your journey with the Oracle') }}
            </p>
          </DialogTitle>
        </DialogHeader>

        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              type="email"
              name="email"
              required
              autofocus
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
              autocomplete="current-password"
              placeholder="Password"
            />
            <InputError :message="errors.password" />
          </div>

          <div class="flex items-center justify-between">
            <Label for="remember" class="flex items-center space-x-3">
              <Checkbox id="remember" name="remember" />
              <span>{{ __('Remember me') }}</span>
            </Label>
          </div>

          <Button type="submit" class="mt-4 w-full" :disabled="processing">
            <Spinner v-if="processing" />
            {{ __('Log in') }}
          </Button>
        </div>
      </Form>
      <div
        v-if="canRegister"
        class="mt-8 flex items-center justify-center gap-6 text-center text-sm"
      >
        <TextLink v-if="canResetPassword" :href="request().url">
          {{ __('Forgot password?') }}
        </TextLink>
        <TextLink :href="register().url">
          {{ __('Sign up') }}
        </TextLink>
      </div>
    </DialogContent>
  </Dialog>
</template>
