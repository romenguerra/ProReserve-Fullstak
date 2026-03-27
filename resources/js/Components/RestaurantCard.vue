<script setup>
defineProps({
    restaurant: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        default: 0,
    },
});

// Ayudante para mostrar el nombre del tipo de cocina
const getTypeName = (type) => {
    switch (type) {
        case 'asador': return 'Asador';
        case 'japones': return 'Japonés';
        case 'mediterraneo': return 'Mediterráneo';
        case 'italiano': return 'Italiano';
        case 'steakhouse': return 'Steakhouse';
        case 'saludable': return 'Saludable';
        default: return 'Restaurante';
    }
};

// Color del badge basado en el tipo
const getTypeColor = (type) => {
    switch (type) {
        case 'asador': return 'bg-orange-600/80';
        case 'japones': return 'bg-red-600/80';
        case 'mediterraneo': return 'bg-blue-500/80';
        case 'italiano': return 'bg-emerald-600/80';
        case 'steakhouse': return 'bg-stone-700/80';
        case 'saludable': return 'bg-lime-600/80';
        default: return 'bg-gray-500/80';
    }
}
</script>

<template>
    <div
        class="group cursor-pointer card-animate restaurant-card"
        :data-index="index"
    >
        <div @click="$emit('open-modal', restaurant)" class="flex items-center gap-4 sm:block group/content">
            <div
                class="w-28 h-28 shrink-0 sm:w-full sm:h-auto sm:aspect-[4/5] overflow-hidden sm:mb-6 bg-gray-100 rounded-3xl sm:rounded-[2rem] image-container shadow-sm group-hover:shadow-2xl transition-all duration-700 relative"
            >
                <img
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000"
                    :src="restaurant.image || '/images/gastronomia.jpg'"
                    :alt="restaurant.name"
                    loading="lazy"
                />
                
                <!-- Badge for Rating if available -->
                <div v-if="restaurant.rating_average > 0" class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/90 backdrop-blur-md px-2 py-1 sm:px-3 sm:py-1.5 rounded-full flex items-center gap-1 sm:gap-1.5 shadow-lg border border-white/20 transform sm:translate-y-2 sm:opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <svg class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-[10px] sm:text-xs font-bold text-gray-900">{{ restaurant.rating_average }}</span>
                </div>

                <!-- Type indicator tag -->
                <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 hidden sm:block" v-if="restaurant.type">
                    <span :class="getTypeColor(restaurant.type)" class="backdrop-blur-md text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ getTypeName(restaurant.type) }}
                    </span>
                </div>
                
                <!-- City indicator tag -->
                <div class="absolute bottom-2 right-2 sm:bottom-4 sm:right-4 hidden sm:block">
                    <span class="bg-gray-900/40 backdrop-blur-md text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ restaurant.city }}
                    </span>
                </div>
            </div>

            <div class="flex-1 min-w-0 py-2 sm:py-0 px-0 sm:px-2 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-1 sm:mb-2">
                    <h3 class="text-lg sm:text-2xl font-bold text-gray-900 leading-tight group-hover:text-[#8EB6A5] transition-colors duration-300 truncate w-full">
                        {{ restaurant.name }}
                    </h3>
                </div>
                <p class="text-gray-500 text-xs sm:text-sm mb-2 sm:mb-4 line-clamp-2 leading-relaxed">
                    {{ restaurant.description }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center text-xs text-gray-950 font-bold uppercase tracking-widest group-hover:text-[#8EB6A5] transition-colors duration-300">
                        Ver detalles
                        <svg
                            class="w-4 h-4 ml-2 transition-transform duration-500 group-hover:translate-x-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"
                            />
                        </svg>
                    </span>
                    <div class="flex gap-2">
                        <span v-if="restaurant.has_terrace" title="Terraza disponible" class="text-gray-400 group-hover:text-[#8EB6A5] transition-colors duration-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.restaurant-card {
    opacity: 0;
    transform: translateY(30px) scale(0.95);
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

:global(.reveal-card) {
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
}

.image-container::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.2));
    opacity: 0;
    transition: opacity 0.5s ease;
}

.restaurant-card:hover .image-container::after {
    opacity: 1;
}
</style>
