<template>
  <div class="product-management">
    <div class="max-w-3xl mx-auto px-4 md:px-0">
      <div class="header-section text-center mb-10">
        <h1 class="titulo-destacado">Administración de Productos</h1>
        <p class="subtitle mt-3">Gestiona el catálogo de seguridad profesional de JJ-Security</p>
      </div>

      <!-- Pestañas de Navegación -->
      <div class="tabs-container mb-10">
        <button 
          @click="activeTab = 'manual'"
          :class="['tab-btn', activeTab === 'manual' ? 'active' : '']">
          Crear Manual
        </button>
        <button 
          @click="activeTab = 'import'"
          :class="['tab-btn', activeTab === 'import' ? 'active' : '']">
          Importación
        </button>
      </div>

      <!-- Tab Crear Manualmente -->
      <div v-if="activeTab === 'manual'" class="glass-card animate-fade-in">
        <div class="card-header-flex mb-8">
          <div class="icon-circle">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          </div>
          <h2 class="section-title">{{ isEditing ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
        </div>
        
        <form @submit.prevent="submitManualProduct" class="admin-form">
          <div class="form-grid">
            <div class="input-group">
              <label class="label-style">Nombre del Producto</label>
              <input v-model="form.nom" type="text" required class="input-style" placeholder="Ej: Cámara Pro X1">
            </div>

            <div class="input-group">
              <label class="label-style">Precio (€)</label>
              <input v-model.number="form.preu" type="number" step="0.01" min="0" required class="input-style" placeholder="0.00">
            </div>

            <div class="input-group">
              <label class="label-style">Categoría</label>
              <select v-model="form.categoria_id" required class="input-style select-style">
                <option value="" disabled>Selecciona una categoría</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.nom }}</option>
              </select>
            </div>

            <div class="input-group">
              <label class="label-style">URL de Imagen</label>
              <input v-model="form.img" type="text" class="input-style" placeholder="https://ejemplo.com/imagen.jpg">
            </div>
          </div>

          <div class="input-group full-width mt-4">
            <label class="label-style">Descripción</label>
            <textarea v-model="form.descripcio" rows="3" required class="input-style textarea-style" placeholder="Describe las características..."></textarea>
          </div>

          <div v-if="isEditing" class="form-grid edit-fields mt-6 pt-6 border-t border-black/5">
            <div class="input-group">
              <label class="label-style corporate-text">SKU (Identificador)</label>
              <input v-model="form.sku" type="text" required class="input-style highlight-border" placeholder="EJ: CAM-1234">
            </div>

            <div class="input-group">
              <label class="label-style corporate-text">Stock Disponible</label>
              <input v-model.number="form.estoc" type="number" min="0" required class="input-style highlight-border" placeholder="0">
            </div>
          </div>

          <div class="form-footer mt-10">
            <div class="status-box">
              <transition name="fade">
                <span v-if="successMsg" class="status-tag success">{{ successMsg }}</span>
                <span v-else-if="errorMsg" class="status-tag error">{{ errorMsg }}</span>
              </transition>
            </div>

            <div class="action-btns">
              <button v-if="isEditing" type="button" @click="cancelEdit" class="btn-cancel mr-4">Cancelar</button>
              <button type="submit" :disabled="isLoading" class="btn-submit">
                <span v-if="isLoading" class="spinner-small"></span>
                {{ isEditing ? 'Actualizar' : 'Guardar Producto' }}
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Tab Importar Archivo -->
      <div v-if="activeTab === 'import'" class="glass-card animate-fade-in">
        <div class="text-center mb-8">
          <h2 class="section-title">Importación Masiva</h2>
          <p class="subtitle-small">Sube múltiples productos mediante archivos JSON o Excel.</p>
        </div>
        
        <form @submit.prevent="submitImport">
          <div class="upload-area" @click="$refs.fileInput.click()">
            <input type="file" ref="fileInput" @change="handleFileChange" accept=".json,.xlsx,.csv,.ods" class="hidden">
            
            <div v-if="!selectedFile" class="upload-placeholder">
              <div class="upload-icon">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
              </div>
              <p class="upload-title">Suelta tu archivo aquí</p>
              <p class="upload-subtitle">o haz clic para explorar</p>
              <div class="format-tags mt-3">
                <span>JSON</span><span>XLSX</span><span>CSV</span>
              </div>
            </div>
            
            <div v-else class="upload-selected">
              <div class="file-icon-badge">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <p class="file-name">{{ selectedFile.name }}</p>
              <button type="button" @click.stop="selectedFile = null" class="btn-remove-file">Quitar archivo</button>
            </div>
          </div>

          <div class="form-footer mt-8">
            <div class="status-box">
              <transition name="fade">
                <span v-if="importSuccessMsg" class="status-tag success">{{ importSuccessMsg }}</span>
                <span v-else-if="importErrorMsg" class="status-tag error">{{ importErrorMsg }}</span>
              </transition>
            </div>

            <button type="submit" :disabled="!selectedFile || isImporting" class="btn-submit dark-mode ml-auto">
              <span v-if="isImporting" class="spinner-small"></span>
              {{ isImporting ? 'Procesando...' : 'Iniciar Importación' }}
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

const activeTab = ref('manual')
const categories = ref([])

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

const isEditing = computed(() => !!route.query.edit)
const editingId = computed(() => route.query.edit)

const fileInput = ref(null)
const selectedFile = ref(null)
const isImporting = ref(false)
const importSuccessMsg = ref('')
const importErrorMsg = ref('')

onMounted(async () => {
  try {
    const res = await api.get('/categorias')
    categories.value = res.data
    if (isEditing.value) {
      fetchProductForEdit(editingId.value)
    }
  } catch (err) {
    console.error('Error al cargar las categorías:', err)
  }
})

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

const cancelEdit = () => {
  router.push({ name: 'AdminProducts' })
  form.value = { nom: '', preu: null, descripcio: '', categoria_id: '', img: '' }
}

const submitManualProduct = async () => {
  isLoading.value = true
  successMsg.value = ''
  errorMsg.value = ''
  try {
    if (isEditing.value) {
      await api.put(`/admin/products/${editingId.value}`, form.value)
      successMsg.value = 'Producto actualizado exitosamente.'
    } else {
      await api.post('/admin/products', form.value)
      successMsg.value = 'Producto creado exitosamente.'
      form.value = { nom: '', preu: null, descripcio: '', categoria_id: '', img: '' }
    }
    setTimeout(() => successMsg.value = '', 5000)
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Error al procesar el producto.'
  } finally {
    isLoading.value = false
  }
}

const handleFileChange = (e) => {
  const file = e.target.files[0]
  if (file) selectedFile.value = file
}

const submitImport = async () => {
  if (!selectedFile.value) return
  isImporting.value = true
  importSuccessMsg.value = ''
  importErrorMsg.value = ''
  const formData = new FormData()
  formData.append('file', selectedFile.value)
  try {
    await api.post('/admin/products/import', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    importSuccessMsg.value = 'Productos importados correctamente.'
    selectedFile.value = null
    if(fileInput.value) fileInput.value.value = ''
    setTimeout(() => importSuccessMsg.value = '', 5000)
  } catch (err) {
    importErrorMsg.value = err.response?.data?.message || 'Error al importar archivo.'
  } finally {
    isImporting.value = false
  }
}
</script>

<style scoped>
.product-management {
  background: linear-gradient(135deg, #f4f7f6 0%, #e8f1ef 100%);
  min-height: calc(100vh - 80px);
  padding: 40px 0 80px;
}

.header-section {
  animation: fadeInDown 0.8s ease-out;
}

.titulo-destacado {
  font-weight: 800;
  font-size: 3rem;
  color: #1a1a1a;
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
  color: #666;
  font-size: 1.2rem;
  text-align: center;
}

.tabs-container {
  display: flex;
  justify-content: center;
  gap: 10px;
  background: rgba(255, 255, 255, 0.5);
  backdrop-filter: blur(8px);
  padding: 6px;
  border-radius: 100px;
  max-width: 320px;
  margin: 0 auto 40px;
  border: 1px solid rgba(255, 255, 255, 0.5);
}

.tab-btn {
  flex: 1;
  padding: 10px 20px;
  border-radius: 100px;
  font-weight: 700;
  font-size: 0.9rem;
  color: #666;
  transition: all 0.3s ease;
  border: none;
  background: transparent;
  cursor: pointer;
}

.tab-btn.active {
  background: #6bc7b5;
  color: white;
  box-shadow: 0 4px 15px rgba(107, 199, 181, 0.3);
}

.glass-card {
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  border-radius: 30px;
  padding: 50px 60px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
}

.card-header-flex {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 30px;
}

.icon-circle {
  width: 44px;
  height: 44px;
  background: #bcd9d6;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #1a1a1a;
}

.section-title {
  font-size: 1.6rem;
  font-weight: 800;
  color: #1a1a1a;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.label-style {
  font-size: 0.85rem;
  font-weight: 700;
  color: #555;
  padding-left: 5px;
}

.input-style {
  padding: 14px 20px;
  border-radius: 15px;
  border: 1px solid rgba(0, 0, 0, 0.08);
  background: white;
  transition: all 0.3s ease;
  font-size: 0.95rem;
  outline: none;
}

.input-style:focus {
  border-color: #6bc7b5;
  box-shadow: 0 0 0 4px rgba(107, 199, 181, 0.1);
}

.highlight-border {
  border-color: rgba(107, 199, 181, 0.3);
}

.corporate-text {
  color: #6bc7b5;
}

.form-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-top: 40px; /* Added spacing above the button */
}

.status-tag {
  padding: 8px 16px;
  border-radius: 100px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-tag.success { background: #e8f5f2; color: #2d7a6a; }
.status-tag.error { background: #fef2f2; color: #991b1b; }

.btn-submit {
  background: #6bc7b5;
  color: white;
  padding: 14px 32px;
  border-radius: 100px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-submit:hover:not(:disabled) {
  background: #5ab3a2;
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(107, 199, 181, 0.3);
}

.btn-submit.dark-mode { background: #1a1a1a; }
.btn-submit.dark-mode:hover { background: #000; }

.upload-area {
  border: 2px dashed #bcd9d6;
  border-radius: 24px;
  padding: 40px;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.upload-area:hover {
  background: rgba(107, 199, 181, 0.05);
  border-color: #6bc7b5;
}

.upload-icon {
  color: #6bc7b5;
  margin-bottom: 15px;
}

.upload-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1a1a1a;
}

.upload-subtitle {
  color: #888;
}

.format-tags {
  display: flex;
  justify-content: center;
  gap: 8px;
}

.format-tags span {
  padding: 4px 10px;
  background: white;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 800;
  color: #aaa;
  border: 1px solid #eee;
}

@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes spin { to { transform: rotate(360deg); } }
.spinner-small {
  width: 18px;
  height: 18px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@media (max-width: 640px) {
  .form-grid { grid-template-columns: 1fr; }
  .form-footer { flex-direction: column; align-items: stretch; }
  .titulo-destacado { font-size: 2rem; }
}
</style>
