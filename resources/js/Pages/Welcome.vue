<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = [
    {
        title: 'Restauración',
        description: 'Reserva en tu restaurante favorito',
        image: '/images/restauracion.jpg',
        color: 'blue',
        link: '#'
    },
    {
        title: 'Bienestar',
        description: 'Entrena en tu gimnasio',
        image: '/images/bienestar.jpg',
        color: 'green',
        link: '#'
    },
    {
        title: 'Ocio',
        description: 'Disfruta de tu tiempo libre',
        image: '/images/ocio.jpg',
        color: 'purple',
        link: '#'
    },
    {
        title: 'Salud',
        description: 'Cuida de tu salud',
        image: '/images/salud.jpg',
        color: 'red',
        link: '#'
    }
];

const getColorClasses = (color) => {
    const colors = {
        blue: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-300',
        green: 'bg-green-600 hover:bg-green-700 focus:ring-green-300',
        purple: 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-300',
        red: 'bg-red-600 hover:bg-red-700 focus:ring-red-300'
    };
    return colors[color] || colors.blue;
};
</script>

<template>
    <MainLayout title="Inicio - ProReserve">
        <!-- Contenido principal -->
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh-15rem)] p-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-gray-900 mb-4 text-center">
                    Bienvenido a ProReserve
                </h1>
                <p class="text-lg text-gray-600 mb-8 text-center">
                    Gestiona tus servicios y reservas de forma simple, rápida y profesional
                </p>

                <!-- Contenedor de tarjetas -->
                <div class="flex flex-wrap justify-center gap-3">
                    <!-- Tarjeta por categoría -->
                    <div
                        v-for="category in categories"
                        :key="category.title"
                        class="bg-white block max-w-sm border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                    >
                        <a :href="category.link">
                            <img
                                class="rounded-t-lg w-64 h-40 object-cover"
                                :src="category.image"
                                :alt="category.title"
                            />
                        </a>
                        <div class="p-6 text-center">
                            <a :href="category.link">
                                <h5 class="mt-3 mb-6 text-2xl font-semibold tracking-tight text-gray-900">
                                    {{ category.title }}
                                </h5>
                            </a>
                            <a
                                :href="category.link"
                                :class="['inline-flex items-center text-white shadow-md font-medium rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-4 transition-all duration-200', getColorClasses(category.color)]"
                            >
                                {{ category.description }}
                                <svg
                                    class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 12H5m14 0-4 4m4-4-4-4"
                                    />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
