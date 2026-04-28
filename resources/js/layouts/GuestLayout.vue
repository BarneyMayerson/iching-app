<script setup lang="ts">
import FlashMessages from '@/components/FlashMessages.vue';
import FooterSection from '@/components/landing/FooterSection.vue';
import LandingNav from '@/components/landing/LandingNav.vue';
</script>

<template>
  <div
    class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 selection:bg-amber-200 dark:bg-slate-950 dark:text-slate-100"
  >
    <LandingNav />

    <main>
      <slot />
    </main>

    <FooterSection />

    <FlashMessages />
  </div>
</template>
