<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              {{ isEditing ? 'Edit Job' : 'Post New Job' }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">
              {{ isEditing ? 'Update your job posting details.' : 'Create a new job posting.' }}
            </p>
          </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
          <form @submit.prevent="handleSubmit">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <!-- Title -->
                <div>
                  <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                  <div class="mt-1">
                    <input
                      type="text"
                      name="title"
                      id="title"
                      v-model="form.title"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    />
                  </div>
                </div>

                <!-- Location -->
                <div>
                  <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                  <div class="mt-1">
                    <input
                      type="text"
                      name="location"
                      id="location"
                      v-model="form.location"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    />
                  </div>
                </div>

                <!-- Type -->
                <div>
                  <label for="type" class="block text-sm font-medium text-gray-700">Job Type</label>
                  <div class="mt-1">
                    <select
                      id="type"
                      name="type"
                      v-model="form.type"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    >
                      <option value="full-time">Full Time</option>
                      <option value="part-time">Part Time</option>
                      <option value="contract">Contract</option>
                      <option value="internship">Internship</option>
                    </select>
                  </div>
                </div>

                <!-- Salary Range -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                  <div>
                    <label for="salary_min" class="block text-sm font-medium text-gray-700">Minimum Salary</label>
                    <div class="mt-1">
                      <input
                        type="number"
                        name="salary_min"
                        id="salary_min"
                        v-model="form.salary_min"
                        placeholder="e.g. 50000"
                        min="0"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                  </div>
                  <div>
                    <label for="salary_max" class="block text-sm font-medium text-gray-700">Maximum Salary</label>
                    <div class="mt-1">
                      <input
                        type="number"
                        name="salary_max"
                        id="salary_max"
                        v-model="form.salary_max"
                        placeholder="e.g. 70000"
                        min="0"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <div class="mt-1">
                    <textarea
                      id="description"
                      name="description"
                      rows="4"
                      v-model="form.description"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    ></textarea>
                  </div>
                </div>

                <!-- Requirements -->
                <div>
                  <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements</label>
                  <div class="mt-1">
                    <textarea
                      id="requirements"
                      name="requirements"
                      rows="4"
                      v-model="form.requirements"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    ></textarea>
                  </div>
                </div>

                <!-- Benefits -->
                <div>
                  <label for="benefits" class="block text-sm font-medium text-gray-700">Benefits</label>
                  <div class="mt-1">
                    <textarea
                      id="benefits"
                      name="benefits"
                      rows="4"
                      v-model="form.benefits"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    ></textarea>
                  </div>
                </div>

                <!-- Status -->
                <div>
                  <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                  <div class="mt-1">
                    <select
                      id="status"
                      name="status"
                      v-model="form.status"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      required
                    >
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="px-4 py-3 bg-gray-50 text-right sm:p-6">
                <span v-if="error" class="mr-3 text-sm text-red-600">{{ error }}</span>
                <button
                  type="button"
                  class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                  @click="$router.back()"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
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
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useJobsStore } from '@/stores/jobs'

const route = useRoute()
const router = useRouter()
const jobsStore = useJobsStore()

const loading = ref(false)
const error = ref('')

const isEditing = computed(() => route.name === 'edit-job')

const form = ref({
  title: '',
  location: '',
  type: 'full-time',
  salary_min: '',
  salary_max: '',
  description: '',
  requirements: '',
  benefits: '',
  status: 'active'
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    const jobData = {
      ...form.value,
      salary_min: form.value.salary_min ? Number(form.value.salary_min) : null,
      salary_max: form.value.salary_max ? Number(form.value.salary_max) : null
    }

    if (isEditing.value) {
      const jobId = Number(route.params.id)
      await jobsStore.updateJob(jobId, jobData)
    } else {
      await jobsStore.createJob(jobData)
    }
    router.push('/dashboard/jobs')
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to save job'
    console.error('Error saving job:', err.response?.data)
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  if (isEditing.value) {
    loading.value = true
    try {
      const jobId = Number(route.params.id)
      const job = await jobsStore.fetchJob(jobId)
      form.value = {
        title: job.title,
        location: job.location,
        type: job.type,
        salary_min: job.salary_min ? job.salary_min.toString() : '',
        salary_max: job.salary_max ? job.salary_max.toString() : '',
        description: job.description,
        requirements: job.requirements,
        benefits: job.benefits,
        status: job.status
      }
    } catch (err: any) {
      error.value = err.message || 'Failed to fetch job details'
    } finally {
      loading.value = false
    }
  }
})
</script> 