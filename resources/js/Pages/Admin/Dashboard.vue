<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    LayoutDashboard,
    Users,
    Store,
    CalendarCheck,
    Settings,
    LogOut,
    Search,
    Plus,
    MoreVertical,
    CheckCircle2,
    Clock,
    ChevronRight,
    MapPin,
    UserCircle,
    Bell,
    Trash2,
    Download,
    Menu,
    X,
} from "lucide-vue-next";
import UserModal from "@/Components/Admin/UserModal.vue";
import LocalFormModal from "@/Components/Admin/LocalFormModal.vue";
import ReservationModal from "@/Components/Admin/ReservationModal.vue";
import ManualReservationModal from "@/Components/Admin/ManualReservationModal.vue";
import { usePermissions } from "@/Composables/usePermissions";

const { isAdmin } = usePermissions();

const props = defineProps({
    users: Array,
    restaurants: Array,
    sportCenters: Array,
    healthCenters: Array,
    beautyCenters: Array,
    leisureCenters: Array,
    reservations: Array,
});

const activeTab = ref("panel"); // panel, usuarios, locales, reservas, solicitudes
const searchQuery = ref("");
const showUserModal = ref(false);
const selectedUser = ref(null);
const showLocalModal = ref(false);
const selectedLocal = ref(null);
const showReservationModal = ref(false);
const selectedReservation = ref(null);
const showManualReservationModal = ref(false);
const sidebarOpen = ref(false);

const openManualReservationModal = () => {
    showManualReservationModal.value = true;
};

const openEditReservationModal = (res) => {
    selectedReservation.value = res;
    showReservationModal.value = true;
};

const getInitials = (name) => {
    if (!name) return "PR";
    const parts = name.trim().split(/\s+/);
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return parts[0].substring(0, 2).toUpperCase();
};

const openCreateModal = () => {
    selectedUser.value = null;
    showUserModal.value = true;
};

const openEditModal = (user) => {
    selectedUser.value = user;
    showUserModal.value = true;
};

const deleteUser = (user) => {
    if (
        confirm(
            `¿Estás seguro de que quieres eliminar a ${user.name}? Esta acción no se puede deshacer.`,
        )
    ) {
        router.delete(route("admin.users.destroy", user.id));
    }
};

const openCreateLocalModal = () => {
    router.visit(route('locales.create'));
};

const openEditLocalModal = (local) => {
    selectedLocal.value = local;
    showLocalModal.value = true;
};

const getLocalImage = (local) => {
    if (local.image) return local.image;

    const defaults = {
        restaurant: "/images/gastronomia.avif",
        sport_center: "/images/deporte.avif",
        health_center: "/images/salud.avif",
        beauty_center: "/images/beauty-wellness.avif",
        leisure_center: "/images/ocio.avif",
    };

    return defaults[local.type] || "/images/salud.avif";
};

const deleteLocal = (local) => {
    if (
        confirm(
            `¿Estás seguro de que quieres eliminar el local "${local.name}"? Se borrarán todos sus datos asociados.`,
        )
    ) {
        router.delete(
            route("admin.locales.destroy", { id: local.id, type: local.type }),
        );
    }
};

// Combinar todos los locales en uno solo para el listado
const allLocals = computed(() => {
    return [
        ...props.restaurants.map((l) => ({
            ...l,
            type: "restaurant",
            category: "Gastronomía",
            color: "text-orange-500",
            bg: "bg-orange-50",
        })),
        ...props.sportCenters.map((l) => ({
            ...l,
            type: "sport_center",
            category: "Deportes",
            color: "text-blue-500",
            bg: "bg-blue-50",
        })),
        ...props.healthCenters.map((l) => ({
            ...l,
            type: "health_center",
            category: "Salud",
            color: "text-teal-500",
            bg: "bg-teal-50",
        })),
        ...props.beautyCenters.map((l) => ({
            ...l,
            type: "beauty_center",
            category: "Imagen",
            color: "text-rose-500",
            bg: "bg-rose-50",
        })),
        ...props.leisureCenters.map((l) => ({
            ...l,
            type: "leisure_center",
            category: "Ocio",
            color: "text-indigo-500",
            bg: "bg-indigo-50",
        })),
    ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const filteredUsers = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return props.users;
    return props.users.filter(
        (u) =>
            (u.name || "").toLowerCase().includes(query) ||
            (u.email || "").toLowerCase().includes(query),
    );
});

const filteredLocals = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) {
        return allLocals.value.filter((l) => l.status !== "pending");
    }
    return allLocals.value.filter(
        (l) =>
            l.status !== "pending" &&
            ((l.name || "").toLowerCase().includes(query) ||
                (l.city || "").toLowerCase().includes(query) ||
                (l.category || "").toLowerCase().includes(query)),
    );
});

const pendingLocals = computed(() => {
    return allLocals.value.filter((l) => l.status === "pending");
});

const filteredPendingLocals = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return pendingLocals.value;
    return pendingLocals.value.filter(
        (l) =>
            (l.name || "").toLowerCase().includes(query) ||
            (l.city || "").toLowerCase().includes(query) ||
            (l.category || "").toLowerCase().includes(query),
    );
});

const filteredReservations = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    if (!query) return props.reservations;
    return props.reservations.filter((res) => {
        const clientName = (
            res.user?.name ||
            res.customer_name ||
            ""
        ).toLowerCase();
        const localName = (res.reservable?.name || "").toLowerCase();
        const serviceName = (res.service?.name || "").toLowerCase();
        const date = (res.reservation_date || "").toLowerCase();
        const status = (res.status || "").toLowerCase();

        return (
            clientName.includes(query) ||
            localName.includes(query) ||
            serviceName.includes(query) ||
            date.includes(query) ||
            status.includes(query)
        );
    });
});

watch(activeTab, () => {
    searchQuery.value = "";
});

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <!-- Overlay for mobile sidebar -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden animate-in fade-in duration-300"
        ></div>

        <div
            class="flex bg-[#F8F9FB] font-sans text-gray-900 overflow-hidden relative"
            style="height: calc(100vh - 80px)"
        >
            <!-- SIDEBAR -->
            <aside
                :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full']"
                class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 flex flex-col shrink-0 transition-transform duration-300 ease-in-out md:translate-x-0"
            >
                <div class="p-8 flex-1 overflow-y-auto">
                    <div class="mb-8 flex items-center justify-between">
                        <p
                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4"
                        >
                            Administración
                        </p>
                        <button
                            @click="sidebarOpen = false"
                            class="md:hidden p-2 hover:bg-gray-100 rounded-xl transition-colors"
                        >
                            <X class="w-5 h-5 text-gray-500" />
                        </button>
                    </div>

                    <nav class="space-y-2">
                        <button
                            @click="activeTab = 'panel'"
                            :class="[
                                activeTab === 'panel'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                            class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                        >
                            <LayoutDashboard class="w-5 h-5" />
                            <span class="font-bold text-sm">Panel</span>
                        </button>

                        <button
                            @click="activeTab = 'reservas'"
                            :class="[
                                activeTab === 'reservas'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                            class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                        >
                            <CalendarCheck class="w-5 h-5" />
                            <span class="font-bold text-sm">Reservas</span>
                        </button>

                        <button
                            v-if="isAdmin()"
                            @click="activeTab = 'usuarios'"
                            :class="[
                                activeTab === 'usuarios'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                            class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                        >
                            <Users class="w-5 h-5" />
                            <span class="font-bold text-sm">Usuarios</span>
                        </button>

                        <button
                            @click="activeTab = 'locales'"
                            :class="[
                                activeTab === 'locales'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                            class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                        >
                            <Store class="w-5 h-5" />
                            <span class="font-bold text-sm">Locales</span>
                        </button>

                        <button
                            v-if="isAdmin()"
                            @click="activeTab = 'solicitudes'"
                            :class="[
                                activeTab === 'solicitudes'
                                    ? 'bg-indigo-50 text-indigo-600'
                                    : 'text-gray-500 hover:bg-gray-50',
                            ]"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-2xl transition-all duration-300 group"
                        >
                            <div class="flex items-center gap-4">
                                <Bell class="w-5 h-5" />
                                <span class="font-bold text-sm"
                                    >Solicitudes</span
                                >
                            </div>
                            <span
                                v-if="pendingLocals.length"
                                class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-full min-w-[20px] text-center"
                            >
                                {{ pendingLocals.length }}
                            </span>
                        </button>
                    </nav>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="flex-1 flex flex-col overflow-hidden w-full">
                <!-- HEADER -->
                <header
                    class="min-h-24 py-4 md:py-0 bg-white/80 backdrop-blur-md border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between px-6 md:px-10 shrink-0 gap-4"
                >
                    <div class="flex items-center gap-3">
                        <!-- Hamburger Menu Button -->
                        <button
                            @click="sidebarOpen = true"
                            class="md:hidden p-2 hover:bg-gray-100 rounded-xl transition-colors shrink-0"
                        >
                            <Menu class="w-6 h-6 text-gray-700" />
                        </button>
                        <div>
                            <h2
                                class="text-xl md:text-2xl font-black tracking-tighter capitalize"
                            >
                                {{ activeTab }}
                            </h2>
                            <p class="text-[10px] md:text-xs font-bold text-gray-400 mt-0.5">
                                Gestión centralizada de ProReserve
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto">
                        <!-- Search Bar -->
                        <div
                            v-if="activeTab !== 'panel'"
                            class="relative group w-full sm:w-72"
                        >
                            <Search
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors"
                            />
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Buscar en el panel..."
                                class="bg-gray-50 border-none rounded-2xl pl-12 pr-6 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 w-full transition-all"
                            />
                        </div>

                        <!-- Buttons Container -->
                        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                            <button
                                v-if="
                                    activeTab === 'locales' || activeTab === 'panel'
                                "
                                @click="openCreateLocalModal"
                                class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 shadow-lg shadow-indigo-100 hover:scale-105 active:scale-95 transition-all whitespace-nowrap"
                            >
                                <Plus class="w-4 h-4" />
                                Añadir Local
                            </button>

                            <a
                                v-if="activeTab === 'usuarios' && isAdmin()"
                                :href="route('admin.export.users')"
                                target="_blank"
                                class="bg-white border border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap shadow-sm"
                            >
                                <Download class="w-4 h-4 text-green-500" />
                                Exportar PDF
                            </a>

                            <a
                                v-if="activeTab === 'locales' && isAdmin()"
                                :href="route('admin.export.locals')"
                                target="_blank"
                                class="bg-white border border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap shadow-sm"
                            >
                                <Download class="w-4 h-4 text-green-500" />
                                Exportar PDF
                            </a>

                            <a
                                v-if="activeTab === 'reservas'"
                                :href="route('admin.export.reservations')"
                                target="_blank"
                                class="bg-white border border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap shadow-sm"
                            >
                                <Download class="w-4 h-4 text-green-500" />
                                Exportar PDF
                            </a>

                            <button
                                v-if="
                                    (activeTab === 'usuarios' ||
                                        activeTab === 'panel') &&
                                    isAdmin()
                                "
                                @click="openCreateModal"
                                class="bg-white border border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap shadow-sm"
                            >
                                <Plus class="w-4 h-4 text-indigo-500" />
                                Nuevo Usuario
                            </button>

                            <button
                                v-if="
                                    activeTab === 'reservas' ||
                                    activeTab === 'panel'
                                "
                                @click="openManualReservationModal"
                                class="bg-white border border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap"
                            >
                                <CalendarCheck class="w-4 h-4 text-orange-500" />
                                Crear Reserva
                            </button>
                        </div>
                    </div>
                </header>

                <!-- SCROLLABLE CONTENT -->
                <div class="flex-1 overflow-y-auto p-4 md:p-10">
                    <!-- TAB: PANEL (RESUMEN) -->
                    <div
                        v-if="activeTab === 'panel'"
                        class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500"
                    >
                        <!-- Stats Grid -->
                        <div
                            :class="
                                isAdmin()
                                    ? 'grid grid-cols-1 md:grid-cols-3 gap-6'
                                    : 'grid grid-cols-1 md:grid-cols-2 gap-6'
                            "
                        >
                            <div
                                v-if="isAdmin()"
                                class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm"
                            >
                                <p
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4"
                                >
                                    Usuarios
                                </p>
                                <div
                                    class="flex items-baseline justify-between"
                                >
                                    <div
                                        class="text-4xl font-black text-gray-900"
                                    >
                                        {{ users.length }}
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm"
                            >
                                <p
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4"
                                >
                                    Locales Totales
                                </p>
                                <div class="flex items-end justify-between">
                                    <span
                                        class="text-4xl font-black tracking-tighter"
                                        >{{ allLocals.length }}</span
                                    >
                                    <span
                                        class="text-indigo-500 font-bold text-xs"
                                        >Activos</span
                                    >
                                </div>
                            </div>
                            <div
                                class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm"
                            >
                                <p
                                    class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4"
                                >
                                    Reservas
                                </p>
                                <div class="flex items-end justify-between">
                                    <span
                                        class="text-4xl font-black tracking-tighter"
                                        >{{ reservations.length }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div
                            :class="
                                isAdmin()
                                    ? 'grid grid-cols-1 md:grid-cols-3 gap-8'
                                    : 'grid grid-cols-1 md:grid-cols-2 gap-8'
                            "
                        >
                            <button
                                @click="openCreateLocalModal"
                                class="group bg-white p-2 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500 w-full text-left"
                            >
                                <div class="flex items-center gap-6 p-6">
                                    <div
                                        class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500"
                                    >
                                        <Store class="w-8 h-8" />
                                    </div>
                                    <div>
                                        <h4
                                            class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors"
                                        >
                                            Añadir Local
                                        </h4>
                                        <p
                                            class="text-xs text-gray-400 font-bold"
                                        >
                                            Registrar nuevo negocio
                                        </p>
                                    </div>
                                </div>
                            </button>

                            <button
                                v-if="isAdmin()"
                                @click="openCreateModal"
                                class="group bg-white p-2 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500 w-full text-left"
                            >
                                <div class="flex items-center gap-6 p-6">
                                    <div
                                        class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500"
                                    >
                                        <Users class="w-8 h-8" />
                                    </div>
                                    <div>
                                        <h4
                                            class="font-black text-gray-900 group-hover:text-emerald-600 transition-colors"
                                        >
                                            Nuevo Usuario
                                        </h4>
                                        <p
                                            class="text-xs text-gray-400 font-bold"
                                        >
                                            Dar de alta cliente
                                        </p>
                                    </div>
                                </div>
                            </button>

                            <button
                                @click="openManualReservationModal"
                                class="group bg-white p-2 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-500 text-left w-full"
                            >
                                <div class="flex items-center gap-6 p-6">
                                    <div
                                        class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500"
                                    >
                                        <CalendarCheck class="w-8 h-8" />
                                    </div>
                                    <div>
                                        <h4
                                            class="font-black text-gray-900 group-hover:text-orange-600 transition-colors"
                                        >
                                            Crear Reserva
                                        </h4>
                                        <p
                                            class="text-xs text-gray-400 font-bold"
                                        >
                                            Nueva cita manual
                                        </p>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <!-- Recent Activity -->
                        <div
                            class="bg-white rounded-2xl shadow-sm overflow-hidden"
                        >
                            <div
                                class="p-8 border-b border-gray-50 flex items-center justify-between"
                            >
                                <h3 class="font-black text-lg">
                                    Últimas Reservas
                                </h3>
                                <button
                                    @click="activeTab = 'reservas'"
                                    class="text-indigo-600 font-bold text-sm hover:underline"
                                >
                                    Ver todas
                                </button>
                            </div>
                            <div class="divide-y divide-gray-50">
                                <div
                                    v-for="res in reservations.slice(0, 5)"
                                    :key="res.id"
                                    @click="openEditReservationModal(res)"
                                    class="p-6 flex items-center gap-6 hover:bg-gray-50/50 transition-colors cursor-pointer group"
                                >
                                    <div
                                        class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 italic font-black text-xs group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors"
                                    >
                                        {{
                                            getInitials(
                                                res.user
                                                    ? res.user.name
                                                    : res.customer_name,
                                            )
                                        }}
                                    </div>
                                    <div class="flex-1">
                                        <p
                                            class="font-black text-gray-900 text-sm"
                                        >
                                            {{
                                                res.user
                                                    ? res.user.name
                                                    : res.customer_name
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ res.reservable.name }}
                                            <template v-if="res.service">
                                                •
                                                {{ res.service.name }}</template
                                            >
                                            <template v-else-if="res.resource">
                                                •
                                                {{
                                                    res.resource.name
                                                }}</template
                                            >
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="font-black text-gray-900 text-sm"
                                        >
                                            {{ res.reservation_time }}h
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ res.reservation_date }}
                                        </p>
                                    </div>
                                    <div
                                        :class="{
                                            'bg-emerald-50 text-emerald-600':
                                                res.status === 'confirmed' ||
                                                res.status === 'completed',
                                            'bg-amber-50 text-amber-600':
                                                res.status === 'pending',
                                            'bg-red-50 text-red-600':
                                                res.status === 'cancelled',
                                        }"
                                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest min-w-[100px] text-center"
                                    >
                                        {{ $t("status." + res.status) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: USUARIOS -->
                    <div
                        v-if="activeTab === 'usuarios'"
                        class="animate-in fade-in slide-in-from-bottom-4 duration-500"
                    >
                        <div
                            v-if="filteredUsers.length === 0"
                            class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm"
                        >
                            <h4 class="font-black text-xl text-gray-900 mb-2">
                                Sin resultados
                            </h4>
                            <p class="text-gray-400 text-sm font-bold">
                                No se encontraron usuarios que coincidan con tu
                                búsqueda.
                            </p>
                        </div>
                        <div
                            v-else
                            class="bg-white rounded-2xl shadow-sm overflow-hidden overflow-x-auto"
                        >
                            <table class="w-full text-left border-collapse min-w-[700px]">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Usuario
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Rol
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right"
                                        >
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr
                                        v-for="user in filteredUsers"
                                        :key="user.id"
                                        @click="openEditModal(user)"
                                        class="hover:bg-gray-50/30 transition-colors group cursor-pointer"
                                    >
                                        <td class="px-8 py-6">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xs font-black uppercase tracking-wider shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300"
                                                >
                                                    {{ getInitials(user.name) }}
                                                </div>
                                                <span
                                                    class="font-black text-gray-900 text-sm group-hover:text-indigo-600 transition-colors"
                                                    >{{ user.name }}</span
                                                >
                                            </div>
                                        </td>
                                        <td
                                            class="px-8 py-6 text-sm text-gray-500 font-medium"
                                        >
                                            {{ user.email }}
                                        </td>
                                        <td class="px-8 py-6">
                                            <span
                                                v-for="role in user.roles"
                                                :key="role.id"
                                                class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest"
                                            >
                                                {{ role.name }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-8 py-6 text-right"
                                            @click.stop
                                        >
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <button
                                                    @click="openEditModal(user)"
                                                    class="p-2 text-gray-400 hover:text-indigo-600 transition-colors bg-gray-50 hover:bg-indigo-50 rounded-xl"
                                                    title="Editar usuario"
                                                >
                                                    <MoreVertical
                                                        class="w-5 h-5"
                                                    />
                                                </button>
                                                <button
                                                    @click="deleteUser(user)"
                                                    class="p-2 text-gray-400 hover:text-red-600 transition-colors bg-gray-50 hover:bg-red-50 rounded-xl"
                                                    title="Eliminar usuario"
                                                >
                                                    <Trash2 class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB: LOCALES -->
                    <div
                        v-if="activeTab === 'locales'"
                        class="animate-in fade-in slide-in-from-bottom-4 duration-500"
                    >
                        <div
                            v-if="filteredLocals.length === 0"
                            class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm"
                        >
                            <h4 class="font-black text-xl text-gray-900 mb-2">
                                Sin resultados
                            </h4>
                            <p class="text-gray-400 text-sm font-bold">
                                No se encontraron locales que coincidan con tu
                                búsqueda.
                            </p>
                        </div>
                        <div
                            v-else
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                        >
                            <div
                                v-for="local in filteredLocals"
                                :key="local.id"
                                @click="openEditLocalModal(local)"
                                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500 cursor-pointer"
                            >
                                <div
                                    class="h-48 bg-gray-100 relative overflow-hidden"
                                >
                                    <img
                                        :src="getLocalImage(local)"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    />
                                    <div class="absolute top-4 left-4">
                                        <span
                                            :class="[local.bg, local.color]"
                                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-black/5 backdrop-blur-md"
                                        >
                                            {{ local.category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <div
                                        class="flex justify-between items-start mb-4"
                                    >
                                        <h4
                                            class="font-black text-xl text-gray-900 leading-tight"
                                        >
                                            {{ local.name }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-1 bg-yellow-50 text-yellow-600 px-2 py-1 rounded-lg text-xs font-black"
                                        >
                                            ⭐
                                            {{ local.rating_average || "5.0" }}
                                        </div>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 text-gray-400 text-xs font-bold mb-6"
                                    >
                                        <MapPin class="w-4 h-4" />
                                        {{ local.city }}
                                    </div>
                                    <div
                                        class="flex items-center justify-between pt-6 border-t border-gray-50"
                                    >
                                        <div
                                            v-if="local.status === 'pending'"
                                            class="flex gap-2 w-full"
                                        >
                                            <button
                                                @click.stop="
                                                    router.post(
                                                        route(
                                                            'admin.locales.approve',
                                                            {
                                                                id: local.id,
                                                                type: local.type,
                                                            },
                                                        ),
                                                    )
                                                "
                                                class="flex-1 bg-emerald-600 text-white py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-colors"
                                            >
                                                Aprobar
                                            </button>
                                            <button
                                                @click.stop="
                                                    router.post(
                                                        route(
                                                            'admin.locales.reject',
                                                            {
                                                                id: local.id,
                                                                type: local.type,
                                                            },
                                                        ),
                                                    )
                                                "
                                                class="flex-1 border border-red-200 text-red-500 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-colors"
                                            >
                                                Rechazar
                                            </button>
                                        </div>
                                        <div v-else class="flex gap-2 w-full">
                                            <button
                                                @click.stop="
                                                    openEditLocalModal(local)
                                                "
                                                class="flex-1 bg-gray-50 text-gray-900 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-gray-100"
                                            >
                                                Editar Local
                                            </button>
                                            <button
                                                @click.stop="deleteLocal(local)"
                                                class="p-3 bg-gray-50 text-gray-400 rounded-xl hover:bg-red-50 hover:text-red-600 transition-all border border-gray-100"
                                                title="Eliminar Local"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: SOLICITUDES -->
                    <div
                        v-if="activeTab === 'solicitudes'"
                        class="animate-in fade-in slide-in-from-bottom-4 duration-500"
                    >
                        <div
                            v-if="pendingLocals.length === 0"
                            class="flex flex-col items-center justify-center py-20 text-center"
                        >
                            <div
                                class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-6"
                            >
                                <CheckCircle2 class="w-10 h-10" />
                            </div>
                            <h4 class="font-black text-xl text-gray-900 mb-2">
                                ¡Todo al día!
                            </h4>
                            <p class="text-gray-400 text-sm font-bold">
                                No hay solicitudes de locales pendientes de
                                revisión.
                            </p>
                        </div>
                        <div
                            v-else-if="filteredPendingLocals.length === 0"
                            class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm"
                        >
                            <h4 class="font-black text-xl text-gray-900 mb-2">
                                Sin resultados
                            </h4>
                            <p class="text-gray-400 text-sm font-bold">
                                No se encontraron solicitudes que coincidan con
                                tu búsqueda.
                            </p>
                        </div>
                        <div
                            v-else
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                        >
                            <div
                                v-for="local in filteredPendingLocals"
                                :key="local.id"
                                @click="openEditLocalModal(local)"
                                class="bg-white rounded-2xl border border-indigo-100 shadow-xl shadow-indigo-50/50 overflow-hidden group transition-all duration-500 relative cursor-pointer hover:shadow-2xl hover:scale-[1.02]"
                            >
                                <div class="absolute top-4 right-4 z-10">
                                    <span
                                        class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest"
                                        >Nueva</span
                                    >
                                </div>
                                <div
                                    class="h-48 bg-gray-100 relative overflow-hidden"
                                >
                                    <img
                                        :src="getLocalImage(local)"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    />
                                    <div class="absolute top-4 left-4">
                                        <span
                                            :class="[local.bg, local.color]"
                                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-black/5 backdrop-blur-md"
                                        >
                                            {{ local.category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <h4
                                        class="font-black text-xl text-gray-900 leading-tight mb-2"
                                    >
                                        {{ local.name }}
                                    </h4>
                                    <div
                                        class="flex items-center gap-2 text-gray-400 text-xs font-bold mb-6"
                                    >
                                        <MapPin class="w-4 h-4" />
                                        {{ local.city }}
                                    </div>
                                    <div
                                        class="flex gap-2 pt-6 border-t border-gray-50"
                                    >
                                        <button
                                            @click.stop="
                                                router.post(
                                                    route(
                                                        'admin.locales.approve',
                                                        {
                                                            id: local.id,
                                                            type: local.type,
                                                        },
                                                    ),
                                                )
                                            "
                                            class="flex-1 bg-emerald-600 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100"
                                        >
                                            Aprobar
                                        </button>
                                        <button
                                            @click.stop="
                                                router.post(
                                                    route(
                                                        'admin.locales.reject',
                                                        {
                                                            id: local.id,
                                                            type: local.type,
                                                        },
                                                    ),
                                                )
                                            "
                                            class="flex-1 border border-red-100 text-red-500 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-colors"
                                        >
                                            Rechazar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="activeTab === 'reservas'"
                        class="animate-in fade-in slide-in-from-bottom-4 duration-500"
                    >
                        <div
                            v-if="filteredReservations.length === 0"
                            class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm"
                        >
                            <h4 class="font-black text-xl text-gray-900 mb-2">
                                Sin resultados
                            </h4>
                            <p class="text-gray-400 text-sm font-bold">
                                No se encontraron reservas que coincidan con tu
                                búsqueda.
                            </p>
                        </div>
                        <div
                            v-else
                            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden overflow-x-auto"
                        >
                            <table class="w-full text-left border-collapse min-w-[800px]">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Usuario
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Establecimiento
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                        >
                                            Fecha/Hora
                                        </th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right"
                                        >
                                            Estado
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr
                                        v-for="res in filteredReservations"
                                        :key="res.id"
                                        @click="openEditReservationModal(res)"
                                        class="hover:bg-gray-50/30 transition-colors group cursor-pointer"
                                    >
                                        <td
                                            class="px-8 py-6 font-black text-gray-900 text-sm"
                                        >
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <div
                                                    class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xs font-black uppercase tracking-wider shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300"
                                                >
                                                    {{
                                                        getInitials(
                                                            res.user
                                                                ? res.user.name
                                                                : res.customer_name,
                                                        )
                                                    }}
                                                </div>
                                                <div>
                                                    <p
                                                        class="font-black text-gray-900 text-sm group-hover:text-indigo-600 transition-colors"
                                                    >
                                                        {{
                                                            res.user
                                                                ? res.user.name
                                                                : res.customer_name
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-gray-400 font-bold mt-0.5"
                                                        v-if="
                                                            res.user?.phone ||
                                                            res.customer_phone
                                                        "
                                                    >
                                                        {{
                                                            res.user
                                                                ? res.user.phone
                                                                : res.customer_phone
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"
                                                        v-else-if="res.user_id"
                                                    >
                                                        Usuario Registrado
                                                    </p>
                                                    <p
                                                        class="text-[10px] text-orange-500 font-black uppercase tracking-widest mt-0.5"
                                                        v-else
                                                    >
                                                        Cita Manual
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="px-8 py-6 text-sm text-gray-500 font-medium"
                                        >
                                            <p class="font-black text-gray-900">
                                                {{ res.reservable.name }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-400 font-bold mt-0.5"
                                            >
                                                {{
                                                    res.service
                                                        ? res.service.name
                                                        : "Espacio/Mesa"
                                                }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-sm font-black text-gray-900"
                                                    >{{
                                                        res.reservation_date
                                                    }}</span
                                                >
                                                <span
                                                    class="text-[10px] font-bold text-gray-400 mt-0.5"
                                                    >{{
                                                        res.reservation_time
                                                    }}h</span
                                                >
                                            </div>
                                        </td>
                                        <td
                                            class="px-8 py-6 text-right"
                                            @click.stop
                                        >
                                            <div
                                                class="flex items-center justify-end gap-4"
                                            >
                                                <span
                                                    :class="{
                                                        'bg-emerald-50 text-emerald-600':
                                                            res.status ===
                                                                'confirmed' ||
                                                            res.status ===
                                                                'completed',
                                                        'bg-amber-50 text-amber-600':
                                                            res.status ===
                                                            'pending',
                                                        'bg-red-50 text-red-600':
                                                            res.status ===
                                                            'cancelled',
                                                    }"
                                                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest min-w-[100px] text-center"
                                                >
                                                    {{
                                                        $t(
                                                            "status." +
                                                                res.status,
                                                        )
                                                    }}
                                                </span>
                                                <button
                                                    @click="
                                                        openEditReservationModal(
                                                            res,
                                                        )
                                                    "
                                                    class="p-2 text-gray-400 hover:text-indigo-600 transition-colors bg-gray-50 hover:bg-indigo-50 rounded-xl"
                                                >
                                                    <MoreVertical
                                                        class="w-5 h-5"
                                                    />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modales -->
        <UserModal
            :show="showUserModal"
            :user="selectedUser"
            @close="showUserModal = false"
        />
        <LocalFormModal
            :show="showLocalModal"
            :local="selectedLocal"
            @close="showLocalModal = false"
        />
        <ReservationModal
            :show="showReservationModal"
            :reservation="selectedReservation"
            @close="showReservationModal = false"
        />
        <ManualReservationModal
            :show="showManualReservationModal"
            :locals="allLocals"
            @close="showManualReservationModal = false"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
.tracking-tighter {
    letter-spacing: -0.05em;
}
.tracking-widest {
    letter-spacing: 0.1em;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}

/* Animations */
.animate-in {
    animation: animate-in 0.5s ease-out;
}
@keyframes animate-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
