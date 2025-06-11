<template>
  <MainLayout>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:flex lg:items-center lg:justify-between">
          <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
              My Applications
            </h2>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
              <div class="mt-2 flex items-center text-sm text-gray-500">
                <DocumentTextIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                {{ applicationsStore.applications.length }} applications submitted
              </div>
            </div>
          </div>
          <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <router-link
              to="/jobs"
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <MagnifyingGlassIcon class="-ml-1 mr-2 h-5 w-5" aria-hidden="true" />
              Browse Jobs
            </router-link>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="applicationsStore.loading" class="mt-8 text-center py-12">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-primary-500 border-t-transparent"></div>
          <p class="mt-2 text-sm text-gray-500">Loading applications...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="applicationsStore.error" class="mt-8 text-center py-12">
          <div class="rounded-md bg-red-50 p-4">
            <div class="flex">
              <div class="flex-shrink-0">
                <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
              </div>
              <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                  {{ applicationsStore.error }}
                </h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="applicationsStore.applications.length === 0" class="mt-8 text-center py-12">
          <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
          <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
          <p class="mt-1 text-sm text-gray-500">
            Start applying to jobs to track your applications here.
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

        <!-- Applications List -->
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
                    <tr v-for="application in applicationsStore.applications" :key="application.id">
                      <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                        <div class="font-medium text-gray-900">{{ application.job?.title }}</div>
                        <div class="text-gray-500">{{ application.job?.company_name }}</div>
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
                                  <router-link
                                    :to="`/jobs/${application.job_id}`"
                                    :class="[
                                      active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                      'block px-4 py-2 text-sm'
                                    ]"
                                  >
                                    View Job
                                  </router-link>
                                </MenuItem>
                                <MenuItem v-slot="{ active }">
                                  <button
                                    type="button"
                                    :class="[
                                      active ? 'bg-gray-100 text-gray-900' : 'text-gray-700',
                                      'block w-full px-4 py-2 text-left text-sm'
                                    ]"
                                    @click="confirmWithdrawApplication(application)"
                                  >
                                    Withdraw Application
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

      <!-- Withdraw Confirmation Modal -->
      <TransitionRoot appear :show="showWithdrawModal" as="template">
        <Dialog as="div" @close="showWithdrawModal = false" class="relative z-10">
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
                    Withdraw Application
                  </DialogTitle>

                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      Are you sure you want to withdraw this application? This action cannot be undone.
                    </p>
                  </div>

                  <div class="mt-4 flex justify-end space-x-3">
                    <button
                      type="button"
                      class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                      @click="showWithdrawModal = false"
                    >
                      Cancel
                    </button>
                    <button
                      type="button"
                      class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                      :disabled="withdrawing"
                      @click="handleWithdrawApplication"
                    >
                      <template v-if="withdrawing">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Withdrawing...
                      </template>
                      <template v-else>Withdraw</template>
                    </button>
                  </div>
                </DialogPanel>
              </TransitionChild>
            </div>
          </div>
        </Dialog>
      </TransitionRoot>
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useApplicationsStore } from '@/stores/applications'
import MainLayout from '@/components/layout/MainLayout.vue'
import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {
  DocumentIcon,
  DocumentTextIcon,
  EllipsisVerticalIcon,
  MagnifyingGlassIcon,
  XCircleIcon,
} from '@heroicons/vue/24/outline'

const applicationsStore = useApplicationsStore()

const showWithdrawModal = ref(false)
const withdrawing = ref(false)
const applicationToWithdraw = ref(null)

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const confirmWithdrawApplication = (application) => {
  applicationToWithdraw.value = application
  showWithdrawModal.value = true
}

const handleWithdrawApplication = async () => {
  if (!applicationToWithdraw.value) return
  
  withdrawing.value = true
  try {
    await applicationsStore.withdrawApplication(applicationToWithdraw.value.id)
    showWithdrawModal.value = false
  } catch (error) {
    console.error('Failed to withdraw application:', error)
  } finally {
    withdrawing.value = false
    applicationToWithdraw.value = null
  }
}

onMounted(async () => {
  try {
    await applicationsStore.fetchMyApplications()
  } catch (error) {
    console.error('Failed to fetch applications:', error)
  }
})
</script> 