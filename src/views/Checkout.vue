<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth' // Assuming auth store exists
import api from '../api'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()

const isRegisteredView = ref(false) // Default to guest per Laravel logic (or toggle)

// Form data
const formData = ref({
  nombre: '',
  email: '',
  direccion: '',
  ciudad: '',
  cp: '',
  tarjeta: '',
  caducidad: '',
  cvv: ''
})

const processPayment = async () => {
  try {
    // In a real app, you would validate and send data to backend
    const response = await api.post('/checkout/procesar', formData.value)
    alert('¡Compra realizada con éxito! Recibirás un correo de confirmación.')
    // Redirect or clear cart
  } catch (error) {
    console.error(error)
    alert('Error al procesar el pago')
  }
}

const toggleView = () => {
  isRegisteredView.value = !isRegisteredView.value
}
</script>

<template>
  <div class="main-wrapper-checkout">
    <h2 class="text-center mb-4">Pago Seguro</h2>

    <div class="container py-5" id="contenido-principal">
      
      <!-- SECCIÓN 1: USUARIO REGISTRADO -->
      <section v-if="isRegisteredView" id="vista-registrado" class="tarjeta-principal-contenedor shadow-lg bg-white p-4 rounded-4" aria-labelledby="titulo-confirmacion">
        <h2 id="titulo-confirmacion" class="text-center mb-4">Confirmar Pedido</h2>
        <div class="alert alert-info text-center" role="status">
            Bienvenido de nuevo, <strong>{{ authStore.user?.name || 'Joan Ripoll' }}</strong>. Usaremos tus datos guardados.
        </div>

        <div class="row g-4">
          <div class="col-md-6">
            <h3>Envío a:</h3>
            <p>Calle Falsa 123, 28001 Madrid<br />España</p>
          </div>
          <div class="col-md-6 text-md-end">
            <h3>Método de pago:</h3>
            <p>Visa terminada en **** 4422</p>
          </div>
        </div>

        <hr aria-hidden="true" />

        <div class="d-flex justify-content-between align-items-center mb-4">
          <span class="fs-4">Total a pagar:</span>
          <span class="fs-2 fw-bold" style="color: #7ED9C7">129.99€</span>
        </div>

        <div class="d-grid gap-3">
          <button class="btn btn-lg text-white py-3 fw-bold" style="background-color: #000; border-radius: 30px" @click="processPayment">CONFIRMAR Y PAGAR AHORA</button>
          <button class="btn btn-outline-danger" style="border-radius: 30px" @click="router.go(-1)">Cancelar compra</button>
        </div>
      </section>

      <!-- SECCIÓN 2: USUARIO NO REGISTRADO -->
      <section v-else id="vista-invitado" class="tarjeta-principal-contenedor shadow-lg bg-white p-4 rounded-4" aria-labelledby="titulo-pago">
        <h2 id="titulo-pago" class="text-center mb-4">Datos de Envío y Pago</h2>

        <form @submit.prevent="processPayment" id="form-pago">
          
          <!-- Datos Personales -->
          <fieldset>
            <legend>Información Personal</legend>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input v-model="formData.nombre" type="text" id="nombre" class="form-control" required placeholder="Ej: Maria García" />
              </div>
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input v-model="formData.email" type="email" id="email" class="form-control" required placeholder="nombre@ejemplo.com" />
              </div>
            </div>
          </fieldset>

          <!-- Dirección -->
          <fieldset>
            <legend>Dirección de Envío</legend>
            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección de la calle</label>
              <input v-model="formData.direccion" type="text" id="direccion" class="form-control" required placeholder="Calle, número, piso..." />
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="ciudad" class="form-label">Ciudad</label>
                <input v-model="formData.ciudad" type="text" id="ciudad" class="form-control" required />
              </div>
              <div class="col-md-6 mb-3">
                <label for="cp" class="form-label">Código Postal</label>
                <input v-model="formData.cp" type="text" id="cp" class="form-control" required pattern="[0-9]{5}" />
              </div>
            </div>
          </fieldset>

          <!-- Pago -->
          <fieldset>
            <legend>Método de Pago</legend>
            <div class="mb-3">
              <label for="tarjeta" class="form-label">Número de Tarjeta</label>
              <input v-model="formData.tarjeta" type="text" id="tarjeta" class="form-control" required placeholder="0000 0000 0000 0000" />
              <small class="text-muted">Aceptamos Visa, MasterCard y Amex.</small>
            </div>
            <div class="row">
              <div class="col-6 mb-3">
                <label for="caducidad" class="form-label">Caducidad (MM/AA)</label>
                <input v-model="formData.caducidad" type="text" id="caducidad" class="form-control" required placeholder="12/26" />
              </div>
              <div class="col-6 mb-3">
                <label for="cvv" class="form-label">CVV</label>
                <input v-model="formData.cvv" type="text" id="cvv" class="form-control" required placeholder="123" />
              </div>
            </div>
          </fieldset>

          <div class="mt-4 d-grid">
            <button type="submit" class="btn btn-lg text-white py-3 fw-bold" style="background-color: #000; border-radius: 30px">FINALIZAR COMPRA (129.99€)</button>
          </div>
        </form>
      </section>

      <!-- BOTÓN DE PRUEBA -->
      <div class="text-center mt-4">
        <button class="btn btn-sm btn-secondary" @click="toggleView">Alternar simulación (Registrado / Invitado)</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Estilos específicos para esta página */
.form-label {
  font-weight: bold;
  color: #222;
}
.form-control:focus {
  border: 2px solid black;
  box-shadow: 0 0 0 0.25rem rgba(126, 217, 199, 0.5);
}
fieldset {
  border: 1px solid #ccc;
  padding: 20px;
  border-radius: 10px;
  margin-bottom: 20px;
}
legend {
  width: auto;
  padding: 0 10px;
  font-size: 1.2rem;
  font-weight: bold;
}
.main-wrapper-checkout {
  padding: 20px;
}
.oculto {
  display: none;
}
</style>
