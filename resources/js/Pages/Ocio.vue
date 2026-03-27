<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import LeisureCard from "@/Components/LeisureCard.vue";
import LocalDetailModal from "@/Components/LocalDetailModal.vue";

const showModal = ref(false);
const selectedCenter = ref(null);

const openDetailModal = (center) => {
    selectedCenter.value = center;
    showModal.value = true;
};

const props = defineProps({
    leisureCenters: {
        type: Array,
        required: true,
    },
});

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const index = entry.target.getAttribute('data-index');
                setTimeout(() => {
                    entry.target.classList.add('reveal-card');
                }, index % 4 * 150); // Staggered reveal based on column (roughly)
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((card) => observer.observe(card));
});
</script>

<template>
    <MainLayout title="Ocio - ProReserve">
        <!-- Hero Section con Mesh Gradient -->
        <section class="relative min-h-[40vh] flex items-center overflow-hidden bg-[#F0EFE9]">
            <div class="absolute inset-0 opacity-70" style="
                background-color: #F0EFE9;
                background-image: 
                    radial-gradient(at 0% 0%, #C3C4E3 0px, transparent 50%),
                    radial-gradient(at 100% 0%, #E8E8F3 0px, transparent 50%),
                    radial-gradient(at 50% 100%, #B5A3C4 0px, transparent 50%);
                filter: blur(40px);
            "></div>
            
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-16 w-full relative z-10">
                <div class="max-w-3xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-indigo-600/10 text-indigo-700 text-xs font-bold uppercase tracking-widest mb-6 animate-fade-in">
                        Tiempo Libre
                    </span>
                    <h1 class="text-6xl md:text-8xl font-bold text-gray-900 leading-[0.95] tracking-tighter mb-6">
                        Desconecta y<br /><span class="text-indigo-600">disfruta.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-700 max-w-xl leading-relaxed">
                        Encuentra los mejores planes para tu tiempo libre. Cines, salas de juegos, discotecas y más.
                    </p>
                </div>
            </div>
        </section>

        <!-- Leisure Grid -->
        <section class="bg-[#F0EFE9] py-24 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <!-- Toolbar/Filters Placeholder -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">Centros de Ocio</h2>
                        <p class="text-gray-500">Descubre {{ leisureCenters.length }} planes increíbles para ti.</p>
                    </div>
                    <div class="flex gap-4">
                        <!-- Aquí se podrían añadir filtros en el futuro -->
                        <div class="h-px w-24 bg-gray-300 mb-4 hidden md:block"></div>
                    </div>
                </div>

                <!-- Grid -->
                <div v-if="leisureCenters.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16">
                    <LeisureCard 
                        v-for="(center, index) in leisureCenters" 
                        :key="center.id" 
                        :center="center"
                        :index="index"
                        @open-modal="openDetailModal"
                    />
                </div>

                <!-- Volver al Inicio Button -->
                <div v-if="leisureCenters.length > 0" class="mt-24 flex">
                    <Link 
                        href="/"
                        class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-full overflow-hidden hover:pr-12 transition-all duration-500 shadow-xl hover:shadow-gray-400/20"
                    >
                        <span class="relative z-10 font-bold tracking-tight">Volver al inicio</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        <svg class="w-5 h-5 relative z-10 transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center py-40 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron centros de ocio</h3>
                    <p class="text-gray-500 max-w-sm">Estamos trabajando para añadir más opciones de ocio a ProReserve muy pronto.</p>
                </div>
            </div>
        </section>

        <!-- Modal de detalle -->
        <LocalDetailModal
            :show="showModal"
            :local="selectedCenter"
            category="ocio"
            @close="showModal = false"
        />
    </MainLayout>
</template>

<style scoped>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}
</style>
