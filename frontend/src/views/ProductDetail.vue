<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useCartStore } from '../stores/cart'
import api from '../api'

const authStore = useAuthStore()
const cartStore = useCartStore()

const route = useRoute()
const router = useRouter()
const product = ref(null)
const loading = ref(true)
const comments = ref([])
const newComment = ref('')
const rating = ref(5)
const isSubmitting = ref(false)
const commentError = ref('')
const commentSuccess = ref('')

const editingCommentId = ref(null)
const editBuffer = ref('')
const editRating = ref(5)
const isUpdating = ref(false)

/**
 * Cargar comentarios del producto
 */
const fetchComments = async () => {
  try {
    const response = await api.get(`/products/${route.params.id}/comments`)
    comments.value = response.data
  } catch (error) {
    console.error('Error al cargar comentarios:', error)
  }
}

/**
 * Enviar un nuevo comentario
 */
const submitComment = async () => {
  if (!newComment.value.trim()) return
  
  isSubmitting.value = true
  commentError.value = ''
  commentSuccess.value = ''
  
  try {
    const response = await api.post(`/products/${product.value.id}/comments`, {
      text: newComment.value,
      puntuacio: rating.value
    })
    
    // Añadir el nuevo comentario a la lista
    comments.value.unshift(response.data)
    
    // Limpiar formulario
    newComment.value = ''
    rating.value = 5
    commentSuccess.value = '¡Gracias por tu comentario!'
    
    // Ocultar mensaje de éxito después de unos segundos
    setTimeout(() => {
      commentSuccess.value = ''
    }, 5000)
    
  } catch (error) {
    console.error('Error al enviar comentario:', error)
    commentError.value = 'No se pudo enviar el comentario. Inténtalo de nuevo.'
  } finally {
    isSubmitting.value = false
  }
}

/**
 * Eliminar un comentario
 */
const deleteComment = async (id) => {
  if (!confirm('¿Estás seguro de que quieres eliminar este comentario?')) return
  
  try {
    await api.delete(`/comments/${id}`)
    comments.value = comments.value.filter(c => c.id !== id)
  } catch (error) {
    console.error('Error al eliminar comentario:', error)
    alert('No se pudo eliminar el comentario.')
  }
}

/**
 * Activar modo edición
 */
const startEditing = (comment) => {
  editingCommentId.value = comment.id
  editBuffer.value = comment.text
  editRating.value = comment.puntuacio
}

/**
 * Cancelar edición
 */
const cancelEditing = () => {
  editingCommentId.value = null
  editBuffer.value = ''
}

/**
 * Guardar cambios del comentario
 */
const updateComment = async () => {
  if (!editBuffer.value.trim()) return
  
  isUpdating.value = true
  
  try {
    const response = await api.put(`/comments/${editingCommentId.value}`, {
      text: editBuffer.value,
      puntuacio: editRating.value
    })
    
    // Actualizar en el array local
    const index = comments.value.findIndex(c => c.id === editingCommentId.value)
    if (index !== -1) {
      comments.value[index] = response.data
    }
    
    cancelEditing()
  } catch (error) {
    console.error('Error al actualizar comentario:', error)
    alert('No se pudo actualizar el comentario.')
  } finally {
    isUpdating.value = false
  }
}

/**
 * Comprobar si el usuario actual es el autor o admin
 */
const canManage = (comment) => {
  if (!authStore.user) return false
  return comment.user_id === authStore.user.id || authStore.isAdmin
}

const canEdit = (comment) => {
  if (!authStore.user) return false
  return comment.user_id === authStore.user.id
}

/**
 * Calcular la puntuación media
 */
const averageRating = computed(() => {
  if (comments.value.length === 0) return 0
  const sum = comments.value.reduce((acc, c) => acc + (c.puntuacio || 0), 0)
  return (sum / comments.value.length).toFixed(1)
})

/**
 * Total de reseñas reales
 */
const totalReviews = computed(() => comments.value.length)

const addToCart = () => {
  if (product.value) {
    cartStore.addItem(product.value)
    // Optional: show a small toast or redirect to cart
    router.push('/cart')
  }
}

// Datos de prueba por si falla la conexión (como se pidió)
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

/**
 * Iniciar el temporizador del carrusel de imágenes
 */
const startCarouselTimer = () => {
  stopCarouselTimer()
  carouselInterval = setInterval(() => {
    nextSlide()
  }, 5000)
}

/**
 * Detener el temporizador del carrusel
 */
const stopCarouselTimer = () => {
  if (carouselInterval) {
    clearInterval(carouselInterval)
    carouselInterval = null
  }
}

/**
 * Reiniciar el temporizador cuando el usuario interactúa
 */
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
      // Intentar cargar el producto real desde la API
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
    console.error('Error al cargar el producto:', error)
    product.value = mockProduct // Usar datos de prueba si hay error
  } finally {
    loading.value = false
  }

  startCarouselTimer()
  fetchComments()
})

/**
 * Redirigir al panel de administración para editar este producto
 */
const goToEdit = () => {
  router.push({ name: 'AdminProducts', query: { edit: product.value.id } })
}

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
            
            <div class="rating-section" v-if="totalReviews > 0">
              <div class="stars header-stars">
                <span v-for="i in 5" :key="i" class="star-static" :class="{ 'filled': Math.round(averageRating) >= i }">★</span>
              </div>
              <span class="reviews-count">({{ totalReviews }} {{ totalReviews === 1 ? 'Reseña' : 'Reseñas' }}) - {{ averageRating }}/5</span>
            </div>
            <div v-else class="rating-section no-ratings-header">
              <span class="reviews-count">Sin valoraciones todavía</span>
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

              <button class="btn-add-cart" @click="addToCart">
                Añadir al carrito
              </button>

              <button class="btn-wishlist">
                <span class="heart-icon">❤️</span> Agregar a Deseados
              </button>

              <!-- Admin Edit Button -->
              <button v-if="authStore.isAdmin" class="btn-edit-admin" @click="goToEdit">
                <span class="edit-icon">✏️</span> EDITAR PRODUCTO
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

        <!-- COMMENTS SECTION -->
        <div class="comments-section mt-10">
          <h4 class="section-title-premium">Opiniones y Valoraciones</h4>
          
          <!-- submission form for authenticated users -->
          <div v-if="authStore.isAuthenticated" class="comment-form-container mb-10">
            <h5 class="form-subtitle">Deja tu opinión</h5>
            <div class="rating-picker mb-4">
              <span class="mr-3">Puntuación:</span>
              <div class="stars-selector">
                <button v-for="i in 5" :key="i" @click="rating = i" class="star-btn" :class="{ 'active': rating >= i }">
                  ★
                </button>
              </div>
            </div>
            <textarea 
              v-model="newComment" 
              placeholder="Escribe aquí tu experiencia con el producto..."
              class="comment-textarea"
              rows="4"
            ></textarea>
            <div class="form-footer-flex mt-4">
              <transition name="fade">
                <span v-if="commentSuccess" class="msg-success">{{ commentSuccess }}</span>
                <span v-else-if="commentError" class="msg-error">{{ commentError }}</span>
              </transition>
              <button @click="submitComment" :disabled="isSubmitting || !newComment.trim()" class="btn-submit-comment">
                <span v-if="isSubmitting" class="spinner-tiny mr-2"></span>
                Publicar comentario
              </button>
            </div>
          </div>

          <!-- guest CTA -->
          <div v-else class="login-cta-card mb-10">
            <p>Solo los usuarios registrados pueden dejar comentarios.</p>
            <router-link to="/login" class="btn-login-cta">Inicia sesión para opinar</router-link>
          </div>

          <!-- comments list -->
          <div class="comments-list">
            <div v-if="comments.length === 0" class="no-comments">
              Aún no hay opiniones para este producto. ¡Sé el primero en comentar!
            </div>
            <div v-for="comment in comments" :key="comment.id" class="comment-item animate-fade-in">
              <div class="comment-header">
                <div class="user-info">
                  <div class="user-avatar">{{ comment.user?.name.charAt(0).toUpperCase() }}</div>
                  <div>
                    <span class="user-name">{{ comment.user?.name }}</span>
                    <span class="comment-date ml-2">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                  </div>
                </div>
                
                <div class="header-actions-flex">
                  <div class="comment-rating mr-4">
                    <span v-for="i in 5" :key="i" class="star-static" :class="{ 'filled': comment.puntuacio >= i }">★</span>
                  </div>
                  
                  <!-- Manage buttons -->
                  <div v-if="canManage(comment)" class="manage-actions">
                    <button v-if="canEdit(comment) && editingCommentId !== comment.id" @click="startEditing(comment)" class="action-link edit" title="Editar">
                      <span class="icon">✏️</span>
                    </button>
                    <button @click="deleteComment(comment.id)" class="action-link delete" title="Eliminar">
                      <span class="icon">🗑️</span>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Inline edit form -->
              <div v-if="editingCommentId === comment.id" class="inline-edit-form">
                <div class="rating-picker mb-3">
                  <div class="stars-selector">
                    <button v-for="i in 5" :key="i" @click="editRating = i" class="star-btn" :class="{ 'active': editRating >= i }">
                      ★
                    </button>
                  </div>
                </div>
                <textarea v-model="editBuffer" class="comment-textarea edit-mode" rows="3"></textarea>
                <div class="edit-actions mt-3">
                  <button @click="cancelEditing" class="btn-cancel-edit">Cancelar</button>
                  <button @click="updateComment" :disabled="isUpdating || !editBuffer.trim()" class="btn-save-edit">
                    <span v-if="isUpdating" class="spinner-tiny mr-1"></span>
                    Guardar cambios
                  </button>
                </div>
              </div>

              <!-- Comment text -->
              <p v-else class="comment-text">{{ comment.text }}</p>
            </div>
          </div>
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
  background-color: var(--card-bg);
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  padding: 40px;
  margin-bottom: 40px;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .product-card-main {
  border-color: rgba(255,255,255,0.05);
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.product-title-centered {
  text-align: center;
  font-size: 2.2rem;
  font-weight: 700;
  color: var(--text-color);
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
  background: rgba(0,0,0,0.02);
  position: relative;
}

.dark-mode .media-container {
  background: rgba(255,255,255,0.02);
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
  color: var(--text-color);
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
  background: var(--card-bg);
  color: var(--text-color);
  border: 1px solid rgba(0,0,0,0.1);
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

.dark-mode .btn-wishlist {
  border-color: rgba(255,255,255,0.1);
}

.btn-edit-admin {
  margin-top: 20px;
  background-color: #333;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 14px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
  border: 2px solid #333;
}

.btn-edit-admin:hover {
  background-color: white;
  color: #333;
}

.edit-icon {
  font-size: 1.2rem;
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
  color: var(--text-color);
  margin-bottom: 15px;
}

.description-text {
  color: var(--text-color);
  opacity: 0.8;
  line-height: 1.8;
  font-size: 1.1rem;
}

/* Comments Section */
.section-title-premium {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-color);
  margin-bottom: 30px;
  position: relative;
  padding-bottom: 12px;
}

.section-title-premium::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 50px;
  height: 4px;
  background: #6bc7b5;
  border-radius: 2px;
}

.comment-form-container {
  background: rgba(107, 199, 181, 0.05);
  border-radius: 20px;
  padding: 30px;
  border: 1px solid rgba(107, 199, 181, 0.2);
}

.dark-mode .comment-form-container {
  background: rgba(255, 255, 255, 0.02);
  border-color: rgba(255, 255, 255, 0.1);
}

.form-subtitle {
  font-weight: 700;
  font-size: 1.2rem;
  margin-bottom: 15px;
  color: var(--text-color);
}

.rating-picker {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: var(--text-color);
  opacity: 0.9;
}

.stars-selector {
  display: flex;
  gap: 5px;
}

.star-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #ddd;
  cursor: pointer;
  transition: all 0.2s;
  padding: 0;
}

.star-btn.active, .star-btn:hover {
  color: #ffcc00;
  transform: scale(1.1);
}

.comment-textarea {
  width: 100%;
  padding: 15px;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,0.1);
  background: var(--card-bg);
  color: var(--text-color);
  font-family: inherit;
  font-size: 1rem;
  resize: vertical;
  transition: border-color 0.3s;
}

.dark-mode .comment-textarea {
  border-color: rgba(255, 255, 255, 0.1);
}

.comment-textarea:focus {
  outline: none;
  border-color: #6bc7b5;
  box-shadow: 0 0 0 3px rgba(107, 199, 181, 0.1);
}

.form-footer-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.btn-submit-comment {
  background: #6bc7b5;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-submit-comment:hover:not(:disabled) {
  background: #5ab3a2;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(107,199,181,0.3);
}

.btn-submit-comment:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.msg-success { color: #2d7a6a; font-weight: 600; font-size: 0.9rem; }
.msg-error { color: #991b1b; font-weight: 600; font-size: 0.9rem; }

.login-cta-card {
  background: rgba(0,0,0,0.02);
  padding: 30px;
  border-radius: 20px;
  text-align: center;
  border: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .login-cta-card {
  background: rgba(255,255,255,0.03);
  border-color: rgba(255,255,255,0.05);
}

.login-cta-card p {
  font-weight: 600;
  color: var(--text-color);
  opacity: 0.8;
  margin-bottom: 15px;
}

.btn-login-cta {
  display: inline-block;
  background: #1a1a1a;
  color: white;
  padding: 10px 20px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 700;
  transition: all 0.3s;
}

.btn-login-cta:hover {
  background: #000;
  transform: translateY(-2px);
}

/* Comments List */
.no-comments {
  text-align: center;
  padding: 40px;
  color: var(--text-color);
  opacity: 0.6;
  font-style: italic;
  background: rgba(0,0,0,0.02);
  border-radius: 20px;
}

.comment-item {
  padding: 25px;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

.dark-mode .comment-item {
  border-bottom-color: rgba(255,255,255,0.05);
}

.comment-item:last-child { border-bottom: none; }

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.header-actions-flex {
  display: flex;
  align-items: center;
}

.manage-actions {
  display: flex;
  gap: 10px;
}

.action-link {
  background: none;
  border: none;
  cursor: pointer;
  padding: 5px;
  border-radius: 8px;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-link:hover {
  background: #f0f0f0;
}

.action-link.delete:hover {
  background: #fee2e2;
}

.action-link .icon {
  font-size: 1rem;
}

/* Inline Edit Form */
.inline-edit-form {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  border: 1px solid #e2f0ed;
  margin-top: 5px;
}

.comment-textarea.edit-mode {
  background: #fafafa;
  font-size: 0.95rem;
}

.edit-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
}

.btn-cancel-edit {
  background: none;
  border: 1px solid #ddd;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  color: #666;
  transition: all 0.2s;
}

.btn-cancel-edit:hover {
  background: #f5f5f5;
}

.btn-save-edit {
  background: #6bc7b5;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-save-edit:hover:not(:disabled) {
  background: #5ab3a2;
}

.btn-save-edit:disabled {
  background: #ccc;
  cursor: not-allowed;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  background: #e2f0ed;
  color: #6bc7b5;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
}

.user-name {
  font-weight: 700;
  color: var(--text-color);
}

.star-static { color: #ddd; font-size: 1.1rem; }
.star-static.filled { color: #ffcc00; }

.comment-text {
  color: var(--text-color);
  opacity: 0.9;
  line-height: 1.6;
  margin-bottom: 10px;
}

.comment-date {
  font-size: 0.8rem;
  color: #999;
}

.spinner-tiny {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  display: inline-block;
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
