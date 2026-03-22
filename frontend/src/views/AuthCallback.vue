<template>
  <div class="callback-container">
    <div class="loader"></div>
    <p>Autenticando con Google...</p>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

onMounted(async () => {
  const token = route.query.token;

  if (token) {
    try {
      // Guardar el token en el store y localStorage
      // El backend redirige a ?token=XYZ, pero no envía los datos del usuario.
      // Así que primero guardamos el token y luego pedimos los datos de 'me'.
      localStorage.setItem('auth_token', token);
      authStore.token = token;
      authStore.isAuthenticated = true;

      // Obtener datos del usuario
      await authStore.fetchUser();
      
      router.push('/');
    } catch (error) {
      console.error('Error al procesar el callback de Google:', error);
      router.push('/login?error=google_failed');
    }
  } else {
    router.push('/login');
  }
});
</script>

<style scoped>
.callback-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 60vh;
  gap: 20px;
}

.loader {
  width: 48px;
  height: 48px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

p {
  font-size: 1.1rem;
  color: #666;
}
</style>
