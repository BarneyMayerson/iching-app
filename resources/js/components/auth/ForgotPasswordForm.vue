<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Spinner from '@/components/ui/spinner/Spinner.vue';
import { email } from '@/routes/password';
import { Form } from '@inertiajs/vue3';
</script>

<template>
  <Form v-bind="email.form()" v-slot="{ errors, processing }">
    <div class="grid gap-2">
      <Label for="email">Email</Label>
      <Input
        id="email"
        type="email"
        name="email"
        autocomplete="off"
        autofocus
        placeholder="email@example.com"
      />
      <InputError :message="errors.email" />
    </div>

    <div class="my-6 flex items-center justify-start">
      <Button
        class="w-full"
        :disabled="processing"
        data-test="email-password-reset-link-button"
      >
        <Spinner v-if="processing" />
        {{ __('Email password reset link') }}
      </Button>
    </div>
  </Form>
</template>
