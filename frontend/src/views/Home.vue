<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'

const router = useRouter()
const searchInput = ref('')
const topRatedProducts = ref([])
const loadingTopRated = ref(true)

const fetchTopRated = async () => {
  try {
    const response = await api.get('/products/top-rated')
    topRatedProducts.value = response.data
  } catch (error) {
    console.error("Error fetching top rated products:", error)
  } finally {
    loadingTopRated.value = false
  }
}

onMounted(() => {
  fetchTopRated()
})

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
    // Si no es una categoría, búsqueda general
    router.push({ name: 'ProductList', query: { search: query } })
  }
}

const servicios = ref([
  { tit: 'Instalación Profesional', img: 'instalacion.jpg', desc: 'Expertos a tu disposición para una configuración impecable.' },
  { tit: 'Monitoreo 24/7', img: 'monitoreo.jpg', desc: 'Equipo de seguridad dedicado supervisando las 24 horas.' },
  { tit: 'Mantenimiento y Soporte', img: 'mantenimiento.jpg', desc: 'Garantía de funcionamiento continuo y asistencia técnica.' }
])

const renderStars = (rating) => {
  const r = parseFloat(rating) || 0
  const stars = []
  for (let i = 1; i <= 5; i++) {
    if (i <= Math.floor(r)) stars.push('full')
    else if (i - 0.5 <= r) stars.push('half')
    else stars.push('empty')
  }
  return stars
}

const productos = ref([
  { tit: 'Llaves Inteligentes', img: 'cerraduras/cerraduraInteligente.jpg' },
  { tit: 'Servicios', img: 'servicios/servicio.jpg' },
  { tit: 'Cámaras', img: 'camaras/camaras.jpg' }
])
</script>

<template>
  <div class="home-content-wrapper">
    <!-- Global Search Bar -->
    <div class="search-container animate-fade-in">
      <div class="search-wrapper shadow-premium">
        <svg class="search-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="11" cy="11" r="8"></circle>
          <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input 
          v-model="searchInput" 
          type="text" 
          placeholder="¿Qué estás buscando hoy? (Cámaras, alarmas...)" 
          @keyup.enter="handleSearch"
          class="search-input"
        >
        <button @click="handleSearch" class="btn-search-go">BUSCAR</button>
      </div>
    </div>

    <!-- PRODUCTOS MEJOR VALORADOS -->
    <div class="seccion-blanca">
      <div class="header-with-badge">
        <h2 class="titulo-destacado">Top 3 Mejor Valorados</h2>
        <span class="badge-premium">PREMIUM CHOICE</span>
      </div>
      
      <div v-if="loadingTopRated" class="loading-state">
        <div class="spinner"></div>
        <p>Cargando recomendaciones...</p>
      </div>

      <div v-else-if="topRatedProducts.length > 0" class="grid-productos">
        <div v-for="p in topRatedProducts" :key="p.id" class="tarjeta-producto" @click="router.push(`/product/${p.id}`)">
          <div class="rating-badge">
            <template v-for="(star, i) in renderStars(p.comments_avg_puntuacio)" :key="i">
              <span class="star" :class="star">★</span>
            </template>
            <span class="rating-num">({{ parseFloat(p.comments_avg_puntuacio || 0).toFixed(1) }})</span>
          </div>
          
          <div class="img-producto-wrapper">
            <img :src="p.img ? (p.img.startsWith('/') ? p.img : '/' + p.img) : '/img/placeholder.jpg'" :alt="p.nom">
          </div>
          
          <h3>{{ p.nom }}</h3>
          <p class="categoria-tag">{{ p.categoria?.nom }}</p>
          <p class="precio-destacado">{{ p.preu }}€</p>
          
          <button class="btn-comprar">VER DETALLE</button>
        </div>
      </div>

      <div v-else class="empty-state">
        <p>No hay valoraciones suficientes aún.</p>
      </div>
    </div>

    <!-- PRODUCTOS DESTACADOS (CATEGORÍAS) -->
    <div class="seccion-blanca">
      <h2 class="titulo-destacado">Nuestras Categorías</h2>
      <div class="grid-productos">
        <div v-for="(p, index) in productos" :key="index" class="tarjeta-producto" tabindex="0">
          <h3>{{ p.tit }}</h3>
          <div class="img-producto-wrapper">
            <img :src="`/img/marcaAgua/${p.img}`" :alt="p.tit">
          </div>
          <p class="deseos">Explora nuestro catálogo</p>
          <router-link to="/product">
            <button class="btn-comprar">VER TODOS</button>
          </router-link>
        </div>
      </div>
    </div>

    <div class="seccion-blanca">
      <h2 class="titulo-destacado">Nuestros Servicios de Vigilancia</h2>
      <div class="grid-servicios">
        <div v-for="(s, index) in servicios" :key="index" class="tarjeta-servicio" tabindex="0">
          <div class="contenedor-logica-circular">
            <div class="anillo-azul-fondo"></div>
            <img :src="`/img/marcaAgua/${s.img}`" :alt="s.tit">
          </div>
          <h3>{{ s.tit }}</h3>
          <p>{{ s.desc }}</p>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
.home-content-wrapper {
  background-color: var(--bg-color);
  width: 100%;
  display: flex;
  flex-direction: column;
  padding: 40px 0;
  align-items: center;
}

.seccion-blanca {
  background-color: var(--card-bg);
  border-radius: 20px;
  padding: 60px 40px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  width: 90%;
  max-width: 1200px;
  margin-bottom: 50px;
}

.dark-mode .seccion-blanca {
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.titulo-destacado {
  text-align: center; 
  font-weight: 700; 
  font-size: 2rem; 
  margin-bottom: 40px; 
  position: relative;
  color: var(--text-color);
}

.titulo-destacado::after {
  content: ""; 
  position: absolute; 
  bottom: -20px; 
  left: 50%; 
  transform: translateX(-50%);
  width: 60px; 
  height: 5px; 
  background-color: #7ed9c7; 
  border-radius: 3px;
}

/* NUEVOS ESTILOS TOP RATED */
.header-with-badge {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 50px;
}

.badge-premium {
  background: #f4b400;
  color: #fff;
  font-size: 0.7rem;
  font-weight: 900;
  padding: 4px 12px;
  border-radius: 50px;
  letter-spacing: 2px;
  margin-top: 15px; /* Cambiado de -50px a 15px para dar margen */
  margin-bottom: 10px;
  box-shadow: 0 5px 15px rgba(244, 180, 0, 0.3);
}

.rating-badge {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2px;
  margin-bottom: 15px;
}

.star {
  font-size: 1.1rem;
}

.star.full { color: #f4b400; }
.star.half { position: relative; color: #ccc; }
.star.half::after {
  content: '★';
  position: absolute;
  left: 0;
  width: 50%;
  overflow: hidden;
  color: #f4b400;
}
.star.empty { color: #ccc; }

.rating-num {
  font-size: 0.85rem;
  color: #888;
  font-weight: 600;
  margin-left: 5px;
}

.categoria-tag {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #6bc7b5;
  font-weight: 800;
  margin-bottom: 5px;
}

.precio-destacado {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text-color);
  margin-bottom: 20px;
}

.loading-state, .empty-state {
  text-align: center;
  padding: 40px;
  color: #888;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid rgba(126, 217, 199, 0.1);
  border-top-color: #7ed9c7;
  border-radius: 50%;
  margin: 0 auto 15px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* PRODUCTOS */
.grid-productos { 
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
}

.tarjeta-producto {
  flex: 1 1 300px;
  max-width: 400px; /* Controlamos el ancho máximo para que no crezca demasiado si está sola */
  background: var(--card-bg); 
  border: 1px solid rgba(0,0,0,0.05); 
  border-radius: 25px; 
  padding: 30px;
  text-align: center; 
  transition: all 0.3s ease; 
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(0,0,0,0.02);
}

.dark-mode .tarjeta-producto {
  border-color: rgba(255,255,255,0.05);
}

.tarjeta-producto:hover {
  border-color: #7ed9c7; 
  box-shadow: 0 15px 30px rgba(126, 217, 199, 0.15); 
  transform: translateY(-8px);
}

.img-producto-wrapper { 
  width: 100%; 
  height: 200px; 
  border-radius: 20px; 
  margin-bottom: 25px; 
  overflow: hidden; 
}

.img-producto-wrapper img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
  transition: transform 0.5s ease; 
}

.tarjeta-producto:hover img { 
  transform: scale(1.1); 
}

.deseos {
  margin-bottom: 25px;
  color: #888;
  font-size: 0.9rem;
}

.btn-comprar {
  background-color: #7ed9c7; 
  color: white; 
  border: none; 
  padding: 12px 50px; 
  border-radius: 30px; 
  font-weight: bold; 
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-comprar:hover {
  background-color: #6bc7b5;
}

/* SERVICIOS */
.grid-servicios { 
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 40px;
}

.tarjeta-servicio {
  flex: 1 1 320px;
  max-width: 380px;
  background: var(--card-bg); 
  border-radius: 25px; 
  padding: 45px 35px; 
  text-align: center; 
  transition: all 0.4s ease; 
  cursor: pointer;
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  border: 1px solid rgba(0,0,0,0.05); /* Target-style border */
}

.dark-mode .tarjeta-servicio {
  border-color: rgba(255,255,255,0.05);
}

.tarjeta-servicio:hover {
  border-color: #7ed9c7;
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(126, 217, 199, 0.1);
}

.contenedor-logica-circular { 
  width: 130px; 
  height: 130px; 
  margin: 0 auto 35px; 
  position: relative; 
  display: flex; 
  align-items: center; 
  justify-content: center; 
}

.anillo-azul-fondo {
  position: absolute; 
  width: 120px; 
  height: 120px; 
  border-radius: 50%; 
  background-color: var(--card-bg);
  box-shadow: 0 0 0 6px #e1f5fe, 0 0 0 12px #b3e5fc; 
  z-index: 1; 
  transition: all 0.4s ease;
}

.dark-mode .anillo-azul-fondo {
  box-shadow: 0 0 0 6px #1e292d, 0 0 0 12px #2c3e50;
}

.tarjeta-servicio:hover .anillo-azul-fondo {
  box-shadow: 0 0 0 8px #bcd9d6, 0 0 0 15px #e0f2f1;
}

.contenedor-logica-circular img {
  width: 120px; 
  height: 120px; 
  border-radius: 50%; 
  object-fit: cover;
  position: relative; 
  z-index: 2; 
  border: 3px solid var(--card-bg); 
  transition: all 0.4s ease;
}

.tarjeta-servicio:hover img { 
  transform: scale(1.1); 
}

.tarjeta-servicio h3 {
  margin-bottom: 15px;
  font-size: 1.4rem;
  color: var(--text-color);
}

.tarjeta-servicio p {
  color: var(--text-color);
  opacity: 0.8;
  line-height: 1.6;
}

/* SEARCH BAR STYLES */
.search-container {
  width: 90%;
  max-width: 800px;
  margin-bottom: 40px;
  z-index: 10;
}

.search-wrapper {
  background: white;
  border-radius: 100px;
  padding: 8px 8px 8px 30px;
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
  box-shadow: 0 15px 35px rgba(107, 199, 181, 0.15);
  transform: translateY(-2px);
}

.search-icon {
  color: #6bc7b5;
  margin-right: 15px;
  flex-shrink: 0;
}

.search-input {
  flex-grow: 1;
  background: transparent;
  border: none;
  font-size: 1.1rem;
  color: var(--text-color);
  outline: none;
  padding: 10px 0;
}

.search-input::placeholder {
  color: #aaa;
}

.btn-search-go {
  background: #1a1a1a;
  color: white;
  border: none;
  border-radius: 100px;
  padding: 12px 35px;
  font-weight: 800;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-search-go:hover {
  background: #000;
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.animate-fade-in {
  animation: fadeInDown 0.8s ease-out;
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 600px) {
  .search-wrapper {
    padding: 5px 5px 5px 20px;
  }
  .search-input {
    font-size: 0.9rem;
  }
  .btn-search-go {
    padding: 10px 20px;
    font-size: 0.8rem;
  }
}
</style>
