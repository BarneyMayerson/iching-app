<script setup lang="ts">
import FeatureSection from '@/components/landing/FeatureSection.vue';
import FooterSection from '@/components/landing/FooterSection.vue';
import HeroHeader from '@/components/landing/HeroHeader.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
import SacredRitualSection from '@/components/landing/SacredRitualSection.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head :title="__('I-Ching Cabinet — Your Digital Oracle')" />

  <div
    class="min-h-screen bg-slate-50 text-slate-900 selection:bg-amber-200 dark:bg-slate-950 dark:text-slate-100"
  >
    <LandingNav />
    <HeroHeader />
    <FeatureSection />
    <SacredRitualSection />
    <FooterSection />
  </div>
</template>
