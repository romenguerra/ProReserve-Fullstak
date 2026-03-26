<script setup>
defineProps({
    center: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        default: 0,
    },
});

// Ayudante para mostrar el nombre del tipo de centro de belleza
const getTypeName = (type) => {
    switch (type) {
        case 'barberia': return 'Barbería';
        case 'peluqueria': return 'Peluquería';
        case 'estetica': return 'Centro de Estética';
        case 'masajes': return 'Masajes / Spa';
        default: return 'Imagen Personal';
    }
};

// Color del badge basado en el tipo
const getTypeColor = (type) => {
    switch (type) {
        case 'barberia': return 'bg-amber-700/80';
        case 'peluqueria': return 'bg-rose-500/80';
        case 'estetica': return 'bg-pink-500/80';
        case 'masajes': return 'bg-purple-500/80';
        default: return 'bg-gray-500/80';
    }
}
</script>

<template>
    <div
        class="group cursor-pointer card-animate beauty-card"
        :data-index="index"
    >
        <div @click="$emit('open-modal', center)" class="block">
            <div
                class="aspect-[4/5] overflow-hidden mb-6 bg-rose-50 rounded-[2rem] image-container shadow-sm group-hover:shadow-2xl transition-all duration-700 relative"
            >
                <img
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000"
                    :src="center.image || '/images/beauty-wellness.png'"
                    :alt="center.name"
                    loading="lazy"
                />
                
                <!-- Badge for Rating if available -->
                <div v-if="center.rating_average > 0" class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-lg border border-white/20 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <svg class="w-3.5 h-3.5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-xs font-bold text-gray-900">{{ center.rating_average }}</span>
                </div>

                <!-- Type indicator tag -->
                <div class="absolute bottom-4 left-4">
                    <span :class="getTypeColor(center.type)" class="backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ getTypeName(center.type) }}
                    </span>
                </div>
                
                <!-- City indicator tag -->
                <div class="absolute bottom-4 right-4">
                    <span class="bg-gray-900/40 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ center.city }}
                    </span>
                </div>
            </div>

            <div class="px-2">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-bold text-gray-900 leading-tight group-hover:text-rose-600 transition-colors duration-300">
                        {{ center.name }}
                    </h3>
                </div>
                <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">
                    {{ center.description }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center text-xs text-rose-950 font-bold uppercase tracking-widest group-hover:text-rose-600 transition-colors duration-300">
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
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.beauty-card {
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
    background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.3));
    opacity: 0;
    transition: opacity 0.5s ease;
}

.beauty-card:hover .image-container::after {
    opacity: 1;
}
</style>
