import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/auth/LoginView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/auth/RegisterView.vue'),
      meta: { requiresGuest: true }
    },
    {
      path: '/jobs',
      name: 'jobs',
      component: () => import('@/views/jobs/JobListingView.vue')
    },
    {
      path: '/jobs/:id',
      name: 'job-details',
      component: () => import('@/views/jobs/JobDetailsView.vue')
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('@/views/dashboard/DashboardView.vue'),
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          name: 'dashboard-home',
          component: () => import('@/views/dashboard/HomeView.vue')
        },
        {
          path: 'profile',
          name: 'profile',
          component: () => import('@/views/dashboard/ProfileView.vue')
        },
        // Company Routes
        {
          path: 'jobs',
          name: 'manage-jobs',
          component: () => import('@/views/dashboard/company/ManageJobsView.vue'),
          meta: { requiresCompany: true }
        },
        {
          path: 'jobs/new',
          name: 'new-job',
          component: () => import('@/views/dashboard/company/JobFormView.vue'),
          meta: { requiresCompany: true }
        },
        {
          path: 'jobs/:id/edit',
          name: 'edit-job',
          component: () => import('@/views/dashboard/company/JobFormView.vue'),
          meta: { requiresCompany: true }
        },
        {
          path: 'jobs/:id/applications',
          name: 'job-applications',
          component: () => import('@/views/dashboard/company/JobApplicationsView.vue'),
          meta: { requiresCompany: true }
        },
        // Job Seeker Routes
        {
          path: 'applications',
          name: 'my-applications',
          component: () => import('@/views/dashboard/seeker/MyApplicationsView.vue'),
          meta: { requiresSeeker: true }
        },
        {
          path: 'saved-jobs',
          name: 'saved-jobs',
          component: () => import('@/views/dashboard/seeker/SavedJobsView.vue'),
          meta: { requiresSeeker: true }
        }
      ]
    }
  ]
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  // Handle authentication requirements
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  if (to.meta.requiresGuest && auth.isAuthenticated) {
    return next({ name: 'dashboard' })
  }

  // Handle user type requirements
  if (to.meta.requiresCompany && !auth.isCompany) {
    return next({ name: 'dashboard' })
  }

  if (to.meta.requiresSeeker && !auth.isSeeker) {
    return next({ name: 'dashboard' })
  }

  next()
})

export default router 