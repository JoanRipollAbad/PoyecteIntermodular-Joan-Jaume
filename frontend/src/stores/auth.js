import { defineStore } from 'pinia'
import api from '../api'
import { useRouter } from 'vue-router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('auth_token') || null,
    isAuthenticated: !!localStorage.getItem('auth_token'),
  }),

  actions: {
    async login(credentials) {
      try {
        const response = await api.post('/login', credentials)
        this.setAuthData(response.data.token, response.data.user)
        return response.data
      } catch (error) {
        throw error
      }
    },

    async register(userData) {
      try {
        const response = await api.post('/register', userData)

        if (response.data.token) {
          this.setAuthData(response.data.token, response.data.user)
        }

        return response.data
      } catch (error) {
        throw error
      }
    },

    async logout() {
      try {
        if (this.isAuthenticated) {
          await api.post('/logout')
        }
      } catch (error) {
        console.warn('Error al tancar sessió al servidor:', error)
      } finally {
        this.clearAuthData()
      }
    },

    async fetchUser() {
      try {
        const response = await api.get('/me')
        this.user = response.data.user
        return response.data.user
      } catch (error) {
        this.clearAuthData()
        throw error
      }
    },

    async updateProfile(profileData) {
      try {
        const response = await api.put('/profile', profileData)
        this.user = response.data.user
        return response.data
      } catch (error) {
        throw error
      }
    },

    setAuthData(token, user) {
      this.token = token
      this.user = user
      this.isAuthenticated = true
      localStorage.setItem('auth_token', token)
    },

    clearAuthData() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
      localStorage.removeItem('auth_token')
    },
  },
})
