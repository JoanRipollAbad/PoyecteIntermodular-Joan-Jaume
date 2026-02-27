<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../api'

const route = useRoute()
const products = ref([])
const loading = ref(true)
const error = ref('')

const searchQuery = computed(() => route.query.search || '')

const filteredProducts = computed(() => {
  if (!searchQuery.value) return products.value
  const q = searchQuery.value.toString().toLowerCase()
  return products.value.filter(p => 
    p.nom.toLowerCase().includes(q) || 
    (p.descripcio && p.descripcio.toLowerCase().includes(q)) ||
    (p.categoria && p.categoria.nom.toLowerCase().includes(q))
  )
})

const fetchProducts = async () => {
  loading.value = true
  error.value = ''
  try {
    const response = await api.get('/products')
    products.value = response.data
  } catch (err) {
    error.value = 'Error al cargar el catálogo de productos.'
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchProducts)
</script>

<template>
  <div class="product-list-view min-h-screen bg-stone-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header -->
      <div class="mb-8 md:mb-12 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-neutral-800 mb-4">Nuestro Catálogo</h1>
        <div class="header-line mx-auto"></div>
        <p class="text-neutral-500 mt-6 max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
          Descubre todas nuestras soluciones de seguridad. Desde cámaras de alta definición hasta sistemas de control de acceso inteligente.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex flex-col items-center justify-center py-20">
        <div class="w-12 h-12 border-4 border-[#bcd9d6] border-t-[#7ed9c7] rounded-full animate-spin mb-4"></div>
        <p class="text-neutral-400 font-medium">Cargando catálogo completo...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 text-red-700 p-6 rounded-2xl text-center">
        <p class="font-bold">{{ error }}</p>
        <button @click="fetchProducts" class="mt-4 text-sm underline font-bold">Reintentar</button>
      </div>

      <!-- Search Info -->
      <div v-if="searchQuery" class="mb-8 animate-fade-in">
        <p class="text-neutral-500">
          Resultados para: <span class="font-bold text-[#6bc7b5]">"{{ searchQuery }}"</span> 
          ({{ filteredProducts.length }} productos encontrados)
        </p>
        <router-link to="/products" class="text-sm text-[#6bc7b5] font-bold hover:underline mt-2 inline-block">
          Ver todo el catálogo
        </router-link>
      </div>

      <!-- Products Grid -->
      <div v-else-if="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <div v-for="product in filteredProducts" :key="product.id" 
             class="product-card group bg-white rounded-[30px] p-6 border border-neutral-100 shadow-sm hover:shadow-xl hover:shadow-[#7ed9c7]/10 hover:border-[#7ed9c7]/30 transition-all duration-500 transform hover:-translate-y-2">
          
          <div class="img-wrapper aspect-square rounded-[24px] overflow-hidden mb-6 bg-stone-50 relative">
            <img :src="product.img || '/img/logo.jpg'" 
                 :alt="product.nom"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            
            <div class="absolute top-4 right-4" v-if="product.categoria">
              <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-[10px] font-bold tracking-widest text-[#6bc7b5] border border-neutral-100 shadow-sm">
                {{ product.categoria.nom.toUpperCase() }}
              </span>
            </div>
          </div>

          <h3 class="text-xl font-bold text-neutral-800 mb-2 truncate group-hover:text-[#6bc7b5] transition-colors">{{ product.nom }}</h3>
          <p class="text-neutral-500 text-sm mb-6 line-clamp-2 leading-relaxed h-10">{{ product.descripcio }}</p>

          <div class="flex items-center justify-between mt-auto pt-4 border-t border-neutral-50">
            <div class="flex flex-col">
              <span class="text-xs text-neutral-400 font-bold uppercase tracking-wider">Precio</span>
              <span class="text-2xl font-black text-neutral-800">{{ product.preu }}<span class="text-sm ml-1 font-bold">€</span></span>
            </div>
            
            <router-link :to="`/product/${product.id}`" 
                         class="w-12 h-12 bg-neutral-800 hover:bg-[#7ed9c7] text-white rounded-2xl flex items-center justify-center shadow-lg shadow-neutral-200 transition-all transform hover:rotate-12 active:scale-95 group/btn">
              <svg class="w-6 h-6 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-20 bg-white rounded-[40px] border border-dashed border-neutral-200">
        <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-neutral-800 mb-2">
          {{ searchQuery ? 'No hay resultados' : 'Sin productos' }}
        </h3>
        <p class="text-neutral-500">
          {{ searchQuery ? `No hemos encontrado nada para "${searchQuery}". Intenta con otros términos.` : 'Estamos actualizando nuestro inventario. Vuelve pronto.' }}
        </p>
        <router-link v-if="searchQuery" to="/products" class="btn-primary mt-6 inline-block">
          Ver todo el catálogo
        </router-link>
      </div>

    </div>
  </div>
</template>

<style scoped>
.header-line {
  width: 60px;
  height: 5px;
  background-color: #7ed9c7;
  border-radius: 3px;
}

/* Smooth entry animation */
.grid > div {
  animation: slideUp 0.6s ease-out forwards;
  opacity: 0;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.grid > div:nth-child(n+1) { animation-delay: calc(0.1s * var(--item-index, 1)); }
</style>
