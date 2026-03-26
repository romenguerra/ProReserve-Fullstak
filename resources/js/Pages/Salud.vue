<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import HealthCard from "@/Components/HealthCard.vue";
import LocalDetailModal from "@/Components/LocalDetailModal.vue";

const showModal = ref(false);
const selectedCenter = ref(null);

const openDetailModal = (center) => {
    selectedCenter.value = center;
    showModal.value = true;
};

const props = defineProps({
    healthCenters: {
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
    <MainLayout title="Salud y Bienestar - ProReserve">
        <!-- Hero Section con Mesh Gradient -->
        <section class="relative min-h-[40vh] flex items-center overflow-hidden bg-[#E9F0EE]">
            <div class="absolute inset-0 opacity-70" style="
                background-color: #E9F0EE;
                background-image: 
                    radial-gradient(at 0% 0%, #A3E4D7 0px, transparent 50%),
                    radial-gradient(at 100% 0%, #D1F2EB 0px, transparent 50%),
                    radial-gradient(at 50% 100%, #76D7C4 0px, transparent 50%);
                filter: blur(40px);
            "></div>
            
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-16 w-full relative z-10">
                <div class="max-w-3xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-teal-600/10 text-teal-700 text-xs font-bold uppercase tracking-widest mb-6 animate-fade-in">
                        Cuidado Especializado
                    </span>
                    <h1 class="text-6xl md:text-8xl font-bold text-gray-900 leading-[0.95] tracking-tighter mb-6">
                        Salud y<br /><span class="text-teal-600">bienestar.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-700 max-w-xl leading-relaxed">
                        Encuentra las mejores clínicas médicas, expertos en estética y hospitales privados, con atención prioritaria.
                    </p>
                </div>
            </div>
        </section>

        <!-- Health Grid -->
        <section class="bg-[#E9F0EE] py-24 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <!-- Toolbar/Filters Placeholder -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">Centros de Salud</h2>
                        <p class="text-gray-500">Descubre {{ healthCenters.length }} centros disponibles para tu cuidado.</p>
                    </div>
                    <div class="flex gap-4">
                        <!-- Aquí se podrían añadir filtros en el futuro -->
                        <div class="h-px w-24 bg-gray-400 mb-4 hidden md:block"></div>
                    </div>
                </div>

                <!-- Grid -->
                <div v-if="healthCenters.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16">
                    <HealthCard 
                        v-for="(center, index) in healthCenters" 
                        :key="center.id" 
                        :center="center"
                        :index="index"
                        @open-modal="openDetailModal"
                    />
                </div>

                <!-- Volver al Inicio Button -->
                <div v-if="healthCenters.length > 0" class="mt-24 flex">
                    <Link 
                        href="/"
                        class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-full overflow-hidden hover:pr-12 transition-all duration-500 shadow-xl hover:shadow-gray-400/20"
                    >
                        <span class="relative z-10 font-bold tracking-tight">Volver al inicio</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-emerald-500 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
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
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron centros</h3>
                    <p class="text-gray-500 max-w-sm">Estamos trabajando para añadir más especialistas médicos a ProReserve muy pronto.</p>
                </div>
            </div>
        </section>

        <!-- Modal de detalle -->
        <LocalDetailModal
            :show="showModal"
            :local="selectedCenter"
            category="salud"
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
