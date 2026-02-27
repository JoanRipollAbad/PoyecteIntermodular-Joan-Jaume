<template>
  <div class="login-wrapper">
    <div class="login-container">
      <h2>Iniciar Sesión</h2>
      <p class="subtitle">Accede a tu panel de seguridad JJ-Security</p>

      <Form @submit="handleLogin" :validation-schema="schema" v-slot="{ errors }">
        <div class="form-group">
          <label for="email">Email</label>
          <Field name="email" type="email" placeholder="tupersona@correo.com" class="form-input" :class="{ 'is-invalid': errors.email }" />
          <ErrorMessage name="email" class="error-msg-small" />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <Field name="password" type="password" placeholder="••••••••" class="form-input" :class="{ 'is-invalid': errors.password }" />
          <ErrorMessage name="password" class="error-msg-small" />
        </div>

        <button type="submit" :disabled="loading" class="btn-primary">
          {{ loading ? 'Cargando...' : 'ENTRAR AHORA' }}
        </button>

        <p v-if="error" class="error-msg">{{ error }}</p>

        <div class="divider">
          <span>O BIEN</span>
        </div>

        <GoogleLoginButton />

        <div class="register-link">
          ¿No tienes cuenta? <router-link to="/register">Regístrate aquí</router-link>
        </div>
      </Form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import GoogleLoginButton from '../components/GoogleLoginButton.vue'
import { Form, Field, ErrorMessage } from 'vee-validate'
import * as yup from 'yup'

export default {
  components: {
    GoogleLoginButton,
    Form,
    Field,
    ErrorMessage
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    const loading = ref(false)
    const error = ref('')

    const schema = yup.object({
      email: yup.string().required('El email es obligatorio').email('Email no válido'),
      password: yup.string().required('La contraseña es obligatoria').min(6, 'Mínimo 6 caracteres')
    })

    const handleLogin = async (values) => {
      loading.value = true
      error.value = ''

      try {
        await authStore.login(values)
        router.push('/')
      } catch (err) {
        error.value = err.response?.data?.error || "Error en la autenticación"
      } finally {
        loading.value = false
      }
    }

    return {
      schema,
      loading,
      error,
      handleLogin,
    }
  },
}
</script>

<style scoped>
.login-wrapper {
  min-height: calc(100vh - 250px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}

.login-container {
  max-width: 450px;
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
  margin-bottom: 25px;
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
  margin-top: 10px;
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

.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 25px 0 10px;
  color: #ccc;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #eee;
}

.divider:not(:empty)::before {
  margin-right: .5em;
}

.divider:not(:empty)::after {
  margin-left: .5em;
}

.divider span {
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 1px;
}

.register-link {
  margin-top: 30px;
  font-size: 0.95rem;
  color: #666;
}

.register-link a {
  color: #7ed9c7;
  text-decoration: none;
  font-weight: 700;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>
