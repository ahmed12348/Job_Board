<template>
  <div>
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold text-gray-900">My Jobs</h1>
        <p class="mt-2 text-sm text-gray-700">
          A list of all jobs you've posted, including their current status and number of applications.
        </p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <router-link
          to="/dashboard/jobs/new"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto"
        >
          Post New Job
        </router-link>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="mt-8 text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
      <p class="mt-2 text-sm text-gray-500">Loading jobs...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="mt-8 text-center">
      <ExclamationCircleIcon class="mx-auto h-12 w-12 text-red-500" />
      <p class="mt-2 text-sm text-red-600">{{ error }}</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="jobs.length === 0" class="mt-8 text-center">
      <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
      <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by posting a new job.</p>
      <div class="mt-6">
        <router-link
          to="/dashboard/jobs/new"
          class="inline-flex items-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        >
          <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
          Post New Job
        </router-link>
      </div>
    </div>

    <!-- Jobs List -->
    <div v-else class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                    Job Title
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Location
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Applications
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Status
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    Posted
                  </th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="job in jobs" :key="job.id">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                    {{ job.title }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ job.location }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ job.applications_count || 0 }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <span
                      class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                      :class="{
                        'bg-green-100 text-green-800': job.status === 'active',
                        'bg-gray-100 text-gray-800': job.status === 'inactive'
                      }"
                    >
                      {{ job.status }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    {{ new Date(job.created_at).toLocaleDateString() }}
                  </td>
                  <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <router-link
                      :to="`/jobs/${job.id}`"
                      class="text-primary-600 hover:text-primary-900"
                    >
                      View<span class="sr-only">, {{ job.title }}</span>
                    </router-link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useJobsStore } from '@/stores/jobs'
import {
  PlusIcon,
  DocumentIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/24/outline'

const jobsStore = useJobsStore()
const loading = ref(true)
const error = ref('')
const jobs = ref([])

onMounted(async () => {
  try {
    const response = await jobsStore.fetchCompanyJobs()
    jobs.value = response
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to fetch jobs'
  } finally {
    loading.value = false
  }
})
</script> 