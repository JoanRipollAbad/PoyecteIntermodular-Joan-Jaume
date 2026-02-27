<template>
  <div class="register-wrapper">
    <div class="register-container">
      <h2>Crea tu Cuenta</h2>
      <p class="subtitle">Únete a la familia JJ-Security</p>

      <Form @submit="handleRegister" :validation-schema="schema" v-slot="{ errors }">
        <div class="form-group">
          <label for="name">Nombre Completo</label>
          <Field name="name" type="text" placeholder="Ej: Juan Pérez" class="form-input" :class="{ 'is-invalid': errors.name }" />
          <ErrorMessage name="name" class="error-msg-small" />
        </div>

        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <Field name="email" type="email" placeholder="tupersona@correo.com" class="form-input" :class="{ 'is-invalid': errors.email }" />
          <ErrorMessage name="email" class="error-msg-small" />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <Field name="password" type="password" placeholder="Mínimo 6 caracteres" class="form-input" :class="{ 'is-invalid': errors.password }" />
          <ErrorMessage name="password" class="error-msg-small" />
        </div>

        <div class="form-group">
          <label for="password_confirmation">Confirmar Contraseña</label>
          <Field name="password_confirmation" type="password" placeholder="Repite tu contraseña" class="form-input" :class="{ 'is-invalid': errors.password_confirmation }" />
          <ErrorMessage name="password_confirmation" class="error-msg-small" />
        </div>

        <button type="submit" :disabled="loading" class="btn-primary">
          {{ loading ? 'Creando cuenta...' : 'REGISTRARME AHORA' }}
        </button>

        <p v-if="error" class="error-msg">{{ error }}</p>

        <div class="divider">
          <span>O BIEN</span>
        </div>

        <GoogleLoginButton />

        <div class="login-link">
          ¿Ya tienes cuenta? <router-link to="/login">Inicia sesión aquí</router-link>
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
      name: yup.string().required('El nombre es obligatorio'),
      email: yup.string().required('El email es obligatorio').email('Email no válido'),
      password: yup.string().required('La contraseña es obligatoria').min(6, 'Mínimo 6 caracteres'),
      password_confirmation: yup.string()
        .required('Confirma tu contraseña')
        .oneOf([yup.ref('password')], 'Las contraseñas no coinciden')
    })

    const handleRegister = async (values) => {
      loading.value = true
      error.value = ''

      try {
        await authStore.register({
          name: values.name,
          email: values.email,
          password: values.password,
        })

        if (authStore.isAuthenticated) {
          router.push('/')
        } else {
          router.push('/login')
          alert('¡Registro correcto! Inicia sesión para continuar')
        }
      } catch (err) {
        if (err.response?.data?.errors) {
          const errors = err.response.data.errors
          error.value = Object.values(errors).flat().join(' ')
        } else {
          error.value = 'Error al registrarse'
        }
      } finally {
        loading.value = false
      }
    }

    return {
      schema,
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

.form-input.is-invalid {
  border-color: #e74c3c;
  background: #fffafa;
}

.error-msg-small {
  color: #e74c3c;
  font-size: 0.75rem;
  font-weight: 600;
  margin-top: 4px;
  display: block;
}

.register-link {
  margin-top: 30px;
  font-size: 0.95rem;
  color: #666;
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
