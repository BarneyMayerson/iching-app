<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { store } from '@/routes/login';
import { Form } from '@inertiajs/vue3';
</script>

<template>
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
          autocomplete="new-password"
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
</template>
