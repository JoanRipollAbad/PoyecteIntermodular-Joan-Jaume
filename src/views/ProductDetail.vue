<template>
  <div class="product-detail">
    <div v-if="loading">Carregant producte...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-if="product" class="product-container">
      <div class="product-header">
        <h1>{{ product.nom }}</h1>
        <p class="product-price">{{ product.preu }}€</p>
      </div>

      <div class="product-description">
        <p>{{ product.descripcio }}</p>
      </div>

      <div class="product-info">
        <p><strong>SKU:</strong> {{ product.sku }}</p>
        <p><strong>Estoc:</strong> {{ product.estoc }}</p>
      </div>

      <div class="comments-section">
        <h2>Comentaris</h2>

        <div v-if="comments.length === 0" class="no-comments">
          No hi ha comentaris encara. Sigues el primer!
        </div>

        <div v-else class="comments-list">
          <div v-for="comment in comments" :key="comment.id" class="comment">
            <div class="comment-header">
              <span class="comment-author">{{ comment.user?.name || 'Usuari anònim' }}</span>
              <span class="comment-date">{{ formatDate(comment.created_at) }}</span>
            </div>
            <p class="comment-text">{{ comment.text }}</p>
            <div v-if="comment.puntuacio" class="comment-rating">
              <span>Valoració: {{ comment.puntuacio }}/5</span>
            </div>
          </div>
        </div>

        <div v-if="authStore.isAuthenticated" class="comment-form">
          <h3>Afegir un comentari</h3>
          <textarea
            v-model="newComment.text"
            placeholder="Escriu el teu comentari..."
            rows="4"
          ></textarea>

          <div class="rating">
            <label>Valoració:</label>
            <select v-model="newComment.puntuacio">
              <option value="">Selecciona una valoració</option>
              <option v-for="i in 5" :key="i" :value="i">{{ i }} estrelles</option>
            </select>
          </div>

          <button @click="submitComment" :disabled="submittingComment">
            {{ submittingComment ? 'Publicant...' : 'Publicar comentari' }}
          </button>

          <p v-if="commentError" class="error">{{ commentError }}</p>
        </div>

        <div v-else class="login-prompt">
          <p>
            Per afegir un comentari, has d'estar autenticat.
            <router-link to="/login">Inicia sessió</router-link>
          </p>
        </div>
      </div>
    </div>

    <div v-else>
      <p>Producte no trobat.</p>
      <router-link to="/">Tornar a la llista de productes</router-link>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import api from '../api'

export default {
  setup() {
    const route = useRoute()
    const router = useRouter()
    const authStore = useAuthStore()

    const product = ref(null)
    const comments = ref([])
    const loading = ref(true)
    const error = ref('')

    const newComment = ref({
      text: '',
      puntuacio: '',
    })

    const submittingComment = ref(false)
    const commentError = ref('')

    const fetchProduct = async () => {
      try {
        loading.value = true
        const response = await api.get(`/products/${route.params.id}`)
        product.value = response.data

        // Carregar comentaris
        await fetchComments()
      } catch (err) {
        error.value = 'Error al carregar el producte'
        console.error(err)
      } finally {
        loading.value = false
      }
    }

    const fetchComments = async () => {
      try {
        const response = await api.get(`/products/${route.params.id}/comments`)
        comments.value = response.data
      } catch (err) {
        console.error('Error carregant comentaris:', err)
      }
    }

    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('ca-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      })
    }

    const submitComment = async () => {
      if (!newComment.value.text.trim()) {
        commentError.value = 'El comentari no pot estar buit'
        return
      }

      if (!newComment.value.puntuacio) {
        commentError.value = 'Selecciona una valoració'
        return
      }

      submittingComment.value = true
      commentError.value = ''

      try {
        await api.post(`/products/${route.params.id}/comments`, {
          text: newComment.value.text,
          puntuacio: newComment.value.puntuacio,
        })

        // Reset form
        newComment.value.text = ''
        newComment.value.puntuacio = ''

        // Recarregar comentaris
        await fetchComments()
      } catch (err) {
        commentError.value = 'Error en publicar el comentari'
        console.error(err)
      } finally {
        submittingComment.value = false
      }
    }

    onMounted(fetchProduct)

    return {
      product,
      comments,
      loading,
      error,
      newComment,
      submittingComment,
      commentError,
      formatDate,
      submitComment,
      authStore,
    }
  },
}
</script>

<style scoped>
.product-detail {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.product-container {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
}

.product-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.product-price {
  font-size: 1.5rem;
  color: #007bff;
  font-weight: bold;
}

.product-description {
  margin-bottom: 20px;
  line-height: 1.6;
}

.product-info {
  background: #f8f9fa;
  padding: 15px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.comments-section {
  margin-top: 30px;
}

.no-comments {
  text-align: center;
  padding: 20px;
  color: #666;
}

.comments-list {
  margin-top: 15px;
}

.comment {
  border-bottom: 1px solid #eee;
  padding: 15px 0;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  color: #666;
}

.comment-author {
  font-weight: bold;
}

.comment-date {
  font-size: 0.8rem;
}

.comment-text {
  line-height: 1.5;
  margin-bottom: 8px;
}

.comment-rating {
  background: #e9f7fe;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 0.9rem;
}

.comment-form {
  margin-top: 20px;
}

.comment-form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 10px;
}

.rating {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.rating label {
  margin-right: 10px;
  font-weight: bold;
}

.rating select {
  padding: 5px 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.login-prompt {
  background: #fff8e6;
  padding: 15px;
  border-radius: 4px;
  text-align: center;
  margin-top: 20px;
}

.error {
  color: red;
  margin-top: 10px;
}
</style>
