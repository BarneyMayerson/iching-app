import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    /**
     * Localization helper.
     * @param key Text in English
     */
    __(key: string): string;

    $page: Page<AppPageProps>;
  }
}

export interface Auth {
  user: User;
}

export interface BreadcrumbItem {
  title: string;
  href: string;
}

export interface NavItem {
  title: string;
  href: NonNullable<InertiaLinkProps['href']>;
  target?: '_blank' | '_self' | '_parent' | '_top';
  icon?: LucideIcon;
  isActive?: boolean;
}

export type AppPageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  name: string;
  auth: Auth;
  hasAdminAccess: boolean;
  sidebarOpen: boolean;
  locale: string;
  translations: Record<string, string>;
};

export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
