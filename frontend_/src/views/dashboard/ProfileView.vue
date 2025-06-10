<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>
            <p class="mt-1 text-sm text-gray-600">
              This information will be displayed publicly so be careful what you share.
            </p>
          </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
          <form @submit.prevent="handleSubmit">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
              <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                <!-- Name -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                  <div class="mt-1">
                    <input
                      type="text"
                      name="name"
                      id="name"
                      v-model="form.name"
                      class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <div class="mt-1">
                    <input
                      type="email"
                      name="email"
                      id="email"
                      v-model="form.email"
                      disabled
                      class="shadow-sm bg-gray-50 block w-full sm:text-sm border-gray-300 rounded-md"
                    />
                  </div>
                  <p class="mt-1 text-sm text-gray-500">Email cannot be changed.</p>
                </div>

                <!-- Company Fields -->
                <template v-if="auth.isCompany">
                  <div>
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                    <div class="mt-1">
                      <input
                        type="text"
                        name="company_name"
                        id="company_name"
                        v-model="form.company_name"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                  </div>

                  <div>
                    <label for="company_website" class="block text-sm font-medium text-gray-700">Company Website</label>
                    <div class="mt-1">
                      <input
                        type="url"
                        name="company_website"
                        id="company_website"
                        v-model="form.company_website"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                  </div>

                  <div>
                    <label for="company_description" class="block text-sm font-medium text-gray-700">Company Description</label>
                    <div class="mt-1">
                      <textarea
                        id="company_description"
                        name="company_description"
                        rows="4"
                        v-model="form.company_description"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      ></textarea>
                    </div>
                  </div>
                </template>

                <!-- Job Seeker Fields -->
                <template v-else>
                  <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Professional Title</label>
                    <div class="mt-1">
                      <input
                        type="text"
                        name="title"
                        id="title"
                        v-model="form.title"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                  </div>

                  <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700">Skills</label>
                    <div class="mt-1">
                      <input
                        type="text"
                        name="skills"
                        id="skills"
                        v-model="form.skills"
                        placeholder="e.g. JavaScript, React, Node.js"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      />
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Separate skills with commas</p>
                  </div>

                  <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <div class="mt-1">
                      <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        v-model="form.bio"
                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      ></textarea>
                    </div>
                  </div>
                </template>
              </div>

              <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <span v-if="error" class="mr-3 text-sm text-red-600">{{ error }}</span>
                <button
                  type="submit"
                  :disabled="loading"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                  <template v-if="loading">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                  </template>
                  <template v-else>Save</template>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const loading = ref(false)
const error = ref('')

const form = ref({
  name: '',
  email: '',
  // Company fields
  company_name: '',
  company_website: '',
  company_description: '',
  // Job seeker fields
  title: '',
  skills: '',
  bio: ''
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  try {
    // TODO: Implement profile update in auth store
    await auth.updateProfile(form.value)
  } catch (err: any) {
    error.value = err.message || 'Failed to update profile'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  // Populate form with current user data
  const user = auth.user
  if (user) {
    form.value = {
      name: user.name || '',
      email: user.email || '',
      company_name: user.company_name || '',
      company_website: user.company_website || '',
      company_description: user.company_description || '',
      title: user.title || '',
      skills: user.skills || '',
      bio: user.bio || ''
    }
  }
})
</script> 