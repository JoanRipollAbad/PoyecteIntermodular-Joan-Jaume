<template>
  <button type="button" @click="loginWithGoogle" class="google-btn">
    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google logo" />
    <span>Continuar con Google</span>
  </button>
</template>

<script setup>
const loginWithGoogle = () => {
  // Redirigir al endpoint de Laravel que inicia el flujo de Google
  window.location.href = `${import.meta.env.VITE_API_URL}/oauth/google/redirect`;
};
</script>

<style scoped>
.google-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
  width: 100%;
  padding: 12px;
  background-color: white;
  color: #3c4043;
  border: 1px solid #dadce0;
  border-radius: 50px;
  font-family: 'Google Sans', Roboto, Arial, sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color .2s, box-shadow .2s, border-color .2s;
  margin-top: 15px;
}

.google-btn:hover {
  background-color: #f8f9fa;
  border-color: #d2e3fc;
  box-shadow: 0 1px 2px 0 rgba(60,64,67,0.30), 0 1px 3px 1px rgba(60,64,67,0.15);
}

.google-btn:active {
  background-color: #ecf3fe;
}

.google-btn img {
  width: 18px;
  height: 18px;
}
</style>
