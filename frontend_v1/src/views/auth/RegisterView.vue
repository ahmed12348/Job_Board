<template>
  <div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Create your account</h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Or
        <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-500">
          sign in to your account
        </router-link>
      </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <!-- Success Message -->
        <div v-if="success" class="rounded-md bg-green-50 p-4 mb-6">
          <div class="flex">
            <div class="flex-shrink-0">
              <CheckCircleIcon class="h-5 w-5 text-green-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-green-800">
                Registration successful!
              </h3>
              <div class="mt-2 text-sm text-green-700">
                <p>Redirecting you to login...</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="rounded-md bg-red-50 p-4 mb-6">
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

        <form class="space-y-6" @submit.prevent="handleSubmit">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Full name
            </label>
            <div class="mt-1">
              <input
                id="name"
                v-model="name"
                name="name"
                type="text"
                required
                class="input"
                :class="{ 'border-red-300': v$.name.$error }"
              />
            </div>
            <p v-if="v$.name.$error" class="mt-2 text-sm text-red-600">
              {{ v$.name.$errors[0].$message }}
            </p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email address
            </label>
            <div class="mt-1">
              <input
                id="email"
                v-model="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                class="input"
                :class="{ 'border-red-300': v$.email.$error }"
              />
            </div>
            <p v-if="v$.email.$error" class="mt-2 text-sm text-red-600">
              {{ v$.email.$errors[0].$message }}
            </p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="password"
                name="password"
                type="password"
                required
                class="input"
                :class="{ 'border-red-300': v$.password.$error }"
              />
            </div>
            <p v-if="v$.password.$error" class="mt-2 text-sm text-red-600">
              {{ v$.password.$errors[0].$message }}
            </p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
              Confirm password
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="passwordConfirmation"
                name="password_confirmation"
                type="password"
                required
                class="input"
                :class="{ 'border-red-300': v$.passwordConfirmation.$error }"
              />
            </div>
            <p v-if="v$.passwordConfirmation.$error" class="mt-2 text-sm text-red-600">
              {{ v$.passwordConfirmation.$errors[0].$message }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Account type</label>
            <div class="mt-2 space-y-4">
              <div class="relative flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="job_seeker"
                    v-model="type"
                    name="type"
                    type="radio"
                    value="job_seeker"
                    class="h-4 w-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="job_seeker" class="font-medium text-gray-700">Job Seeker</label>
                  <p class="text-gray-500">I want to find and apply for jobs</p>
                </div>
              </div>
              <div class="relative flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="company"
                    v-model="type"
                    name="type"
                    type="radio"
                    value="company"
                    class="h-4 w-4 text-primary-600 border-gray-300 focus:ring-primary-500"
                  />
                </div>
                <div class="ml-3 text-sm">
                  <label for="company" class="font-medium text-gray-700">Company</label>
                  <p class="text-gray-500">I want to post jobs and manage applications</p>
                </div>
              </div>
            </div>
            <p v-if="v$.type.$error" class="mt-2 text-sm text-red-600">
              {{ v$.type.$errors[0].$message }}
            </p>
          </div>

          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              :disabled="loading"
            >
              <template v-if="loading">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating account...
              </template>
              <template v-else>
                Create account
              </template>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { XCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/solid'
import { useVuelidate } from '@vuelidate/core'
import { required, email as emailValidator, minLength, sameAs } from '@vuelidate/validators'

const router = useRouter()
const auth = useAuthStore()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const type = ref('')
const loading = ref(false)
const error = ref('')
const success = ref(false)

const rules = {
  name: { required },
  email: { required, email: emailValidator },
  password: { required, minLength: minLength(6) },
  passwordConfirmation: { required, sameAs: sameAs(password) },
  type: { required }
}

const v$ = useVuelidate(rules, {
  name,
  email,
  password,
  passwordConfirmation,
  type
})

const handleSubmit = async () => {
  error.value = ''
  const isValid = await v$.value.$validate()
  if (!isValid) return

  loading.value = true
  try {
    await auth.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      type: type.value === 'company' ? 'company' : 'seeker'
    })
    
    success.value = true
    // Show success message and redirect after a short delay
    setTimeout(() => {
      router.push({
        path: '/login',
        query: { 
          message: 'Registration successful! Please sign in to continue.',
          email: email.value
        }
      })
    }, 1500)
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create account. Please try again.'
    if (err.response?.data?.errors) {
      const errors = err.response.data.errors
      Object.keys(errors).forEach(key => {
        if (v$.value[key]) {
          v$.value[key].$errors.push({ $message: errors[key][0] })
        }
      })
    }
  } finally {
    loading.value = false
  }
}
</script> 