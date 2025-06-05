<template>
  <div class="py-6">
    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>
    
    <template v-else>
      <!-- Company Dashboard -->
      <div v-if="auth.isCompany">
        <h1 class="text-2xl font-semibold text-gray-900">Company Dashboard</h1>
        
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          <!-- Posted Jobs Card -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <BriefcaseIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Posted Jobs</dt>
                    <dd class="flex items-baseline">
                      <div class="text-2xl font-semibold text-gray-900">{{ stats.postedJobs || 0 }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm">
                <router-link to="/dashboard/jobs" class="font-medium text-primary-700 hover:text-primary-900">
                  View all jobs
                </router-link>
              </div>
            </div>
          </div>

          <!-- Active Applications Card -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <UserGroupIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Active Applications</dt>
                    <dd class="flex items-baseline">
                      <div class="text-2xl font-semibold text-gray-900">{{ stats.activeApplications || 0 }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm">
                <router-link to="/dashboard/applications" class="font-medium text-primary-700 hover:text-primary-900">
                  View all applications
                </router-link>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
              <div class="mt-4 space-y-2">
                <router-link
                  to="/dashboard/jobs/new"
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700"
                >
                  <PlusIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
                  Post New Job
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Job Seeker Dashboard -->
      <div v-else>
        <h1 class="text-2xl font-semibold text-gray-900">Job Seeker Dashboard</h1>
        
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
          <!-- Applications Card -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <DocumentTextIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">My Applications</dt>
                    <dd class="flex items-baseline">
                      <div class="text-2xl font-semibold text-gray-900">{{ stats.myApplications || 0 }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm">
                <router-link to="/dashboard/applications" class="font-medium text-primary-700 hover:text-primary-900">
                  View all applications
                </router-link>
              </div>
            </div>
          </div>

          <!-- Available Jobs Card -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <BriefcaseIcon class="h-6 w-6 text-gray-400" aria-hidden="true" />
                </div>
                <div class="ml-5 w-0 flex-1">
                  <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Available Jobs</dt>
                    <dd class="flex items-baseline">
                      <div class="text-2xl font-semibold text-gray-900">{{ stats.availableJobs || 0 }}</div>
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
              <div class="text-sm">
                <router-link to="/jobs" class="font-medium text-primary-700 hover:text-primary-900">
                  Browse all jobs
                </router-link>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
              <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
              <div class="mt-4 space-y-2">
                <router-link
                  to="/jobs"
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700"
                >
                  <MagnifyingGlassIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
                  Find Jobs
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useJobsStore } from '@/stores/jobs'
import { useApplicationsStore } from '@/stores/applications'
import {
  BriefcaseIcon,
  UserGroupIcon,
  DocumentTextIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

const auth = useAuthStore()
const jobsStore = useJobsStore()
const applicationsStore = useApplicationsStore()

const loading = ref(true)
const stats = ref({
  postedJobs: 0,
  activeApplications: 0,
  myApplications: 0,
  availableJobs: 0
})

onMounted(async () => {
  try {
    if (auth.isCompany) {
      await jobsStore.fetchCompanyJobs()
      stats.value.postedJobs = jobsStore.companyJobs.length
      stats.value.activeApplications = jobsStore.companyJobs.reduce(
        (total, job) => total + (job.applications_count || 0),
        0
      )
    } else {
      await Promise.all([
        applicationsStore.fetchMyApplications(),
        jobsStore.fetchJobs()
      ])
      stats.value.myApplications = applicationsStore.applications.length
      stats.value.availableJobs = jobsStore.jobs.length
    }
  } catch (error) {
    console.error('Failed to fetch dashboard stats:', error)
  } finally {
    loading.value = false
  }
})
</script> 