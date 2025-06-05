<template>
  <form @submit.prevent="handleSubmit" class="space-y-8 divide-y divide-gray-200">
    <div class="space-y-8 divide-y divide-gray-200">
      <div>
        <div>
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ isEditing ? 'Edit Job Posting' : 'Create New Job Posting' }}
          </h3>
          <p class="mt-1 text-sm text-gray-500">
            {{ isEditing ? 'Update the details of your job posting.' : 'Fill in the details of your new job posting.' }}
          </p>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
          <div class="sm:col-span-4">
            <label for="title" class="block text-sm font-medium text-gray-700">
              Job Title
            </label>
            <div class="mt-1">
              <input
                type="text"
                name="title"
                id="title"
                v-model="form.title"
                class="input"
                :class="{ 'border-red-300': v$.title.$error }"
              />
            </div>
            <p v-if="v$.title.$error" class="mt-2 text-sm text-red-600">
              {{ v$.title.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-4">
            <label for="location" class="block text-sm font-medium text-gray-700">
              Location
            </label>
            <div class="mt-1">
              <input
                type="text"
                name="location"
                id="location"
                v-model="form.location"
                class="input"
                :class="{ 'border-red-300': v$.location.$error }"
              />
            </div>
            <p v-if="v$.location.$error" class="mt-2 text-sm text-red-600">
              {{ v$.location.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-4">
            <label for="salary_range" class="block text-sm font-medium text-gray-700">
              Salary Range
            </label>
            <div class="mt-1">
              <input
                type="text"
                name="salary_range"
                id="salary_range"
                v-model="form.salary_range"
                class="input"
                :class="{ 'border-red-300': v$.salary_range.$error }"
                placeholder="e.g. $50,000 - $70,000"
              />
            </div>
            <p v-if="v$.salary_range.$error" class="mt-2 text-sm text-red-600">
              {{ v$.salary_range.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-3">
            <label for="job_type" class="block text-sm font-medium text-gray-700">
              Job Type
            </label>
            <div class="mt-1">
              <select
                id="job_type"
                name="job_type"
                v-model="form.job_type"
                class="input"
                :class="{ 'border-red-300': v$.job_type.$error }"
              >
                <option value="">Select a type</option>
                <option value="full_time">Full Time</option>
                <option value="part_time">Part Time</option>
                <option value="contract">Contract</option>
                <option value="internship">Internship</option>
              </select>
            </div>
            <p v-if="v$.job_type.$error" class="mt-2 text-sm text-red-600">
              {{ v$.job_type.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-6">
            <label for="description" class="block text-sm font-medium text-gray-700">
              Job Description
            </label>
            <div class="mt-1">
              <textarea
                id="description"
                name="description"
                rows="5"
                v-model="form.description"
                class="input"
                :class="{ 'border-red-300': v$.description.$error }"
              />
            </div>
            <p v-if="v$.description.$error" class="mt-2 text-sm text-red-600">
              {{ v$.description.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-6">
            <label for="requirements" class="block text-sm font-medium text-gray-700">
              Requirements
            </label>
            <div class="mt-1">
              <textarea
                id="requirements"
                name="requirements"
                rows="5"
                v-model="form.requirements"
                class="input"
                :class="{ 'border-red-300': v$.requirements.$error }"
              />
            </div>
            <p v-if="v$.requirements.$error" class="mt-2 text-sm text-red-600">
              {{ v$.requirements.$errors[0].$message }}
            </p>
          </div>

          <div class="sm:col-span-3">
            <label for="status" class="block text-sm font-medium text-gray-700">
              Status
            </label>
            <div class="mt-1">
              <select
                id="status"
                name="status"
                v-model="form.status"
                class="input"
                :class="{ 'border-red-300': v$.status.$error }"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <p v-if="v$.status.$error" class="mt-2 text-sm text-red-600">
              {{ v$.status.$errors[0].$message }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="pt-5">
      <div class="flex justify-end">
        <router-link
          to="/dashboard"
          class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          Cancel
        </router-link>
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
            {{ isEditing ? 'Updating...' : 'Creating...' }}
          </template>
          <template v-else>
            {{ isEditing ? 'Update Job' : 'Create Job' }}
          </template>
        </button>
      </div>
    </div>
  </form>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useJobsStore } from '../stores/jobs'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'

const props = defineProps<{
  initialData?: {
    id?: number
    title: string
    description: string
    location: string
    salary_range: string
    job_type: string
    requirements: string
    status: 'active' | 'inactive'
  }
}>()

const router = useRouter()
const jobsStore = useJobsStore()
const loading = ref(false)

const form = ref({
  title: props.initialData?.title || '',
  description: props.initialData?.description || '',
  location: props.initialData?.location || '',
  salary_range: props.initialData?.salary_range || '',
  job_type: props.initialData?.job_type || '',
  requirements: props.initialData?.requirements || '',
  status: props.initialData?.status || 'active'
})

const rules = {
  title: { required, minLength: minLength(5) },
  description: { required, minLength: minLength(50) },
  location: { required },
  salary_range: { required },
  job_type: { required },
  requirements: { required, minLength: minLength(50) },
  status: { required }
}

const v$ = useVuelidate(rules, form)

const isEditing = computed(() => !!props.initialData?.id)

const handleSubmit = async () => {
  const isValid = await v$.value.$validate()
  if (!isValid) return

  loading.value = true
  try {
    if (isEditing.value && props.initialData?.id) {
      await jobsStore.updateJob(props.initialData.id, form.value)
    } else {
      await jobsStore.createJob(form.value)
    }
    router.push('/dashboard')
  } catch (error) {
    console.error('Failed to save job:', error)
  } finally {
    loading.value = false
  }
}
</script> 