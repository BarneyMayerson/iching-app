import { toUrl } from '@/lib/utils';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { computed, readonly } from 'vue';

export function useActiveUrl() {
  const page = usePage();

  const currentUrlReactive = computed(
    () => new URL(page.url, window?.location.origin).pathname,
  );

  /**
   * Проверяет, активен ли URL.
   * @param urlToCheck - URL из навигации
   * @param exact - если true, требуется строгое совпадение.
   * если false (по умолчанию), проверяется и вложенность.
   */
  function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    exact = false,
  ) {
    const targetUrl = toUrl(urlToCheck);
    const currentUrl = currentUrlReactive.value;

    if (exact) {
      return currentUrl === targetUrl;
    }

    return currentUrl === targetUrl || currentUrl.startsWith(`${targetUrl}/`);
  }

  return {
    currentUrl: readonly(currentUrlReactive),
    urlIsActive,
  };
}
