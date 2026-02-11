<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';

// Props que vienen desde el controlador (opcional)
defineProps({
    servicios: {
        type: Array,
        default: () => []
    }
});

// Datos locales (esto se podría pasar desde el backend)
const categorias = [
    {
        id: 1,
        nombre: 'Restauración',
        descripcion: 'Los mejores restaurantes de Lanzarote',
        icono: '🍽️',
        color: 'blue',
        slug: 'restauracion'
    },
    {
        id: 2,
        nombre: 'Bienestar',
        descripcion: 'Gimnasios, spas y centros de bienestar',
        icono: '💪',
        color: 'green',
        slug: 'bienestar'
    },
    {
        id: 3,
        nombre: 'Ocio',
        descripcion: 'Actividades de entretenimiento y diversión',
        icono: '🎉',
        color: 'purple',
        slug: 'ocio'
    },
    {
        id: 4,
        nombre: 'Salud',
        descripcion: 'Consultas médicas y servicios de salud',
        icono: '🏥',
        color: 'red',
        slug: 'salud'
    }
];

const getColorClasses = (color) => {
    const colores = {
        blue: 'bg-blue-50 border-blue-200 hover:border-blue-400',
        green: 'bg-green-50 border-green-200 hover:border-green-400',
        purple: 'bg-purple-50 border-purple-200 hover:border-purple-400',
        red: 'bg-red-50 border-red-200 hover:border-red-400'
    };
    return colores[color] || colores.blue;
};

const getIconBg = (color) => {
    const colores = {
        blue: 'bg-blue-100',
        green: 'bg-green-100',
        purple: 'bg-purple-100',
        red: 'bg-red-100'
    };
    return colores[color] || colores.blue;
};
</script>

<template>
    <MainLayout title="Servicios - ProReserve">
        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Encabezado -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        Nuestros Servicios
                    </h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Descubre todas las categorías de servicios disponibles en ProReserve.
                        Reserva fácilmente en los mejores establecimientos de Lanzarote.
                    </p>
                </div>

                <!-- Grid de Categorías -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    <Link
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        :href="`/servicios/${categoria.slug}`"
                        :class="[
                            'block p-6 border-2 rounded-xl transition-all duration-200 hover:shadow-lg',
                            getColorClasses(categoria.color)
                        ]"
                    >
                        <div class="flex items-start gap-4">
                            <div 
                                :class="[
                                    'w-16 h-16 rounded-lg flex items-center justify-center text-3xl flex-shrink-0',
                                    getIconBg(categoria.color)
                                ]"
                            >
                                {{ categoria.icono }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                    {{ categoria.nombre }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ categoria.descripcion }}
                                </p>
                                <div class="mt-4 flex items-center text-blue-600 font-medium">
                                    Ver servicios
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Sección de beneficios -->
                <div class="bg-white rounded-xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                        ¿Por qué usar ProReserve?
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Rápido y Fácil</h3>
                            <p class="text-gray-600 text-sm">
                                Reserva en segundos desde cualquier dispositivo
                            </p>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Confirmación Instantánea</h3>
                            <p class="text-gray-600 text-sm">
                                Recibe la confirmación de tu reserva al instante
                            </p>
                        </div>

                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2">Disponibilidad 24/7</h3>
                            <p class="text-gray-600 text-sm">
                                Gestiona tus reservas cuando quieras
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
