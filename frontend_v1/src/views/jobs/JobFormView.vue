<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Job</h1>
        <p class="mt-2 text-sm text-gray-500">Post a new job opportunity for your company.</p>
      </div>
    </div>

    <form @submit.prevent="handleSubmit" class="mt-8 space-y-6">
      <div v-if="error" class="rounded-md bg-red-50 p-4">
        <div class="flex">
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
          </div>
        </div>
      </div>

      <!-- Job Title -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
        <div class="mt-1">
          <input
            id="title"
            v-model="form.title"
            type="text"
            required
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
          />
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <div class="mt-1">
          <textarea
            id="description"
            v-model="form.description"
            rows="4"
            required
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
          />
        </div>
        <p class="mt-2 text-sm text-gray-500">Write a detailed description of the job role and responsibilities.</p>
      </div>

      <!-- Requirements -->
      <div>
        <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
        <div class="mt-1">
          <textarea
            id="requirements"
            v-model="form.requirements"
            rows="4"
            required
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
          />
        </div>
        <p class="mt-2 text-sm text-gray-500">List the required skills, experience, and qualifications.</p>
      </div>

      <!-- Location -->
      <div>
        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
        <div class="mt-1">
          <input
            id="location"
            v-model="form.location"
            type="text"
            required
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
          />
        </div>
        <p class="mt-2 text-sm text-gray-500">Specify if it's remote, hybrid, or on-site with the location.</p>
      </div>

      <!-- Salary Range -->
      <div>
        <label for="salary_range" class="block text-sm font-medium text-gray-700">Salary Range</label>
        <div class="mt-1">
          <input
            id="salary_range"
            v-model="form.salary_range"
            type="text"
            required
            placeholder="e.g. $50,000 - $70,000"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
          />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end space-x-4">
        <router-link
          to="/dashboard"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Cancel
        </router-link>
        <button
          type="submit"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          <template v-if="loading">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating...
          </template>
          <template v-else>
            Create Job
          </template>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useJobStore } from '@/stores/jobs'

const router = useRouter()
const jobStore = useJobStore()

const loading = ref(false)
const error = ref('')

const form = ref({
  title: '',
  description: '',
  requirements: '',
  location: '',
  salary_range: ''
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''

  try {
    await jobStore.createJob(form.value)
    router.push('/dashboard')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to create job'
  } finally {
    loading.value = false
  }
}
</script> 