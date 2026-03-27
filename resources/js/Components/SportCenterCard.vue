<script setup>
defineProps({
    sportCenter: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        default: 0,
    },
});

// Ayudante para mostrar el nombre del tipo de deporte
const getTypeName = (type) => {
    switch (type) {
        case 'gimnasio': return 'Gimnasio';
        case 'padel': return 'Pádel';
        case 'natacion': return 'Natación';
        case 'crossfit': return 'Crossfit';
        case 'yoga': return 'Yoga';
        default: return 'Deportes';
    }
};

// Color del badge basado en el tipo
const getTypeColor = (type) => {
    switch (type) {
        case 'gimnasio': return 'bg-blue-600/80';
        case 'padel': return 'bg-cyan-600/80';
        case 'natacion': return 'bg-sky-500/80';
        case 'crossfit': return 'bg-orange-600/80';
        case 'yoga': return 'bg-indigo-500/80';
        default: return 'bg-gray-500/80';
    }
}
</script>

<template>
    <div
        class="group cursor-pointer card-animate sport-card"
        :data-index="index"
    >
        <div @click="$emit('open-modal', sportCenter)" class="flex items-center gap-4 sm:block group/content">
            <div
                class="w-28 h-28 shrink-0 sm:w-full sm:h-auto sm:aspect-[4/5] overflow-hidden sm:mb-6 bg-gray-100 rounded-3xl sm:rounded-[2rem] image-container shadow-sm group-hover:shadow-2xl transition-all duration-700 relative"
            >
                <img
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000"
                    :src="sportCenter.image || '/images/deportes.jpg'"
                    :alt="sportCenter.name"
                    loading="lazy"
                />
                
                <!-- Badge for Rating if available -->
                <div v-if="sportCenter.rating_average > 0" class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white/90 backdrop-blur-md px-2 py-1 sm:px-3 sm:py-1.5 rounded-full flex items-center gap-1 sm:gap-1.5 shadow-lg border border-white/20 transform sm:translate-y-2 sm:opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <svg class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 text-blue-500 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-[10px] sm:text-xs font-bold text-gray-900">{{ sportCenter.rating_average }}</span>
                </div>

                <!-- Type indicator tag -->
                <div class="absolute bottom-2 left-2 sm:bottom-4 sm:left-4 hidden sm:block" v-if="sportCenter.type">
                    <span :class="getTypeColor(sportCenter.type)" class="backdrop-blur-md text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ getTypeName(sportCenter.type) }}
                    </span>
                </div>
                
                <!-- City Tag -->
                <div class="absolute bottom-2 right-2 sm:bottom-4 sm:right-4 hidden sm:block">
                    <span class="bg-gray-900/40 backdrop-blur-md text-white px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ sportCenter.city }}
                    </span>
                </div>
            </div>

            <div class="flex-1 min-w-0 py-2 sm:py-0 px-0 sm:px-2 flex flex-col justify-center">
                <div class="flex justify-between items-start mb-1 sm:mb-2">
                    <h3 class="text-lg sm:text-2xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors duration-300 truncate w-full">
                        {{ sportCenter.name }}
                    </h3>
                </div>
                <p class="text-gray-500 text-xs sm:text-sm mb-2 sm:mb-4 line-clamp-2 leading-relaxed">
                    {{ sportCenter.description }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center text-xs text-gray-950 font-bold uppercase tracking-widest group-hover:text-blue-600 transition-colors duration-300">
                        Ver centro
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
                        <span v-if="sportCenter.has_parking" title="Parking disponible" class="text-gray-400 group-hover:text-blue-600 transition-colors duration-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sport-card {
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

.sport-card:hover .image-container::after {
    opacity: 1;
}
</style>
