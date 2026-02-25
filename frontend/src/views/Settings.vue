<script setup>
import { useSettingsStore } from '../stores/settings'

const settingsStore = useSettingsStore()

const languages = [
  { code: 'es', name: 'Español' },
  { code: 'en', name: 'English' }
]
</script>

<template>
  <div class="settings-container py-5 px-3 px-md-5">
    <div class="header-section text-center mb-5">
      <h1 class="titulo-destacado">{{ settingsStore.t('settings') }}</h1>
    </div>

    <div class="settings-card">
      <div class="card-glass-overlay"></div>
      <div class="card-content">
        <!-- Dark Mode Toggle -->
        <div class="setting-item">
          <div class="setting-info">
            <div class="icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
              </svg>
            </div>
            <span>{{ settingsStore.t('darkMode') }}</span>
          </div>
          <div class="switch" @click="settingsStore.toggleDarkMode" :class="{ active: settingsStore.darkMode }">
            <div class="toggle"></div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- Language Selector -->
        <div class="setting-item">
          <div class="setting-info">
            <div class="icon-circle">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="2" y1="12" x2="22" y2="12"></line>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
              </svg>
            </div>
            <span>{{ settingsStore.t('language') }}</span>
          </div>
          <div class="language-selector">
            <button 
              v-for="lang in languages" 
              :key="lang.code"
              @click="settingsStore.setLanguage(lang.code)"
              :class="{ active: settingsStore.language === lang.code }"
              class="lang-btn"
            >
              {{ lang.name }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.settings-container {
  min-height: calc(100vh - 160px);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.settings-card {
  position: relative;
  background: rgba(255, 255, 255, 0.7);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  padding: 40px;
  border-radius: 24px;
  width: 100%;
  max-width: 600px;
  border: 1px solid rgba(255, 255, 255, 0.5);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
  overflow: hidden;
  animation: fadeInUp 0.6s ease-out forwards;
}

.dark-mode .settings-card {
  background: rgba(30, 30, 30, 0.7);
  border-color: rgba(255, 255, 255, 0.1);
}

.setting-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
}

.setting-info {
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: 1.2rem;
  font-weight: 600;
}

.icon-circle {
  width: 45px;
  height: 45px;
  background: #6bc7b5;
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.divider {
  height: 1px;
  background: rgba(0, 0, 0, 0.05);
  margin: 10px 0;
}

.dark-mode .divider {
  background: rgba(255, 255, 255, 0.1);
}

/* Switch Styles */
.switch {
  width: 60px;
  height: 30px;
  background: #ccc;
  border-radius: 30px;
  position: relative;
  cursor: pointer;
  transition: background 0.3s;
}

.switch.active {
  background: #6bc7b5;
}

.toggle {
  width: 26px;
  height: 26px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: transform 0.3s;
}

.switch.active .toggle {
  transform: translateX(30px);
}

/* Language Selector Styles */
.language-selector {
  display: flex;
  gap: 10px;
}

.lang-btn {
  padding: 8px 16px;
  border-radius: 20px;
  border: 1px solid #6bc7b5;
  background: transparent;
  color: #6bc7b5;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s;
}

.lang-btn.active {
  background: #6bc7b5;
  color: white;
}

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

.titulo-destacado::after {
  content: "";
  display: block;
  width: 80px;
  height: 5px;
  background: linear-gradient(90deg, #6bc7b5, #bcd9d6);
  margin: 25px auto;
  border-radius: 10px;
}
</style>
