<template>
  <div class="register-wrapper">
    <div class="register-container">
      <h2>Crea tu Cuenta</h2>
      <p class="subtitle">Únete a la familia JJ-Security</p>

      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label for="name">Nombre Completo</label>
          <input type="text" id="name" v-model="name" placeholder="Ej: Juan Pérez" required />
        </div>

        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input type="email" id="email" v-model="email" placeholder="tupersona@correo.com" required />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" id="password" v-model="password" placeholder="Mínimo 6 caracteres" required minlength="6" />
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar Contraseña</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="passwordConfirmation"
            placeholder="Repite tu contraseña"
            required
            minlength="6"
          />
        </div>

        <button type="submit" :disabled="loading" class="btn-primary">
          {{ loading ? 'Creando cuenta...' : 'REGISTRARME AHORA' }}
        </button>

        <p v-if="error" class="error-msg">{{ error }}</p>

        <div class="login-link">
          ¿Ya tienes cuenta? <router-link to="/login">Inicia sesión aquí</router-link>
        </div>
      </form>
    </div>
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

      if (password.value !== passwordConfirmation.value) {
        error.value = 'Las contraseñas no coinciden'
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
          alert('¡Registro correcto! Inicia sesión para continuar')
        }
      } catch (err) {
        error.value = err.response?.data?.errors?.email?.[0] || 'Error al registrarse'
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
.register-wrapper {
  min-height: calc(100vh - 250px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}

.register-container {
  max-width: 500px;
  width: 100%;
  background: white;
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  text-align: center;
}

h2 {
  margin-bottom: 10px;
  color: #333;
  font-weight: 700;
}

.subtitle {
  color: #888;
  margin-bottom: 35px;
  font-size: 0.95rem;
}

.form-group {
  margin-bottom: 20px;
  text-align: left;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 700;
  font-size: 0.9rem;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 14px;
  border: 1px solid #eee;
  border-radius: 12px;
  background: #f9f9f9;
  font-family: inherit;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.form-group input:focus {
  outline: none;
  border-color: #7ed9c7;
  background: white;
  box-shadow: 0 0 0 4px rgba(126, 217, 199, 0.1);
}

.btn-primary {
  width: 100%;
  padding: 16px;
  background: #000;
  color: white;
  border: none;
  border-radius: 50px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 1px;
  transition: all 0.3s ease;
  margin-top: 15px;
}

.btn-primary:hover:not(:disabled) {
  background: #333;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-primary:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.error-msg {
  color: #e74c3c;
  margin-top: 15px;
  font-size: 0.9rem;
  background: #fdf2f2;
  padding: 10px;
  border-radius: 8px;
}

.login-link {
  margin-top: 30px;
  font-size: 0.95rem;
  color: #666;
}

.login-link a {
  color: #7ed9c7;
  text-decoration: none;
  font-weight: 700;
}

.login-link a:hover {
  text-decoration: underline;
}
</style>
