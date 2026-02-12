<script setup>
import { Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = [
    {
        title: 'Gastronomía',
        description: 'Reserva en tu restaurante favorito',
        image: '/images/gastronomia.jpg',
        color: 'orange',
        link: '#'
    },
    {
        title: 'Deporte',
        description: 'Entrena y mantente en forma',
        image: '/images/deporte.jpg',
        color: 'green',
        link: '#'
    },
    {
        title: 'Salud',
        description: 'Cuida de tu bienestar físico',
        image: '/images/salud.jpg',
        color: 'red',
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
        title: 'Personal',
        description: 'Servicios a tu medida',
        image: '/images/personal.jpg', 
        color: 'indigo',
        link: '#'
    }
];

const getColorClasses = (color) => {
    const colors = {
        orange: 'bg-orange-500 hover:bg-orange-600 focus:ring-orange-300',
        green: 'bg-green-600 hover:bg-green-700 focus:ring-green-300',
        purple: 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-300',
        red: 'bg-red-600 hover:bg-red-700 focus:ring-red-300',
        indigo: 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-300'
    };
    return colors[color] || colors.indigo;
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
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    <!-- Tarjeta por categoría -->
                    <div
                        v-for="category in categories"
                        :key="category.title"
                        class="bg-white flex flex-col border border-gray-200 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden"
                    >
                        <a :href="category.link" class="block aspect-video overflow-hidden">
                            <img
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                :src="category.image"
                                :alt="category.title"
                            />
                        </a>
                        <div class="p-5 flex flex-col flex-grow text-center">
                            <h5 class="mb-3 text-xl font-bold tracking-tight text-gray-900">
                                {{ category.title }}
                            </h5>
                            <p class="text-sm text-gray-600 mb-5 flex-grow">
                                {{ category.description }}
                            </p>
                            <a
                                :href="category.link"
                                :class="['inline-flex items-center justify-center text-white shadow-md font-semibold rounded-lg text-sm px-4 py-2.5 focus:outline-none focus:ring-4 transition-all duration-200', getColorClasses(category.color)]"
                            >
                                Reservar
                                <svg
                                    class="w-4 h-4 ms-2"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
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
