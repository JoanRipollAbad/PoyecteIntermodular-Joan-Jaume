<template>
  <div class="profile-wrapper">
    <div class="profile-container">
      <div class="profile-card shadow-lg">
        <div class="profile-header">
          <div class="avatar-circle">
            {{ getInitials(authStore.user?.name) }}
          </div>
          <h1>Mi Perfil</h1>
          <p class="subtitle">Gestiona tu información personal y seguridad</p>
        </div>

        <form @submit.prevent="handleUpdate" class="profile-form">
          <div class="form-section">
            <h2 class="section-title">Información Personal</h2>
            <div class="mb-3">
              <label for="name" class="form-label">Nombre Completo</label>
              <input v-model="formData.name" type="text" id="name" class="form-control custom-input" required />
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo Electrónico</label>
              <input v-model="formData.email" type="email" id="email" class="form-control custom-input" required />
            </div>
          </div>

          <div class="form-section mt-4">
            <h2 class="section-title">Cambiar Contraseña</h2>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Deja en blanco si no deseas cambiarla</p>
            <div class="mb-3">
              <label for="password" class="form-label">Nueva Contraseña</label>
              <input v-model="formData.password" type="password" id="password" class="form-control custom-input" minlength="6" />
            </div>
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
              <input v-model="formData.password_confirmation" type="password" id="password_confirmation" class="form-control custom-input" />
            </div>
          </div>

          <div v-if="message" :class="['alert', isError ? 'alert-danger' : 'alert-success']" class="mt-3">
            {{ message }}
          </div>

          <div class="profile-actions mt-5">
            <button type="submit" :disabled="loading" class="btn-save">
              {{ loading ? 'GUARDANDO...' : 'GUARDAR CAMBIOS' }}
            </button>
            <button type="button" class="btn-orders" @click="router.push('/pedidos')">
              VER MIS PEDIDOS
            </button>
            <button type="button" class="btn-logout" @click="handleLogout">
              CERRAR SESIÓN
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const loading = ref(false)
const message = ref('')
const isError = ref(false)

const formData = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

onMounted(async () => {
  if (authStore.user) {
    formData.value.name = authStore.user.name
    formData.value.email = authStore.user.email
  } else if (authStore.isAuthenticated) {
    try {
      const user = await authStore.fetchUser()
      formData.value.name = user.name
      formData.value.email = user.email
    } catch (err) {
      router.push('/login')
    }
  } else {
    router.push('/login')
  }
})

const getInitials = (name) => {
  if (!name) return 'JJ'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2)
}

const handleUpdate = async () => {
  loading.value = true
  message.value = ''
  isError.value = false

  try {
    await authStore.updateProfile(formData.value)
    message.value = '¡Perfil actualizado con éxito!'
    formData.value.password = ''
    formData.value.password_confirmation = ''
  } catch (err) {
    isError.value = true
    message.value = err.response?.data?.message || 'Error al actualizar el perfil'
  } finally {
    loading.value = false
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.profile-wrapper {
  background-color: var(--bg-color);
  min-height: calc(100vh - 80px);
  padding: 60px 20px;
}

.profile-container {
  max-width: 700px;
  margin: 0 auto;
}

.profile-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 40px;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .profile-card {
  border-color: rgba(255,255,255,0.05);
}

.profile-header {
  text-align: center;
  margin-bottom: 40px;
}

.avatar-circle {
  width: 80px;
  height: 80px;
  background-color: #6bc7b5;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  font-weight: 700;
  margin: 0 auto 15px;
}

h1 {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text-color);
}

.subtitle {
  color: var(--text-color);
  opacity: 0.6;
}

.section-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .section-title {
  border-bottom-color: rgba(255,255,255,0.1);
}

.form-label {
  font-weight: 700;
  color: var(--text-color);
  opacity: 0.9;
}

.custom-input {
  border-radius: 10px;
  padding: 12px;
  border: 1px solid rgba(0,0,0,0.1);
  background: var(--card-bg);
  color: var(--text-color);
}

.custom-input:focus {
  border-color: #6bc7b5;
  box-shadow: 0 0 0 3px rgba(107, 199, 181, 0.2);
}

.btn-save {
  width: 100%;
  background: black;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-save:hover {
  background: #333;
  transform: translateY(-2px);
}

.dark-mode .btn-save {
  background: #6bc7b5;
  color: white;
}

.dark-mode .btn-save:hover {
  background: #5ab3a2;
}

.btn-logout {
  width: 100%;
  background: transparent;
  color: #e74c3c;
  border: 1px solid #e74c3c;
  border-radius: 50px;
  padding: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-logout:hover {
  background: #fdf2f2;
}

.btn-orders {
  width: 100%;
  background: #bcd9d6;
  color: #1a1a1a;
  border: none;
  border-radius: 50px;
  padding: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-bottom: 5px;
}

.btn-orders:hover {
  background: #6bc7b5;
  color: white;
  transform: translateY(-2px);
}

.profile-actions {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.alert {
  padding: 15px;
  border-radius: 10px;
  font-weight: 600;
}

.alert-success {
  background-color: #e8f5e9;
  color: #2e7d32;
}

.alert-danger {
  background-color: #ffebee;
  color: #c62828;
}
</style>
