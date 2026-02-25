import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('cart_items')) || []
    }),

    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        totalPrice: (state) => state.items.reduce((total, item) => total + (item.preu * item.quantity), 0).toFixed(2),
    },

    actions: {
        addItem(product) {
            const existingItem = this.items.find(item => item.id === product.id)
            if (existingItem) {
                existingItem.quantity++
            } else {
                this.items.push({
                    ...product,
                    quantity: 1
                })
            }
            this.saveCart()
        },

        removeItem(productId) {
            this.items = this.items.filter(item => item.id !== productId)
            this.saveCart()
        },

        updateQuantity(productId, quantity) {
            const item = this.items.find(item => item.id === productId)
            if (item) {
                item.quantity = Math.max(1, quantity)
                this.saveCart()
            }
        },

        clearCart() {
            this.items = []
            this.saveCart()
        },

        saveCart() {
            localStorage.setItem('cart_items', JSON.stringify(this.items))
        }
    }
})
