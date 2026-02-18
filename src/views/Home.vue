<script setup>
import { ref } from 'vue'

const productos = ref([
  { tit: 'Llaves Inteligentes', img: 'cerraduras/cerraduraInteligente.jpg' },
  { tit: 'Servicios', img: 'servicios/servicio.jpg' },
  { tit: 'Cámaras', img: 'camaras/camaras.jpg' }
])

const servicios = ref([
  { tit: 'Instalación Profesional', img: 'instalacion.jpg', desc: 'Expertos a tu disposición para una configuración impecable.' },
  { tit: 'Monitoreo 24/7', img: 'monitoreo.jpg', desc: 'Equipo de seguridad dedicado supervisando las 24 horas.' },
  { tit: 'Mantenimiento y Soporte', img: 'mantenimiento.jpg', desc: 'Garantía de funcionamiento continuo y asistencia técnica.' }
])
</script>

<template>
  <div class="home-content-wrapper">
    
    <div class="seccion-blanca">
      <h2 class="titulo-destacado">Productos Destacados</h2>
      <div class="grid-productos">
        <div v-for="(p, index) in productos" :key="index" class="tarjeta-producto" tabindex="0">
          <h3>{{ p.tit }}</h3>
          <div class="img-producto-wrapper">
            <img :src="`/img/marcaAgua/${p.img}`" :alt="p.tit">
          </div>
          <p class="deseos">Agregar a lista de Deseados</p>
          <router-link to="/product">
            <button class="btn-comprar">COMPRAR</button>
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
  background-color: #f4f7f6;
  width: 100%;
  display: flex;
  flex-direction: column;
  padding: 40px 0;
  align-items: center;
}

.seccion-blanca {
  background-color: #ffffff;
  border-radius: 20px;
  padding: 60px 40px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.05);
  width: 90%;
  max-width: 1200px;
  margin-bottom: 50px;
}

.titulo-destacado {
  text-align: center; 
  font-weight: 700; 
  font-size: 2rem; 
  margin-bottom: 60px; 
  position: relative;
  color: #222;
}

.titulo-destacado::after {
  content: ""; 
  position: absolute; 
  bottom: -15px; 
  left: 50%; 
  transform: translateX(-50%);
  width: 60px; 
  height: 5px; 
  background-color: #7ed9c7; 
  border-radius: 3px;
}

/* PRODUCTOS */
.grid-productos { 
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
}

.tarjeta-producto {
  background: #ffffff; 
  border: 1px solid #eee; 
  border-radius: 25px; 
  padding: 30px;
  text-align: center; 
  transition: all 0.3s ease; 
  cursor: pointer;
  box-shadow: 0 5px 15px rgba(0,0,0,0.02);
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
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 40px;
}

.tarjeta-servicio {
  background: #ffffff; 
  border-radius: 25px; 
  padding: 45px 35px; 
  text-align: center; 
  transition: all 0.4s ease; 
  cursor: pointer;
  border: 2px solid transparent; /* Border for hover/active state */
  box-shadow: 0 10px 25px rgba(0,0,0,0.05);
  border: 1px solid #f0f0f0; /* Target-style border */
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
  background-color: #fff;
  box-shadow: 0 0 0 6px #e1f5fe, 0 0 0 12px #b3e5fc; 
  z-index: 1; 
  transition: all 0.4s ease;
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
  border: 3px solid #fff; 
  transition: all 0.4s ease;
}

.tarjeta-servicio:hover img { 
  transform: scale(1.1); 
}

.tarjeta-servicio h3 {
  margin-bottom: 15px;
  font-size: 1.4rem;
  color: #333;
}

.tarjeta-servicio p {
  color: #666;
  line-height: 1.6;
}
</style>
