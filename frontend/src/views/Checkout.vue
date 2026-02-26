<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'
import api from '../api'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

// Eliminamos la variable de simulación


// Form data for guest
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
  if (cartStore.items.length === 0) {
    alert('Tu carrito está vacío.')
    return
  }

  try {
    // Determine user data (prefer authStore if available)
    const userEmail = authStore.user?.email || formData.value.email
    const userName = authStore.user?.name || formData.value.nombre
    
    // Call n8n webhook for order confirmation email
    // IMPORTANT: Make sure your n8n webhook is listening at this URL
    //const webhookUrl = 'http://localhost:5678/webhook-test/confirmacion-compra' // URL producción n8n
    const webhookUrl = 'http://localhost:5678/webhook/confirmacion-compra'
    
    await api.post(webhookUrl, {
      email: userEmail,
      nombre: userName,
      total: `${cartStore.totalPrice}€`,
      fecha: new Date().toLocaleDateString(),
      items: cartStore.items.map(item => ({
        name: item.nom,
        price: `${item.preu}€`,
        quantity: item.quantity
      }))
    }, {
      baseURL: '' 
    })

    alert('¡Compra realizada con éxito! Recibirás un correo de confirmación.')
    cartStore.clearCart()
    router.push('/')
  } catch (error) {
    console.error('Error enviando confirmación a n8n:', error)
    alert('Error al procesar el pago. Por favor, revisa tu conexión con n8n.')
  }
}

// No longer needed
// const toggleView = () => {
//   isRegisteredView.value = !isRegisteredView.value
// }
</script>

<template>
  <div class="checkout-page-bg">
    <div class="checkout-container">
      <h1 class="page-title">Pago Seguro</h1>

      <div class="checkout-card shadow-lg">
        
        <!-- VISTA: INVITADO (GUEST) -->
        <section v-if="!authStore.isAuthenticated" class="guest-checkout">
          <h2 class="card-subtitle">Datos de Envío y Pago</h2>

          <form @submit.prevent="processPayment" class="checkout-form">
            
            <!-- Información Personal -->
            <fieldset class="form-section">
              <legend>Información Personal</legend>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="nombre" class="form-label">Nombre Completo</label>
                  <input v-model="formData.nombre" type="text" id="nombre" class="form-control custom-input" required placeholder="Ej: Maria García" />
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">Correo Electrónico</label>
                  <input v-model="formData.email" type="email" id="email" class="form-control custom-input" required placeholder="nombre@ejemplo.com" />
                </div>
              </div>
            </fieldset>

            <!-- Dirección de Envío -->
            <fieldset class="form-section">
              <legend>Dirección de Envío</legend>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección de la calle</label>
                <input v-model="formData.direccion" type="text" id="direccion" class="form-control custom-input" required placeholder="Calle, número, piso..." />
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="ciudad" class="form-label">Ciudad</label>
                  <input v-model="formData.ciudad" type="text" id="ciudad" class="form-control custom-input" required />
                </div>
                <div class="col-md-6">
                  <label for="cp" class="form-label">Código Postal</label>
                  <input v-model="formData.cp" type="text" id="cp" class="form-control custom-input" required />
                </div>
              </div>
            </fieldset>

            <!-- Método de Pago -->
            <fieldset class="form-section">
              <legend>Método de Pago</legend>
              <div class="mb-3">
                <label for="tarjeta" class="form-label">Número de Tarjeta</label>
                <input v-model="formData.tarjeta" type="text" id="tarjeta" class="form-control custom-input" required placeholder="0000 0000 0000 0000" />
                <p class="payment-help text-muted mt-1">Aceptamos Visa, MasterCard y Amex.</p>
              </div>
              <div class="row g-3">
                <div class="col-6">
                  <label for="caducidad" class="form-label">Caducidad (MM/AA)</label>
                  <input v-model="formData.caducidad" type="text" id="caducidad" class="form-control custom-input" required placeholder="12/26" />
                </div>
                <div class="col-6">
                  <label for="cvv" class="form-label">CVV</label>
                  <input v-model="formData.cvv" type="text" id="cvv" class="form-control custom-input" required placeholder="123" />
                </div>
              </div>
            </fieldset>

            <button type="submit" class="btn-finalize mt-4">
              FINALIZAR COMPRA ({{ cartStore.totalPrice }}€)
            </button>
          </form>
        </section>

        <!-- VISTA: REGISTRADO (CONFIRMACIÓN) -->
        <section v-else class="registered-checkout">
          <h2 class="card-subtitle text-center">Confirmar Pedido</h2>
          
          <div class="welcome-alert">
            <span class="info-icon">ℹ️</span>
            <span>Bienvenido de nuevo, <strong>{{ authStore.user?.name || 'Joan Ripoll' }}</strong>. Usaremos tus datos guardados.</span>
          </div>

          <div class="info-grid mt-4">
            <div class="info-block">
              <h3 class="info-title">Envío a:</h3>
              <p class="info-content">Calle Falsa 123, 28001 Madrid<br />España</p>
            </div>
            <div class="info-block text-end">
              <h3 class="info-title">Método de pago:</h3>
              <p class="info-content">Visa terminada en **** 4422</p>
            </div>
          </div>

          <div class="total-section mt-4">
            <span class="total-label">Total a pagar:</span>
            <span class="total-price">{{ cartStore.totalPrice }}€</span>
          </div>

          <div class="registered-actions mt-5">
            <button class="btn-confirm-pay" @click="processPayment">
              CONFIRMAR Y PAGAR AHORA
            </button>
            <button class="btn-cancel" @click="router.go(-1)">
              Cancelar compra
            </button>
          </div>
        </section>
      </div>


    </div>
  </div>
</template>

<style scoped>
.checkout-page-bg {
  background-color: var(--bg-color);
  min-height: 100vh;
  padding: 60px 20px;
  display: flex;
  justify-content: center;
}

.checkout-container {
  width: 100%;
  max-width: 900px;
}

.page-title {
  text-align: center;
  font-size: 2.5rem;
  font-weight: 600;
  color: var(--primary-color);
  margin-bottom: 50px;
}

.checkout-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 40px;
  border: 1px solid rgba(0,0,0,0.05);
  box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}

.dark-mode .checkout-card {
  border-color: rgba(255, 255, 255, 0.05);
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.card-subtitle {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 35px;
  text-align: center;
}

/* FORM STYLES */
.form-section {
  border: 1px solid rgba(0,0,0,0.1);
  border-radius: 12px;
  padding: 25px;
  margin-bottom: 25px;
}

.dark-mode .form-section {
  border-color: rgba(255,255,255,0.1);
}

.form-section legend {
  float: none;
  width: auto;
  padding: 0 10px;
  font-size: 1.15rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 0;
}

.form-label {
  font-weight: 700;
  font-size: 0.95rem;
  color: var(--text-color);
  opacity: 0.9;
  margin-bottom: 8px;
}

.custom-input {
  border-radius: 8px;
  padding: 12px 15px;
  border: 1px solid rgba(0,0,0,0.1);
  background: var(--bg-color);
  color: var(--text-color);
  font-size: 1rem;
}

.dark-mode .custom-input {
  border-color: rgba(255,255,255,0.1);
}

.custom-input::placeholder {
  color: #bbb;
}

.custom-input:focus {
  border-color: #7ED9C7;
  box-shadow: 0 0 0 3px rgba(126, 217, 199, 0.2);
}

.payment-help {
  font-size: 0.85rem;
}

.btn-finalize {
  width: 100%;
  background: black;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 20px;
  font-weight: 700;
  font-size: 1.2rem;
  transition: transform 0.2s, opacity 0.2s;
  cursor: pointer;
}

.btn-finalize:hover {
  opacity: 0.9;
  transform: translateY(-2px);
}

/* REGISTERED VIEW STYLES */
.welcome-alert {
  background-color: rgba(107, 199, 181, 0.1);
  color: #6bc7b5;
  padding: 20px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: 1.1rem;
  border: 1px solid rgba(107, 199, 181, 0.2);
}

.dark-mode .welcome-alert {
  background-color: rgba(255, 255, 255, 0.02);
  color: var(--text-color);
  border-color: rgba(255, 255, 255, 0.1);
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  padding: 10px 0;
}

.info-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 10px;
}

.info-content {
  color: var(--text-color);
  opacity: 0.7;
  font-size: 1.1rem;
  line-height: 1.5;
}

.total-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid rgba(0,0,0,0.1);
  padding-top: 30px;
}

.dark-mode .total-section {
  border-top-color: rgba(255,255,255,0.1);
}

.total-label {
  font-size: 1.8rem;
  color: var(--text-color);
}

.total-price {
  font-size: 2.5rem;
  font-weight: 800;
  color: #6bc7b5;
}

.registered-actions {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.btn-confirm-pay {
  background: black;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 20px;
  font-weight: 700;
  font-size: 1.2rem;
  cursor: pointer;
}

.btn-cancel {
  background: transparent;
  color: #6bc7b5;
  border: 1px solid #6bc7b5;
  border-radius: 50px;
  padding: 12px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-cancel:hover {
  background: #6bc7b5;
  color: white;
}

/* SIMULATION AREA */
.simulation-area {
  margin-top: 40px;
  text-align: center;
}

.btn-toggle-sim {
  background: #6c757d;
  color: white;
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-size: 0.9rem;
  cursor: pointer;
}

@media (max-width: 768px) {
  .info-grid {
    grid-template-columns: 1fr;
    text-align: left !important;
    gap: 20px;
  }
  .info-block.text-end {
    text-align: left !important;
  }
}

@media (max-width: 600px) {
  .page-title { font-size: 1.8rem; margin-bottom: 30px; }
  .checkout-card { padding: 25px 15px; border-radius: 15px; }
  .card-subtitle { font-size: 1.5rem; margin-bottom: 25px; }
  .form-section { padding: 15px; margin-bottom: 20px; }
  .btn-finalize, .btn-confirm-pay { padding: 15px; font-size: 1.1rem; }
  .total-label { font-size: 1.3rem; }
  .total-price { font-size: 1.8rem; }
  .welcome-alert { font-size: 0.95rem; padding: 15px; }
}
</style>
