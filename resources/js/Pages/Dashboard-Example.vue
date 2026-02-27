<script setup>
import { usePermissions } from '@/Composables/usePermissions';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const { hasRole, can, isAdmin } = usePermissions();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="mb-12">
                            <h3 class="text-3xl font-bold text-gray-900 leading-tight tracking-tighter">
                                ¡Hola de nuevo, <span class="text-[#8EB6A5]">{{ $page.props.auth.user.name }}</span>!
                            </h3>
                            <p class="text-gray-500 mt-2">Este es tu resumen de hoy en ProReserve.</p>
                        </div>
                        
                        <!-- Intereses del Usuario -->
                        <div v-if="$page.props.auth.user.interests?.length" class="mb-10">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Tus gustos</h4>
                            <div class="flex flex-wrap gap-3">
                                <div 
                                    v-for="interest in $page.props.auth.user.interests" 
                                    :key="interest.id"
                                    class="inline-flex items-center gap-2 bg-white border border-gray-100 px-4 py-2 rounded-2xl shadow-sm hover:border-[#8EB6A5]/30 transition-all"
                                >
                                    <span class="text-2xl">{{ interest.icon }}</span>
                                    <span class="font-bold text-gray-700">{{ interest.name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Contenido solo para admin -->
                            <div v-if="isAdmin()" class="p-8 bg-gray-900 border border-gray-800 rounded-[2rem] shadow-xl shadow-gray-200">
                                <h4 class="font-bold text-white text-xl mb-2 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-[#8EB6A5] rounded-lg flex items-center justify-center text-sm">🔐</span>
                                    Panel Admin
                                </h4>
                                <p class="text-gray-400 mb-6">Gestión completa de la plataforma.</p>
                                <div class="flex flex-wrap gap-3">
                                    <Link 
                                        href="/admin/usuarios" 
                                        class="px-5 py-2.5 bg-[#8EB6A5] text-white font-bold rounded-xl hover:bg-white hover:text-gray-900 transition-all"
                                    >
                                        Usuarios
                                    </Link>
                                    <Link 
                                        href="/admin/servicios" 
                                        class="px-5 py-2.5 border border-gray-700 text-gray-300 font-bold rounded-xl hover:border-[#8EB6A5] hover:text-[#8EB6A5] transition-all"
                                    >
                                        Servicios
                                    </Link>
                                </div>
                            </div>

                            <!-- Contenido para clientes/usuarios -->
                            <div v-if="can('crear reserva')" class="p-8 bg-white border border-gray-100 rounded-[2rem] shadow-xl shadow-gray-100/50">
                                <h4 class="font-bold text-gray-900 text-xl mb-2 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-[#8EB6A5]/10 rounded-lg flex items-center justify-center text-sm">📅</span>
                                    Reservas
                                </h4>
                                <p class="text-gray-500 mb-6">Gestiona tus planes y citas.</p>
                                <div class="flex flex-wrap gap-3">
                                    <Link 
                                        href="/reservas/create" 
                                        class="px-5 py-2.5 bg-gray-900 text-white font-bold rounded-xl hover:bg-black shadow-lg shadow-gray-200 transition-all"
                                    >
                                        Nueva Reserva
                                    </Link>
                                    <Link 
                                        href="/reservas" 
                                        class="px-5 py-2.5 border border-gray-200 text-gray-600 font-bold rounded-xl hover:border-[#8EB6A5] hover:text-[#8EB6A5] transition-all"
                                    >
                                        Mis Reservas
                                    </Link>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
