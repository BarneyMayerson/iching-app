import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useTranslate() {
  const page = usePage<AppPageProps>();

  const __ = (key: string): string => page.props.translations[key] || key;

  const locale = computed(() => page.props.locale);

  return { __, locale };
}
