import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';

export function useTranslate() {
  const page = usePage<AppPageProps>();

  const __ = (key: string): string => page.props.translations[key] || key;

  return {
    __,
    locale: page.props.locale, // пригодится для форматирования дат
  };
}
