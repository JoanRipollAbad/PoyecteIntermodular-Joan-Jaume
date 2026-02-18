<script setup>
import { ref } from 'vue'
import api from '../api'

const formData = ref({
  nombre: '',
  email: '',
  asunto: '',
  mensaje: ''
})

const message = ref({
  text: '',
  type: '' // 'success' or 'error'
})

const submitForm = async () => {
  try {
    const response = await api.post('/contacto', formData.value)
    
    if (response.data.success) {
      message.value = {
        text: response.data.message || 'Mensaje enviado correctamente.',
        type: 'success'
      }
      // Reset form
      formData.value = {
        nombre: '',
        email: '',
        asunto: '',
        mensaje: ''
      }
    } else {
      throw new Error(response.data.message || 'Error al enviar el mensaje.')
    }
  } catch (error) {
    message.value = {
      text: error.response?.data?.message || 'Error de conexión con el servidor.',
      type: 'error'
    }
  }
}
</script>

<template>
  <div class="container contact-container">
    <h1>Contáctanos</h1>

    <div v-if="message.text" :class="['alert', message.type === 'success' ? 'alert-success' : 'alert-danger']">
      {{ message.text }}
    </div>

    <form @submit.prevent="submitForm" class="contact-form">
      <div class="form-group">
        <label for="nombre">Nombre *</label>
        <input v-model="formData.nombre" type="text" id="nombre" required>
      </div>

      <div class="form-group">
        <label for="email">Email *</label>
        <input v-model="formData.email" type="email" id="email" required>
      </div>

      <div class="form-group">
        <label for="asunto">Asunto *</label>
        <input v-model="formData.asunto" type="text" id="asunto" required>
      </div>

      <div class="form-group">
        <label for="mensaje">Mensaje *</label>
        <textarea v-model="formData.mensaje" id="mensajeTexto" required></textarea>
      </div>

      <button type="submit">Enviar Mensaje</button>
    </form>
  </div>
</template>

<style scoped>
.contact-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 40px 20px;
}

h1 {
  text-align: center;
  margin-bottom: 30px;
}

.alert {
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
}

.alert-success {
  color: #3c763d;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}

.alert-danger {
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
}

.contact-form {
  background: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
}

input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

textarea {
  min-height: 150px;
  resize: vertical;
}

button {
  background-color: #7ed9c7;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: background-color 0.3s;
  width: 100%;
}

button:hover {
  background-color: #6bc7b5;
}
</style>
