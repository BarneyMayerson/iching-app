<script setup lang="ts">
defineProps<{
  question: string;
  date: string;
  time: string;
  relative_date: string;
}>();
</script>

<template>
  <div>
    <div class="relative inline-block px-12">
      <Quote
        class="absolute top-0 left-0 size-10 text-amber-500/10 dark:text-amber-500/20"
      />
      <h1
        class="font-serif text-3xl font-bold tracking-tight text-slate-900 italic lg:text-5xl dark:text-slate-100"
      >
        {{ question }}
      </h1>
      <Quote
        class="absolute right-0 bottom-0 size-10 rotate-180 text-amber-500/10 dark:text-amber-500/20"
      />
    </div>

    <div class="mt-6 flex flex-col items-center gap-2">
      <p class="text-xs font-bold tracking-[0.3em] text-slate-400 uppercase">
        Cast on {{ date }} at {{ time }}
      </p>
      <span class="text-xs text-amber-600/60 italic dark:text-amber-400/50">
        {{ relative_date }}
      </span>
    </div>
  </div>
</template>
