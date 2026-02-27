import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: () => import('../views/Checkout.vue'),
  },
  {
    path: '/cart',
    name: 'Cart',
    component: () => import('../views/Cart.vue'),
  },
  {
    path: '/contact',
    name: 'Contact',
    component: () => import('../views/Contact.vue'),
  },
  {
    path: '/upload',
    name: 'Upload',
    component: () => import('../views/Upload.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
    meta: { requiresGuest: true },
  },
  {
    path: '/filters',
    name: 'Filters',
    component: () => import('../views/Filters.vue'),
  },
  {
    path: '/product/:id?',
    name: 'ProductDetail',
    component: () => import('../views/ProductDetail.vue'),
  },
  {
    path: '/profile',
    name: 'Profile',
    component: () => import('../views/Profile.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/pedidos',
    name: 'UserOrders',
    component: () => import('../views/UserOrders.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/admin/products',
    name: 'AdminProducts',
    component: () => import('../views/admin/ProductManagement.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/category/:id',
    name: 'CategoryProducts',
    component: () => import('../views/CategoryProducts.vue'),
  },
  {
    path: '/products',
    name: 'ProductList',
    component: () => import('../views/ProductList.vue'),
  },
  {
    path: '/settings',
    name: 'Settings',
    component: () => import('../views/Settings.vue'),
  },
  {
    path: '/sustainability',
    name: 'Sustainability',
    component: () => import('../views/Sustainability.vue'),
  },
  {
    path: '/support',
    name: 'Support',
    component: () => import('../views/Support.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

/**
 * Guardas de navegación para proteger las rutas según autenticación y roles
 */
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // Si la ruta requiere estar logueado y no lo está, al login
    next('/login')
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    // Si requiere ser admin y no lo es, a la home
    next('/')
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    // Si es para invitados (login/register) y ya está logueado, a la home
    next('/')
  } else {
    // En cualquier otro caso, dejamos pasar
    next()
  }
})

export default router
