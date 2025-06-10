<template>
  <div class="relative">
    <!-- Hero section -->
    <div class="relative bg-white overflow-hidden">
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
          <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 lg:mt-16 lg:px-8 xl:mt-20">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Find your next</span>
                <span class="block text-primary-600">dream job</span>
              </h1>
              <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                Browse thousands of job opportunities from top companies and find the perfect role for your career growth.
              </p>
              <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                <!-- Browse Jobs button - shown to everyone -->
                <div class="rounded-md shadow">
                  <router-link
                    to="/jobs"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 md:py-4 md:text-lg md:px-10"
                  >
                    Browse Jobs
                  </router-link>
                </div>

                <!-- Post Job button - shown only to unauthenticated users -->
                <div v-if="!auth.isAuthenticated" class="mt-3 sm:mt-0 sm:ml-3">
                  <router-link
                    to="/register"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 md:py-4 md:text-lg md:px-10"
                  >
                    Post a Job
                  </router-link>
                </div>

                <!-- Company specific button - shown to authenticated companies -->
                <div v-if="auth.isEmployer" class="mt-3 sm:mt-0 sm:ml-3">
                  <router-link
                    to="/dashboard/jobs/new"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 md:py-4 md:text-lg md:px-10"
                  >
                    Post New Job
                  </router-link>
                </div>

                <!-- Job seeker specific button - shown to authenticated job seekers -->
                <div v-if="auth.isJobSeeker" class="mt-3 sm:mt-0 sm:ml-3">
                  <router-link
                    to="/dashboard/applications"
                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 md:py-4 md:text-lg md:px-10"
                  >
                    My Applications
                  </router-link>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </div>

    <!-- Featured jobs section -->
    <div class="bg-gray-50 py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-base text-primary-600 font-semibold tracking-wide uppercase">Featured Jobs</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
            Latest Opportunities
          </p>
        </div>

        <div class="mt-10">
          <div v-if="loading" class="text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
          </div>

          <div v-else-if="error" class="text-center text-red-600">
            {{ error }}
          </div>

          <div v-else class="grid gap-6 lg:grid-cols-2">
            <div
              v-for="job in jobs.slice(0, 4)"
              :key="job.id"
              class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow duration-200"
            >
              <div class="flex justify-between items-start">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">
                    <router-link :to="'/jobs/' + job.id" class="hover:text-primary-600">
                      {{ job.title }}
                    </router-link>
                  </h3>
                  <p class="mt-1 text-sm text-gray-500">{{ job.company.name }}</p>
                </div>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-green-100 text-green-800': job.status === 'active',
                    'bg-gray-100 text-gray-800': job.status === 'inactive'
                  }"
                >
                  {{ job.status }}
                </span>
              </div>
              <div class="mt-4 flex items-center space-x-4 text-sm text-gray-500">
                <div class="flex items-center">
                  <MapPinIcon class="h-4 w-4 mr-1" />
                  {{ job.location }}
                </div>
                <div class="flex items-center">
                  <CurrencyDollarIcon class="h-4 w-4 mr-1" />
                  {{ job.salary_range }}
                </div>
                <div class="flex items-center">
                  <BriefcaseIcon class="h-4 w-4 mr-1" />
                  {{ job.job_type }}
                </div>
              </div>
              <p class="mt-3 text-sm text-gray-500 line-clamp-2">
                {{ job.description }}
              </p>
            </div>
          </div>

          <div class="mt-10 text-center">
            <router-link
              to="/jobs"
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200"
            >
              View all jobs
              <ArrowRightIcon class="ml-2 h-5 w-5" />
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useJobsStore } from '@/stores/jobs'
import {
  MapPinIcon,
  CurrencyDollarIcon,
  BriefcaseIcon,
  ArrowRightIcon,
} from '@heroicons/vue/24/outline'

interface Job {
  id: number
  title: string
  company_id: number
  description: string
  requirements: string
  location: string
  salary_range: string
  type: string
  status: 'active' | 'inactive'
  created_at: string
  updated_at: string
  applications_count?: number
}

const auth = useAuthStore()
const jobsStore = useJobsStore()

const loading = ref(false)
const error = ref('')
const jobs = ref<Job[]>([])

onMounted(async () => {
  try {
    loading.value = true
    const fetchedJobs = await jobsStore.fetchJobs()
    jobs.value = fetchedJobs
  } catch (err: any) {
    error.value = err.message || 'Failed to fetch jobs'
  } finally {
    loading.value = false
  }
})
</script> 