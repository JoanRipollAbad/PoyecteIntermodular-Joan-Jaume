<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '../stores/cart'
import api from '../api'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()

const searchInput = ref('')

const handleSearch = () => {
  const query = searchInput.value.trim().toLowerCase()
  if (!query) {
    router.push({ name: 'ProductList' })
    return
  }

  // Mapeo selectivo a categorías para términos comunes
  if (query.includes('camara') || query.includes('cámara')) {
    router.push({ name: 'CategoryProducts', params: { id: 1 } })
  } else if (query.includes('cerradura') || query.includes('llave')) {
    router.push({ name: 'CategoryProducts', params: { id: 2 } })
  } else if (query.includes('sensor')) {
    router.push({ name: 'CategoryProducts', params: { id: 3 } })
  } else if (query.includes('alarma')) {
    router.push({ name: 'CategoryProducts', params: { id: 4 } })
  } else if (query.includes('servicio')) {
    router.push({ name: 'CategoryProducts', params: { id: 5 } })
  } else {
    router.push({ name: 'ProductList', query: { search: query } })
  }
}

/**
 * Añadir producto al carrito desde la rejilla
 */
const addToCart = (product) => {
  cartStore.addItem(product)
  // Opcional: algún feedback visual aquí
}
const products = ref([])
const category = ref(null)
const loading = ref(true)
const error = ref('')

// Map of specific images for cameras
const cameraImages = [
  '/img/camaras/camara1.jpg',
  '/img/camaras/camara2.jpg',
  '/img/camaras/camara3.jpg',
  '/img/camaras/camara4.jpg',
  '/img/camaras/camara5.jpg'
]

// Map of specific images for locks
const lockImages = [
  '/img/cerraduras/cerradura5.jpg',
  '/img/cerraduras/cerradura1.jpg',
  '/img/cerraduras/cerradura3.jpg',
  '/img/cerraduras/cerradura4.jpg',
  '/img/cerraduras/cerradura2.jpg'
]

// Map of specific images for sensors
const sensorImages = [
  '/img/sensores/sensor2.jpg',
  '/img/sensores/sensor1.jpg',
  '/img/sensores/sensor3.jpg',
  '/img/sensores/sensor4.jpg',
  '/img/sensores/sensor5.jpg'
]

// Map of specific images for alarms
const alarmImages = [
  '/img/alarmas/alarma1.jpg',
  '/img/alarmas/alarma4.jpg',
  '/img/alarmas/alarma3.jpg',
  '/img/alarmas/alarma2.jpg',
  '/img/alarmas/alarma5.jpg'
]

// Map of specific images for services
const serviceImages = [
  '/img/servicios/servicio1.jpg',
  '/img/servicios/servicio2.jpg',
  '/img/servicios/servicio3.jpg',
  '/img/servicios/servicio4.jpg',
  '/img/servicios/servicio5.jpg'
]

const fetchData = async () => {
  loading.value = true
  error.value = ''
  try {
    const categoryId = route.params.id
    
    // Fetch products for this category
    const productsRes = await api.get(`/products?categoria_id=${categoryId}`)
    let fetchedProducts = productsRes.data
    
    // Fetch category details to show the name
    const categoriesRes = await api.get('/categorias')
    const currentCategory = categoriesRes.data.find(c => c.id == categoryId)
    category.value = currentCategory
    
    products.value = fetchedProducts.map(p => ({
      ...p,
      img: p.img ? (p.img.startsWith('/') ? p.img : '/' + p.img) : '/img/logo.jpg'
    }))
    
  } catch (err) {
    error.value = 'Error al cargar los productos de esta categoría.'
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchData)

// Re-fetch if the ID changes
watch(() => route.params.id, fetchData)
</script>

<template>
  <div class="category-view py-5 px-3 px-md-5">
    <div class="max-w-7xl mx-auto">
      
      <!-- Actions Header -->
      <div class="actions-header mb-10">
        <!-- Back Link -->
        <router-link to="/filters" class="back-link group">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span>Volver a Categorías</span>
        </router-link>

        <!-- Global Search Bar -->
        <div class="search-container animate-fade-in mx-auto">
          <div class="search-wrapper shadow-premium">
            <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input 
              v-model="searchInput" 
              type="text" 
              placeholder="¿Buscas algo más? (Cámaras, alarmas...)" 
              @keyup.enter="handleSearch"
              class="search-input"
            >
            <button @click="handleSearch" class="btn-search-go">BUSCAR</button>
          </div>
        </div>
      </div>

      <!-- Header Section -->
      <div v-if="category" class="header-section text-center mb-5">
        <h1 class="titulo-destacado">{{ category.nom }}</h1>
        <p class="subtitle mt-3">Explora nuestra selección exclusiva de {{ category.nom.toLowerCase() }}</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Cargando catálogo...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="error-msg">
        <p>{{ error }}</p>
        <button @click="fetchData" class="btn-retry">Reintentar</button>
      </div>

      <!-- Products Grid -->
      <div v-else-if="products.length > 0" class="products-grid">
        <div v-for="(product, index) in products" 
             :key="product.id" 
             class="product-card"
             :style="{ animationDelay: `${index * 0.1}s` }">
          
          <div class="img-wrapper">
            <img :src="product.img || '/img/logo.jpg'" 
                 :alt="product.nom"
                 class="product-img">
            
            <div class="category-tag">
              <span>{{ category.nom.toUpperCase() }}</span>
            </div>
          </div>

          <div class="card-body">
            <h3 class="product-name">{{ product.nom }}</h3>
            <p class="product-desc">{{ product.descripcio }}</p>

            <div class="card-footer">
              <div class="price-box">
                <span class="price-label">Precio</span>
                <span class="price-value">{{ product.preu }}<span class="currency">€</span></span>
              </div>
              
              <div class="card-actions">
                <button @click="addToCart(product)" 
                        class="btn-cart-direct"
                        title="Añadir al carrito"
                        aria-label="Añadir al carrito">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"></path>
                  </svg>
                </button>

                <router-link :to="`/product/${product.id}`" 
                             class="btn-detail"
                             aria-label="Ver detalle del producto">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                  </svg>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <div class="empty-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
        </div>
        <h3>Sin productos disponibles</h3>
        <p>Pronto añadiremos nuevos artículos a esta categoría.</p>
        <router-link to="/filters" class="btn-primary-alt mt-4">
          Ver otras categorías
        </router-link>
      </div>

    </div>
  </div>
</template>

<style scoped>
.category-view {
  background: var(--bg-color);
  min-height: calc(100vh - 80px);
  padding-top: 60px; /* Margen arriba */
  padding-bottom: 80px;
}

.dark-mode .category-view {
  background: linear-gradient(135deg, #121212 0%, #1a1a1a 100%);
}

.actions-header {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

.back-link {
  position: absolute;
  left: 50px;
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  color: white;
  font-weight: 700;
  font-size: 0.75rem;
  transition: all 0.3s ease;
  padding: 6px 14px;
  border-radius: 50px;
  background: #6bc7b5;
  box-shadow: 0 4px 15px rgba(107, 199, 181, 0.2);
  z-index: 5;
}

.back-link svg {
  width: 14px; /* Smaller icon */
  height: 14px;
}

.back-link:hover {
  color: white;
  background: #5ab3a2;
  transform: translateX(5px);
  box-shadow: 0 6px 20px rgba(107, 199, 181, 0.4);
}

.header-section {
  animation: fadeInDown 0.8s ease-out;
}

.titulo-destacado {
  font-weight: 800;
  font-size: 3rem;
  color: var(--text-color);
  margin: 0;
  letter-spacing: -1px;
  text-align: center;
}

.titulo-destacado::after {
  content: "";
  display: block;
  width: 80px;
  height: 5px;
  background: linear-gradient(90deg, #6bc7b5, #bcd9d6);
  margin: 25px auto;
  border-radius: 10px;
}

.subtitle {
  color: var(--text-color);
  opacity: 0.7;
  font-size: 1.2rem;
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.loading-state, .error-msg, .empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 100px 0;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(107, 199, 181, 0.1);
  border-left-color: #6bc7b5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

.products-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  margin-top: 40px;
  padding: 0 40px;
}

.product-card {
  flex: 1 1 300px;
  max-width: 380px;
  background: var(--card-bg);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border-radius: 24px;
  padding: 20px;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  opacity: 0;
  animation: fadeInUp 0.6s ease-out forwards;
}

.dark-mode .product-card {
  border-color: rgba(255, 255, 255, 0.05);
  background: rgba(30, 30, 30, 0.7);
}

.product-card:hover {
  transform: translateY(-12px);
  background: rgba(255, 255, 255, 0.9);
  border-color: #6bc7b5;
  box-shadow: 0 20px 40px rgba(107, 199, 181, 0.15);
}

.dark-mode .product-card:hover {
  background: rgba(45, 45, 45, 0.9);
}

.img-wrapper {
  position: relative;
  aspect-ratio: 1;
  border-radius: 20px;
  overflow: hidden;
  background: rgba(0, 0, 0, 0.02);
  margin-bottom: 20px;
}

.dark-mode .img-wrapper {
  background: rgba(255, 255, 255, 0.05);
}

.product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.product-card:hover .product-img {
  transform: scale(1.1);
}

.category-tag {
  position: absolute;
  top: 15px;
  right: 15px;
  background: var(--card-bg);
  backdrop-filter: blur(4px);
  padding: 4px 12px;
  border-radius: 100px;
  font-size: 10px;
  font-weight: 800;
  color: #6bc7b5;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.product-name {
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 10px;
}

.product-desc {
  color: var(--text-color);
  opacity: 0.7;
  font-size: 0.95rem;
  line-height: 1.5;
  margin-bottom: 20px;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  height: 2.8em;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .card-footer {
  border-top-color: rgba(255, 255, 255, 0.05);
}

.card-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-cart-direct {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background-color: rgba(107, 199, 181, 0.1);
  color: #6bc7b5;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.dark-mode .btn-cart-direct {
  background-color: rgba(255, 255, 255, 0.05);
}

.btn-cart-direct:hover {
  background-color: #6bc7b5;
  color: white;
  transform: scale(1.05);
}

.price-box {
  display: flex;
  flex-direction: column;
}

.price-label {
  font-size: 10px;
  text-transform: uppercase;
  color: #aaa;
  font-weight: 700;
  letter-spacing: 1px;
}

.price-value {
  font-size: 1.6rem;
  font-weight: 900;
  color: var(--text-color);
}

.currency {
  font-size: 0.9rem;
  margin-left: 2px;
}

.btn-detail {
  width: 48px;
  height: 48px;
  background: var(--text-color);
  color: var(--card-bg);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.product-card:hover .btn-detail {
  background: #6bc7b5;
  transform: rotate(10deg);
}

/* Empty State Styles */
.empty-icon {
  width: 80px;
  height: 80px;
  color: #bcd9d6;
  margin-bottom: 20px;
}

.btn-primary-alt {
  background: #6bc7b5;
  color: white;
  padding: 12px 30px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s ease;
}

.btn-primary-alt:hover {
  background: #5ab3a2;
  transform: scale(1.05);
}

/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@media (max-width: 992px) {
  .titulo-destacado { font-size: 2.5rem; }
  .products-grid { padding: 0 20px; }
  .product-card { flex: 1 1 45%; }
}

@media (max-width: 600px) {
  .category-view { padding-top: 40px; }
  .titulo-destacado { font-size: 1.8rem; }
  .back-link { margin-left: 20px; }
  .products-grid { padding: 0 15px; gap: 20px; }
  .product-card { flex: 1 1 100%; max-width: 100%; }
  .subtitle { font-size: 1rem; padding: 0 20px; }
}

/* SEARCH BAR STYLES */
.search-container {
  width: 100%;
  max-width: 700px;
  z-index: 10;
}

.search-wrapper {
  background: white;
  border-radius: 100px;
  padding: 6px 6px 6px 25px;
  display: flex;
  align-items: center;
  border: 1px solid rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.dark-mode .search-wrapper {
  background: #1e292d;
  border-color: rgba(255,255,255,0.1);
}

.search-wrapper:focus-within {
  border-color: #6bc7b5;
  box-shadow: 0 10px 25px rgba(107, 199, 181, 0.1);
  transform: translateY(-2px);
}

.search-icon {
  color: #6bc7b5;
  margin-right: 12px;
  flex-shrink: 0;
}

.search-input {
  flex-grow: 1;
  background: transparent;
  border: none;
  font-size: 1rem;
  color: var(--text-color);
  outline: none;
  padding: 8px 0;
}

.btn-search-go {
  background: #1a1a1a;
  color: white;
  border: none;
  border-radius: 100px;
  padding: 10px 25px;
  font-weight: 800;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-search-go:hover {
  background: #000;
  transform: scale(1.02);
}

.animate-fade-in {
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 600px) {
  .search-wrapper {
    padding: 4px 4px 4px 15px;
  }
  .search-input {
    font-size: 0.9rem;
  }
  .btn-search-go {
    padding: 8px 15px;
    font-size: 0.75rem;
  }
  .search-container {
    margin-bottom: 30px;
    padding: 0 10px;
  }
}

@media (max-width: 900px) {
  .actions-header {
    flex-direction: column;
    gap: 20px;
  }
  .back-link {
    position: static;
    margin-bottom: 10px;
  }
}
</style>
