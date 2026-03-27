<script setup>
import { watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    local: {
        type: Object,
        default: null,
    },
    category: {
        type: String,
        default: 'servicio',
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

// Manejo del scroll del body
watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

// Helper para colores según categoría
const getCategoryTheme = (cat) => {
    switch(cat) {
        case 'gastronomia': return { label: 'Gastronomía', color: 'bg-orange-500', textTitle: 'text-orange-900', textAccent: 'text-orange-500', bgMuted: 'bg-orange-50' };
        case 'deportes': return { label: 'Deportes', color: 'bg-blue-500', textTitle: 'text-blue-900', textAccent: 'text-blue-500', bgMuted: 'bg-blue-50' };
        case 'ocio': return { label: 'Ocio', color: 'bg-indigo-500', textTitle: 'text-indigo-900', textAccent: 'text-indigo-500', bgMuted: 'bg-indigo-50' };
        case 'salud': return { label: 'Salud', color: 'bg-teal-500', textTitle: 'text-teal-900', textAccent: 'text-teal-500', bgMuted: 'bg-teal-50' };
        case 'belleza': return { label: 'Imagen Personal', color: 'bg-rose-500', textTitle: 'text-rose-900', textAccent: 'text-rose-500', bgMuted: 'bg-rose-50' };
        default: return { label: 'Servicio', color: 'bg-gray-800', textTitle: 'text-gray-900', textAccent: 'text-gray-800', bgMuted: 'bg-gray-100' };
    }
};

const formatTypeName = (type) => {
    if (!type) return null;
    // Capitalizar la primera letra y manejar tildes de forma sencilla para este demo
    const titles = {
        'asador': 'Asador',
        'japones': 'Japonés',
        'mediterraneo': 'Mediterráneo',
        'italiano': 'Italiano',
        'steakhouse': 'Steakhouse',
        'saludable': 'Saludable',
        'gimnasio': 'Gimnasio',
        'padel': 'Pádel',
        'natacion': 'Natación',
        'crossfit': 'Crossfit',
        'yoga': 'Yoga',
        'clinica-estetica': 'Clínica Estética',
        'clinica-medica': 'Clínica Médica',
        'hospital': 'Hospital',
        'barberia': 'Barbería',
        'peluqueria': 'Peluquería',
        'salon-belleza': 'Salón de Belleza',
        'spa': 'Spa',
        'cine': 'Cine',
        'bolera': 'Bolera',
        'parque-atracciones': 'Parque de Atracciones',
        'museo': 'Museo'
    };
    return titles[type] || type.charAt(0).toUpperCase() + type.slice(1);
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-show="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                scroll-region
            >
                <!-- Backdrop oscuro -->
                <div
                    class="fixed inset-0 bg-gray-900/40 backdrop-blur-md transition-opacity"
                    @click="close"
                ></div>

                <!-- Modal Content flotante estilo "página" -->
                <Transition
                    enter-active-class="ease-out duration-500"
                    enter-from-class="opacity-0 translate-y-20 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-4 scale-95"
                    leave-to-class="opacity-0 translate-y-10 scale-95"
                >
                    <div
                        v-show="show"
                        class="bg-white rounded-3xl shadow-2xl overflow-hidden transform transition-all w-full max-w-6xl max-h-[90vh] flex flex-col relative z-50 border border-white/20"
                    >
                        <div v-if="local" class="flex flex-col md:flex-row h-full overflow-y-auto md:overflow-hidden">
                            <!-- Botón cerrar (Móvil & Desktop) -->
                            <button 
                                @click="close"
                                class="absolute top-4 right-4 z-50 bg-white/50 backdrop-blur tracking-widest hover:bg-white text-gray-900 p-2.5 rounded-full shadow-lg border border-gray-100 transition-all duration-300 group"
                            >
                                <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <!-- Lado Izquierdo: Imagen Ampliada -->
                            <div class="md:w-1/2 relative min-h-[300px] md:min-h-0 bg-gray-100 shrink-0">
                                <img 
                                    :src="local.image || '/images/salud.jpg'" 
                                    :alt="local.name"
                                    class="absolute inset-0 w-full h-full object-cover"
                                >
                                <!-- Sombra de base de la imagen para legibilidad si tuviera texto abajo -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-60"></div>
                                
                                <div class="absolute bottom-6 left-6 flex items-center gap-3">
                                    <span :class="['px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest text-white backdrop-blur-md shadow-lg', getCategoryTheme(category).color]">
                                        {{ formatTypeName(local.type) || getCategoryTheme(category).label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Lado Derecho: Menú e Información -->
                            <div class="md:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col overflow-y-auto w-full">
                                
                                <!-- Rating -->
                                <div class="flex items-center gap-2 mb-4" v-if="local.rating_average > 0">
                                    <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <span class="font-bold text-gray-900">{{ local.rating_average }}</span>
                                    <span class="text-gray-500 text-sm">({{ local.rating_count }} valoraciones)</span>
                                </div>

                                <!-- Título y descripción -->
                                <h1 :class="['text-4xl md:text-5xl font-extrabold mb-6 leading-tight tracking-tight', getCategoryTheme(category).textTitle]">
                                    {{ local.name }}
                                </h1>
                                
                                <p class="text-lg text-gray-600 mb-10 leading-relaxed font-light border-b border-gray-100 pb-8">
                                    {{ local.description || 'Descubre nuestro increíble centro y disfruta de una atención espectacular.' }}
                                </p>

                                <!-- Información de contacto (Cajas flotantes estilo moderno) -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12 flex-1">
                                    
                                    <!-- Dirección -->
                                    <div class="flex items-start gap-4">
                                        <div :class="['p-3 rounded-2xl shrink-0', getCategoryTheme(category).bgMuted, getCategoryTheme(category).textAccent]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 mb-0.5">Ubicación</p>
                                            <p class="text-sm text-gray-600 leading-snug">{{ local.address }}, {{ local.city }}</p>
                                        </div>
                                    </div>

                                    <!-- Horario -->
                                    <div class="flex items-start gap-4">
                                        <div :class="['p-3 rounded-2xl shrink-0', getCategoryTheme(category).bgMuted, getCategoryTheme(category).textAccent]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 mb-0.5">Horario Habitual</p>
                                            <p class="text-sm text-gray-600">
                                                {{ local.opening_time ? local.opening_time.substring(0,5) : '--:--' }} - 
                                                {{ local.closing_time ? local.closing_time.substring(0,5) : '--:--' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Teléfono -->
                                    <div class="flex items-start gap-4" v-if="local.phone">
                                        <div :class="['p-3 rounded-2xl shrink-0', getCategoryTheme(category).bgMuted, getCategoryTheme(category).textAccent]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 mb-0.5">Contacto</p>
                                            <p class="text-sm text-gray-600">{{ local.phone }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Servicios Destacados -->
                                    <div class="flex items-start gap-4">
                                        <div :class="['p-3 rounded-2xl shrink-0', getCategoryTheme(category).bgMuted, getCategoryTheme(category).textAccent]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 mb-0.5">Destacado</p>
                                            <p class="text-sm text-gray-600">
                                                <span v-if="local.has_parking">Parking ✓ </span>
                                                <span v-if="local.wheelchair_access">Acceso ✓ </span>
                                                <span v-if="local.online_booking">Reserva Online ✓</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón Reservar principal -->
                                <div class="mt-auto">
                                    <button
                                        class="w-full relative group inline-flex items-center justify-center gap-3 px-8 py-5 bg-gray-950 text-white rounded-2xl overflow-hidden transition-all duration-300 shadow-xl shadow-gray-950/20 hover:shadow-gray-950/40"
                                    >
                                        <span class="relative z-10 font-bold text-lg tracking-tight">Reservar Cita Ahora</span>
                                        <div :class="['absolute inset-0 translate-y-full group-hover:translate-y-0 transition-transform duration-500', getCategoryTheme(category).color]"></div>
                                        <svg class="w-5 h-5 relative z-10 transition-transform duration-500 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
