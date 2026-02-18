<template>
  <div class="product-list">
    <h1>Productes</h1>

    <div v-if="loading">Carregant productes...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div class="products-grid" v-if="products.length">
      <div v-for="product in products" :key="product.id" class="product-card">
        <h3>{{ product.nom }}</h3>
        <div class="product-category">
          Categoria: {{ product.categoria?.nom || product.categoria?.tipo || 'Sense categoria' }}
        </div>
        <p>{{ product.descripcio }}</p>
        <p>
          <strong>{{ product.preu }}€</strong>
        </p>
        <p>Estoc: {{ product.estoc }}</p>
        <router-link :to="`/products/${product.id}`">Veure detalls</router-link>
      </div>
    </div>

    <div v-else>
      <p>No hi ha productes disponibles.</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../api'

export default {
  setup() {
    const products = ref([])
    const loading = ref(true)
    const error = ref('')

    const fetchProducts = async () => {
      try {
        const response = await api.get('/products')
        products.value = response.data
      } catch (err) {
        error.value = 'Error al carregar els productes'
        console.error(err)
      } finally {
        loading.value = false
      }
    }

    onMounted(fetchProducts)

    return {
      products,
      loading,
      error,
    }
  },
}
</script>

<style scoped>
.product-list {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.product-card {
  border: 1px solid #ddd;
  padding: 15px;
  border-radius: 8px;
  text-align: center;
}

.product-card h3 {
  margin-bottom: 10px;
}

.product-card p {
  margin: 5px 0;
}

a {
  display: inline-block;
  margin-top: 10px;
  color: #007bff;
  text-decoration: none;
}
</style>
