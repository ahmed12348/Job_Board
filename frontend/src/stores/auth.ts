import { defineStore } from 'pinia'
import axios from '@/plugins/axios'
import router from '@/router'

interface User {
  id: number
  name: string
  email: string
  type: 'employer' | 'job_seeker'
  company?: {
    id: number
    name: string
    website?: string
    description?: string
  }
  title?: string
  skills?: string
  bio?: string
}

interface LoginData {
  email: string
  password: string
}

interface RegisterData extends LoginData {
  name: string
  type: 'employer' | 'job_seeker'
}

interface ProfileData {
  name: string
  email: string
  company_name?: string
  company_website?: string
  company_description?: string
  company_location?: string
  title?: string
  skills?: string
  bio?: string
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    token: localStorage.getItem('token'),
    loading: false,
    error: null as string | null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isEmployer: (state) => state.user?.type === 'employer',
    isJobSeeker: (state) => state.user?.type === 'job_seeker'
  },

  actions: {
    async login(data: LoginData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/auth/login', data)
        const { token, user } = response.data
        
        this.token = token
        this.user = user
        localStorage.setItem('token', token)
        
        // Set the token in axios defaults
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        
        // Redirect based on user type
        if (this.isEmployer) {
          await router.push('/dashboard/jobs')
        } else {
          await router.push('/jobs')
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Login failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(data: RegisterData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/auth/register', data)
        const { token, user } = response.data
        
        this.token = token
        this.user = user
        localStorage.setItem('token', token)
        
        // Set the token in axios defaults
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        
        // Redirect based on user type
        if (this.isEmployer) {
          await router.push('/dashboard/jobs')
        } else {
          await router.push('/jobs')
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Registration failed'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchUser() {
      if (!this.token) return

      try {
        const response = await axios.get('/auth/profile')
        this.user = response.data
      } catch (error) {
        this.logout()
        throw error
      }
    },

    async logout() {
      try {
        if (this.token) {
          await axios.post('/auth/logout')
        }
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
        await router.push('/login')
      }
    },

    async initializeAuth() {
      const token = localStorage.getItem('token')
      if (token) {
        this.token = token
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        await this.fetchUser()
      }
    },

    async updateProfile(data: ProfileData) {
      this.loading = true
      this.error = null
      try {
        // If employer is updating company info
        if (this.isEmployer && (data.company_name || data.company_website || data.company_description)) {
          const companyData = {
            name: data.company_name,
            website: data.company_website,
            description: data.company_description,
            location: data.company_location || 'Not specified'
          }

          if (this.user?.company) {
            // Update existing company
            await axios.put(`/companies/${this.user.company.id}`, companyData)
          } else {
            // Create new company
            await axios.post('/companies', companyData)
            // Refresh user data to get the new company info
            await this.fetchUser()
          }
        }

        // Update user profile
        const userData = {
          name: data.name,
          title: data.title,
          skills: data.skills,
          bio: data.bio
        }

        const response = await axios.put('/auth/profile', userData)
        this.user = response.data

        return {
          success: true,
          message: this.isEmployer ? 'Profile and company information updated successfully' : 'Profile updated successfully',
          isNewCompany: !this.user?.company
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to update profile'
        throw error
      } finally {
        this.loading = false
      }
    }
  }
}) 