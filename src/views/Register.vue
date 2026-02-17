<template>
  <div class="register-container">
    <h2>Registra't</h2>

    <form @submit.prevent="handleRegister">
      <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" id="name" v-model="name" required />
      </div>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" v-model="email" required />
      </div>

      <div class="form-group">
        <label for="password">Contrasenya</label>
        <input type="password" id="password" v-model="password" required minlength="6" />
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirmar contrasenya</label>
        <input
          type="password"
          id="password_confirmation"
          v-model="passwordConfirmation"
          required
          minlength="6"
        />
      </div>

      <button type="submit" :disabled="loading">
        {{ loading ? 'Registrament...' : 'Registrar-me' }}
      </button>

      <p v-if="error" class="error">{{ error }}</p>

      <router-link to="/login">Ja tens compte? Inicia sessió</router-link>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

export default {
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()

    const name = ref('')
    const email = ref('')
    const password = ref('')
    const passwordConfirmation = ref('')
    const loading = ref(false)
    const error = ref('')

    const handleRegister = async () => {
      loading.value = true
      error.value = ''

      // Validació de contrasenya
      if (password.value !== passwordConfirmation.value) {
        error.value = 'Les contrasenyes no coincideixen'
        loading.value = false
        return
      }

      try {
        await authStore.register({
          name: name.value,
          email: email.value,
          password: password.value,
        })

        if (authStore.isAuthenticated) {
          router.push('/')
        } else {
          router.push('/login')
          alert('Registre correcte! Inicia sessió per continuar')
        }
      } catch (err) {
        error.value = err.response?.data?.errors?.email?.[0] || 'Error al registrar'
      } finally {
        loading.value = false
      }
    }

    return {
      name,
      email,
      password,
      passwordConfirmation,
      loading,
      error,
      handleRegister,
    }
  },
}
</script>

<style scoped>
.register-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 12px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.error {
  color: red;
  margin-top: 10px;
}

a {
  display: block;
  text-align: center;
  margin-top: 15px;
  color: #007bff;
}
</style>
