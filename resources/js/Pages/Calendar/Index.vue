<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { format, addMonths, subMonths } from "date-fns";
import { Plus, ChevronLeft, ChevronRight, Calendar as CalendarIcon } from "lucide-vue-next";
import MonthGrid from '@/Components/Calendar/MonthGrid.vue';
import DaySchedule from '@/Components/Calendar/DaySchedule.vue';
import Navigation from '@/Components/Navigation.vue';

// Dummy bookings representing your state / backend data
const bookings = ref([]);

const currentDate = ref(new Date());
const selectedDate = ref(new Date());

const handleMonthChange = (direction) => {
    if (direction === "prev") {
        currentDate.value = subMonths(currentDate.value, 1);
    } else {
        currentDate.value = addMonths(currentDate.value, 1);
    }
};

const goToToday = () => {
    const today = new Date();
    currentDate.value = today;
    selectedDate.value = today;
};

// Quick stats
const todayBookings = computed(() => {
    return bookings.value.filter(b => {
        if (b.status === "cancelled") return false;
        const today = new Date();
        const bookingDate = new Date(b.date);
        return (
            bookingDate.getDate() === today.getDate() &&
            bookingDate.getMonth() === today.getMonth() &&
            bookingDate.getFullYear() === today.getFullYear()
        );
    });
});
const totalBookings = computed(() => bookings.value.filter(b => b.status === "booked").length);
const cancelledBookings = computed(() => bookings.value.filter(b => b.status === "cancelled").length);

const handleTimeSlotClick = (time) => {
    alert(`Abrir modal de nueva reserva para el día ${format(selectedDate.value, 'dd/MM/yyyy')} a las ${time}`);
};

const handleDeleteBooking = (id) => {
    if (confirm("Are you sure you want to delete this booking completely?")) {
        bookings.value = bookings.value.filter(b => b.id !== id);
    }
};

const handleCancelBooking = (id) => {
    if (confirm("Are you sure you want to cancel this booking?")) {
        bookings.value = bookings.value.map(b => 
            b.id === id ? { ...b, status: 'cancelled' } : b
        );
    }
};
</script>

<template>
    <Head title="Calendario" />
    <Navigation />

    <div class="min-h-screen bg-gray-50 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="p-4 sm:p-8 bg-white/50 backdrop-blur rounded-xl border border-gray-200">
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <CalendarIcon class="w-8 h-8 text-blue-600" />
                            <h1 class="text-3xl font-bold text-gray-900">Calendario de Reservas</h1>
                        </div>

                        <button @click="handleTimeSlotClick('12:00')" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring bg-blue-600 text-white hover:bg-blue-700 h-10 px-4 py-2 gap-2">
                            <Plus class="w-5 h-5" />
                            Nueva Reserva
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-xl border bg-white p-6 shadow-sm">
                            <div class="text-sm text-gray-500 mb-1">Reservas para hoy</div>
                            <div class="text-2xl font-bold">{{ todayBookings.length }}</div>
                        </div>
                        <div class="rounded-xl border bg-white p-6 shadow-sm">
                            <div class="text-sm text-gray-500 mb-1">Total Activas</div>
                            <div class="text-2xl font-bold">{{ totalBookings }}</div>
                        </div>
                        <div class="rounded-xl border bg-white p-6 shadow-sm">
                            <div class="text-sm text-gray-500 mb-1">Canceladas</div>
                            <div class="text-2xl font-bold">{{ cancelledBookings }}</div>
                        </div>
                    </div>
                </div>

                <!-- Month Navigation -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 capitalize">{{ format(currentDate, "MMMM yyyy") }}</h2>
                    <div class="flex items-center gap-2">
                        <button @click="handleMonthChange('prev')" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-100 hover:text-gray-900 h-10 w-10">
                            <ChevronLeft class="w-5 h-5" />
                        </button>
                        <button @click="handleMonthChange('next')" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-100 hover:text-gray-900 h-10 w-10">
                            <ChevronRight class="w-5 h-5" />
                        </button>
                        <button @click="goToToday" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-100 px-4 h-10 text-sm font-medium">
                            Hoy
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid & Day Schedule -->
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-6">
                    <MonthGrid
                        :currentDate="currentDate"
                        :bookings="bookings"
                        :selectedDate="selectedDate"
                        @dateClick="selectedDate = $event"
                    />

                    <DaySchedule
                        :selectedDate="selectedDate"
                        :bookings="bookings"
                        @deleteBooking="handleDeleteBooking"
                        @cancelBooking="handleCancelBooking"
                        @timeSlotClick="handleTimeSlotClick"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
