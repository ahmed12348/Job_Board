<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <router-link to="/" class="text-2xl font-bold text-primary-600">JobBoard</router-link>
            </div>

            <!-- Navigation Links -->
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <!-- Common Links -->
              <router-link
                to="/dashboard"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                active-class="border-b-2 border-primary-500"
              >
                Dashboard
              </router-link>

              <!-- Company Links -->
              <template v-if="isEmployer">
                <router-link
                  to="/dashboard/jobs"
                  class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                  active-class="border-b-2 border-primary-500"
                >
                  My Jobs
                </router-link>
              </template>

              <!-- Job Seeker Links -->
              <template v-if="isJobSeeker">
                <router-link
                  to="/dashboard/applications"
                  class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                  active-class="border-b-2 border-primary-500"
                >
                  My Applications
                </router-link>
              </template>

              <!-- Common Links -->
              <router-link
                to="/dashboard/profile"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                active-class="border-b-2 border-primary-500"
              >
                Profile
              </router-link>
            </div>
          </div>

          <!-- Right side buttons -->
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <button
              type="button"
              class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none"
            >
              <span class="sr-only">View notifications</span>
              <BellIcon class="h-6 w-6" aria-hidden="true" />
            </button>

            <!-- Profile dropdown -->
            <Menu as="div" class="ml-3 relative">
              <div>
                <MenuButton class="bg-white rounded-full flex text-sm focus:outline-none">
                  <span class="sr-only">Open user menu</span>
                  <UserCircleIcon class="h-8 w-8 text-gray-400" aria-hidden="true" />
                </MenuButton>
              </div>
              <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <MenuItems class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <router-link
                      to="/dashboard/profile"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                    >
                      Your Profile
                    </router-link>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a
                      href="#"
                      :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                      @click.prevent="handleLogout"
                    >
                      Sign out
                    </a>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main class="py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, UserCircleIcon } from '@heroicons/vue/24/outline'

const router = useRouter()
const auth = useAuthStore()

const isEmployer = computed(() => auth.isEmployer)
const isJobSeeker = computed(() => auth.isJobSeeker)

const handleLogout = async () => {
  await auth.logout()
  router.push('/login')
}
</script> 