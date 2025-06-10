<template>
  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Profile Settings
          </h2>
        </div>
      </div>

      <div class="mt-8 max-w-3xl">
        <form @submit.prevent="handleSubmit" class="space-y-8 divide-y divide-gray-200">
          <div class="space-y-8 divide-y divide-gray-200">
            <div>
              <div v-if="error" class="mb-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                      {{ error }}
                    </h3>
                  </div>
                </div>
              </div>

              <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-4">
                  <label for="name" class="block text-sm font-medium text-gray-700">
                    Full Name
                  </label>
                  <div class="mt-1">
                    <input
                      type="text"
                      name="name"
                      id="name"
                      v-model="form.name"
                      class="input"
                      :class="{ 'border-red-300': v$.name.$error }"
                    />
                  </div>
                  <p v-if="v$.name.$error" class="mt-2 text-sm text-red-600">
                    {{ v$.name.$errors[0].$message }}
                  </p>
                </div>

                <div class="sm:col-span-4">
                  <label for="email" class="block text-sm font-medium text-gray-700">
                    Email Address
                  </label>
                  <div class="mt-1">
                    <input
                      type="email"
                      name="email"
                      id="email"
                      v-model="form.email"
                      class="input"
                      :class="{ 'border-red-300': v$.email.$error }"
                    />
                  </div>
                  <p v-if="v$.email.$error" class="mt-2 text-sm text-red-600">
                    {{ v$.email.$errors[0].$message }}
                  </p>
                </div>

                <div class="sm:col-span-4">
                  <label for="current_password" class="block text-sm font-medium text-gray-700">
                    Current Password
                  </label>
                  <div class="mt-1">
                    <input
                      type="password"
                      name="current_password"
                      id="current_password"
                      v-model="form.current_password"
                      class="input"
                      :class="{ 'border-red-300': v$.current_password.$error }"
                    />
                  </div>
                  <p v-if="v$.current_password.$error" class="mt-2 text-sm text-red-600">
                    {{ v$.current_password.$errors[0].$message }}
                  </p>
                </div>

                <div class="sm:col-span-4">
                  <label for="new_password" class="block text-sm font-medium text-gray-700">
                    New Password
                  </label>
                  <div class="mt-1">
                    <input
                      type="password"
                      name="new_password"
                      id="new_password"
                      v-model="form.new_password"
                      class="input"
                    />
                  </div>
                  <p class="mt-2 text-sm text-gray-500">
                    Leave blank to keep your current password
                  </p>
                </div>

                <div class="sm:col-span-4">
                  <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">
                    Confirm New Password
                  </label>
                  <div class="mt-1">
                    <input
                      type="password"
                      name="new_password_confirmation"
                      id="new_password_confirmation"
                      v-model="form.new_password_confirmation"
                      class="input"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-5">
            <div class="flex justify-end">
              <button
                type="submit"
                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                :disabled="loading"
              >
                <template v-if="loading">
                  <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Saving...
                </template>
                <template v-else>
                  Save Changes
                </template>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { XCircleIcon } from '@heroicons/vue/24/solid'
import { useVuelidate } from '@vuelidate/core'
import { required, email as emailValidator } from '@vuelidate/validators'

const auth = useAuthStore()
const loading = ref(false)
const error = ref('')

const form = ref({
  name: auth.user?.name || '',
  email: auth.user?.email || '',
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const rules = {
  name: { required },
  email: { required, email: emailValidator },
  current_password: { required }
}

const v$ = useVuelidate(rules, form)

const handleSubmit = async () => {
  error.value = ''
  const isValid = await v$.value.$validate()
  if (!isValid) return

  loading.value = true
  try {
    // TODO: Implement profile update API call
    console.log('Profile update:', form.value)
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to update profile'
  } finally {
    loading.value = false
  }
}
</script> 