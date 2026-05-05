<script setup lang="ts">
import FeatureSection from '@/components/landing/FeatureSection.vue';
import HeroHeader from '@/components/landing/HeroHeader.vue';
import SacredRitualSection from '@/components/landing/SacredRitualSection.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head :title="__('I-Ching Cabinet Your Digital Oracle')" />

  <GuestLayout>
    <div class="my-24 flex items-center gap-6"></div>
    <HeroHeader />
    <FeatureSection />
    <SacredRitualSection />
  </GuestLayout>
</template>
