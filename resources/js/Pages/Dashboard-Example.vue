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
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">¡Bienvenido al Sistema de Reservas ProReserve!</h3>
                        
                        <!-- Contenido solo para admin -->
                        <div v-if="isAdmin()" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-semibold text-blue-900 mb-2">🔐 Panel de Administrador</h4>
                            <p class="text-blue-700 mb-3">Tienes acceso completo al sistema</p>
                            <div class="space-x-2">
                                <Link 
                                    href="/admin/usuarios" 
                                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                >
                                    Administrar Usuarios
                                </Link>
                                <Link 
                                    href="/admin/servicios" 
                                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                >
                                    Administrar Servicios
                                </Link>
                            </div>
                        </div>

                        <!-- Contenido para usuarios con permiso específico -->
                        <div v-if="can('crear reserva')" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="font-semibold text-green-900 mb-2">📅 Reservas</h4>
                            <p class="text-green-700 mb-3">Puedes crear y gestionar tus reservas</p>
                            <div class="space-x-2">
                                <Link 
                                    href="/reservas/create" 
                                    class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                                >
                                    Nueva Reserva
                                </Link>
                                <Link 
                                    href="/reservas" 
                                    class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                                >
                                    Mis Reservas
                                </Link>
                            </div>
                        </div>

                        <!-- Contenido general -->
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <p class="text-gray-700">
                                Sistema de gestión de reservas con control de acceso basado en roles.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
