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
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <Head title="Create account" />

    <div class="bg-white rounded-xl shadow-md max-w-md w-full p-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-2">Create your account</h2>
      <p class="text-sm text-gray-500 mb-8">Join us today</p>

      <form @submit.prevent="form.post('/auth/register')">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Full name</label>
          <input
            id="name"
            type="text"
            v-model="form.name"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="form.errors.name ? 'border-red-500' : 'border-gray-300'"
          />
          <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email address</label>
          <input
            id="email"
            type="email"
            v-model="form.email"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="form.errors.email ? 'border-red-500' : 'border-gray-300'"
          />
          <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="password">Password</label>
          <input
            id="password"
            type="password"
            v-model="form.password"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="form.errors.password ? 'border-red-500' : 'border-gray-300'"
          />
          <p v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</p>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">Confirm password</label>
          <input
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="form.errors.password_confirmation ? 'border-red-500' : 'border-gray-300'"
          />
          <p v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ form.errors.password_confirmation }}</p>
        </div>

        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
        >
          {{ form.processing ? 'Creating account...' : 'Create account' }}
        </button>
      </form>
    </div>

    <p class="mt-4 text-sm text-gray-600">
      Already have an account?
      <Link href="/auth" class="text-blue-600 hover:underline">Sign in</Link>
    </p>
  </div>
</template>