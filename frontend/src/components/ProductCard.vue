<script setup>
defineProps({
  product: {
    type: Object,
    required: true
  }
})
</script>

<template>
  <div class="product-card group bg-white rounded-[30px] p-6 border border-neutral-100 shadow-sm hover:shadow-xl hover:shadow-[#7ed9c7]/10 hover:border-[#7ed9c7]/30 transition-all duration-500 transform hover:-translate-y-2">
    
    <div class="img-wrapper aspect-square rounded-[24px] overflow-hidden mb-6 bg-stone-50 relative">
      <img :src="product.img || '/img/logo.jpg'" 
           :alt="product.nom"
           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
      
      <div class="absolute top-4 right-4 flex flex-col gap-2 items-end">
        <span v-if="product.categoria" class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-[10px] font-bold tracking-widest text-[#6bc7b5] border border-neutral-100 shadow-sm">
          {{ product.categoria.nom.toUpperCase() }}
        </span>
        <span v-if="product.id % 2 === 0" class="px-3 py-1 bg-[#6bc7b5] text-white rounded-full text-[10px] font-bold tracking-widest shadow-sm">
          ECO-FRIENDLY
        </span>
      </div>
    </div>

    <h3 class="text-xl font-bold text-neutral-800 mb-2 truncate group-hover:text-[#6bc7b5] transition-colors">{{ product.nom }}</h3>
    <p class="text-neutral-500 text-sm mb-6 line-clamp-2 leading-relaxed h-10">{{ product.descripcio }}</p>

    <div class="flex items-center justify-between mt-auto pt-4 border-t border-neutral-50">
      <div class="flex flex-col">
        <span class="text-xs text-neutral-400 font-bold uppercase tracking-wider">Precio</span>
        <span class="text-2xl font-black text-neutral-800">{{ product.preu }}<span class="text-sm ml-1 font-bold">€</span></span>
      </div>
      
      <router-link :to="`/product/${product.id}`" 
                   class="w-12 h-12 bg-neutral-800 hover:bg-[#7ed9c7] text-white rounded-2xl flex items-center justify-center shadow-lg shadow-neutral-200 transition-all transform hover:rotate-12 active:scale-95 group/btn">
        <svg class="w-6 h-6 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
      </router-link>
    </div>
  </div>
</template>

<style scoped>
.img-wrapper {
  aspect-ratio: 1 / 1;
  width: 100%;
}

.product-card {
  display: flex;
  flex-direction: column;
}
</style>
