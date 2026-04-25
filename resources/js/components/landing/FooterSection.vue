<template>
  <footer class="border-t border-slate-900 bg-slate-950 py-12 text-center">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
      <p class="text-sm text-slate-500">
        © 2026 I-Ching Cabinet.
        {{ __('Built with precision for the modern sage.') }}
      </p>
      <div
        class="mt-6 flex justify-center gap-6 text-xs font-bold tracking-widest text-slate-400 uppercase"
      >
        <a href="#" class="hover:text-amber-500">{{ __('Privacy') }}</a>
        <a href="#" class="hover:text-amber-500">{{ __('Terms') }}</a>
        <a href="#" class="hover:text-amber-500">{{ __('Guide') }}</a>
      </div>
    </div>
  </footer>
</template>
