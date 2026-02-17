<template>
  <div id="app">
    <nav v-if="authStore.isAuthenticated">
      <router-link to="/">Productes</router-link>
      <button @click="logout">Tancar sessió</button>
    </nav>

    <router-view />
  </div>
</template>

<script>
import { useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth'

export default {
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()

    const logout = () => {
      authStore.logout()
      router.push('/login')
    }

    return {
      authStore,
      logout,
    }
  },
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

#app {
  font-family: Arial, sans-serif;
  padding: 20px;
}

nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #ddd;
  margin-bottom: 20px;
}

nav a {
  color: #007bff;
  text-decoration: none;
  margin-right: 15px;
}

nav button {
  background: #dc3545;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}
</style>
