<script setup>
import { useSettingsStore } from '../stores/settings'

const settingsStore = useSettingsStore()
</script>

<template>
  <div class="sustainability-container py-5 px-3 px-md-5">
    <div class="background-decor">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
    </div>

    <div class="content-wrapper">
      <div class="header-section text-center mb-5 animate-down">
        <h1 class="titulo-destacado">{{ settingsStore.t('sustainability_title') || 'Política de Sostenibilidad' }}</h1>
        <p class="subtitle mt-3">{{ settingsStore.t('sustainability_subtitle') || 'Nuestro compromiso con un futuro más seguro y verde.' }}</p>
      </div>

      <section class="policy-grid">
        <div class="policy-card animate-up" style="animation-delay: 0.2s">
          <div class="card-glass-overlay"></div>
          <div class="card-content">
            <div class="card-icon">🌱</div>
            <h3>{{ settingsStore.t('eco_materials_title') || 'Materiales Eco' }}</h3>
            <p>{{ settingsStore.t('eco_materials_desc') || 'Priorizamos el uso de componentes reciclables y procesos de fabricación de bajo impacto ambiental para todos nuestros dispositivos de seguridad.' }}</p>
          </div>
        </div>

        <div class="policy-card animate-up" style="animation-delay: 0.4s">
          <div class="card-glass-overlay"></div>
          <div class="card-content">
            <div class="card-icon">⚡</div>
            <h3>{{ settingsStore.t('energy_efficiency_title') || 'Eficiencia Energética' }}</h3>
            <p>{{ settingsStore.t('energy_efficiency_desc') || 'Nuestros sistemas están diseñados para consumir el mínimo de energía posible, reduciendo la huella de carbono operacional de nuestros clientes.' }}</p>
          </div>
        </div>

        <div class="policy-card animate-up" style="animation-delay: 0.6s">
          <div class="card-glass-overlay"></div>
          <div class="card-content">
            <div class="card-icon">♻️</div>
            <h3>{{ settingsStore.t('waste_reduction_title') || 'Reducción de Residuos' }}</h3>
            <p>{{ settingsStore.t('waste_reduction_desc') || 'Implementamos políticas estrictas de gestión de residuos electrónicos, ofreciendo programas de reciclaje gratuitos para dispositivos antiguos.' }}</p>
          </div>
        </div>
      </section>

      <div class="mission-section mt-5 animate-up" style="animation-delay: 0.8s">
        <div class="mission-glass">
          <h2>{{ settingsStore.t('our_mission_title') || 'Nuestra Misión' }}</h2>
          <p>{{ settingsStore.t('our_mission_desc') || 'En JJ-Security, creemos que la seguridad no debe comprometer el bienestar de nuestro planeta. Trabajamos cada día para integrar soluciones tecnológicas avanzadas con una responsabilidad ambiental inquebrantable.' }}</p>
          <router-link to="/" class="btn-back">
            {{ settingsStore.t('back_to_home') || 'Volver al Inicio' }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.sustainability-container {
  background: var(--bg-color);
  min-height: calc(100vh - 80px);
  position: relative;
  overflow: hidden;
  padding-bottom: 100px;
}

.background-decor {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  pointer-events: none;
}

.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.15;
}

.blob-1 {
  width: 500px;
  height: 500px;
  background: #6bc7b5;
  top: -100px;
  right: -100px;
}

.blob-2 {
  width: 400px;
  height: 400px;
  background: #2e7d32;
  bottom: -50px;
  left: -50px;
}

.content-wrapper {
  position: relative;
  z-index: 2;
  max-width: 1200px;
  margin: 0 auto;
  padding-top: 40px; /* Extra space from the top */
}

.icon-hero {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.titulo-destacado {
  font-weight: 800;
  font-size: 3rem;
  color: var(--text-color);
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
  margin: 25px auto 25px;
  border-radius: 10px;
}

.subtitle {
  color: #666;
  font-size: 1.2rem;
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.policy-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 30px;
  margin-top: 50px;
}

.policy-card {
  position: relative;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  padding: 50px 40px;
  border-radius: 30px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.policy-card:hover {
  transform: translateY(-10px);
  background: rgba(255, 255, 255, 0.9);
}

.card-icon {
  font-size: 3rem;
  margin-bottom: 25px;
}

.policy-card h3 {
  font-size: 1.8rem;
  font-weight: 700;
  color: #2e7d32;
  margin-bottom: 20px;
}

.policy-card p {
  color: #555;
  line-height: 1.7;
  font-size: 1.1rem;
}

.mission-section {
  max-width: 900px;
  margin: 80px auto 0;
}

.mission-glass {
  background: rgba(107, 199, 181, 0.1);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  padding: 60px;
  border-radius: 40px;
  border: 2px solid rgba(107, 199, 181, 0.2);
  text-align: center;
}

.mission-glass h2 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #2c3e50;
  margin-bottom: 30px;
}

.mission-glass p {
  font-size: 1.3rem;
  color: #444;
  line-height: 1.8;
  margin-bottom: 40px;
}

.btn-back {
  display: inline-block;
  background: #2e7d32;
  color: white;
  padding: 18px 40px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 700;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(46, 125, 50, 0.2);
}

.btn-back:hover {
  background: #1b5e20;
  transform: scale(1.05);
  box-shadow: 0 15px 40px rgba(46, 125, 50, 0.3);
}

/* Animations */
.animate-down {
  opacity: 0;
  animation: fadeInDown 0.8s ease-out forwards;
}

.animate-up {
  opacity: 0;
  animation: fadeInUp 0.8s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dark-mode .titulo-destacado {
  color: #e0e0e0;
}

.dark-mode .policy-card {
  background: rgba(30, 30, 30, 0.7);
  border-color: rgba(255, 255, 255, 0.1);
}

.dark-mode .policy-card p, .dark-mode .subtitle {
  color: #aaa;
}

.dark-mode .mission-glass {
  background: rgba(107, 199, 181, 0.05);
}

.dark-mode .mission-glass h2 {
  color: #6bc7b5;
}

.dark-mode .mission-glass p {
  color: #bbb;
}

@media (max-width: 768px) {
  .titulo-destacado {
    font-size: 2.5rem;
  }
  .policy-card {
    padding: 30px;
  }
  .mission-glass {
    padding: 30px;
  }
}
</style>
