<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})
</script>

<template>
  <div style="display: flex; min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9;">
    <Head title="Create Account — AutoVia" />

    <!-- Left panel: dark gradient with logo, tagline, features -->
    <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 40px;">
      <!-- Logo -->
      <div style="margin-bottom: 24px;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #f97316;">
          <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor" />
        </svg>
      </div>
      <!-- Tagline -->
      <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">AutoVia</h1>
      <p style="font-size: 16px; color: rgba(255,255,255,0.7); margin-bottom: 40px;">Brazil's trusted car marketplace</p>
      <!-- Features -->
      <div style="text-align: left;">
        <div style="font-size: 15px; color: rgba(255,255,255,0.85); margin-bottom: 12px;">
          <span style="color: #f97316; font-weight: 700;">✓</span> Free to list your car
        </div>
        <div style="font-size: 15px; color: rgba(255,255,255,0.85); margin-bottom: 12px;">
          <span style="color: #f97316; font-weight: 700;">✓</span> Connect with real buyers
        </div>
        <div style="font-size: 15px; color: rgba(255,255,255,0.85); margin-bottom: 12px;">
          <span style="color: #f97316; font-weight: 700;">✓</span> Secure & verified sellers
        </div>
      </div>
    </div>

    <!-- Right panel: white card -->
    <div style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 40px; background: #f1f5f9;">
      <div style="background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 420px; padding: 40px;">
        <!-- Logo small -->
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 24px;">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #f97316;">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor" />
          </svg>
          <span style="font-size: 20px; font-weight: 700; color: #0f172a;">AutoVia</span>
        </div>

        <!-- Title and subtitle -->
        <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 24px; margin-bottom: 4px;">Create your account</h2>
        <p style="color: #64748B; font-size: 14px; margin-bottom: 28px;">Join thousands of Brazilian car buyers & sellers</p>

        <!-- Form -->
        <form @submit.prevent="form.post('/auth/register')">
          <!-- Name -->
          <div style="margin-bottom: 16px;">
            <label for="name" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">Full name</label>
            <input
              id="name"
              type="text"
              v-model="form.name"
              :style="{
                border: form.errors.name ? '1px solid #EF4444' : '1px solid #E2E8F0',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                width: '100%',
                outline: 'none',
                transition: 'border-color 0.2s',
              }"
            />
            <p v-if="form.errors.name" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.name }}</p>
          </div>

          <!-- Email -->
          <div style="margin-bottom: 16px;">
            <label for="email" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">Email address</label>
            <input
              id="email"
              type="email"
              v-model="form.email"
              :style="{
                border: form.errors.email ? '1px solid #EF4444' : '1px solid #E2E8F0',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                width: '100%',
                outline: 'none',
              }"
            />
            <p v-if="form.errors.email" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.email }}</p>
          </div>

          <!-- Password -->
          <div style="margin-bottom: 16px;">
            <label for="password" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">Password</label>
            <input
              id="password"
              type="password"
              v-model="form.password"
              :style="{
                border: form.errors.password ? '1px solid #EF4444' : '1px solid #E2E8F0',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                width: '100%',
                outline: 'none',
              }"
            />
            <p v-if="form.errors.password" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.password }}</p>
          </div>

          <!-- Confirm Password -->
          <div style="margin-bottom: 24px;">
            <label for="password_confirmation" style="display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px;">Confirm password</label>
            <input
              id="password_confirmation"
              type="password"
              v-model="form.password_confirmation"
              :style="{
                border: form.errors.password_confirmation ? '1px solid #EF4444' : '1px solid #E2E8F0',
                borderRadius: '10px',
                padding: '11px 14px',
                fontSize: '14px',
                width: '100%',
                outline: 'none',
              }"
            />
            <p v-if="form.errors.password_confirmation" style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ form.errors.password_confirmation }}</p>
          </div>

          <!-- Submit button -->
          <button
            type="submit"
            :disabled="form.processing"
            style="
              width: 100%;
              background: #f97316;
              color: white;
              font-weight: 600;
              padding: 12px 16px;
              border: none;
              border-radius: 10px;
              font-size: 15px;
              cursor: pointer;
              transition: background 0.2s;
              opacity: 1;
            "
            :style="[form.processing ? { opacity: 0.7, cursor: 'not-allowed' } : { opacity: 1 }]"
          >
            {{ form.processing ? 'Creating account...' : 'Create account →' }}
          </button>
        </form>

        <!-- Sign-in link -->
        <p style="margin-top: 24px; text-align: center; font-size: 14px; color: #64748B;">
          Already have an account?
          <Link href="/auth" style="color: #f97316; font-weight: 600; text-decoration: underline;">Sign in</Link>
        </p>
      </div>
    </div>
  </div>
</template>