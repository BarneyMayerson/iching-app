<script setup lang="ts">
import FeatureSection from '@/components/landing/FeatureSection.vue';
import HeroHeader from '@/components/landing/HeroHeader.vue';
import SacredRitualSection from '@/components/landing/SacredRitualSection.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head :title="__('I-Ching Cabinet — Your Digital Oracle')" />

  <GuestLayout
    class="min-h-screen bg-slate-50 text-slate-900 selection:bg-amber-200 dark:bg-slate-950 dark:text-slate-100"
  >
    <HeroHeader />
    <FeatureSection />
    <SacredRitualSection />
  </GuestLayout>
</template>
