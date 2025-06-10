import { defineStore } from 'pinia'
import axios from '@/plugins/axios'
import router from '@/router'

interface Job {
  id: number
  title: string
  company_id: number
  description: string
  requirements: string
  location: string
  salary_range: string
  type: string
  status: 'active' | 'inactive'
  created_at: string
  updated_at: string
  applications_count?: number
  company?: {
    name: string
  }
  is_saved?: boolean
}

interface JobFilters {
  search: string
  location: string
  type: string
}

interface JobsState {
  jobs: Job[]
  companyJobs: Job[]
  savedJobs: Job[]
  loading: boolean
  error: string | null
  filters: JobFilters
}

export const useJobsStore = defineStore('jobs', {
  state: (): JobsState => ({
    jobs: [],
    companyJobs: [],
    savedJobs: [],
    loading: false,
    error: null,
    filters: {
      search: '',
      location: '',
      type: ''
    }
  }),

  getters: {
    filteredJobs: (state): Job[] => {
      return state.jobs.filter(job => {
        const searchTerm = state.filters.search.toLowerCase()
        const locationTerm = state.filters.location.toLowerCase()
        
        const matchesSearch = !searchTerm || 
          job.title.toLowerCase().includes(searchTerm) ||
          job.description.toLowerCase().includes(searchTerm)

        const matchesLocation = !locationTerm ||
          job.location.toLowerCase().includes(locationTerm)

        const matchesType = !state.filters.type || job.type === state.filters.type

        return matchesSearch && matchesLocation && matchesType && job.status === 'active'
      })
    }
  },

  actions: {
    setFilters(filters: Partial<JobFilters>) {
      this.filters = { ...this.filters, ...filters }
    },

    resetFilters() {
      this.filters = {
        search: '',
        location: '',
        type: ''
      }
    },

    async fetchJobs() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/jobs/public')
        this.jobs = response.data.data
        return this.jobs
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch jobs'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchCompanyJobs() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/jobs')
        this.companyJobs = response.data
        return this.companyJobs
      } catch (error: any) {
        if (error.response?.data?.needs_company) {
          this.error = 'Please create a company profile before managing jobs'
          router.push('/dashboard/profile')
        } else {
          this.error = error.response?.data?.message || 'Failed to fetch company jobs'
        }
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchJob(id: number) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`/jobs/public/${id}`)
        return response.data
      } catch (error: any) {
        if (error.response?.status === 404) {
          this.error = 'Job posting not found or has been removed'
          router.push('/jobs')
        } else {
          this.error = error.response?.data?.message || 'Failed to fetch job'
        }
        throw error
      } finally {
        this.loading = false
      }
    },

    async createJob(jobData: Partial<Job>) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post('/jobs', jobData)
        const newJob = response.data
        this.companyJobs.push(newJob)
        return newJob
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to create job'
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateJob(id: number, jobData: Partial<Job>) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`/jobs/${id}`, jobData)
        const updatedJob = response.data
        const index = this.companyJobs.findIndex(job => job.id === id)
        if (index !== -1) {
          this.companyJobs[index] = updatedJob
        }
        return updatedJob
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to update job'
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteJob(id: number) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`/jobs/${id}`)
        this.companyJobs = this.companyJobs.filter(job => job.id !== id)
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to delete job'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchApplications() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/applications')
        return Array.isArray(response.data) ? response.data : []
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch applications'
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchSavedJobs() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/saved-jobs')
        this.savedJobs = response.data
        return this.savedJobs
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch saved jobs'
        throw error
      } finally {
        this.loading = false
      }
    },

    async saveJob(jobId: number) {
      this.loading = true
      this.error = null
      try {
        await axios.post(`/jobs/${jobId}/save`)
        // Update is_saved status in jobs list
        const job = this.jobs.find(j => j.id === jobId)
        if (job) {
          job.is_saved = true
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to save job'
        throw error
      } finally {
        this.loading = false
      }
    },

    async unsaveJob(jobId: number) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`/jobs/${jobId}/save`)
        // Remove from saved jobs list
        this.savedJobs = this.savedJobs.filter(job => job.id !== jobId)
        // Update is_saved status in jobs list
        const job = this.jobs.find(j => j.id === jobId)
        if (job) {
          job.is_saved = false
        }
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to unsave job'
        throw error
      } finally {
        this.loading = false
      }
    }
  }
}) 