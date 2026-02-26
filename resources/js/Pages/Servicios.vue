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
        <!-- Hero Section -->
        <section class="bg-white py-20 pt-32">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-4xl">
                    <h1
                        class="text-6xl md:text-7xl lg:text-8xl font-bold text-gray-900 leading-tight mb-6"
                    >
                        Todos<br />los servicios.
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 max-w-2xl">
                        Explora nuestra selección completa de servicios
                        disponibles para reservar.
                    </p>
                </div>
            </div>
        </section>

        <!-- Services Grid Section -->
        <section class="bg-white py-20">
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
                            class="inline-flex items-center text-sm text-gray-900 font-medium group-hover:gap-1 transition-all"
                        >
                            Explorar
                            <svg
                                class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1"
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
        <section class="bg-gray-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-3xl">
                    <h2 class="text-5xl md:text-6xl font-bold mb-6">
                        ¿Tienes un negocio?
                    </h2>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl">
                        Únete a ProReserve y empieza a gestionar las reservas de
                        tu negocio de forma simple y eficiente.
                    </p>
                    <Link
                        href="/register"
                        class="inline-block bg-white text-gray-900 px-8 py-4 rounded-full font-medium hover:bg-gray-200 transition-all duration-200"
                    >
                        Comenzar ahora
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
