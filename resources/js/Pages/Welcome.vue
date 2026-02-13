<script setup>
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = [
    {
        title: "Gastronomía",
        description: "Reserva en tu restaurante",
        image: "/images/gastronomia.jpg",
        link: "#",
    },
    {
        title: "Deporte",
        description: "Entrena y mantente en forma",
        image: "/images/deporte.jpg",
        link: "#",
    },
    {
        title: "Salud",
        description: "Cuida de tu bienestar físico",
        image: "/images/salud.jpg",
        link: "#",
    },
    {
        title: "Ocio",
        description: "Disfruta de tu tiempo libre",
        image: "/images/ocio.jpg",
        link: "#",
    },
    {
        title: "Imagen personal",
        description: "Servicios a tu medida",
        image: "/images/personal.jpg",
        link: "#",
    },
];
</script>

<template>
    <MainLayout title="Inicio - ProReserve">
        <!-- Hero Section - Con Gradiente Premium -->
        <section class="bg-[url('/images/hero-gradient.png')] bg-cover bg-center min-h-[70vh] flex items-center">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-20 w-full">
                <div class="max-w-3xl">
                    <h1
                        class="text-6xl md:text-7xl lg:text-8xl font-bold text-gray-900 leading-tight mb-6"
                    >
                        Reservas<br />simples.
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-800 mb-8 max-w-xl">
                        Tu tiempo es valioso. Reserva lo que necesitas sin
                        complicaciones.
                    </p>
                    <Link
                        href="/register"
                        class="inline-block bg-gray-900 text-white px-8 py-4 rounded-full font-medium hover:bg-gray-800 transition-all duration-200"
                    >
                        Comenzar ahora
                    </Link>
                </div>
            </div>
        </section>

        <!-- Services Section - Blanco -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="mb-10">
                    <h2
                        class="text-5xl md:text-6xl font-bold text-gray-900 mb-6"
                    >
                        Qué podemos<br />hacer por ti.
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl">
                        Selecciona el servicio que necesitas y reserva tu cita.
                    </p>
                </div>

                <!-- Grid de servicios minimalista -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-5 gap-6 max-w-6xl">
                        <div
                            v-for="category in categories"
                            :key="category.title"
                            class="group cursor-pointer"
                        >
                            <a :href="category.link" class="block">
                                <div
                                    class="aspect-square overflow-hidden mb-3 bg-gray-100 rounded-lg"
                                >
                                    <img
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                        :src="category.image"
                                        :alt="category.title"
                                    />
                                </div>
                                <h3
                                    class="text-lg font-bold text-gray-900 mb-1"
                                >
                                    {{ category.title }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ category.description }}
                                </p>
                                <span
                                    class="inline-flex items-center text-sm text-gray-900 font-medium group-hover:gap-1 transition-all"
                                >
                                    Ver más
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
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section - Negro -->
        <section class="bg-gray-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-3xl">
                    <h2 class="text-5xl md:text-6xl font-bold mb-6">
                        El método ProReserve.
                    </h2>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl">
                        Sistema de reservas diseñado para negocios que valoran
                        la simplicidad y la eficiencia.
                    </p>
                    <Link
                        href="/login"
                        class="inline-block bg-[#ffffff] text-gray-900 px-8 py-4 rounded-full font-medium hover:bg-[#adadad] transition-all duration-200"
                    >
                        Iniciar sesión
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
