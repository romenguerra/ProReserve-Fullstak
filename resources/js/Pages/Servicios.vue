<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    servicios: {
        type: Array,
        default: () => [],
    },
});

const categorias = [
    {
        id: 1,
        nombre: "Gastronomía",
        descripcion: "Reserva en tu restaurante",
        imagen: "/images/gastronomia.jpg",
        slug: "gastronomia",
    },
    {
        id: 2,
        nombre: "Deporte",
        descripcion: "Entrena y mantente en forma",
        imagen: "/images/deporte.jpg",
        slug: "deporte",
    },
    {
        id: 3,
        nombre: "Salud",
        descripcion: "Cuida de tu bienestar físico",
        imagen: "/images/salud.jpg",
        slug: "salud",
    },
    {
        id: 4,
        nombre: "Ocio",
        descripcion: "Disfruta de tu tiempo libre",
        imagen: "/images/ocio.jpg",
        slug: "ocio",
    },
    {
        id: 5,
        nombre: "Imagen personal",
        descripcion: "Servicios a tu medida",
        imagen: "/images/personal.jpg",
        slug: "imagen-personal",
    },
];
</script>

<template>
    <MainLayout title="Servicios - ProReserve">
        <div class="bg-[#F0EEE9] min-h-screen">
            <!-- Hero Section -->
            <section class="relative flex items-center overflow-hidden pt-32 pb-20">
                <!-- Decorative colored element -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -left-24 w-72 h-72 bg-white/40 rounded-full blur-[80px]"></div>
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-4xl">
                    <h1
                        class="text-6xl md:text-7xl lg:text-8xl font-bold text-gray-900 leading-tight mb-6 tracking-tighter"
                    >
                        Todos los<br /><span class="text-[#8EB6A5]">servicios.</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 max-w-2xl leading-relaxed">
                        Explora nuestra selección completa de servicios
                        disponibles para reservar.
                    </p>
                </div>
            </div>
            </section>

        <!-- Services Grid Section -->
        <section class="py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <Link
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        :href="`/servicios/${categoria.slug}`"
                        class="group cursor-pointer"
                    >
                        <div
                            class="aspect-[4/3] overflow-hidden mb-4 bg-gray-100 rounded-lg"
                        >
                            <img
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                :src="categoria.imagen"
                                :alt="categoria.nombre"
                            />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ categoria.nombre }}
                        </h3>
                        <p class="text-base text-gray-600 mb-3">
                            {{ categoria.descripcion }}
                        </p>
                        <span
                            class="inline-flex items-center text-sm text-gray-900 font-bold group-hover:text-[#8EB6A5] transition-all"
                        >
                            Explorar
                            <svg
                                class="w-4 h-4 ml-1.5 transition-transform group-hover:translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                />
                            </svg>
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-gray-950 text-white py-24 relative overflow-hidden">
            <!-- Decorative blur layers -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[120px]"></div>
            
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                <div class="max-w-3xl">
                    <h2 class="text-5xl md:text-7xl font-bold mb-8 leading-tight tracking-tighter">
                        ¿Tienes un <span class="text-[#8EB6A5]">negocio?</span>
                    </h2>
                    <p class="text-xl text-gray-400 mb-10 leading-relaxed max-w-2xl">
                        Únete a ProReserve y empieza a gestionar las reservas de
                        tu negocio de forma simple, eficiente y con estilo.
                    </p>
                    <Link
                        href="/register"
                        class="inline-block bg-white text-gray-950 px-10 py-5 rounded-full font-bold hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 shadow-2xl shadow-white/5"
                    >
                        Comenzar ahora
                    </Link>
                </div>
            </div>
        </section>
        </div>
    </MainLayout>
</template>
