<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { BellIcon, UserCircleIcon } from '@heroicons/vue/24/outline'

const auth = useAuthStore()

onMounted(() => {
  auth.initializeAuth()
})

const handleLogout = async () => {
  await auth.logout()
}
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <router-link to="/" class="text-2xl font-bold text-primary-600">JobBoard</router-link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link
                to="/jobs"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                active-class="border-b-2 border-primary-500"
              >
                Jobs
              </router-link>

              <template v-if="auth.isAuthenticated">
                <template v-if="auth.isSeeker">
                  <router-link
                    to="/dashboard/applications"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                    active-class="border-b-2 border-primary-500"
                  >
                    My Applications
                  </router-link>
                </template>

                <template v-if="auth.isCompany">
                  <router-link
                    to="/dashboard/jobs"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                    active-class="border-b-2 border-primary-500"
                  >
                    My Jobs
                  </router-link>
                  <router-link
                    to="/dashboard"
                    class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900"
                    active-class="border-b-2 border-primary-500"
                  >
                    Dashboard
                  </router-link>
                </template>
              </template>
            </div>
          </div>

          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <template v-if="auth.isAuthenticated">
              <button
                type="button"
                class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none"
              >
                <span class="sr-only">View notifications</span>
                <BellIcon class="h-6 w-6" aria-hidden="true" />
              </button>

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
            </template>

            <template v-else>
              <router-link
                to="/login"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700"
              >
                Sign in
              </router-link>
              <router-link
                to="/register"
                class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-primary-600 bg-white hover:bg-gray-50"
              >
                Sign up
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <main>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <router-view v-slot="{ Component }">
          <transition
            name="fade"
            mode="out-in"
            enter-active-class="transition-opacity duration-150"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </main>
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
