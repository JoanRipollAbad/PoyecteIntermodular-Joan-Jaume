<script setup>
import { ref } from 'vue'
import api from '../api'

const fileInput = ref(null)

const uploadFile = async () => {
  const file = fileInput.value.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('fitxer', file)

  try {
    await api.post('/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    alert('Archivo subido correctamente')
  } catch (error) {
    console.error(error)
    alert('Error al subir el archivo')
  }
}
</script>

<template>
  <div class="container">
    <div class="upload-container">
      <h1>Pujar Fitxer Excel</h1>
      <p class="text-muted">Formats acceptats: .xlsx, .xls, .csv</p>

      <form @submit.prevent="uploadFile" class="upload-form">
        <div class="form-group">
          <label for="fitxer" style="font-weight: bold;">Tria un fitxer del teu ordinador:</label>
          <input ref="fileInput" type="file" id="fitxer" accept=".xlsx,.xls,.csv" required />
        </div>

        <br>
        <button type="submit" class="btn-upload">Pujar Fitxer</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.upload-container {
  max-width: 600px;
  margin: 50px auto;
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  text-align: center;
}
.upload-form input[type="file"] {
  margin: 20px 0;
  padding: 10px;
  border: 2px dashed #7ed9c7;
  width: 100%;
  border-radius: 10px;
}
.btn-upload {
  background-color: #000;
  color: white;
  padding: 10px 25px;
  border: none;
  border-radius: 30px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s;
}
.btn-upload:hover {
  background-color: #333;
}
</style>
