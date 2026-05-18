<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import {
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
} from '@/components/ui/dropdown-menu';
import { useTranslate } from '@/composables/useTranslate';
import { logout } from '@/routes';
import { update as languageUpdate } from '@/routes/language';
import { edit } from '@/routes/profile';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { Check, Globe, LogOut, Settings } from 'lucide-vue-next';

interface Props {
  user: User;
}

defineProps<Props>();

const { locale } = useTranslate();

const handleLogout = () => {
  router.flushAll();
};

const changeLanguage = (lang: string) => {
  if (lang === locale.value) return;

  router.post(
    languageUpdate().url,
    { language: lang },
    {
      preserveScroll: true,
    },
  );
};
</script>

<template>
  <DropdownMenuLabel class="p-0 font-normal">
    <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
      <UserInfo :user="user" :show-email="true" />
    </div>
  </DropdownMenuLabel>
  <DropdownMenuSeparator />
  <DropdownMenuGroup>
    <DropdownMenuItem :as-child="true">
      <Link class="block w-full cursor-pointer" :href="edit()" prefetch>
        <Settings class="mr-2 h-4 w-4" />
        {{ __('Settings') }}
      </Link>
    </DropdownMenuItem>

    <DropdownMenuSub>
      <DropdownMenuSubTrigger class="cursor-pointer">
        <Globe class="mr-4 h-4 w-4" />
        <span>{{ __('Language') }}</span>
        <span
          class="ml-auto rounded bg-slate-100 px-1.5 py-0.5 text-xs font-semibold text-muted-foreground uppercase dark:bg-slate-800"
        >
          {{ locale }}
        </span>
      </DropdownMenuSubTrigger>

      <DropdownMenuPortal>
        <DropdownMenuSubContent class="min-w-40">
          <DropdownMenuItem
            @click="changeLanguage('ru')"
            class="flex cursor-pointer items-center justify-between"
          >
            <span>Русский</span>
            <Check v-if="locale === 'ru'" class="h-4 w-4 text-emerald-600" />
          </DropdownMenuItem>

          <DropdownMenuItem
            @click="changeLanguage('en')"
            class="flex cursor-pointer items-center justify-between"
          >
            <span>English</span>
            <Check v-if="locale === 'en'" class="h-4 w-4 text-emerald-600" />
          </DropdownMenuItem>
        </DropdownMenuSubContent>
      </DropdownMenuPortal>
    </DropdownMenuSub>
  </DropdownMenuGroup>
  <DropdownMenuSeparator />
  <DropdownMenuItem :as-child="true">
    <Link
      class="block w-full cursor-pointer"
      :href="logout()"
      @click="handleLogout"
      as="button"
      data-test="logout-button"
    >
      <LogOut class="mr-2 h-4 w-4" />
      {{ __('Log out') }}
    </Link>
  </DropdownMenuItem>
</template>
