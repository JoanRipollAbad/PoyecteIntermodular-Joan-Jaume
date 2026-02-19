<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api'

const route = useRoute()
const router = useRouter()
const product = ref(null)
const loading = ref(true)

// Fallback data for testing/demo as requested
const mockProduct = {
  id: 1,
  nom: 'Cámara de Seguridad Interior Ultra HD',
  preu: 129.99,
  descripcio: 'La Cámara de Seguridad Interior Ultra HD de JJ-Security ofrece una vigilancia inigualable para tu hogar o negocio. Con una resolución 4K impresionante, cada detalle se captura con la máxima claridad. Equipada con sensores inteligentes y visión nocturna, garantiza tranquilidad total las 24 horas.',
  caracteristicas: [
    'Resolución 4K Ultra HD',
    'Visión Nocturna a Color',
    'Detección IA Avanzada',
    'Resistente al Agua IP66'
  ],
  gallery: [
    { type: 'video', src: '/videos/videoCamara.mp4' },
    { type: 'image', src: '/img/marcaAgua/camaras/camaras.jpg' },
    { type: 'image', src: '/img/camara1.jpg' }
  ]
}

const activeSlide = ref(0)
let carouselInterval

const startCarouselTimer = () => {
  stopCarouselTimer()
  carouselInterval = setInterval(() => {
    nextSlide()
  }, 5000)
}

const stopCarouselTimer = () => {
  if (carouselInterval) {
    clearInterval(carouselInterval)
    carouselInterval = null
  }
}

const resetCarouselTimer = () => {
  stopCarouselTimer()
  startCarouselTimer()
}

const nextSlide = () => {
  if (product.value) {
    activeSlide.value = (activeSlide.value + 1) % product.value.gallery.length
  }
}

const prevSlide = () => {
  if (product.value) {
    activeSlide.value = (activeSlide.value === 0) ? product.value.gallery.length - 1 : activeSlide.value - 1
    resetCarouselTimer()
  }
}

const setSlide = (index) => {
  activeSlide.value = index
  resetCarouselTimer()
}

const manualNextSlide = () => {
  nextSlide()
  resetCarouselTimer()
}

onMounted(async () => {
  try {
    if (route.params.id) {
      const response = await api.get(`/products/${route.params.id}`)
      product.value = {
        ...response.data,
        caracteristicas: mockProduct.caracteristicas,
        gallery: mockProduct.gallery
      }
    } else {
      product.value = mockProduct
    }
  } catch (error) {
    console.error('Error fetching product:', error)
    product.value = mockProduct // Fallback on error
  } finally {
    loading.value = false
  }

  startCarouselTimer()
})

onUnmounted(() => {
  stopCarouselTimer()
})
</script>

<template>
  <div class="product-view-container">
    
    <!-- MIGAS DE PAN (BREADCRUMBS) -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
      <ol class="breadcrumb custom-breadcrumb">
        <li class="breadcrumb-item"><RouterLink to="/">Inicio</RouterLink></li>
        <li class="divider">/</li>
        <li class="breadcrumb-item"><a href="#">Cámaras</a></li>
        <li class="divider">/</li>
        <li class="breadcrumb-item active" aria-current="page">{{ product?.nom }}</li>
      </ol>
    </nav>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Cargando los mejores detalles para ti...</p>
    </div>

    <section v-else class="product-card-main">
      <div class="card-inner">
        <!-- PRODUCT TITLE (Centered at top) -->
        <h2 class="product-title-centered">{{ product.nom }}</h2>

        <div class="product-content-grid">
          <!-- LEFT: MEDIA GALLERY (Carousel) -->
          <div class="media-column">
            <div class="media-container shadow-sm">
              <div class="media-inner ratio ratio-16x9">
                <TransitionGroup name="fade">
                  <div 
                    v-for="(item, index) in product.gallery" 
                    :key="index"
                    v-show="activeSlide === index" 
                    class="gallery-item"
                  >
                    <video v-if="item.type === 'video'" autoplay muted loop class="w-100 h-100 object-cover">
                      <source :src="item.src" type="video/mp4" />
                    </video>
                    <img v-else :src="item.src" class="w-100 h-100 object-cover" alt="Vista del producto">
                  </div>
                </TransitionGroup>

                </div>

                <!-- Gallery Controls -->
                <div class="gallery-nav" v-if="product.gallery.length > 1">
                  <button class="nav-btn prev" @click="prevSlide" aria-label="Anterior"></button>
                  <button class="nav-btn next" @click="manualNextSlide" aria-label="Siguiente"></button>
                </div>

                <!-- Carousel Indicators (Dots) -->
                <div class="carousel-indicators" v-if="product.gallery.length > 1">
                  <button 
                    v-for="(_, index) in product.gallery" 
                    :key="index"
                    class="indicator-dot"
                    :class="{ 'active': activeSlide === index }"
                    @click="setSlide(index)"
                    :aria-label="'Ver imagen ' + (index + 1)"
                  ></button>
                </div>
            </div>
          </div>

          <!-- RIGHT: PURCHASE DETAILS -->
          <div class="details-column">
            <div class="price-section">
              <span class="price-value">{{ product.preu }}€</span>
            </div>
            
            <div class="rating-section">
              <span class="stars">★★★★★</span>
              <span class="reviews-count">(154 Reseñas)</span>
            </div>

            <ul class="features-list">
              <li v-for="feature in product.caracteristicas" :key="feature" class="feature-item">
                <span class="check-box">
                  <span class="check-mark">✓</span>
                </span>
                {{ feature }}
              </li>
            </ul>

            <div class="action-buttons">
              <button class="btn-buy-now" @click="router.push('/checkout')">
                COMPRAR YA
              </button>

              <button class="btn-add-cart">
                Añadir al carrito
              </button>

              <button class="btn-wishlist">
                <span class="heart-icon">❤️</span> Agregar a Deseados
              </button>
            </div>
          </div>
        </div>

        <!-- DESCRIPTION SECTION -->
        <div class="description-section">
          <h4 class="description-title">Descripción del Producto</h4>
          <p class="description-text">
            {{ product.descripcio }}
          </p>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.product-view-container {
  background-color: var(--bg-color);
  min-height: calc(100vh - 80px);
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Breadcrumbs */
.breadcrumb-nav {
  width: 100%;
  max-width: 1100px;
  margin-bottom: 20px;
}

.custom-breadcrumb {
  display: flex;
  list-style: none;
  padding: 0;
  gap: 12px;
  align-items: center;
  font-size: 0.95rem;
}

.breadcrumb-item a {
  color: #6bc7b5;
  text-decoration: none;
  font-weight: 500;
}

.breadcrumb-item.active {
  color: #999;
}

.divider {
  color: #ccc;
}

/* Main Card */
.product-card-main {
  width: 100%;
  max-width: 1100px;
  background-color: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  padding: 40px;
  margin-bottom: 40px;
}

.product-title-centered {
  text-align: center;
  font-size: 2.2rem;
  font-weight: 700;
  color: #444;
  margin-bottom: 40px;
}

.product-content-grid {
  display: grid;
  grid-template-columns: 1.2fr 0.8fr;
  gap: 40px;
  align-items: flex-start;
}

/* Media Column */
.media-container {
  border-radius: 12px;
  overflow: hidden;
  background: #f9f9f9;
  position: relative;
}

.gallery-item {
  width: 100%;
  height: 100%;
}

.gallery-nav {
  position: absolute;
  top: 50%;
  left: 0;
  width: 100%;
  box-sizing: border-box; /* Crucial to prevent padding from expanding the 100% width */
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
  padding: 0 50px;
  pointer-events: none;
  z-index: 50; /* Ensure it's above everything */
}

.nav-btn {
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.9); /* White background for visibility */
  border: none;
  border-radius: 50%;
  cursor: pointer;
  pointer-events: auto;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  z-index: 51;
}

.nav-btn:hover {
  background: white;
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.2);
}

.nav-btn::before {
  content: '';
  width: 14px;
  height: 14px;
  border-top: 3.5px solid #333; /* Dark arrow */
  border-right: 3.5px solid #333;
  display: inline-block;
}

.prev::before { transform: rotate(-135deg); margin-left: 6px; }
.next::before { transform: rotate(45deg); margin-right: 6px; }

/* Carousel Indicators */
.carousel-indicators {
  position: absolute;
  bottom: 15px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  z-index: 10;
}

.indicator-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: rgba(255,255,255,0.5);
  border: 2px solid rgba(0,0,0,0.1);
  padding: 0;
  cursor: pointer;
  transition: all 0.2s;
}

.indicator-dot.active {
  background-color: var(--primary-color);
  transform: scale(1.2);
  border-color: white;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Details Column */
.details-column {
  display: flex;
  flex-direction: column;
}

.price-section {
  text-align: center; 
  margin-bottom: 5px;
}

.price-value {
  font-size: 4rem;
  font-weight: 800;
  color: #6bc7b5; 
}

.rating-section {
  display: flex;
  justify-content: center; 
  align-items: center;
  gap: 10px;
  margin-bottom: 30px;
}

.stars {
  color: #ffcc00;
  font-size: 1.1rem;
}

.reviews-count {
  color: #999;
  font-size: 0.95rem;
}

.features-list {
  list-style: none;
  padding: 0;
  margin-bottom: 30px;
}

.feature-item {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 12px;
  font-size: 1.15rem;
  font-weight: 500;
  color: #333;
}

.check-box {
  width: 22px;
  height: 22px;
  background-color: #6bc7b5;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: bold;
  font-size: 1rem;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.btn-buy-now {
  background-color: #78dcc8; 
  color: white;
  border: none;
  border-radius: 50px;
  padding: 16px;
  font-weight: 700;
  font-size: 1.25rem;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(120, 220, 200, 0.4);
  transition: transform 0.2s;
}

.btn-buy-now:hover { transform: translateY(-2px); }

.btn-add-cart {
  background-color: #90aeb0; 
  color: white;
  border: none;
  border-radius: 50px;
  padding: 14px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
}

.btn-wishlist {
  background: white;
  color: #555;
  border: 1px solid #ddd;
  border-radius: 50px;
  padding: 12px;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Description Section */
.description-section {
  margin-top: 40px;
  border-top: 1px solid #eee;
  padding-top: 30px;
}

.description-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #444;
  margin-bottom: 15px;
}

.description-text {
  color: #666;
  line-height: 1.8;
  font-size: 1.1rem;
}

/* Loading State */
.loading-state {
  padding: 100px;
  text-align: center;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #eee;
  border-top-color: #6bc7b5;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 20px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.ratio { position: relative; width: 100%; }
.ratio::before { content: ""; display: block; padding-top: 56.25%; }
.ratio > * { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
.object-cover { object-fit: cover; }

@media (max-width: 991px) {
  .product-content-grid {
    grid-template-columns: 1fr;
    gap: 30px;
  }
}
</style>
