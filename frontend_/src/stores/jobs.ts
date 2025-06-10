import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

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
}

interface JobFilters {
  search: string
  location: string
  type: string
}

interface JobsState {
  jobs: Job[]
  companyJobs: Job[]
  loading: boolean
  error: string | null
  filters: JobFilters
}

export const useJobsStore = defineStore('jobs', {
  state: (): JobsState => ({
    jobs: [],
    companyJobs: [],
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
      // Ensure jobs is an array before filtering
      const jobsArray = Array.isArray(state.jobs) ? state.jobs : []
      
      return jobsArray.filter(job => {
        if (!job) return false

        const searchTerm = state.filters.search.toLowerCase()
        const locationTerm = state.filters.location.toLowerCase()
        
        const matchesSearch = !searchTerm || 
          (job.title?.toLowerCase().includes(searchTerm) ||
           job.description?.toLowerCase().includes(searchTerm))

        const matchesLocation = !locationTerm ||
          job.location?.toLowerCase().includes(locationTerm)

        const matchesType = !state.filters.type || job.type === state.filters.type

        return matchesSearch && matchesLocation && matchesType
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
        const response = await axios.get('/jobs')
        // Ensure we're setting an array
        this.jobs = Array.isArray(response.data) ? response.data : []
        return this.jobs
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch jobs'
        this.jobs = [] // Reset to empty array on error
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchCompanyJobs() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/company/jobs')
        // Ensure we're setting an array
        this.companyJobs = Array.isArray(response.data) ? response.data : []
        return this.companyJobs
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch company jobs'
        this.companyJobs = [] // Reset to empty array on error
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchJob(id: number) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`/jobs/${id}`)
        return response.data
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch job'
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
        this.companyJobs = [...this.companyJobs, newJob]
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
        this.companyJobs = this.companyJobs.map(job => 
          job.id === id ? updatedJob : job
        )
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
    }
  }
}) 