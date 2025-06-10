import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import './assets/main.css'

// Configure axios defaults
axios.defaults.baseURL = 'http://localhost:8000/api'
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Create and configure the app
const app = createApp(App)
app.use(createPinia())
app.use(router)
app.mount('#app')
