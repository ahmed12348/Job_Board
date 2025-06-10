import { defineStore } from 'pinia'
import axios from '@/plugins/axios'
import router from '@/router'

interface User {
  id: number
  name: string
  email: string
  type: 'company' | 'seeker'
}

interface LoginData {
  email: string
  password: string
}

interface RegisterData extends LoginData {
  name: string
  type: 'company' | 'seeker'
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
    isCompany: (state) => state.user?.type === 'company',
    isSeeker: (state) => state.user?.type === 'seeker'
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
        if (this.isCompany) {
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
        if (this.isCompany) {
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
        const response = await axios.get('/auth/user')
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

    initializeAuth() {
      const token = localStorage.getItem('token')
      if (token) {
        this.token = token
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        this.fetchUser()
      }
    }
  }
}) 