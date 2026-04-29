<template>
  <div style="display: flex; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif;">
    <!-- Left panel -->
    <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 48px; text-align: center; background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 100%);">
      <!-- Logo -->
      <div style="display: flex; align-items: center; gap: 12px;">
        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="24" cy="24" r="24" fill="#f07040"/>
          <path d="M16 28l4-8h8l4 8v4H16v-4zM18 24h12M20 20h8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span style="font-size: 28px; font-weight: 800; color: #fff;">Auto<span style="color: #f07040;">Via</span></span>
      </div>
      <!-- Tagline -->
      <p style="font-size: 16px; color: rgba(255,255,255,0.7); margin-top: 16px; max-width: 280px;">O marketplace de carros mais confiável do Brasil</p>
      <!-- Stats -->
      <div style="display: flex; gap: 32px; margin-top: 48px;">
        <div>
          <div style="font-size: 24px; font-weight: 800; color: #fff;">12k+</div>
          <div style="font-size: 12px; color: rgba(255,255,255,0.5);">Listings</div>
        </div>
        <div>
          <div style="font-size: 24px; font-weight: 800; color: #fff;">4.8★</div>
          <div style="font-size: 12px; color: rgba(255,255,255,0.5);">Rating</div>
        </div>
        <div>
          <div style="font-size: 24px; font-weight: 800; color: #fff;">98%</div>
          <div style="font-size: 12px; color: rgba(255,255,255,0.5);">Safety</div>
        </div>
      </div>
    </div>

    <!-- Right panel -->
    <div style="flex: 1; background: #F8FAFC; display: flex; justify-content: center; align-items: center;">
      <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); padding: 40px; max-width: 400px; width: 100%; margin: 24px;">
        <!-- Small logo (mobile feel) -->
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="16" cy="16" r="16" fill="#f07040"/>
            <path d="M10 20l3-6h6l3 6v3H10v-3zM12 17h8M13.5 14h5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span style="font-size: 20px; font-weight: 800; color: #0F172A;">Auto<span style="color: #f07040;">Via</span></span>
        </div>
        <h2 style="font-size: 26px; font-weight: 800; color: #0F172A; margin-top: 0; margin-bottom: 4px;">Welcome back</h2>
        <p style="font-size: 14px; color: #64748B; margin-top: 0; margin-bottom: 28px;">Sign in to your AutoVia account</p>

        <form @submit.prevent="form.post('/auth/login')">
          <div style="margin-bottom: 16px;">
            <label for="email" style="display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #0F172A;">Email address</label>
            <input
              id="email"
              type="email"
              v-model="form.email"
              :style="{
                width: '100%',
                border: form.errors.email ? '1px solid #EF4444' : '1px solid #D1D5DB',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                outline: 'none',
                transition: 'border-color 0.2s',
                boxSizing: 'border-box'
              }"
              class="custom-focus"
            />
            <p v-if="form.errors.email" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.email }}</p>
          </div>

          <div style="margin-bottom: 16px;">
            <label for="password" style="display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #0F172A;">Password</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              :style="{
                width: '100%',
                border: form.errors.password ? '1px solid #EF4444' : '1px solid #D1D5DB',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                outline: 'none',
                transition: 'border-color 0.2s',
                boxSizing: 'border-box'
              }"
              class="custom-focus"
            />
            <p v-if="form.errors.password" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.password }}</p>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            style="
              width: 100%;
              background: #f07040;
              color: #fff;
              border-radius: 10px;
              padding: 13px;
              font-size: 15px;
              font-weight: 700;
              cursor: pointer;
              border: none;
              margin-top: 8px;
              transition: opacity 0.2s;
            "
            :style="{ opacity: form.processing ? 0.7 : 1 }"
          >
            {{ form.processing ? 'Signing in...' : 'Sign in →' }}
          </button>
        </form>

        <div style="margin-top: 20px; text-align: center; font-size: 14px; color: #64748B;">
          Don't have an account?
          <Link href="/auth/register" style="color: #f07040; text-decoration: none;">Create one</Link>
        </div>
      </div>
    </div>

    <Head title="Sign In — AutoVia" />
  </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})
</script>

<style scoped>
.custom-focus:focus {
  border-color: #f07040 !important;
}
body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  margin: 0;
}
</style>