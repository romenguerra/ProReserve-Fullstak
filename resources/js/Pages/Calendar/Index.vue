<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { 
    Calendar as CalendarIcon, 
    Clock, 
    MapPin, 
    CheckCircle, 
    XCircle, 
    User as UserIcon, 
    ChevronRight, 
    LayoutList, 
    Plus,
    Utensils,
    Trophy,
    HeartPulse,
    Music,
    Sparkles,
    Trash2
} from "lucide-vue-next";
import { ref, computed } from "vue";
import { useI18n } from "@/Composables/useI18n";

const { currentLocale, t } = useI18n();

const activeTab = ref('upcoming'); // upcoming, past, cancelled

const allReservations = ref([
    {
        id: 1,
        title: "Cena Aniversario",
        venue_name: "La Tasca de Don Pedro",
        date: "2026-04-10",
        time: "20:30",
        guests: 2,
        status: "confirmed",
        type: "restaurant",
        image: "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=600",
    },
    {
        id: 2,
        title: "Pista de Pádel",
        venue_name: "Club Polideportivo El Valle",
        date: "2026-04-12",
        time: "10:00",
        guests: 4,
        status: "pending",
        type: "sport",
        image: "https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?q=80&w=600&auto=format&fit=crop",
    },
    {
        id: 3,
        title: "Masaje Relajante",
        venue_name: "Zen Spa & Wellness",
        date: "2026-04-15",
        time: "17:00",
        guests: 1,
        status: "confirmed",
        type: "health",
        image: "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=600",
    },
    {
        id: 4,
        title: "Cata de Vinos",
        venue_name: "Bodegas del Marqués",
        date: "2026-04-20",
        time: "19:00",
        guests: 2,
        status: "cancelled",
        type: "restaurant",
        image: "https://images.unsplash.com/photo-1510812431401-41d2bd2722f3?auto=format&fit=crop&q=80&w=600",
    },
    {
        id: 5,
        title: "Entrenamiento Personal",
        venue_name: "Fit Pro Elite",
        date: "2026-03-15",
        time: "09:00",
        guests: 1,
        status: "confirmed",
        type: "sport",
        image: "https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&q=80&w=600",
    }
]);

const filteredReservations = computed(() => {
    if (activeTab.value === 'upcoming') {
        return allReservations.value.filter(r => (r.status === 'confirmed' || r.status === 'pending') && new Date(r.date) >= new Date());
    } else if (activeTab.value === 'past') {
        return allReservations.value.filter(r => r.status === 'confirmed' && new Date(r.date) < new Date());
    } else if (activeTab.value === 'cancelled') {
        return allReservations.value.filter(r => r.status === 'cancelled');
    }
    return [];
});

const formatDate = (dateString, format = 'full') => {
    const date = new Date(dateString);
    if (format === 'day') return date.getDate();
    if (format === 'month') return date.toLocaleDateString(currentLocale.value === 'es' ? 'es-ES' : 'en-US', { month: 'short' }).toUpperCase();
    
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString(currentLocale.value === 'es' ? 'es-ES' : 'en-US', options);
};

const getStatusStyles = (status) => {
    switch (status) {
        case 'confirmed': return 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20';
        case 'pending': return 'bg-amber-500/10 text-amber-500 border-amber-500/20';
        case 'cancelled': return 'bg-red-500/10 text-red-500 border-red-500/20';
        default: return 'bg-gray-500/10 text-gray-500 border-gray-500/20';
    }
};

const getTypeIcon = (type) => {
    switch (type) {
        case 'restaurant': return Utensils;
        case 'sport': return Trophy;
        case 'health': return HeartPulse;
        case 'leisure': return Music;
        case 'beauty': return Sparkles;
        default: return CalendarIcon;
    }
};

const getStatusLabel = (status) => {
    const labels = {
        es: { confirmed: 'Confirmada', pending: 'Pendiente', cancelled: 'Cancelada' },
        en: { confirmed: 'Confirmed', pending: 'Pending', cancelled: 'Cancelled' }
    };
    return labels[currentLocale.value]?.[status] || status;
};

// Count summary for active users to see at a glance
const counts = computed(() => ({
    upcoming: allReservations.value.filter(r => (r.status === 'confirmed' || r.status === 'pending') && new Date(r.date) >= new Date()).length,
    past: allReservations.value.filter(r => r.status === 'confirmed' && new Date(r.date) < new Date()).length,
    cancelled: allReservations.value.filter(r => r.status === 'cancelled').length
}));

const cancelReservation = (id) => {
    // In a real app we'd call an API
    if (confirm(t('calendar.confirm_cancel') || '¿Estás seguro de que quieres cancelar esta reserva?')) {
        const res = allReservations.value.find(r => r.id === id);
        if (res) res.status = 'cancelled';
    }
};
</script>

<template>
    <MainLayout :title="$t('calendar.meta_title')">
        <!-- Dashboard Header -->
        <section data-nav-theme="dark" class="relative pt-16 pb-20 bg-[#0f172a] text-[#F0EEE9] overflow-hidden">
            <!-- Animated Ambient Glow -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-[10%] -right-[5%] w-[600px] h-[600px] rounded-full bg-[#8EB6A5]/15 blur-[120px] animate-pulse"></div>
                <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-blue-600/10 blur-[130px]"></div>
            </div>

            <div class="max-w-4xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-12">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-white/90 font-bold text-xs uppercase tracking-[0.2em] mb-8 backdrop-blur-md shadow-2xl">
                            <Sparkles class="w-4 h-4 text-[#8EB6A5]" />
                            {{ $t('calendar.badge') }}
                        </div>
                        <h1 class="text-6xl md:text-7xl font-bold tracking-tightest mb-6 leading-[1.1]">
                            {{ $t('calendar.title') }}
                        </h1>
                        <p class="text-xl text-white/60 leading-relaxed font-normal max-w-xl">
                            {{ $t('calendar.subtitle') }}
                        </p>
                    </div>

                    <!-- Visual Summary Widget -->
                    <div class="flex items-center gap-6 p-2 rounded-[2.5rem] bg-white/5 border border-white/10 backdrop-blur-2xl shadow-3xl">
                        <div class="flex flex-col items-center justify-center w-36 h-36 rounded-[2rem] bg-white text-[#0f172a] shadow-inner relative overflow-hidden group">
                           <div class="absolute inset-0 bg-gradient-to-br from-[#8EB6A5]/20 to-transparent"></div>
                           <span class="text-xs font-black uppercase tracking-widest text-[#8EB6A5]/80 mb-1 z-10">{{ formatDate(new Date(), 'month') }}</span>
                           <span class="text-5xl font-black tabular-nums z-10 group-hover:scale-110 transition-transform">{{ formatDate(new Date(), 'day') }}</span>
                        </div>
                        <div class="pr-8 py-4">
                            <p class="text-sm font-bold text-white/50 uppercase tracking-widest mb-3">{{ $t('calendar.summary_today') || 'Resumen de hoy' }}</p>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-[#8EB6A5]"></div>
                                    <span class="text-lg font-bold text-white">{{ counts.upcoming }} {{ $t('calendar.upcoming') || 'Próximas' }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                    <span class="text-sm font-medium text-white/40">{{ counts.past }} {{ $t('calendar.completed') || 'Completadas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dynamic Content Engine -->
        <section data-nav-theme="light" class="py-20 bg-[#F0EEE9] min-h-screen relative">
            <!-- Subtle Decorative Element -->
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-white/30 to-transparent pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                
                <div class="flex flex-col lg:flex-row gap-16">
                    
                    <!-- Sidebar: Filters & Navigation -->
                    <aside class="lg:w-80 shrink-0">
                        <div class="sticky top-32 space-y-12">
                            <!-- Premium Vertical Tab System -->
                            <nav class="flex flex-col space-y-3 p-2 bg-white/50 backdrop-blur-xl rounded-3xl border border-white/60 shadow-xl">
                                <button 
                                    @click="activeTab = 'upcoming'"
                                    :class="activeTab === 'upcoming' ? 'bg-[#0f172a] text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100/50'"
                                    class="flex items-center justify-between px-6 py-5 rounded-2xl transition-all duration-500 group"
                                >
                                    <div class="flex items-center gap-4">
                                        <LayoutList class="w-5 h-5 transition-transform group-hover:scale-110" />
                                        <span class="font-bold text-sm tracking-wide">{{ $t('calendar.tabs.upcoming') }}</span>
                                    </div>
                                    <span v-if="counts.upcoming > 0" class="flex items-center justify-center w-6 h-6 rounded-full bg-[#8EB6A5] text-[#0f172a] text-[10px] font-black">{{ counts.upcoming }}</span>
                                </button>
                                
                                <button 
                                    @click="activeTab = 'past'"
                                    :class="activeTab === 'past' ? 'bg-[#0f172a] text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100/50'"
                                    class="flex items-center justify-between px-6 py-5 rounded-2xl transition-all duration-500 group"
                                >
                                    <div class="flex items-center gap-4">
                                        <CheckCircle class="w-5 h-5 transition-transform group-hover:scale-110" />
                                        <span class="font-bold text-sm tracking-wide">{{ $t('calendar.tabs.past') }}</span>
                                    </div>
                                </button>

                                <button 
                                    @click="activeTab = 'cancelled'"
                                    :class="activeTab === 'cancelled' ? 'bg-[#0f172a] text-white shadow-lg' : 'text-gray-500 hover:bg-gray-100/50'"
                                    class="flex items-center justify-between px-6 py-5 rounded-2xl transition-all duration-500 group"
                                >
                                    <div class="flex items-center gap-4">
                                        <XCircle class="w-5 h-5 transition-transform group-hover:scale-110" />
                                        <span class="font-bold text-sm tracking-wide">{{ $t('calendar.tabs.cancelled') }}</span>
                                    </div>
                                </button>
                            </nav>

                            <!-- Mini CTA box (Desktop only - Shown here) -->
                            <div class="hidden lg:block p-8 rounded-[2rem] bg-[#8EB6A5] text-[#0f172a] shadow-2xl relative overflow-hidden">
                                <Plus class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10 rotate-12" />
                                <h4 class="text-xl font-black mb-2 leading-tight">¿Tienes un nuevo plan?</h4>
                                <p class="text-sm font-medium mb-6 opacity-80 leading-relaxed text-[#0f172a]/70">No esperes a última hora y asegura tu sitio preferido.</p>
                                <Link
                                    href="/servicios"
                                    class="inline-flex items-center gap-2 bg-[#0f172a] text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-xl"
                                >
                                    Reservar Ahora
                                    <ChevronRight class="w-4 h-4" />
                                </Link>
                            </div>
                        </div>
                    </aside>

                    <!-- Main List: Vertical Timeline Style -->
                    <main class="flex-1">
                        <Transition
                            mode="out-in"
                            enter-active-class="transition duration-500 ease-out"
                            enter-from-class="opacity-0 translate-y-8"
                            enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition duration-300 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0 -translate-y-8"
                        >
                            <div v-if="filteredReservations.length > 0" class="relative pl-12 sm:pl-20 border-l border-gray-200/80 space-y-12">
                                <div 
                                    v-for="(reservation, index) in filteredReservations" 
                                    :key="reservation.id"
                                    class="relative group"
                                >
                                    <!-- Timeline Dot and Line Extension -->
                                    <div 
                                        class="absolute -left-[calc(3rem+1px)] sm:-left-[calc(5rem+1px)] top-8 flex flex-col items-center"
                                    >
                                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-white border border-gray-100 shadow-xl flex flex-col items-center justify-center transition-all duration-500 group-hover:border-[#8EB6A5] group-hover:shadow-[#8EB6A5]/10">
                                            <span class="text-[10px] sm:text-xs font-black tracking-widest text-[#8EB6A5] leading-none mb-1">{{ formatDate(reservation.date, 'month') }}</span>
                                            <span class="text-lg sm:text-2xl font-black text-[#0f172a] leading-none">{{ formatDate(reservation.date, 'day') }}</span>
                                        </div>
                                    </div>

                                    <!-- Main Actionable Card -->
                                    <div class="bg-white rounded-[2.5rem] overflow-hidden border border-white/20 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] hover:shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)] transition-all duration-700 hover:-translate-y-2 flex flex-col lg:flex-row h-full">
                                        <!-- Image Section -->
                                        <div class="lg:w-72 shrink-0 relative overflow-hidden h-48 lg:h-auto rounded-t-[2.5rem] lg:rounded-l-[2.5rem] lg:rounded-tr-none isolate">
                                            <img 
                                                :src="reservation.image" 
                                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                            />
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                            
                                            <div class="absolute bottom-6 left-6 flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-xl border border-white/20 flex items-center justify-center text-white">
                                                    <component :is="getTypeIcon(reservation.type)" class="w-5 h-5" />
                                                </div>
                                                <span class="text-xs font-black text-white uppercase tracking-widest drop-shadow-lg">{{ reservation.type }}</span>
                                            </div>

                                            <!-- Absolute Status for Mobile -->
                                            <div class="absolute top-6 right-6 lg:hidden">
                                                <span 
                                                    class="px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border backdrop-blur-xl shadow-2xl"
                                                    :class="getStatusStyles(reservation.status)"
                                                >
                                                    {{ getStatusLabel(reservation.status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="flex-1 p-8 lg:p-10 flex flex-col">
                                            <div class="flex justify-between items-start mb-6">
                                                <div class="max-w-md">
                                                    <h3 class="text-2xl sm:text-3xl font-black text-[#0f172a] mb-2 tracking-tight group-hover:text-[#8EB6A5] transition-colors">{{ reservation.title }}</h3>
                                                    <div class="flex items-center gap-2 text-gray-400 group-hover:text-gray-600 transition-colors">
                                                        <MapPin class="w-4 h-4" />
                                                        <span class="text-sm font-semibold tracking-wide">{{ reservation.venue_name }}</span>
                                                    </div>
                                                </div>

                                                <span 
                                                    class="hidden lg:inline-flex px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all duration-500 hover:scale-105"
                                                    :class="getStatusStyles(reservation.status)"
                                                >
                                                    {{ getStatusLabel(reservation.status) }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 pt-8 mt-auto border-t border-gray-100">
                                                <div class="space-y-1">
                                                    <p class="text-[10px] font-black text-[#8EB6A5] uppercase tracking-widest opacity-80">{{ $t('calendar.date') }}</p>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                                            <CalendarIcon class="w-4 h-4 text-[#0f172a]" />
                                                        </div>
                                                        <span class="text-sm font-bold text-[#0f172a] capitalize">{{ formatDate(reservation.date) }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="space-y-1">
                                                    <p class="text-[10px] font-black text-[#8EB6A5] uppercase tracking-widest opacity-80">{{ $t('calendar.time') }}</p>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                                            <Clock class="w-4 h-4 text-[#0f172a]" />
                                                        </div>
                                                        <span class="text-sm font-bold text-[#0f172a]">{{ reservation.time }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-span-2 lg:col-span-1 space-y-1">
                                                    <p class="text-[10px] font-black text-[#8EB6A5] uppercase tracking-widest opacity-80">{{ $t('calendar.guests') }}</p>
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                                            <UserIcon class="w-4 h-4 text-[#0f172a]" />
                                                        </div>
                                                        <span class="text-sm font-bold text-[#0f172a]">{{ reservation.guests }} {{ reservation.guests > 1 ? 'personas' : 'persona' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bottom Bar Actions -->
                                            <div class="mt-8 pt-8 border-t border-gray-50 flex flex-wrap sm:flex-nowrap justify-between sm:justify-end items-center gap-4">
                                                <button 
                                                    v-if="reservation.status !== 'cancelled' && activeTab === 'upcoming'"
                                                    @click="cancelReservation(reservation.id)"
                                                    class="group/btn inline-flex items-center gap-2 text-red-500/80 hover:text-red-600 font-black text-[10px] uppercase tracking-widest transition-all px-3 py-2 hover:bg-red-50 rounded-xl whitespace-nowrap"
                                                >
                                                    <Trash2 class="w-3.5 h-3.5 transition-transform group-hover/btn:scale-110" />
                                                    {{ $t('calendar.cancel_action') || 'Cancelar' }}
                                                </button>
                                                <div class="flex-1 hidden sm:block"></div>
                                                <Link
                                                    :href="route('servicios')"
                                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-3 bg-gray-50 text-[#0f172a] px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-[#0f172a] hover:text-white transition-all shadow-sm whitespace-nowrap"
                                                >
                                                    {{ $t('calendar.details_action') || 'Ver detalles' }}
                                                    <ChevronRight class="w-4 h-4" />
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- High-End Empty State -->
                            <div v-else class="flex flex-col items-center justify-center py-32 sm:py-48 px-8 bg-white/40 backdrop-blur-md rounded-[4rem] border border-white/60 shadow-inner">
                                <div class="relative mb-12">
                                    <div class="absolute inset-0 bg-[#8EB6A5]/20 blur-[60px] rounded-full scale-150 animate-pulse"></div>
                                    <div class="relative w-32 h-32 rounded-[2.5rem] bg-[#0f172a] flex items-center justify-center shadow-3xl text-white">
                                        <Plus class="w-12 h-12" />
                                    </div>
                                </div>
                                <h3 class="text-4xl font-black text-[#0f172a] mb-4 text-center tracking-tight">{{ $t('calendar.empty_title') }}</h3>
                                <p class="text-gray-500 text-lg font-medium text-center mb-12 max-w-sm leading-relaxed">
                                    {{ $t('calendar.empty_subtitle') }}
                                </p>
                                <Link
                                    href="/servicios"
                                    class="inline-flex px-12 py-6 bg-[#8EB6A5] text-[#0f172a] font-black text-sm uppercase tracking-widest rounded-[2rem] shadow-[0_20px_40px_-10px_rgba(142,182,165,0.4)] hover:-translate-y-2 hover:shadow-[0_30px_60px_-12px_rgba(142,182,165,0.5)] transition-all"
                                >
                                    {{ $t('calendar.explore_cta') }}
                                </Link>
                            </div>
                        </Transition>
                    </main>
                </div>

                <!-- Mini CTA box (Mobile/Tablet only - Shown here at the end) -->
                <div class="mt-16 lg:hidden p-8 rounded-[2.5rem] bg-[#8EB6A5] text-[#0f172a] shadow-2xl relative overflow-hidden">
                    <Plus class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10 rotate-12" />
                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-8">
                        <div>
                            <h4 class="text-3xl font-black mb-3 leading-tight tracking-tightest">¿Tienes un nuevo plan?</h4>
                            <p class="text-base font-medium opacity-80 leading-relaxed max-w-md">No esperes a última hora y asegura tu sitio preferido en los mejores locales.</p>
                        </div>
                        <Link
                            href="/servicios"
                            class="inline-flex items-center justify-center gap-3 bg-[#0f172a] text-white px-10 py-5 rounded-2xl text-sm font-black uppercase tracking-widest hover:scale-105 transition-transform shadow-2xl"
                        >
                            Reservar Ahora
                            <ChevronRight class="w-5 h-5" />
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped>
.tracking-tightest {
    letter-spacing: -0.04em;
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Glassmorphism utility if not globally available */
.backdrop-blur-xl {
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
}

/* Custom shadow for premium feel */
.shadow-3xl {
    box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
}

/* Smooth smooth smooth transitions */
.transition-all {
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
