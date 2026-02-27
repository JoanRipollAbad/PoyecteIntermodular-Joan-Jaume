<script setup>
import { useCartStore } from '../stores/cart'
import { useRouter } from 'vue-router'

const cartStore = useCartStore()
const router = useRouter()

const goToCheckout = () => {
  router.push('/checkout')
}

const continueShopping = () => {
  router.push('/filters')
}
</script>

<template>
  <div class="cart-page">
    <div class="cart-container animate-fade-in">
      <h2 class="cart-title">Tu Carrito de Compra</h2>

      <div v-if="cartStore.items.length === 0" class="empty-cart-state">
        <div class="empty-icon">🛒</div>
        <h3>Tu carrito está vacío</h3>
        <p>Parece que aún no has añadido nada a tu lista de seguridad.</p>
        <button @click="continueShopping" class="btn-primary">Explorar Productos</button>
      </div>

      <div v-else class="cart-content">
        <!-- List of items -->
        <div class="cart-items-list">
          <div v-for="item in cartStore.items" :key="item.id" class="cart-item-card">
            <div class="item-img-container">
              <img :src="item.img ? (item.img.startsWith('/') ? item.img : '/' + item.img) : (item.gallery?.[0]?.src || '/img/logo.jpg')" :alt="item.nom" />
            </div>

            
            <div class="item-details">
              <h4 class="item-name">{{ item.nom }}</h4>
              <p class="item-price">{{ item.preu }}€ / unidad</p>
            </div>

            <div class="item-quantity-controls">
              <button @click="cartStore.updateQuantity(item.id, item.quantity - 1)" class="qty-btn" :disabled="item.quantity <= 1">−</button>
              <span class="qty-number">{{ item.quantity }}</span>
              <button @click="cartStore.updateQuantity(item.id, item.quantity + 1)" class="qty-btn">+</button>
            </div>

            <div class="item-subtotal">
              {{ (item.preu * item.quantity).toFixed(2) }}€
            </div>

            <button @click="cartStore.removeItem(item.id)" class="btn-remove" title="Eliminar artículo">
              🗑️
            </button>
          </div>
        </div>

        <!-- Summary Section -->
        <div class="cart-summary-panel">
          <div class="summary-card">
            <h3>Resumen del pedido</h3>
            <div class="summary-row">
              <span>Artículos ({{ cartStore.totalItems }}):</span>
              <span>{{ cartStore.totalPrice }}€</span>
            </div>
            <div class="summary-row">
              <span>Envío:</span>
              <span class="free-text">Gratis</span>
            </div>
            <div class="summary-total">
              <span>Total:</span>
              <span class="total-amount">{{ cartStore.totalPrice }}€</span>
            </div>
            
            <div class="summary-actions">
              <button @click="goToCheckout" class="btn-checkout">PROCEDER AL PAGO</button>
              <button @click="continueShopping" class="btn-secondary">Seguir comprando</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cart-page {
  padding: 60px 20px;
  background-color: var(--bg-color);
  min-height: calc(100vh - 160px);
}

.cart-container {
  max-width: 1200px;
  margin: 0 auto;
}

.cart-title {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--text-color);
  margin-bottom: 40px;
  text-align: center;
}

/* Empty State */
.empty-cart-state {
  background: var(--card-bg);
  padding: 60px;
  border-radius: 24px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.btn-primary {
  background: #6bc7b5;
  color: white;
  border: none;
  padding: 14px 28px;
  border-radius: 12px;
  font-weight: 700;
  margin-top: 20px;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-primary:hover {
  background: #5ab3a2;
  transform: translateY(-2px);
}

/* Cart Content */
.cart-content {
  display: grid;
  grid-template-columns: 1fr 350px;
  gap: 40px;
}

.cart-items-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.cart-item-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.03);
  transition: transform 0.3s;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .cart-item-card {
  border-color: rgba(255,255,255,0.05);
}

.cart-item-card:hover {
  transform: translateX(5px);
}

.item-img-container {
  width: 100px;
  height: 100px;
  background: #f9f9f9;
  border-radius: 12px;
  overflow: hidden;
  flex-shrink: 0;
}

.item-img-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.item-details {
  flex: 1;
}

.item-name {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--text-color);
  margin: 0 0 5px 0;
}

.item-price {
  color: var(--text-color);
  opacity: 0.6;
  font-size: 0.9rem;
}

.item-quantity-controls {
  display: flex;
  align-items: center;
  gap: 15px;
  background: rgba(107, 199, 181, 0.1);
  padding: 8px 15px;
  border-radius: 12px;
}

.qty-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  font-weight: 700;
  color: #6bc7b5;
  cursor: pointer;
  width: 24px;
  height: 24px;
}

.qty-btn:disabled {
  color: #ccc;
  cursor: not-allowed;
}

.qty-number {
  font-weight: 700;
  color: var(--text-color);
  min-width: 20px;
  text-align: center;
}

.item-subtotal {
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--text-color);
  min-width: 100px;
  text-align: right;
}

.btn-remove {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 10px;
  border-radius: 10px;
  transition: background 0.2s;
}

.btn-remove:hover {
  background: #fee2e2;
}

/* Summary Panel */
.summary-card {
  background: var(--card-bg);
  padding: 30px;
  border-radius: 24px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.05);
  position: sticky;
  top: 100px;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .summary-card {
  border-color: rgba(255,255,255,0.05);
}

.summary-card h3 {
  margin-top: 0;
  font-size: 1.4rem;
  margin-bottom: 25px;
  color: var(--text-color);
}

.summary-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  color: #666;
  font-weight: 600;
}

.free-text {
  color: #6bc7b5;
  font-weight: 700;
}

.summary-total {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
  padding-top: 20px;
  border-top: 2px solid rgba(107, 199, 181, 0.2);
  font-size: 1.3rem;
  font-weight: 800;
  color: var(--text-color);
  margin-bottom: 30px;
}

.summary-actions {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.btn-checkout {
  background: #6bc7b5;
  color: white;
  border: none;
  padding: 18px;
  border-radius: 14px;
  font-weight: 800;
  font-size: 1.1rem;
  cursor: pointer;
  box-shadow: 0 10px 20px rgba(107, 199, 181, 0.3);
  transition: all 0.3s;
}

.btn-checkout:hover {
  background: #5ab3a2;
  transform: translateY(-2px);
  box-shadow: 0 12px 25px rgba(107, 199, 181, 0.4);
}

.btn-secondary {
  background: rgba(107, 199, 181, 0.1);
  color: var(--text-color);
  border: none;
  padding: 14px;
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-secondary:hover {
  background: rgba(107, 199, 181, 0.2);
}

@media (max-width: 991px) {
  .cart-content {
    grid-template-columns: 1fr;
  }
  .summary-card {
    position: static;
  }
}

@media (max-width: 600px) {
  .cart-item-card {
    flex-direction: column;
    text-align: center;
  }
  .item-subtotal {
    text-align: center;
  }
}
</style>
