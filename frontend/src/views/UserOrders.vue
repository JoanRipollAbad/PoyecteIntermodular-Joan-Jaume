<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api'
import { Package, Calendar, CreditCard, ChevronDown, ChevronUp, MapPin, Truck } from 'lucide-vue-next'

const orders = ref([])
const loading = ref(true)
const error = ref('')
const expandedOrderId = ref(null)
const router = useRouter()

const fetchMyOrders = async () => {
  loading.value = true
  try {
    const response = await api.get('/my-pedidos')
    orders.value = response.data
  } catch (err) {
    console.error('Error fetching orders:', err)
    error.value = 'No se pudieron cargar tus pedidos. Por favor, intenta de nuevo más tarde.'
  } finally {
    loading.value = false
  }
}

const toggleOrder = (orderId) => {
  if (expandedOrderId.value === orderId) {
    expandedOrderId.value = null
  } else {
    expandedOrderId.value = orderId
  }
}

const getStatusClass = (status) => {
  switch (status.toLowerCase()) {
    case 'pagado': return 'status-paid'
    case 'pendiente': return 'status-pending'
    case 'enviado': return 'status-shipped'
    case 'completado': return 'status-completed'
    case 'cancelado': return 'status-cancelled'
    default: return ''
  }
}

const getStatusLabel = (status) => {
  switch (status.toLowerCase()) {
    case 'pagado': return 'Pagado'
    case 'pendiente': return 'Pendiente'
    case 'enviado': return 'Enviado'
    case 'completado': return 'Completado'
    case 'cancelado': return 'Cancelado'
    default: return status
  }
}

onMounted(fetchMyOrders)
</script>

<template>
  <div class="user-orders-view">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <!-- Top Header Area -->
      <div class="header-wrapper">
        <router-link to="/profile" class="back-link">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Volver a mi Perfil
        </router-link>

        <div class="header-section text-center mb-12">
          <h1 class="titulo-destacado">Mis Pedidos</h1>
          <p class="subtitle mt-3">Historial de tus compras de seguridad en JJ-Security</p>
        </div>
      </div>

      <!-- Content Area -->
      <div class="glass-container">
        <div v-if="loading" class="loading-state">
          <div class="spinner"></div>
          <p>Cargando tus pedidos...</p>
        </div>

        <div v-else-if="error" class="error-state">
          <div class="error-icon">⚠️</div>
          <p>{{ error }}</p>
          <button @click="fetchMyOrders" class="btn-primary mt-4">Reintentar</button>
        </div>

        <div v-else-if="orders.length === 0" class="empty-state">
          <div class="empty-illustration">
             <Package class="w-16 h-16 opacity-20" />
          </div>
          <h3>Aún no tienes pedidos</h3>
          <p>Tus compras aparecerán aquí una vez que realices tu primer pedido.</p>
          <router-link to="/filters" class="btn-primary mt-6">Ir a la tienda</router-link>
        </div>

        <div v-else class="orders-list">
          <div v-for="order in orders" :key="order.id" class="order-card-wrapper mb-6">
            <div 
              class="order-card-header shadow-premium" 
              :class="{ 'expanded': expandedOrderId === order.id }"
              @click="toggleOrder(order.id)"
            >
              <div class="order-main-info">
                <div class="order-id">
                  <span class="label">Pedido</span>
                  <span class="value">#{{ order.id }}</span>
                </div>
                <div class="order-date">
                  <span class="label">Fecha</span>
                  <span class="value">{{ new Date(order.created_at).toLocaleDateString() }}</span>
                </div>
                <div class="order-total">
                  <span class="label">Total</span>
                  <span class="value highlight">{{ order.total }}€</span>
                </div>
                <div class="order-status">
                  <span class="status-badge" :class="getStatusClass(order.status)">
                    {{ getStatusLabel(order.status) }}
                  </span>
                </div>
              </div>
              <div class="order-toggle">
                <ChevronDown v-if="expandedOrderId !== order.id" class="w-5 h-5" />
                <ChevronUp v-else class="w-5 h-5" />
              </div>
            </div>

            <transition name="expand">
              <div v-if="expandedOrderId === order.id" class="order-details-panel shadow-premium">
                <div class="grid md:grid-cols-2 gap-8 p-8 border-b border-black/5">
                  <div class="delivery-info">
                    <h4 class="detail-title"><Truck class="w-4 h-4 mr-2" /> Dirección de Envío</h4>
                    <div class="detail-content">
                      <p class="font-bold">{{ order.nombre }}</p>
                      <p>{{ order.direccion }}</p>
                      <p>{{ order.cp }}, {{ order.ciudad }}</p>
                    </div>
                  </div>
                  <div class="payment-summary">
                    <h4 class="detail-title"><CreditCard class="w-4 h-4 mr-2" /> Resumen de Pago</h4>
                    <div class="detail-content">
                      <div class="flex justify-between items-center mb-2">
                        <span>Estado:</span>
                        <span class="font-bold">{{ getStatusLabel(order.status) }}</span>
                      </div>
                      <div class="flex justify-between items-center text-lg">
                        <span class="font-bold">Total Pagado:</span>
                        <span class="font-extrabold text-[#6bc7b5]">{{ order.total }}€</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="order-items p-8">
                  <h4 class="detail-title mb-6"><Package class="w-4 h-4 mr-2" /> Artículos</h4>
                  <div class="items-list-inner">
                    <div v-for="item in order.items" :key="item.id" class="item-row">
                      <div class="item-img-box">
                        <img :src="item.product?.img ? (item.product.img.startsWith('/') ? item.product.img : '/' + item.product.img) : '/img/logo.jpg'" alt="producto" />
                      </div>
                      <div class="item-info">
                        <p class="item-name">{{ item.product?.nom || 'Producto no disponible' }}</p>
                        <p class="item-qty">Cantidad: {{ item.quantitat }}</p>
                      </div>
                      <div class="item-price">
                        {{ item.preu }}€
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.user-orders-view {
  background: linear-gradient(135deg, #f4f7f6 0%, #e8f1ef 100%);
  min-height: calc(100vh - 80px);
}

.titulo-destacado {
  font-weight: 800;
  font-size: 3rem;
  color: #1a1a1a;
  letter-spacing: -1px;
  text-align: center;
}

.titulo-destacado::after {
  content: "";
  display: block;
  width: 60px;
  height: 5px;
  background: linear-gradient(90deg, #6bc7b5, #bcd9d6);
  margin: 15px auto;
  border-radius: 10px;
}

.subtitle {
  color: #666;
  font-size: 1.1rem;
  text-align: center;
}

.header-wrapper {
  position: relative;
  display: flex;
  flex-direction: column;
}

.back-link {
  position: absolute;
  left: 0;
  top: 10px;
  display: inline-flex;
  align-items: center;
  color: #666;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  z-index: 10;
}

@media (max-width: 1024px) {
  .back-link {
    position: static;
    margin-bottom: 20px;
  }
  .header-wrapper {
    align-items: center;
  }
}

.back-link:hover {
  color: #6bc7b5;
  transform: translateX(-5px);
}

.glass-container {
  min-height: 400px;
}

/* Order Card Header */
.order-card-header {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.5);
  padding: 24px 30px;
  border-radius: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.order-card-header:hover {
  background: rgba(255, 255, 255, 0.9);
  transform: translateY(-2px);
}

.order-card-header.expanded {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  background: white;
}

.order-main-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
  flex-grow: 1;
  gap: 20px;
}

.order-main-info > div {
  display: flex;
  flex-direction: column;
}

.label {
  font-size: 0.7rem;
  text-transform: uppercase;
  font-weight: 800;
  color: #aaa;
  letter-spacing: 1px;
  margin-bottom: 4px;
}

.value {
  font-weight: 700;
  color: #1a1a1a;
}

.value.highlight {
  color: #6bc7b5;
}

.status-badge {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 800;
  text-align: center;
  max-width: fit-content;
}

.status-paid { background: #e8f5f2; color: #2d7a6a; }
.status-pending { background: #fffcf0; color: #b08d05; }
.status-shipped { background: #f0f7ff; color: #007bff; }
.status-completed { background: #f0fdf4; color: #166534; }
.status-cancelled { background: #fef2f2; color: #991b1b; }

/* Order Details Panel */
.order-details-panel {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-top: none;
  border-bottom-left-radius: 20px;
  border-bottom-right-radius: 20px;
  overflow: hidden;
}

.detail-title {
  display: flex;
  align-items: center;
  font-size: 0.85rem;
  font-weight: 800;
  text-transform: uppercase;
  color: #1a1a1a;
  margin-bottom: 12px;
  opacity: 0.6;
}

.detail-content p {
  margin-bottom: 2px;
  color: #555;
}

/* Item Row */
.item-row {
  display: flex;
  align-items: center;
  padding: 15px 0;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  gap: 20px;
}

.item-row:last-child {
  border-bottom: none;
}

.item-img-box {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  overflow: hidden;
  flex-shrink: 0;
}

.item-img-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.item-info {
  flex-grow: 1;
}

.item-name {
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 4px;
}

.item-qty {
  font-size: 0.85rem;
  color: #888;
}

.item-price {
  font-weight: 800;
  color: #1a1a1a;
}

/* States */
.loading-state, .empty-state, .error-state {
  text-align: center;
  padding: 60px 0;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(107, 199, 181, 0.1);
  border-top-color: #6bc7b5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

.empty-illustration {
  margin-bottom: 20px;
}

.btn-primary {
  background: #6bc7b5;
  color: white;
  padding: 14px 32px;
  border-radius: 50px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: #5ab3a2;
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(107, 199, 181, 0.3);
}

.shadow-premium {
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04);
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Transitions */
.expand-enter-active, .expand-leave-active {
  transition: all 0.3s ease-out;
  max-height: 1000px;
}
.expand-enter-from, .expand-leave-to {
  max-height: 0;
  opacity: 0;
}

@media (max-width: 768px) {
  .titulo-destacado { font-size: 2.2rem; }
  .order-card-header { padding: 20px 15px; }
  .order-main-info { grid-template-columns: 1fr; gap: 15px; }
  .order-details-panel .grid { grid-template-columns: 1fr; padding: 20px; }
  .order-items { padding: 20px; }
  .order-main-info > div { flex-direction: row; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 5px; }
  .order-main-info > div:last-child { border-bottom: none; }
  .label { margin-bottom: 0; }
  .detail-title { font-size: 0.75rem; }
}
</style>
