<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
        <p class="mt-2 text-sm text-gray-500">Loading applications...</p>
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

      <template v-else>
        <!-- Job Header -->
        <div class="lg:flex lg:items-center lg:justify-between">
          <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              {{ job?.title }}
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <MapPinIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                {{ job?.location }}
              </div>
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <BriefcaseIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                {{ job?.type }}
              </div>
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <CurrencyDollarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                {{ job?.salary_range }}
              </div>
              <div class="mt-2 flex items-center text-sm">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-green-100 text-green-800': job?.status === 'active',
                    'bg-gray-100 text-gray-800': job?.status === 'inactive'
                  }"
                >
                  {{ job?.status }}
                </span>
              </div>
            </div>
          </div>
          <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <router-link
              :to="`/dashboard/jobs/${job?.id}/edit`"
              class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <PencilIcon class="-ml-1 mr-2 h-5 w-5 text-gray-500" aria-hidden="true" />
              Edit Job
            </router-link>
          </div>
        </div>

        <!-- Applications List -->
        <div class="mt-8">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Applications</h3>
              <p class="mt-2 text-sm text-gray-700">
                A list of all applications for this position.
              </p>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="applications.length === 0" class="text-center py-12">
            <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">No applications</h3>
            <p class="mt-1 text-sm text-gray-500">
              No one has applied for this position yet.
            </p>
          </div>

          <!-- Applications Table -->
          <div v-else class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                          Applicant
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                          Status
                        </th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                          Applied On
                        </th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">Actions</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                      <tr v-for="application in applications" :key="application.id">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                          <div class="font-medium text-gray-900">{{ application.user?.name }}</div>
                          <div class="text-gray-500">{{ application.user?.email }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                          <span
                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                            :class="{
                              'bg-yellow-100 text-yellow-800': application.status === 'pending',
                              'bg-green-100 text-green-800': application.status === 'accepted',
                              'bg-red-100 text-red-800': application.status === 'rejected'
                            }"
                          >
                            {{ application.status }}
                          </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          {{ formatDate(application.created_at) }}
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                          <Menu as="div" class="relative inline-block text-left">
                            <div>
                              <MenuButton class="inline-flex items-center rounded-full p-1.5 text-gray-400 hover:text-gray-600">
                                <span class="sr-only">Open options</span>
                                <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                              </MenuButton>
                            </div>

                            <transition
                              enter-active-class="transition ease-out duration-100"
                              enter-from-class="transform opacity-0 scale-95"
                              enter-to-class="transform opacity-100 scale-100"
                              leave-active-class="transition ease-in duration-75"
                              leave-from-class="transform opacity-100 scale-100"
                              leave-to-class="transform opacity-0 scale-95"
                            >
                              <MenuItems class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="py-1">
                                  <MenuItem v-slot="{ active }">
                                    <button
                                      type="button"
                                      :class="[
                                        active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                        'block w-full px-4 py-2 text-left text-sm'
                                      ]"
                                      @click="showApplicationDetails(application)"
                                    >
                                      View Details
                                    </button>
                                  </MenuItem>
                                  <MenuItem v-if="application.status === 'pending'" v-slot="{ active }">
                                    <button
                                      type="button"
                                      :class="[
                                        active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                        'block w-full px-4 py-2 text-left text-sm'
                                      ]"
                                      @click="updateApplicationStatus(application.id, 'accepted')"
                                    >
                                      Accept
                                    </button>
                                  </MenuItem>
                                  <MenuItem v-if="application.status === 'pending'" v-slot="{ active }">
                                    <button
                                      type="button"
                                      :class="[
                                        active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                        'block w-full px-4 py-2 text-left text-sm'
                                      ]"
                                      @click="updateApplicationStatus(application.id, 'rejected')"
                                    >
                                      Reject
                                    </button>
                                  </MenuItem>
                                </div>
                              </MenuItems>
                            </transition>
                          </Menu>
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

      <!-- Application Details Modal -->
      <TransitionRoot appear :show="showDetailsModal" as="template">
        <Dialog as="div" @close="showDetailsModal = false" class="relative z-10">
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
                    Application Details
                  </DialogTitle>

                  <div v-if="selectedApplication" class="mt-4">
                    <div class="space-y-4">
                      <div>
                        <h4 class="text-sm font-medium text-gray-500">Applicant</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ selectedApplication.user?.name }}</p>
                        <p class="text-sm text-gray-500">{{ selectedApplication.user?.email }}</p>
                      </div>

                      <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <p class="mt-1">
                          <span
                            class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                            :class="{
                              'bg-yellow-100 text-yellow-800': selectedApplication.status === 'pending',
                              'bg-green-100 text-green-800': selectedApplication.status === 'accepted',
                              'bg-red-100 text-red-800': selectedApplication.status === 'rejected'
                            }"
                          >
                            {{ selectedApplication.status }}
                          </span>
                        </p>
                      </div>

                      <div>
                        <h4 class="text-sm font-medium text-gray-500">Applied On</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedApplication.created_at) }}</p>
                      </div>

                      <div>
                        <h4 class="text-sm font-medium text-gray-500">Cover Letter</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ selectedApplication.cover_letter }}</p>
                      </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                      <button
                        type="button"
                        class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                        @click="showDetailsModal = false"
                      >
                        Close
                      </button>
                      <template v-if="selectedApplication.status === 'pending'">
                        <button
                          type="button"
                          class="inline-flex justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                          @click="updateApplicationStatus(selectedApplication.id, 'accepted')"
                        >
                          Accept
                        </button>
                        <button
                          type="button"
                          class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                          @click="updateApplicationStatus(selectedApplication.id, 'rejected')"
                        >
                          Reject
                        </button>
                      </template>
                    </div>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useJobsStore } from '@/stores/jobs'
import { useApplicationsStore } from '@/stores/applications'
import {
  BriefcaseIcon,
  CurrencyDollarIcon,
  DocumentIcon,
  EllipsisVerticalIcon,
  MapPinIcon,
  PencilIcon,
  XCircleIcon
} from '@heroicons/vue/24/outline'
import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot
} from '@headlessui/vue'

const route = useRoute()
const jobsStore = useJobsStore()
const applicationsStore = useApplicationsStore()

const loading = ref(true)
const error = ref('')
const job = ref(null)
const applications = ref([])
const showDetailsModal = ref(false)
const selectedApplication = ref(null)

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const showApplicationDetails = (application) => {
  selectedApplication.value = application
  showDetailsModal.value = true
}

const updateApplicationStatus = async (applicationId: number, status: 'accepted' | 'rejected') => {
  try {
    await applicationsStore.updateApplicationStatus(applicationId, status)
    // Update the status in the local list
    const application = applications.value.find(app => app.id === applicationId)
    if (application) {
      application.status = status
    }
    if (selectedApplication.value?.id === applicationId) {
      selectedApplication.value.status = status
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to update application status'
  }
}

onMounted(async () => {
  try {
    const jobId = Number(route.params.id)
    const [jobData, applicationsData] = await Promise.all([
      jobsStore.fetchJob(jobId),
      applicationsStore.fetchJobApplications(jobId)
    ])
    job.value = jobData
    applications.value = applicationsData
  } catch (err: any) {
    error.value = err.message || 'Failed to load job and applications'
  } finally {
    loading.value = false
  }
})
</script> 