<script setup>
import { Link } from "@inertiajs/vue3";

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
</script>

<template>
    <div
        class="group cursor-pointer card-animate sport-card"
        :data-index="index"
    >
        <Link :href="`/deportes/${sportCenter.slug}`" class="block">
            <div
                class="aspect-[4/5] overflow-hidden mb-6 bg-gray-100 rounded-[2rem] image-container shadow-sm group-hover:shadow-2xl transition-all duration-700 relative"
            >
                <img
                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000"
                    :src="sportCenter.image || '/images/deportes.jpg'"
                    :alt="sportCenter.name"
                    loading="lazy"
                />
                
                <!-- Badge for Rating if available -->
                <div v-if="sportCenter.rating_average > 0" class="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-lg border border-white/20 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                    <svg class="w-3.5 h-3.5 text-blue-500 fill-current" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="text-xs font-bold text-gray-900">{{ sportCenter.rating_average }}</span>
                </div>

                <!-- City Tag -->
                <div class="absolute bottom-4 left-4">
                    <span class="bg-gray-900/40 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/10">
                        {{ sportCenter.city }}
                    </span>
                </div>
            </div>

            <div class="px-2">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-2xl font-bold text-gray-900 leading-tight group-hover:text-blue-600 transition-colors duration-300">
                        {{ sportCenter.name }}
                    </h3>
                </div>
                <p class="text-gray-500 text-sm mb-4 line-clamp-2 leading-relaxed">
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
        </Link>
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
