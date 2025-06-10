<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
      <p class="mt-2 text-sm text-gray-500">Loading job details...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-12">
      <div class="rounded-md bg-red-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- Job Details -->
    <div v-else-if="job" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between items-start">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ job.title }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Posted {{ formatDate(job.created_at) }}</p>
          </div>
          <div v-if="auth.isJobSeeker" class="flex-shrink-0">
            <button
              v-if="!hasApplied"
              type="button"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              @click="showApplicationModal = true"
            >
              Apply Now
            </button>
            <span
              v-else
              class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800"
            >
              Applied
            </span>
          </div>
        </div>
      </div>
      <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Location</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ job.location }}</dd>
          </div>
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Job Type</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ job.type }}</dd>
          </div>
          <div class="sm:col-span-1">
            <dt class="text-sm font-medium text-gray-500">Salary Range</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ job.salary_range }}</dd>
          </div>
          <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Description</dt>
            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ job.description }}</dd>
          </div>
          <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Requirements</dt>
            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ job.requirements }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Application Modal -->
    <TransitionRoot appear :show="showApplicationModal" as="template">
      <Dialog as="div" @close="showApplicationModal = false" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Apply for {{ job?.title }}
                </DialogTitle>

                <form @submit.prevent="handleSubmitApplication" class="mt-4">
                  <div>
                    <label for="cover_letter" class="block text-sm font-medium text-gray-700">
                      Cover Letter
                    </label>
                    <div class="mt-1">
                      <textarea
                        id="cover_letter"
                        v-model="coverLetter"
                        rows="4"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                        placeholder="Tell us why you're a great fit for this position..."
                        required
                      />
                    </div>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button
                      type="button"
                      class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                      @click="showApplicationModal = false"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      :disabled="submitting"
                      class="inline-flex justify-center rounded-md border border-transparent bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    >
                      <template v-if="submitting">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Submitting...
                      </template>
                      <template v-else>Submit Application</template>
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useJobsStore } from '@/stores/jobs'
import { useAuthStore } from '@/stores/auth'
import { useApplicationsStore } from '@/stores/applications'
import { XCircleIcon } from '@heroicons/vue/24/outline'
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'

const route = useRoute()
const router = useRouter()
const jobsStore = useJobsStore()
const auth = useAuthStore()
const applicationsStore = useApplicationsStore()

const loading = ref(false)
const error = ref('')
const job = ref(null)
const showApplicationModal = ref(false)
const coverLetter = ref('')
const submitting = ref(false)

const hasApplied = computed(() => {
  if (!job.value) return false
  return applicationsStore.applicationsByJob(job.value.id).length > 0
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const fetchJobDetails = async () => {
  loading.value = true
  error.value = ''
  try {
    const jobId = Number(route.params.id)
    if (isNaN(jobId)) {
      error.value = 'Invalid job ID'
      router.push('/jobs')
      return
    }
    
    job.value = await jobsStore.fetchJob(jobId)
    if (auth.isJobSeeker) {
      await applicationsStore.fetchMyApplications()
    }
  } catch (err: any) {
    if (err.response?.status === 404) {
      error.value = 'Job posting not found or has been removed'
      setTimeout(() => router.push('/jobs'), 2000) // Redirect after showing error
    } else {
      error.value = err.message || 'Failed to fetch job details'
    }
    console.error('Error fetching job details:', err)
  } finally {
    loading.value = false
  }
}

const handleSubmitApplication = async () => {
  if (!job.value) return
  
  submitting.value = true
  error.value = ''
  try {
    await applicationsStore.applyToJob(job.value.id, coverLetter.value)
    showApplicationModal.value = false
    coverLetter.value = ''
    // Refresh applications list
    await applicationsStore.fetchMyApplications()
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to submit application'
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  fetchJobDetails()
})
</script> 