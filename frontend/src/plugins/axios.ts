import axios from 'axios'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'

// Create axios instance with custom config
const instance = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  withCredentials: true // Enable sending cookies with requests
})

// Add request interceptor
instance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

// Add response interceptor
instance.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      // Only redirect to login if not already on login page and not trying to logout
      if (router.currentRoute.value.name !== 'login' && !error.config.url.includes('/auth/logout')) {
        const auth = useAuthStore()
        await auth.logout()
      }
    }
    return Promise.reject(error)
  }
)

export default instance 