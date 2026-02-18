<script setup>
import { ref, onMounted } from 'vue'
import api from '../api'

const categories = ref([])
const loading = ref(true)

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
  <div class="filters-container py-5 px-md-5">
    <h1 class="text-center mb-5 titulo-destacado">Explorar Categorías</h1>

    <div v-if="loading" class="text-center py-5">
      <p>Cargando categorías...</p>
    </div>

    <div v-else class="categories-grid">
      <div v-for="category in categories" :key="category.id" class="category-card" tabindex="0">
        <div class="card-icon">
          <img src="/img/filtros.jpg" alt="Categoría">
        </div>
        <h3>{{ category.nom }}</h3>
        <p>Ver todos los productos en {{ category.nom }}</p>
        <button class="btn-explore">VER PRODUCTOS</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.filters-container {
  background-color: #f4f7f6;
  min-height: calc(100vh - 90px);
}

.titulo-destacado {
  font-weight: 700;
  position: relative;
  display: inline-block;
  left: 50%;
  transform: translateX(-50%);
  margin-bottom: 80px;
}

.titulo-destacado::after {
  content: "";
  position: absolute;
  bottom: -15px;
  left: 50%;
  transform: translateX(-50%);
  width: 50px;
  height: 4px;
  background-color: #7ed9c7;
  border-radius: 2px;
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.category-card {
  background: white;
  padding: 40px;
  border-radius: 20px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
  border: 1px solid #f0f0f0;
  cursor: pointer;
}

.category-card:hover {
  transform: translateY(-8px);
  border-color: #7ed9c7;
  box-shadow: 0 15px 35px rgba(126, 217, 199, 0.1);
}

.card-icon {
  width: 80px;
  height: 80px;
  background-color: #e0f2f1;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 25px;
}

.card-icon img {
  width: 40px;
  height: 40px;
}

.category-card h3 {
  margin-bottom: 10px;
  color: #333;
}

.category-card p {
  color: #888;
  margin-bottom: 25px;
}

.btn-explore {
  background-color: #7ed9c7;
  color: white;
  border: none;
  padding: 10px 30px;
  border-radius: 30px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}

.btn-explore:hover {
  background-color: #6bc7b5;
}
</style>
