<template>
  <div class="product-management bg-gradient-to-br from-neutral-50 to-neutral-100 min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4">
      <h1 class="text-4xl font-extrabold text-neutral-800 tracking-tight mb-8">Administración de Productos</h1>

      <!-- Pestañas de Navegación -->
      <div class="flex border-b border-neutral-200 mb-8">
        <button 
          @click="activeTab = 'manual'"
          :class="[
            'py-3 px-6 font-medium text-sm focus:outline-none transition-colors duration-200',
            activeTab === 'manual' 
              ? 'border-b-2 border-primary-600 text-primary-600' 
              : 'text-neutral-500 hover:text-neutral-700'
          ]">
          Crear Manualmente
        </button>
        <button 
          @click="activeTab = 'import'"
          :class="[
            'py-3 px-6 font-medium text-sm focus:outline-none transition-colors duration-200',
            activeTab === 'import' 
              ? 'border-b-2 border-primary-600 text-primary-600' 
              : 'text-neutral-500 hover:text-neutral-700'
          ]">
          Importar desde Archivo
        </button>
      </div>

      <!-- Tab Crear Manualmente -->
      <div v-if="activeTab === 'manual'" class="bg-white p-8 rounded-2xl shadow-sm border border-neutral-100">
        <h2 class="text-2xl font-bold text-neutral-800 mb-6">{{ isEditing ? 'Editar Producto' : 'Añadir Nuevo Producto' }}</h2>
        
        <form @submit.prevent="submitManualProduct" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-neutral-700 mb-2">Nombre del Producto</label>
              <input v-model="form.nom" type="text" required
                     class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                     placeholder="Ej: Cámara de Seguridad X1">
            </div>

            <div>
              <label class="block text-sm font-semibold text-neutral-700 mb-2">Precio (€)</label>
              <input v-model.number="form.preu" type="number" step="0.01" min="0" required
                     class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                     placeholder="0.00">
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-neutral-700 mb-2">Categoría</label>
            <select v-model="form.categoria_id" required
                    class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none bg-white">
              <option value="" disabled>Selecciona una categoría</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.nom }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-neutral-700 mb-2">Descripción</label>
            <textarea v-model="form.descripcio" rows="4" required
                      class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none resize-none"
                      placeholder="Describe el producto o servicio..."></textarea>
          </div>

          <div>
             <label class="block text-sm font-semibold text-neutral-700 mb-2">URL de Imagen (Opcional)</label>
              <input v-model="form.img" type="text"
                     class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                     placeholder="https://ejemplo.com/imagen.jpg">
          </div>

          <div v-if="isEditing" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-neutral-700 mb-2">SKU (Identificador Único)</label>
              <input v-model="form.sku" type="text" required
                     class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                     placeholder="EJ: CAM-1234">
            </div>

            <div>
              <label class="block text-sm font-semibold text-neutral-700 mb-2">Stock Disponible</label>
              <input v-model.number="form.estoc" type="number" min="0" required
                     class="w-full px-4 py-3 rounded-xl border border-neutral-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                     placeholder="0">
            </div>
          </div>

          <div class="pt-4 flex items-center justify-between">
            <span v-if="successMsg" class="text-green-600 font-medium">{{ successMsg }}</span>
            <span v-else-if="errorMsg" class="text-red-500 font-medium">{{ errorMsg }}</span>
            <span v-else></span>

            <button type="submit" :disabled="isLoading"
                    class="bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
              <span v-if="isLoading" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              {{ isEditing ? 'Actualizar Producto' : 'Crear Producto' }}
            </button>
            <button v-if="isEditing" type="button" @click="cancelEdit"
                    class="ml-4 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 font-semibold py-3 px-8 rounded-xl transition-all">
              Cancelar
            </button>
          </div>
        </form>
      </div>

      <!-- Tab Importar Archivo -->
      <div v-if="activeTab === 'import'" class="bg-white p-8 rounded-2xl shadow-sm border border-neutral-100">
        <h2 class="text-2xl font-bold text-neutral-800 mb-2">Importación Masiva</h2>
        <p class="text-neutral-500 mb-8">Sube un archivo JSON o Excel (.xlsx) con los datos de los productos.</p>
        
        <form @submit.prevent="submitImport">
          <div class="border-2 border-dashed border-neutral-300 rounded-2xl p-10 text-center hover:bg-neutral-50 transition-colors cursor-pointer mb-6"
               @click="$refs.fileInput.click()">
            <input type="file" ref="fileInput" @change="handleFileChange" accept=".json,.xlsx,.csv,.ods" class="hidden">
            
            <div v-if="!selectedFile" class="flex flex-col items-center">
              <svg class="w-12 h-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
              </svg>
              <p class="text-lg font-medium text-neutral-700">Haz clic para seleccionar un archivo</p>
              <p class="text-sm text-neutral-500 mt-1">Formatos soportados: JSON, XLSX, CSV, ODS</p>
            </div>
            
            <div v-else class="flex flex-col items-center">
              <svg class="w-12 h-12 text-primary-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-lg font-bold text-neutral-800">{{ selectedFile.name }}</p>
              <p class="text-sm text-neutral-500 mt-1">{{ (selectedFile.size / 1024).toFixed(2) }} KB</p>
            </div>
          </div>

          <div class="flex items-center justify-between">
            <span v-if="importSuccessMsg" class="text-green-600 font-medium">{{ importSuccessMsg }}</span>
            <span v-else-if="importErrorMsg" class="text-red-500 font-medium">{{ importErrorMsg }}</span>
            <span v-else></span>

            <button type="submit" :disabled="!selectedFile || isImporting"
                    class="bg-neutral-800 hover:bg-neutral-900 text-white font-semibold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
              <span v-if="isImporting" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
              {{ isImporting ? 'Importando...' : 'Subir Archivo' }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api'

const route = useRoute()
const router = useRouter()

// Estados generales
const activeTab = ref('manual')
const categories = ref([])

// Estados del formulario manual
const form = ref({
  nom: '',
  preu: null,
  descripcio: '',
  categoria_id: '',
  img: ''
})
const isLoading = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

// Estados de edición
const isEditing = computed(() => !!route.query.edit)
const editingId = computed(() => route.query.edit)

// Estados de la importación por archivo
const fileInput = ref(null)
const selectedFile = ref(null)
const isImporting = ref(false)
const importSuccessMsg = ref('')
const importErrorMsg = ref('')

onMounted(async () => {
  try {
    // Cargar las categorías disponibles al montar el componente
    const res = await api.get('/categorias')
    categories.value = res.data
    
    // Si estamos en modo edición (hay un ID en la URL), cargamos los datos del producto
    if (isEditing.value) {
      fetchProductForEdit(editingId.value)
    }
  } catch (err) {
    console.error('Error al cargar las categorías:', err)
  }
})

/**
 * Obtener los datos de un producto específico para editarlo
 */
const fetchProductForEdit = async (id) => {
  try {
    const res = await api.get(`/admin/products/${id}`)
    const p = res.data
    form.value = {
      nom: p.nom,
      preu: p.preu,
      descripcio: p.descripcio,
      categoria_id: p.categoria_id,
      img: p.img || '',
      sku: p.sku,
      estoc: p.estoc
    }
  } catch (err) {
    errorMsg.value = 'Error al cargar el producto para editar.'
  }
}

/**
 * Cancelar la edición y limpiar el formulario
 */
const cancelEdit = () => {
  router.push({ name: 'AdminProducts' })
  form.value = { nom: '', preu: null, descripcio: '', categoria_id: '', img: '' }
}

/**
 * Enviar el formulario de creación o edición manual
 */
const submitManualProduct = async () => {
  isLoading.value = true
  successMsg.value = ''
  errorMsg.value = ''
  
  try {
    if (isEditing.value) {
      // Si editamos, usamos PUT
      await api.put(`/admin/products/${editingId.value}`, form.value)
      successMsg.value = 'Producto actualizado exitosamente.'
    } else {
      // Si es nuevo, usamos POST
      await api.post('/admin/products', form.value)
      successMsg.value = 'Producto creado exitosamente.'
      // Resetear formulario solo al crear
      form.value = { nom: '', preu: null, descripcio: '', categoria_id: '', img: '' }
    }
    
    setTimeout(() => successMsg.value = '', 5000)
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Error al procesar el producto.'
  } finally {
    isLoading.value = false
  }
}

/**
 * Gestionar el cambio de archivo en el input
 */
const handleFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    selectedFile.value = file
  }
}

/**
 * Enviar el archivo para la importación masiva
 */
const submitImport = async () => {
  if (!selectedFile.value) return

  isImporting.value = true
  importSuccessMsg.value = ''
  importErrorMsg.value = ''

  const formData = new FormData()
  formData.append('file', selectedFile.value)

  try {
    await api.post('/admin/products/import', formData, {
      headers: {
         'Content-Type': 'multipart/form-data'
      }
    })
    importSuccessMsg.value = 'Productos importados correctamente.'
    selectedFile.value = null
    if(fileInput.value) fileInput.value.value = '' // resetear el input HTML
    
    setTimeout(() => importSuccessMsg.value = '', 5000)
  } catch (err) {
    importErrorMsg.value = err.response?.data?.message || 'Error al importar archivo.'
  } finally {
    isImporting.value = false
  }
}
</script>

<style scoped>
</style>
