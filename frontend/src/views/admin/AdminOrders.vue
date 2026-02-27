<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../api'
import { Package, User, Mail, MapPin, Calendar, CreditCard, ChevronDown, ChevronUp, ExternalLink } from 'lucide-vue-next'

const orders = ref([])
const loading = ref(true)
const error = ref('')
const expandedOrderId = ref(null)
const router = useRouter()

const fetchOrders = async () => {
  loading.value = true
  try {
    const response = await api.get('/admin/pedidos')
    orders.value = response.data
  } catch (err) {
    console.error('Error fetching orders:', err)
    error.value = 'No se pudieron cargar los pedidos. Asegúrate de ser administrador.'
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

onMounted(fetchOrders)
</script>

<template>
  <div class="admin-orders-container">
  <div class="orders-component-wrapper">
    <!-- Header with Refresh Button -->
    <div class="component-header">
      <h3 class="component-title">Ventas Registradas</h3>
      <button @click="fetchOrders" class="btn-refresh" :disabled="loading">
        <Calendar class="w-4 h-4 mr-2" />
        Actualizar Pedidos
      </button>
    </div>

    <!-- Content Area -->
    <div class="orders-content shadow-premium">
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Cargando pedidos...</p>
      </div>

      <div v-else-if="error" class="error-state">
        <p>{{ error }}</p>
        <button @click="fetchOrders" class="btn-retry">Reintentar</button>
      </div>

      <div v-else-if="orders.length === 0" class="empty-state">
        <Package class="empty-icon" />
        <h3>No hay pedidos registrados</h3>
        <p>Todavía no se ha realizado ninguna venta.</p>
      </div>

      <div v-else class="table-responsive">
        <table class="orders-table">
          <thead>
            <tr>
              <th class="hide-mobile">ID</th>
              <th>Cliente</th>
              <th class="hide-tablet">Fecha</th>
              <th>Estado</th>
              <th>Total</th>
              <th class="text-right">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="order in orders" :key="order.id">
              <tr :class="{ 'row-expanded': expandedOrderId === order.id }">
                <td class="font-bold hide-mobile">#{{ order.id }}</td>
                <td>
                  <div class="user-cell">
                    <span class="user-name">{{ order.nombre }}</span>
                    <span class="user-email hide-tablet">{{ order.email }}</span>
                  </div>
                </td>
                <td class="hide-tablet">
                  <div class="date-cell">
                    {{ new Date(order.created_at).toLocaleDateString() }}
                  </div>
                </td>
                <td>
                  <span class="status-badge" :class="getStatusClass(order.status)">
                    {{ order.status.toUpperCase() }}
                  </span>
                </td>
                <td class="font-bold price-text">{{ order.total }}€</td>
                <td class="text-right">
                  <button @click="toggleOrder(order.id)" class="btn-action-view">
                    {{ expandedOrderId === order.id ? 'Cerrar' : 'Ver Detalles' }}
                    <ChevronDown v-if="expandedOrderId !== order.id" class="w-4 h-4 ml-1" />
                    <ChevronUp v-else class="w-4 h-4 ml-1" />
                  </button>
                </td>
              </tr>
              
              <!-- Expanded Order Details -->
              <tr v-if="expandedOrderId === order.id" class="details-row">
                <td colspan="6">
                  <div class="expanded-panel animate-fade-in">
                    <div class="details-grid">
                      <!-- Customer Info -->
                      <div class="info-group">
                        <h4 class="group-title"><User class="w-4 h-4 mr-2" /> Información del Cliente</h4>
                        <div class="info-content">
                          <p><strong>Nombre:</strong> {{ order.nombre }}</p>
                          <p><strong>Email:</strong> {{ order.email }}</p>
                          <p v-if="order.user"><strong>Usuario ID:</strong> {{ order.user_id }}</p>
                        </div>
                      </div>

                      <!-- Shipping Info -->
                      <div class="info-group">
                        <h4 class="group-title"><MapPin class="w-4 h-4 mr-2" /> Envío</h4>
                        <div class="info-content">
                          <p>{{ order.direccion }}</p>
                          <p>{{ order.cp }}, {{ order.ciudad }}</p>
                          <p>España</p>
                        </div>
                      </div>

                      <!-- Payment Info -->
                      <div class="info-group">
                        <h4 class="group-title"><CreditCard class="w-4 h-4 mr-2" /> Pago</h4>
                        <div class="info-content">
                          <p><strong>Total:</strong> {{ order.total }}€</p>
                          <p><strong>Estado:</strong> {{ order.status }}</p>
                        </div>
                      </div>
                    </div>

                    <div class="items-list mt-6">
                      <h4 class="group-title mb-4"><Package class="w-4 h-4 mr-2" /> Artículos del Pedido</h4>
                      <div class="items-table-wrapper">
                        <table class="items-table">
                          <thead>
                            <tr>
                              <th>Producto</th>
                              <th>Cantidad</th>
                              <th>Precio Unit.</th>
                              <th class="text-right">Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="item in order.items" :key="item.id">
                              <td>
                                <div class="item-product">
                                  <img :src="item.product?.img || '/img/logo.jpg'" class="item-img" />
                                  <span>{{ item.product?.nom || 'Producto eliminado' }}</span>
                                </div>
                              </td>
                              <td class="text-center">{{ item.quantitat }}</td>
                              <td>{{ item.preu }}€</td>
                              <td class="text-right font-bold">{{ (item.quantitat * item.preu).toFixed(2) }}€</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>
.orders-component-wrapper {
  animation: fadeIn 0.5s ease-out;
}

.component-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.component-title {
  font-size: 1.4rem;
  font-weight: 800;
  color: #1a1a1a;
  margin: 0;
}

.dark-mode .component-title {
  color: white;
}

.btn-refresh {
  background: var(--primary-color, #6bc7b5);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(107, 199, 181, 0.2);
}

.btn-refresh:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(107, 199, 181, 0.3);
}

.shadow-premium {
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
}

.dark-mode .shadow-premium {
  box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

.orders-content {
  background: var(--card-bg);
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .orders-content {
  border-color: rgba(255,255,255,0.05);
}

/* Table Styles */
.orders-table {
  width: 100%;
  border-collapse: collapse;
}

.orders-table th {
  text-align: left;
  padding: 20px;
  background: rgba(0,0,0,0.02);
  font-weight: 700;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  opacity: 0.6;
}

.dark-mode .orders-table th {
  background: rgba(255,255,255,0.02);
}

.orders-table td {
  padding: 24px 20px;
  border-bottom: 1px solid rgba(0,0,0,0.04);
}

.dark-mode .orders-table td {
  border-bottom-color: rgba(255,255,255,0.04);
}

.row-expanded td {
  background: rgba(107, 199, 181, 0.03);
  border-bottom: none;
}

.user-cell {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 700;
  font-size: 1.05rem;
}

.user-email {
  font-size: 0.85rem;
  opacity: 0.6;
}

.status-badge {
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.5px;
}

.status-paid { background: rgba(107, 199, 181, 0.1); color: #4fa896; }
.status-pending { background: rgba(255, 193, 7, 0.1); color: #b08d05; }
.status-shipped { background: rgba(0, 123, 255, 0.1); color: #0056b3; }
.status-completed { background: rgba(40, 167, 69, 0.1); color: #1e7e34; }
.status-cancelled { background: rgba(220, 53, 69, 0.1); color: #c82333; }

.price-text {
  font-size: 1.1rem;
  color: #6bc7b5;
}

.btn-action-view {
  background: transparent;
  color: var(--text-color);
  border: 1px solid rgba(0,0,0,0.1);
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  transition: all 0.2s;
}

.dark-mode .btn-action-view {
  border-color: rgba(255,255,255,0.1);
}

.btn-action-view:hover {
  background: rgba(0,0,0,0.05);
  transform: translateY(-1px);
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

/* Expanded Panel */
.details-row td {
  padding: 0 40px 40px 40px;
}

.expanded-panel {
  background: rgba(0,0,0,0.02);
  border-radius: 16px;
  padding: 30px;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .expanded-panel {
  background: rgba(255,255,255,0.02);
  border-color: rgba(255,255,255,0.05);
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.group-title {
  font-size: 0.95rem;
  font-weight: 800;
  text-transform: uppercase;
  margin-bottom: 15px;
  display: flex;
  align-items: center;
  opacity: 0.8;
}

.info-content p {
  margin-bottom: 6px;
  opacity: 0.9;
}

/* Items Table */
.items-table-wrapper {
  overflow-x: auto;
}

.items-table {
  width: 100%;
  border-collapse: collapse;
}

.items-table th {
  padding: 12px;
  background: transparent;
  font-size: 0.8rem;
  border-bottom: 2px solid rgba(0,0,0,0.05);
}

.items-table td {
  padding: 15px 12px;
  font-size: 0.95rem;
}

.item-product {
  display: flex;
  align-items: center;
  gap: 12px;
}

.item-img {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  object-fit: cover;
}

/* States */
.loading-state, .error-state, .empty-state {
  padding: 80px;
  text-align: center;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(107, 199, 181, 0.1);
  border-left-color: #6bc7b5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin { to { transform: rotate(360deg); } }

.empty-icon {
  width: 64px;
  height: 64px;
  opacity: 0.3;
  margin-bottom: 16px;
}

.text-right { text-align: right; }
.text-center { text-align: center; }
.font-bold { font-weight: 700; }
.animate-fade-in { animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

@media (max-width: 768px) {
  .admin-orders-container { padding: 0; }
  .admin-title { font-size: 2rem; }
  .component-header { flex-direction: column; gap: 15px; align-items: flex-start; }
  .orders-table th, .orders-table td { padding: 12px 8px; font-size: 0.85rem; }
  .hide-tablet { display: none; }
  .details-row td { padding: 0 10px 20px 10px; }
  .expanded-panel { padding: 20px 15px; }
  .btn-refresh { padding: 10px 16px; font-size: 0.85rem; }
}

@media (max-width: 480px) {
  .hide-mobile { display: none; }
  .btn-refresh { width: 100%; justify-content: center; }
  .user-name { font-size: 0.85rem; }
  .status-badge { padding: 4px 8px; font-size: 0.65rem; }
  .price-text { font-size: 0.95rem; }
}
</style>
