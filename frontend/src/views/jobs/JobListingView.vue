<template>
  <div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
          <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
            Available Jobs
          </h2>
          <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
            <div class="mt-2 flex items-center text-sm text-gray-500">
              <BriefcaseIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
              {{ filteredJobs.length }} jobs found
            </div>
          </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
          <span v-if="isAuthenticated && isCompany" class="sm:ml-3">
            <router-link
              to="/dashboard/jobs/new"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
              Post a Job
            </router-link>
          </span>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Search Jobs</h3>
            <p class="mt-1 text-sm text-gray-500">
              Use the filters to find the perfect job for you.
            </p>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="handleSearch">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-3">
                  <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                  <input
                    type="text"
                    name="search"
                    id="search"
                    v-model="filters.search"
                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="Job title or keywords"
                  />
                </div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                  <input
                    type="text"
                    name="location"
                    id="location"
                    v-model="filters.location"
                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="City or remote"
                  />
                </div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="type" class="block text-sm font-medium text-gray-700">Job Type</label>
                  <select
                    id="type"
                    name="type"
                    v-model="filters.type"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  >
                    <option value="">All Types</option>
                    <option value="full-time">Full Time</option>
                    <option value="part-time">Part Time</option>
                    <option value="contract">Contract</option>
                    <option value="internship">Internship</option>
                  </select>
                </div>

                <div class="col-span-6 sm:col-span-3 flex items-end">
                  <button
                    type="submit"
                    class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                  >
                    Search Jobs
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="jobsStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
        <p class="mt-2 text-sm text-gray-500">Loading jobs...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="jobsStore.error" class="text-center py-12">
        <div class="rounded-md bg-red-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">
                {{ jobsStore.error }}
              </h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredJobs.length === 0" class="text-center py-12">
        <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
        <p class="mt-1 text-sm text-gray-500">
          Try adjusting your search filters or check back later for new opportunities.
        </p>
      </div>

      <!-- Jobs List -->
      <div v-else class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
          <li v-for="job in filteredJobs" :key="job.id">
            <router-link :to="`/jobs/${job.id}`" class="block hover:bg-gray-50">
              <div class="px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                  <p class="text-sm font-medium text-primary-600 truncate">
                    {{ job.title }}
                  </p>
                  <div class="ml-2 flex-shrink-0 flex">
                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                       :class="{
                         'bg-green-100 text-green-800': job.status === 'active',
                         'bg-gray-100 text-gray-800': job.status === 'inactive'
                       }"
                    >
                      {{ job.status }}
                    </p>
                  </div>
                </div>
                <div class="mt-2 sm:flex sm:justify-between">
                  <div class="sm:flex">
                    <p class="flex items-center text-sm text-gray-500">
                      <MapPinIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                      {{ job.location }}
                    </p>
                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                      <BriefcaseIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                      {{ job.type }}
                    </p>
                  </div>
                  <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                    <CalendarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                    <p>
                      Posted
                      <time :datetime="job.created_at">{{ formatDate(job.created_at) }}</time>
                    </p>
                  </div>
                </div>
              </div>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useJobsStore } from '@/stores/jobs'
import { useAuthStore } from '../../stores/auth'
import {
  PlusIcon,
  BriefcaseIcon,
  MapPinIcon,
  CalendarIcon,
  DocumentIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'

const jobsStore = useJobsStore()
const authStore = useAuthStore()

const isAuthenticated = computed(() => authStore.isAuthenticated)
const isCompany = computed(() => authStore.isCompany)
const filteredJobs = computed(() => jobsStore.filteredJobs)

const filters = ref({
  search: '',
  location: '',
  type: ''
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const handleSearch = () => {
  jobsStore.setFilters(filters.value)
}

onMounted(async () => {
  try {
    await jobsStore.fetchJobs()
  } catch (error) {
    console.error('Failed to fetch jobs:', error)
  }
})
</script>

<style scoped>
.input {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm;
}
</style> 