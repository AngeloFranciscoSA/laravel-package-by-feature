<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  currentRoute: {
    type: String,
    required: true
  }
})

const page = usePage()
const currentUrl = computed(() => page.url)

const navLinks = [
  { label: 'Home', href: '/cars' },
  { label: 'Search', href: '/cars/search' },
  { label: 'Compare', href: '/cars/compare' }
]

function isActive(href) {
  const url = currentUrl.value
  return url === href || url.startsWith(href)
}
</script>

<template>
  <nav style="position: sticky; top: 0; z-index: 100; background-color: #ffffff; border-bottom: 1px solid #E2E8F0; font-family: 'Plus Jakarta Sans', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; height: 64px; display: flex; flex-direction: row; align-items: center; gap: 32px; padding: 0 24px;">
      <!-- Logo -->
      <Link href="/cars" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="14" cy="14" r="14" fill="#f07040"/>
          <path d="M8 16.5L9.5 12.5L12 11H16L18.5 12.5L20 16.5H8Z" fill="white"/>
          <circle cx="10.5" cy="16.5" r="1.5" fill="white"/>
          <circle cx="17.5" cy="16.5" r="1.5" fill="white"/>
        </svg>
        <span style="font-size: 18px; font-weight: 800; color: #0F172A; letter-spacing: -0.5px;">
          Auto<span style="color: #f07040;">Via</span>
        </span>
      </Link>

      <!-- Spacer -->
      <div style="flex: 1;"></div>

      <!-- Nav links -->
      <div style="display: flex; gap: 24px;">
        <Link
          v-for="link in navLinks"
          :key="link.href"
          :href="link.href"
          :style="{
            fontSize: '14px',
            fontWeight: isActive(link.href) ? 700 : 500,
            color: isActive(link.href) ? '#f07040' : '#64748B',
            borderBottom: isActive(link.href) ? '2px solid #f07040' : '2px solid transparent',
            padding: '4px 0',
            height: '64px',
            display: 'inline-flex',
            alignItems: 'center',
            textDecoration: 'none'
          }"
        >
          {{ link.label }}
        </Link>
      </div>

      <!-- CTA button -->
      <Link
        href="/cars/new"
        style="background-color: #f07040; color: #ffffff; border-radius: 8px; padding: 10px 20px; font-weight: 700; font-size: 14px; text-decoration: none;"
      >
        + List a Car
      </Link>

      <!-- Logout button -->
      <button
        style="font-size: 13px; color: #64748B; border: none; background: none; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif;"
        @click="router.post('/auth/logout')"
      >
        Logout
      </button>
    </div>
  </nav>
</template>