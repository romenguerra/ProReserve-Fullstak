<script setup>
import { onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import SportCenterCard from "@/Components/SportCenterCard.vue";

const props = defineProps({
    sportCenters: {
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
                }, (index % 4) * 150);
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
    <MainLayout title="Deportes - ProReserve">
        <!-- Hero Section con Mesh Gradient Sporty -->
        <section class="relative min-h-[40vh] flex items-center overflow-hidden bg-[#E9EDF5]">
            <div class="absolute inset-0 opacity-70" style="
                background-color: #E9EDF5;
                background-image: 
                    radial-gradient(at 0% 0%, #3B82F6 0px, transparent 50%),
                    radial-gradient(at 100% 0%, #BFDBFE 0px, transparent 50%),
                    radial-gradient(at 50% 100%, #60A5FA 0px, transparent 50%);
                filter: blur(40px);
            "></div>
            
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-16 w-full relative z-10">
                <div class="max-w-3xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-700 text-xs font-bold uppercase tracking-widest mb-6 animate-fade-in">
                        Vida Activa y Saludable
                    </span>
                    <h1 class="text-6xl md:text-8xl font-bold text-gray-900 leading-[0.95] tracking-tighter mb-6">
                        Domina el<br /><span class="text-blue-600">juego.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-700 max-w-xl leading-relaxed">
                        Encuentra los mejores centros deportivos, pistas y gimnasios. Reserva tu espacio y empieza a entrenar.
                    </p>
                </div>
            </div>
        </section>

        <!-- Sports Centers Grid -->
        <section class="bg-[#E9EDF5] py-24 min-h-screen">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <!-- Toolbar/Header -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-2">Centros Deportivos</h2>
                        <p class="text-gray-500">Explora {{ sportCenters.length }} instalaciones de alto nivel.</p>
                    </div>
                </div>

                <!-- Grid -->
                <div v-if="sportCenters.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16">
                    <SportCenterCard 
                        v-for="(center, index) in sportCenters" 
                        :key="center.id" 
                        :sportCenter="center"
                        :index="index"
                    />
                </div>

                <!-- Volver al Inicio Button -->
                <div v-if="sportCenters.length > 0" class="mt-24 flex">
                    <Link 
                        href="/"
                        class="group relative inline-flex items-center gap-3 px-8 py-4 bg-gray-900 text-white rounded-full overflow-hidden hover:pr-12 transition-all duration-500 shadow-xl hover:shadow-blue-400/20"
                    >
                        <span class="relative z-10 font-bold tracking-tight">Volver al inicio</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        <svg class="w-5 h-5 relative z-10 transition-transform duration-500 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-else class="flex flex-col items-center justify-center py-40 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No se encontraron centros deportivos</h3>
                    <p class="text-gray-500 max-w-sm">Estamos trabajando para añadir más opciones deportivas a ProReserve muy pronto.</p>
                </div>
            </div>
        </section>
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
