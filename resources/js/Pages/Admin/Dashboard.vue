<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
    UserCircle
} from 'lucide-vue-next';

const props = defineProps({
    users: Array,
    restaurants: Array,
    sportCenters: Array,
    healthCenters: Array,
    beautyCenters: Array,
    leisureCenters: Array,
    reservations: Array
});

const activeTab = ref('panel'); // panel, usuarios, locales, reservas
const searchQuery = ref('');

// Combinar todos los locales en uno solo para el listado
const allLocals = computed(() => {
    return [
        ...props.restaurants.map(l => ({ ...l, category: 'Gastronomía', color: 'text-orange-500', bg: 'bg-orange-50' })),
        ...props.sportCenters.map(l => ({ ...l, category: 'Deportes', color: 'text-blue-500', bg: 'bg-blue-50' })),
        ...props.healthCenters.map(l => ({ ...l, category: 'Salud', color: 'text-teal-500', bg: 'bg-teal-50' })),
        ...props.beautyCenters.map(l => ({ ...l, category: 'Imagen', color: 'text-rose-500', bg: 'bg-rose-50' })),
        ...props.leisureCenters.map(l => ({ ...l, category: 'Ocio', color: 'text-indigo-500', bg: 'bg-indigo-50' })),
    ].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const filteredUsers = computed(() => {
    return props.users.filter(u => 
        u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
        u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const filteredLocals = computed(() => {
    return allLocals.value.filter(l => 
        l.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
        l.city.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <div class="flex bg-[#F8F9FB] font-sans text-gray-900 overflow-hidden" style="height: calc(100vh - 80px);">
            
            <!-- SIDEBAR -->
            <aside class="w-72 bg-white border-r border-gray-100 flex flex-col shrink-0">
                <div class="p-8 flex-1 overflow-y-auto">
                    <div class="mb-8">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-4">Administración</p>
                    </div>

                    <nav class="space-y-2">
                    <button 
                        @click="activeTab = 'panel'"
                        :class="[activeTab === 'panel' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50']"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                    >
                        <LayoutDashboard class="w-5 h-5" />
                        <span class="font-bold text-sm">Panel</span>
                    </button>

                    <button 
                        @click="activeTab = 'reservas'"
                        :class="[activeTab === 'reservas' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50']"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                    >
                        <CalendarCheck class="w-5 h-5" />
                        <span class="font-bold text-sm">Reservas</span>
                    </button>

                    <button 
                        @click="activeTab = 'usuarios'"
                        :class="[activeTab === 'usuarios' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50']"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                    >
                        <Users class="w-5 h-5" />
                        <span class="font-bold text-sm">Usuarios</span>
                    </button>

                    <button 
                        @click="activeTab = 'locales'"
                        :class="[activeTab === 'locales' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50']"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                    >
                        <Store class="w-5 h-5" />
                        <span class="font-bold text-sm">Locales</span>
                    </button>

                    <button 
                        @click="activeTab = 'config'"
                        :class="[activeTab === 'config' ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50']"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-300 group"
                    >
                        <Settings class="w-5 h-5" />
                        <span class="font-bold text-sm">Configuración</span>
                    </button>
                </nav>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- HEADER -->
            <header class="h-24 bg-white/80 backdrop-blur-md border-b border-gray-100 flex items-center justify-between px-10 shrink-0">
                <div>
                    <h2 class="text-2xl font-black tracking-tighter capitalize">{{ activeTab }}</h2>
                    <p class="text-xs font-bold text-gray-400 mt-0.5">Gestión centralizada de ProReserve</p>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Search Bar -->
                    <div class="relative group">
                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            placeholder="Buscar en el panel..." 
                            class="bg-gray-50 border-none rounded-2xl pl-12 pr-6 py-3 text-sm focus:ring-2 focus:ring-indigo-500/20 w-80 transition-all"
                        />
                    </div>

                    <Link 
                        v-if="activeTab === 'locales' || activeTab === 'panel'"
                        href="/locales/crear" 
                        class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 shadow-lg shadow-indigo-100 hover:scale-105 active:scale-95 transition-all whitespace-nowrap"
                    >
                        <Plus class="w-4 h-4" />
                        Añadir Local
                    </Link>

                    <Link 
                        v-if="activeTab === 'usuarios' || activeTab === 'panel'"
                        href="/register" 
                        class="bg-white border-2 border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap"
                    >
                        <Plus class="w-4 h-4 text-indigo-500" />
                        Nuevo Usuario
                    </Link>

                    <Link 
                        v-if="activeTab === 'reservas' || activeTab === 'panel'"
                        href="/servicios" 
                        class="bg-white border-2 border-gray-100 text-gray-900 px-6 py-3 rounded-2xl font-black text-sm flex items-center gap-2 hover:bg-gray-50 transition-all whitespace-nowrap"
                    >
                        <CalendarCheck class="w-4 h-4 text-orange-500" />
                        Crear Reserva
                    </Link>
                </div>
            </header>

            <!-- SCROLLABLE CONTENT -->
            <div class="flex-1 overflow-y-auto p-10">
                
                <!-- TAB: PANEL (RESUMEN) -->
                <div v-if="activeTab === 'panel'" class="space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Usuarios</p>
                            <div class="flex items-end justify-between">
                                <span class="text-4xl font-black tracking-tighter">{{ users.length }}</span>
                                <span class="text-green-500 font-bold text-xs">+12%</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Locales Totales</p>
                            <div class="flex items-end justify-between">
                                <span class="text-4xl font-black tracking-tighter">{{ allLocals.length }}</span>
                                <span class="text-indigo-500 font-bold text-xs">Activos</span>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Reservas</p>
                            <div class="flex items-end justify-between">
                                <span class="text-4xl font-black tracking-tighter">{{ reservations.length }}</span>
                                <span class="text-orange-500 font-bold text-xs">Hoy</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <Link href="/locales/crear" class="group bg-white p-2 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500">
                            <div class="flex items-center gap-6 p-6">
                                <div class="w-16 h-16 bg-indigo-50 rounded-[1.5rem] flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                                    <Store class="w-8 h-8" />
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors">Añadir Local</h4>
                                    <p class="text-xs text-gray-400 font-bold">Registrar nuevo negocio</p>
                                </div>
                            </div>
                        </Link>
                        
                        <Link href="/register" class="group bg-white p-2 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500">
                            <div class="flex items-center gap-6 p-6">
                                <div class="w-16 h-16 bg-emerald-50 rounded-[1.5rem] flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                                    <Users class="w-8 h-8" />
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 group-hover:text-emerald-600 transition-colors">Nuevo Usuario</h4>
                                    <p class="text-xs text-gray-400 font-bold">Dar de alta cliente</p>
                                </div>
                            </div>
                        </Link>

                        <Link href="/servicios" class="group bg-white p-2 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-500">
                            <div class="flex items-center gap-6 p-6">
                                <div class="w-16 h-16 bg-orange-50 rounded-[1.5rem] flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                                    <CalendarCheck class="w-8 h-8" />
                                </div>
                                <div>
                                    <h4 class="font-black text-gray-900 group-hover:text-orange-600 transition-colors">Crear Reserva</h4>
                                    <p class="text-xs text-gray-400 font-bold">Nueva cita manual</p>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="font-black text-lg">Últimas Reservas</h3>
                            <button @click="activeTab = 'reservas'" class="text-indigo-600 font-bold text-sm hover:underline">Ver todas</button>
                        </div>
                        <div class="divide-y divide-gray-50">
                            <div v-for="res in reservations.slice(0, 5)" :key="res.id" class="p-6 flex items-center gap-6 hover:bg-gray-50/50 transition-colors">
                                <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 italic font-black text-xs">PR</div>
                                <div class="flex-1">
                                    <p class="font-black text-gray-900 text-sm">{{ res.user.name }}</p>
                                    <p class="text-xs text-gray-400">{{ res.reservable.name }} • {{ res.service.name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-gray-900 text-sm">{{ res.time }}h</p>
                                    <p class="text-xs text-gray-400">{{ res.date }}</p>
                                </div>
                                <div class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest">
                                    Confirmado
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: USUARIOS -->
                <div v-if="activeTab === 'usuarios'" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Usuario</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Rol</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50/30 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-black text-gray-400">{{ user.name.charAt(0) }}</div>
                                            <span class="font-black text-gray-900 text-sm">{{ user.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm text-gray-500 font-medium">{{ user.email }}</td>
                                    <td class="px-8 py-6">
                                        <span v-for="role in user.roles" :key="role.id" class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                            {{ role.name }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="p-2 text-gray-400 hover:text-indigo-600 transition-colors"><MoreVertical class="w-5 h-5" /></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB: LOCALES -->
                <div v-if="activeTab === 'locales'" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="local in filteredLocals" :key="local.id" class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-500">
                            <div class="h-48 bg-gray-100 relative overflow-hidden">
                                <img :src="local.image || '/images/salud.avif'" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                <div class="absolute top-4 left-4">
                                    <span :class="[local.bg, local.color]" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-black/5 backdrop-blur-md">
                                        {{ local.category }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-8">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-black text-xl text-gray-900 leading-tight">{{ local.name }}</h4>
                                    <div class="flex items-center gap-1 bg-yellow-50 text-yellow-600 px-2 py-1 rounded-lg text-xs font-black">
                                        ⭐ {{ local.rating_average || '5.0' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400 text-xs font-bold mb-6">
                                    <MapPin class="w-4 h-4" />
                                    {{ local.city }}
                                </div>
                                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                    <div class="flex -space-x-2">
                                        <div v-for="i in 3" :key="i" class="w-8 h-8 rounded-full border-2 border-white bg-gray-200"></div>
                                        <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-50 text-indigo-600 flex items-center justify-center text-[10px] font-black">+5</div>
                                    </div>
                                    <button class="text-indigo-600 font-bold text-sm flex items-center gap-1 group/btn">
                                        Gestionar
                                        <ChevronRight class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: RESERVAS -->
                <div v-if="activeTab === 'reservas'" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                     <div class="bg-white rounded-[3rem] border border-gray-100 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Usuario</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Establecimiento</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha/Hora</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="res in reservations" :key="res.id" class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-8 py-6 font-black text-gray-900 text-sm">{{ res.user.name }}</td>
                                    <td class="px-8 py-6 text-sm text-gray-500 font-medium">{{ res.reservable.name }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-gray-900">{{ res.date }}</span>
                                            <span class="text-[10px] font-bold text-gray-400">{{ res.time }}h</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                            Completada
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.tracking-tighter { letter-spacing: -0.05em; }
.tracking-widest { letter-spacing: 0.1em; }

/* Custom Scrollbar */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }

/* Animations */
.animate-in { animation: animate-in 0.5s ease-out; }
@keyframes animate-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
