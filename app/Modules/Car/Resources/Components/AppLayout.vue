<script setup>
import Navbar from './Navbar.vue'
</script>

<template>
  <div style="min-height: 100vh; background: #F8FAFC; font-family: 'Plus Jakarta Sans', sans-serif;">
    <Navbar />
    <main>
      <slot />
    </main>
    <footer style="padding: 32px 24px; background: #0F172A; color: #94A3B8; text-align: center;">
      <div style="font-weight: 800; font-size: 16px; color: #fff;">
        Auto<span style="color: #f07040">Via</span>
      </div>
      <div>O marketplace de carros do Brasil</div>
      <div>© 2026 AutoVia. All rights reserved.</div>
    </footer>
  </div>
</template>