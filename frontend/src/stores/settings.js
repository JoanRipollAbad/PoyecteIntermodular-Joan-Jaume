import { defineStore } from 'pinia'

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        darkMode: localStorage.getItem('dark_mode') === 'true',
        language: localStorage.getItem('language') || 'es',
        translations: {
            es: {
                settings: 'Ajustes',
                darkMode: 'Modo Oscuro',
                language: 'Idioma',
                spanish: 'Español',
                english: 'Inglés',
                profile: 'Perfil',
                login: 'Iniciar Sesión',
                logout: 'Cerrar Sesión',
                cart: 'Carrito',
                home: 'Inicio',
                filters: 'Filtros',
                admin: 'Admin',
                help: 'Ayuda',
                contact: 'Contacto',
                sustainability_title: 'Política de Sostenibilidad',
                sustainability_subtitle: 'Nuestro compromiso con un futuro más seguro y verde.',
                eco_materials_title: 'Materiales Eco',
                eco_materials_desc: 'Priorizamos el uso de componentes reciclables y procesos de fabricación de bajo impacto ambiental.',
                energy_efficiency_title: 'Eficiencia Energética',
                energy_efficiency_desc: 'Nuestros sistemas están diseñados para consumir el mínimo de energía posible.',
                waste_reduction_title: 'Reducción de Residuos',
                waste_reduction_desc: 'Implementamos políticas estrictas de gestión de residuos electrónicos.',
                our_mission_title: 'Nuestra Misión',
                our_mission_desc: 'En JJ-Security, creemos que la seguridad no debe comprometer el bienestar de nuestro planeta.',
                back_to_home: 'Volver al Inicio',
            },
            en: {
                settings: 'Settings',
                darkMode: 'Dark Mode',
                language: 'Language',
                spanish: 'Spanish',
                english: 'English',
                profile: 'Profile',
                login: 'Login',
                logout: 'Logout',
                cart: 'Cart',
                home: 'Home',
                filters: 'Filters',
                admin: 'Admin',
                help: 'Help',
                contact: 'Contact',
                sustainability_title: 'Sustainability Policy',
                sustainability_subtitle: 'Our commitment to a safer and greener future.',
                eco_materials_title: 'Eco Materials',
                eco_materials_desc: 'We prioritize the use of recyclable components and low environmental impact manufacturing processes.',
                energy_efficiency_title: 'Energy Efficiency',
                energy_efficiency_desc: 'Our systems are designed to consume the minimum possible energy.',
                waste_reduction_title: 'Waste Reduction',
                waste_reduction_desc: 'We implement strict electronic waste management policies.',
                our_mission_title: 'Our Mission',
                our_mission_desc: 'At JJ-Security, we believe that security should not compromise the well-being of our planet.',
                back_to_home: 'Back to Home',
            }
        }
    }),

    getters: {
        t: (state) => (key) => {
            return state.translations[state.language][key] || key
        }
    },

    actions: {
        toggleDarkMode() {
            this.darkMode = !this.darkMode
            localStorage.setItem('dark_mode', this.darkMode)
            this.applyTheme()
        },

        setLanguage(lang) {
            if (['es', 'en'].includes(lang)) {
                this.language = lang
                localStorage.setItem('language', lang)
            }
        },

        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark-mode')
            } else {
                document.documentElement.classList.remove('dark-mode')
            }
        }
    }
})
