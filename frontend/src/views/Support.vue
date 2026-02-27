<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import api from '../api'

const authStore = useAuthStore()

const formData = ref({
  nombre: '',
  email: '',
  asunto: '',
  mensaje: ''
})

const loading = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

onMounted(() => {
  // Pre-rellenar si el usuario está logeado
  if (authStore.isAuthenticated) {
    formData.value.nombre = authStore.user?.nom || ''
    formData.value.email = authStore.user?.email || ''
  }
})

const handleSubmit = async () => {
  loading.value = true
  successMsg.value = ''
  errorMsg.value = ''

  try {
    const response = await api.post('/incidencias', formData.value)
    successMsg.value = response.data.message
    // Limpiar formulario excepto nombre/email si está logeado
    formData.value.asunto = ''
    formData.value.mensaje = ''
  } catch (err) {
    if (err.response?.data?.errors) {
      errorMsg.value = Object.values(err.response.data.errors).flat().join(' ')
    } else {
      errorMsg.value = 'Ocurrió un error al enviar el formulario. Por favor, inténtalo de nuevo.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="support-wrapper">
    <div class="support-container">
      <h2>Centro de Soporte</h2>
      <p class="subtitle">¿Tienes alguna duda o incidencia? Estamos aquí para ayudarte.</p>

      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="nombre">Nombre Completo</label>
          <input type="text" id="nombre" v-model="formData.nombre" placeholder="Ej. Juan Pérez" required />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="formData.email" placeholder="tu@email.com" required />
        </div>

        <div class="form-group">
          <label for="asunto">Asunto</label>
          <input type="text" id="asunto" v-model="formData.asunto" placeholder="¿Cómo podemos ayudarte?" required />
        </div>

        <div class="form-group">
          <label for="mensaje">Mensaje</label>
          <textarea id="mensaje" v-model="formData.mensaje" rows="4" placeholder="Describe el problema..." required></textarea>
        </div>

        <button type="submit" :disabled="loading" class="btn-primary">
          {{ loading ? 'Enviando...' : 'ENVIAR SOLICITUD' }}
        </button>

        <p v-if="successMsg" class="success-msg">{{ successMsg }}</p>
        <p v-if="errorMsg" class="error-msg">{{ errorMsg }}</p>
      </form>
    </div>
  </div>
</template>

<style scoped>
.support-wrapper {
  min-height: calc(100vh - 250px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
}

.support-container {
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

.form-group input,
.form-group textarea {
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

.form-group textarea {
  resize: none;
}

.form-group input:focus,
.form-group textarea:focus {
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

.success-msg {
  color: #27ae60;
  margin-top: 15px;
  font-size: 0.9rem;
  background: #f0fff4;
  padding: 10px;
  border-radius: 8px;
}

.error-msg {
  color: #e74c3c;
  margin-top: 15px;
  font-size: 0.9rem;
  background: #fdf2f2;
  padding: 10px;
  border-radius: 8px;
}
</style>
