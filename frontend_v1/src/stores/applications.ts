import { defineStore } from 'pinia'
import axios from '@/plugins/axios'

interface Application {
  id: number
  job_id: number
  user_id: number
  status: 'pending' | 'accepted' | 'rejected'
  cover_letter: string
  created_at: string
  updated_at: string
  job?: {
    id: number
    title: string
    company_name: string
  }
}

interface ApplicationsState {
  applications: Application[]
  loading: boolean
  error: string | null
}

export const useApplicationsStore = defineStore('applications', {
  state: (): ApplicationsState => ({
    applications: [],
    loading: false,
    error: null
  }),

  getters: {
    myApplications: (state) => state.applications,
    applicationsByJob: (state) => (jobId: number) => 
      state.applications.filter(app => app.job_id === jobId)
  },

  actions: {
    async fetchMyApplications() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get('/applications')
        this.applications = response.data
        return this.applications
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to fetch applications'
        throw error
      } finally {
        this.loading = false
      }
    },

    async applyToJob(jobId: number, coverLetter: string) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post(`/jobs/${jobId}/apply`, {
          cover_letter: coverLetter
        })
        this.applications.push(response.data)
        return response.data
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to apply for job'
        throw error
      } finally {
        this.loading = false
      }
    },

    async withdrawApplication(applicationId: number) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`/applications/${applicationId}`)
        this.applications = this.applications.filter(app => app.id !== applicationId)
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to withdraw application'
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateApplicationStatus(applicationId: number, status: Application['status']) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`/applications/${applicationId}`, { status })
        const index = this.applications.findIndex(app => app.id === applicationId)
        if (index !== -1) {
          this.applications[index] = response.data
        }
        return response.data
      } catch (error: any) {
        this.error = error.response?.data?.message || 'Failed to update application status'
        throw error
      } finally {
        this.loading = false
      }
    }
  }
}) 