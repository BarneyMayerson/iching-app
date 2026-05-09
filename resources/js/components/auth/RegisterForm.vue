<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { store } from '@/routes/register';
import { Form } from '@inertiajs/vue3';
</script>
<template>
  <Form
    v-bind="store.form()"
    :reset-on-success="['password', 'password_confirmation']"
    v-slot="{ errors, processing }"
    class="flex flex-col gap-6"
  >
    <div class="grid gap-6">
      <div class="grid gap-2">
        <Label for="name">{{ __('Name') }}</Label>
        <Input
          id="name"
          type="text"
          required
          autofocus
          :tabindex="1"
          autocomplete="name"
          name="name"
          :placeholder="__('Full name')"
        />
        <InputError :message="errors.name" />
      </div>

      <div class="grid gap-2">
        <Label for="email">Email</Label>
        <Input
          id="email"
          type="email"
          required
          :tabindex="2"
          autocomplete="email"
          name="email"
          placeholder="email@example.com"
        />
        <InputError :message="errors.email" />
      </div>

      <div class="grid gap-2">
        <Label for="password">{{ __('Password') }}</Label>
        <Input
          id="password"
          type="password"
          required
          :tabindex="3"
          autocomplete="new-password"
          name="password"
          :placeholder="__('Password')"
        />
        <InputError :message="errors.password" />
      </div>

      <div class="grid gap-2">
        <Label for="password_confirmation">
          {{ __('Confirm Password') }}
        </Label>
        <Input
          id="password_confirmation"
          type="password"
          required
          :tabindex="4"
          autocomplete="new-password"
          name="password_confirmation"
          :placeholder="__('Confirm Password')"
        />
        <InputError :message="errors.password_confirmation" />
      </div>

      <Button
        type="submit"
        class="mt-2 w-full"
        tabindex="5"
        :disabled="processing"
        data-test="register-user-button"
      >
        <Spinner v-if="processing" />
        {{ __('Create account') }}
      </Button>
    </div>
  </Form>
</template>
