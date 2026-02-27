<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({
            totalReservations: 24,
            completedReservations: 18,
            points: 350
        })
    },
    activities: {
        type: Array,
        default: () => [
            {
                id: 1,
                type: 'reservation',
                title: 'Reserva confirmada',
                description: 'Sala de conferencias - 15:00h',
                time: 'Hace 2 horas',
                icon: 'check',
                color: 'blue'
            },
            {
                id: 2,
                type: 'points',
                title: 'Puntos ganados',
                description: '+50 puntos por reserva completada',
                time: 'Hace 1 día',
                icon: 'star',
                color: 'green'
            },
            {
                id: 3,
                type: 'profile',
                title: 'Perfil actualizado',
                description: 'Información del perfil modificada',
                time: 'Hace 3 días',
                icon: 'user',
                color: 'purple'
            }
        ]
    }
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    city: '',
    bio: ''
});

const formatMemberSince = (date) => {
    if (!date) return 'Enero 2024';
    const d = new Date(date);
    return d.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
};

const submitForm = () => {
    form.post(route('profile.update'), {
        preserveScroll: true
    });
};
</script>

<template>
    <MainLayout title="Mi Perfil">
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Header del Perfil -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <!-- Banner -->
                    <div class="h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>

                    <!-- Información del Usuario -->
                    <div class="px-6 pb-6">
                        <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12">
                            <!-- Foto de Perfil -->
                            <div class="relative">
                                <img 
                                    class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                                    :src="`https://ui-avatars.com/api/?name=${user?.name || 'Usuario'}&size=128&background=6366f1&color=fff`"
                                    alt="Foto de perfil"
                                />
                                <button class="absolute bottom-2 right-2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>

                            <!-- Nombre y Bio -->
                            <div class="mt-8 sm:mt-0 sm:ml-6 flex-1 text-center sm:text-left">
                                <h1 class="text-2xl font-bold text-gray-900">{{ user?.name || 'Tu Nombre' }}</h1>
                                <p class="text-gray-600">{{ user?.email || 'tu@email.com' }}</p>
                                <p class="mt-2 text-sm text-gray-500 max-w-2xl">
                                    Desarrollador apasionado por crear experiencias increíbles. Amante de la tecnología y el diseño.
                                </p>
                            </div>

                            <!-- Botón Editar -->
                            <div class="mt-4 sm:mt-0">
                                <button 
                                    @click="() => {}"
                                    class="px-6 py-2 bg-[#8EB6A5] text-white rounded-lg hover:bg-black transition font-medium shadow-sm shadow-[#8EB6A5]/20"
                                >
                                    Editar Perfil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Columna Izquierda -->
                    <div class="lg:col-span-1 space-y-6">

                        <!-- Estadísticas -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h2>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Reservas</p>
                                            <p class="text-xs text-gray-500">Totales</p>
                                        </div>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">{{ stats.totalReservations }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Completadas</p>
                                            <p class="text-xs text-gray-500">Este mes</p>
                                        </div>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">{{ stats.completedReservations }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Puntos</p>
                                            <p class="text-xs text-gray-500">Acumulados</p>
                                        </div>
                                    </div>
                                    <span class="text-xl font-bold text-gray-900">{{ stats.points }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información Personal -->
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-50">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Información Personal</h2>
                            <div class="space-y-3">
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ user?.email || 'tu@email.com' }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-gray-600">+34 600 000 000</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="text-gray-600">Madrid, España</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-600">Miembro desde {{ formatMemberSince(user?.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mis Intereses Section -->
                        <div v-if="user?.interests?.length" class="bg-white rounded-2xl shadow-sm p-6 border border-gray-50">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Mis Intereses</h2>
                            <div class="flex flex-wrap gap-2">
                                <div 
                                    v-for="interest in user.interests" 
                                    :key="interest.id"
                                    class="inline-flex items-center gap-2 bg-gray-50 border border-gray-100 px-3 py-1.5 rounded-xl hover:border-[#8EB6A5]/50 transition-colors"
                                >
                                    <span>{{ interest.icon }}</span>
                                    <span class="text-sm font-medium text-gray-700">{{ interest.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Formulario de Edición -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Editar Información</h2>

                            <form @submit.prevent="submitForm" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre Completo
                                        </label>
                                        <input 
                                            type="text"
                                            v-model="form.name"
                                            :placeholder="user?.name"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Email
                                        </label>
                                        <input 
                                            type="email"
                                            v-model="form.email"
                                            :placeholder="user?.email"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Teléfono
                                        </label>
                                        <input 
                                            type="tel"
                                            v-model="form.phone"
                                            placeholder="+34 600 000 000"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Ciudad
                                        </label>
                                        <input 
                                            type="text"
                                            v-model="form.city"
                                            placeholder="Madrid"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Biografía
                                    </label>
                                    <textarea 
                                        rows="4"
                                        v-model="form.bio"
                                        placeholder="Cuéntanos algo sobre ti..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                                    ></textarea>
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button 
                                        type="button" 
                                        @click="form.reset()"
                                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium"
                                    >
                                        Cancelar
                                    </button>
                                    <button 
                                        type="submit" 
                                        :disabled="form.processing"
                                        class="px-6 py-2 bg-[#8EB6A5] text-white rounded-lg hover:bg-black transition font-medium shadow-sm shadow-[#8EB6A5]/20 disabled:opacity-50"
                                    >
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Actividad Reciente -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h2>
                            <div class="space-y-4">
                                <div 
                                    v-for="activity in activities" 
                                    :key="activity.id"
                                    class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0"
                                >
                                    <div 
                                        :class="[
                                            'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0',
                                            activity.color === 'blue' && 'bg-blue-100',
                                            activity.color === 'green' && 'bg-green-100',
                                            activity.color === 'purple' && 'bg-purple-100'
                                        ]"
                                    >
                                        <svg 
                                            v-if="activity.icon === 'check'"
                                            :class="[
                                                'w-5 h-5',
                                                activity.color === 'blue' && 'text-blue-600',
                                                activity.color === 'green' && 'text-green-600',
                                                activity.color === 'purple' && 'text-purple-600'
                                            ]"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <svg 
                                            v-if="activity.icon === 'star'"
                                            :class="[
                                                'w-5 h-5',
                                                activity.color === 'blue' && 'text-blue-600',
                                                activity.color === 'green' && 'text-green-600',
                                                activity.color === 'purple' && 'text-purple-600'
                                            ]"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        <svg 
                                            v-if="activity.icon === 'user'"
                                            :class="[
                                                'w-5 h-5',
                                                activity.color === 'blue' && 'text-blue-600',
                                                activity.color === 'green' && 'text-green-600',
                                                activity.color === 'purple' && 'text-purple-600'
                                            ]"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900 font-medium">{{ activity.title }}</p>
                                        <p class="text-sm text-gray-600">{{ activity.description }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ activity.time }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
