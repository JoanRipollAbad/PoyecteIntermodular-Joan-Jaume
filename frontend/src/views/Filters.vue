<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'
import { 
  Camera, 
  Lock, 
  Activity, 
  Bell, 
  ShieldCheck, 
  Package 
} from 'lucide-vue-next'

const categories = ref([])
const loading = ref(true)

// Mapper to relate category names with Lucide icons
const iconMap = {
  'Cámaras': Camera,
  'Cerraduras': Lock,
  'Sensores': Activity,
  'Alarmas': Bell,
  'Servicios': ShieldCheck
}

// Default icon if category not found
const defaultIcon = Package

onMounted(async () => {
  try {
    const response = await api.get('/categorias')
    categories.value = response.data
  } catch (error) {
    console.error('Error fetching categories:', error)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="filters-container py-5 px-3 px-md-5">
    <div class="header-section text-center mb-5">
      <h1 class="titulo-destacado">Explorar Categorías</h1>
      <p class="subtitle mt-3">Descubre soluciones avanzadas de seguridad para cada necesidad</p>
    </div>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p>Cargando categorías de seguridad...</p>
    </div>

    <div v-else class="categories-grid">
      <router-link v-for="(category, index) in categories" 
                   :key="category.id" 
                   :to="`/category/${category.id}`"
                   class="category-card" 
                   :style="{ animationDelay: `${index * 0.1}s` }"
                   tabindex="0">
        <div class="card-glass-overlay"></div>
        <div class="card-content">
          <div class="card-icon">
            <component :is="iconMap[category.nom] || defaultIcon" :size="48" stroke-width="1.5" />
          </div>
          <h3>{{ category.nom }}</h3>
          <p>Soluciones certificadas para {{ category.nom.toLowerCase() }}</p>
          <div class="btn-explore">
            <span>VER PRODUCTOS</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </div>
        </div>
      </router-link>
    </div>
  </div>
</template>

<style scoped>
.filters-container {
  background: var(--bg-color);
  min-height: calc(100vh - 80px);
  position: relative;
  overflow: hidden;
  padding-bottom: 80px; /* Spacing between grid and footer */
}

.dark-mode .filters-container {
  background: linear-gradient(135deg, #121212 0%, #1e292d 100%);
}

.header-section {
  position: relative;
  z-index: 2;
  animation: fadeInDown 0.8s ease-out;
}

.titulo-destacado {
  font-weight: 800;
  font-size: 3rem;
  color: var(--text-color);
  margin: 0;
  letter-spacing: -1px;
  text-align: center; /* Enforces centering */
}

.subtitle {
  color: #666;
  font-size: 1.2rem;
  max-width: 600px;
  margin: 0 auto;
}

.dark-mode .subtitle {
  color: #aaa;
}

.titulo-destacado::after {
  content: "";
  display: block;
  width: 80px;
  height: 5px;
  background: linear-gradient(90deg, #6bc7b5, #bcd9d6);
  margin: 25px auto 25px; /* Increased top and bottom margin for more space */
  border-radius: 10px;
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 100px 0;
  color: #6bc7b5;
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

.categories-grid {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 30px;
  max-width: 1400px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.category-card {
  flex: 1 1 320px;
  max-width: 400px;
  position: relative;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  padding: 40px 30px;
  border-radius: 24px;
  text-decoration: none;
  transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  overflow: hidden;
  opacity: 0;
  animation: fadeInUp 0.6s ease-out forwards;
}

.dark-mode .category-card {
  background: rgba(30, 41, 45, 0.4);
  border-color: rgba(255, 255, 255, 0.05);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.category-card:hover {
  transform: translateY(-12px) scale(1.02);
  background: rgba(255, 255, 255, 0.9);
  border-color: #6bc7b5;
  box-shadow: 0 20px 40px rgba(107, 199, 181, 0.15);
}

.dark-mode .category-card:hover {
  background: rgba(40, 55, 60, 0.6);
  border-color: #6bc7b5;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
}

.card-content {
  position: relative;
  z-index: 2;
  text-align: center;
}

.card-icon {
  width: 100px;
  height: 100px;
  margin: 0 auto 25px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  color: #6bc7b5;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  transition: all 0.4s ease;
}

.dark-mode .card-icon {
  background: #232d31;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
}

.category-card:hover .card-icon {
  transform: rotate(5deg) scale(1.1);
  background: #6bc7b5;
  color: white;
}

.category-card h3 {
  font-size: 1.8rem;
  font-weight: 700;
  color: #222;
  margin: 0 0 12px;
}

.dark-mode .category-card h3 {
  color: #fff;
}

.category-card p {
  font-size: 1rem;
  color: #777;
  margin: 0 0 30px;
  line-height: 1.6;
}

.dark-mode .category-card p {
  color: #bbb;
}

.btn-explore {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: #6bc7b5; /* Corporate color */
  color: white;
  padding: 12px 24px;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
}

.category-card:hover .btn-explore {
  background: #5ab3a2; /* Slightly darker for hover */
  padding-right: 30px;
}

.btn-explore svg {
  transition: transform 0.3s ease;
}

.category-card:hover .btn-explore svg {
  transform: translateX(5px);
}

/* Animations */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@media (max-width: 992px) {
  .titulo-destacado {
    font-size: 2.5rem;
  }
  .category-card {
    flex: 1 1 45%; /* Two columns roughly on tablet */
  }
}

@media (max-width: 600px) {
  .titulo-destacado {
    font-size: 2rem;
  }
  .category-card {
    flex: 1 1 100%; /* Single column on mobile */
  }
  .subtitle {
    font-size: 1rem;
  }
}
</style>
