<script setup lang="ts">
import { useTranslate } from '@/composables/useTranslate';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const { __ } = useTranslate();

defineProps<{
  payment: {
    order_id: string;
    amount: number;
    status: string;
  };
}>();

const donationPlatforms = [
  {
    name: 'Boosty',
    description: 'Оплата картами РБ, РФ и зарубежными картами',
    logo: '/storage/images/platforms/boosty.svg',
    url: 'https://boosty.to/your-profile',
    color: 'hover:border-orange-500',
  },
  {
    name: 'CloudTips',
    description: 'Быстрый донат через банковские карты',
    logo: '/storage/images/platforms/cloudtips.svg',
    url: 'https://pay.cloudtips.ru/p/your-id',
    color: 'hover:border-blue-500',
  },
  {
    name: 'Patreon',
    description: 'Для ежемесячной поддержки (зарубежные карты)',
    logo: '/storage/images/platforms/patreon.svg',
    url: 'https://www.patreon.com/your-profile',
    color: 'hover:border-red-400',
  },
];

const breadcrumbs = computed<BreadcrumbItem[]>(() => [
  {
    title: __('Donate'),
    href: '#',
  },
]);
</script>

<template>
  <AppLayout :breadcrumbs>
    <Head :title="__('Donate')" />

    <div class="py-12">
      <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
          <div
            class="mb-4 inline-flex items-center justify-center rounded-full bg-amber-100 p-3 dark:bg-amber-900/30"
          >
            <Heart class="size-8 fill-amber-600 text-amber-600" />
          </div>
          <h1
            class="font-serif text-4xl font-bold text-slate-900 dark:text-white"
          >
            {{ __('Become a Patron') }}
          </h1>
          <p
            class="mx-auto mt-4 max-w-2xl text-lg text-slate-600 dark:text-slate-400"
          >
            {{
              __(
                'Choose a convenient donation method. Once the transfer is confirmed, your account will receive full access to all I-Ching Cabinet features.',
              )
            }}
          </p>
        </div>

        <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-3">
          <a
            v-for="platform in donationPlatforms"
            :key="platform.name"
            :href="platform.url"
            target="_blank"
            :class="[
              'group block rounded-3xl border-2 border-slate-100 bg-white p-6 shadow-sm transition-all dark:border-slate-800 dark:bg-slate-900',
              platform.color,
            ]"
          >
            <div class="mb-4 flex h-12 items-center gap-4">
              <img :src="platform.logo" class="h-6 w-6" />
              <span class="text-xl font-bold dark:text-white">{{
                platform.name
              }}</span>
            </div>
            <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">
              {{ platform.description }}
            </p>
            <div
              class="flex items-center text-sm font-bold text-slate-900 transition-transform group-hover:translate-x-1 dark:text-white"
            >
              Перейти к оплате
              <ExternalLink class="ml-2 size-4" />
            </div>
          </a>
        </div>

        <div
          class="flex flex-col items-center justify-between gap-6 rounded-3xl bg-slate-900 p-8 text-white md:flex-row dark:bg-amber-900/20"
        >
          <div class="flex items-center gap-4">
            <div class="rounded-2xl bg-white/10 p-3">
              <ShieldCheck class="size-6 text-amber-400" />
            </div>
            <div>
              <p
                class="text-xs font-bold tracking-widest text-slate-400 uppercase"
              >
                {{ __('Your unique order ID') }}
              </p>
              <p class="font-mono text-2xl font-bold">{{ payment.order_id }}</p>
            </div>
          </div>
          <div
            class="text-center text-sm text-slate-400 md:max-w-75 md:text-left"
          >
            {{
              __(
                'Please include this number in the donation comment so that I can instantly activate your profile.',
              )
            }}
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
