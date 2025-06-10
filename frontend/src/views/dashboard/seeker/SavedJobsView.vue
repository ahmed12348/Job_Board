<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
          <h1 class="text-xl font-semibold text-gray-900">Saved Jobs</h1>
          <p class="mt-2 text-sm text-gray-700">
            A list of all jobs you've saved for later.
          </p>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="jobsStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
        <p class="mt-2 text-sm text-gray-500">Loading saved jobs...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="jobsStore.error" class="text-center py-12">
        <div class="rounded-md bg-red-50 p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">{{ jobsStore.error }}</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="jobsStore.savedJobs.length === 0" class="text-center py-12">
        <BookmarkIcon class="mx-auto h-12 w-12 text-gray-400" />
        <h3 class="mt-2 text-sm font-medium text-gray-900">No saved jobs</h3>
        <p class="mt-1 text-sm text-gray-500">
          Start saving jobs you're interested in to view them here.
        </p>
        <div class="mt-6">
          <router-link
            to="/jobs"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
          >
            <MagnifyingGlassIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
            Browse Jobs
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
                      Job
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Company
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Location
                    </th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                      Type
                    </th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                      <span class="sr-only">Actions</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  <tr v-for="job in jobsStore.savedJobs" :key="job.id">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                      <div class="font-medium text-gray-900">{{ job.title }}</div>
                      <div class="text-gray-500">{{ job.salary_range }}</div>
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ job.company?.name }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ job.location }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                      {{ job.type }}
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                      <router-link
                        :to="`/jobs/${job.id}`"
                        class="text-primary-600 hover:text-primary-900 mr-4"
                      >
                        View<span class="sr-only">, {{ job.title }}</span>
                      </router-link>
                      <button
                        type="button"
                        class="text-red-600 hover:text-red-900"
                        @click="handleUnsaveJob(job.id)"
                      >
                        Remove<span class="sr-only">, {{ job.title }}</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useJobsStore } from '@/stores/jobs'
import {
  BookmarkIcon,
  MagnifyingGlassIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'

const jobsStore = useJobsStore()

const handleUnsaveJob = async (jobId: number) => {
  try {
    await jobsStore.unsaveJob(jobId)
  } catch (error) {
    console.error('Failed to remove job from saved list:', error)
  }
}

onMounted(async () => {
  try {
    await jobsStore.fetchSavedJobs()
  } catch (error) {
    console.error('Failed to fetch saved jobs:', error)
  }
})
</script> 