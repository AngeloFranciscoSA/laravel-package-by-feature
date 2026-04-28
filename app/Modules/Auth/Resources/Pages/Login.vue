<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white rounded-xl shadow-md max-w-md w-full p-8">
      <Head title="Login" />

      <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome back</h2>
      <p class="text-sm text-gray-500 mb-8">Sign in to your account</p>

      <form @submit.prevent="form.post('/auth/login')">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.email }"
          />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
        </div>

        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.password }"
          />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 text-white rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ form.processing ? 'Signing in...' : 'Sign in' }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <Link href="/auth/register" class="text-blue-600 hover:underline">Create one</Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: '',
})
</script>