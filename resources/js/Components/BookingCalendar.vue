<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import {
    ChevronLeft,
    ChevronRight,
    Users,
    Calendar as CalendarIcon,
    Clock,
    CheckCircle2,
    ArrowRight,
    Tag,
    Star,
} from "lucide-vue-next";

const props = defineProps({
    local: {
        type: Object,
        required: true,
    },
    category: {
        type: String,
        default: "servicio",
    },
    theme: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["back", "confirm"]);

// --- State ---
const bookingStep = ref(0); // 0: Select Service, 1: Select Date/Time/Guests
const selectedService = ref(null);
const selectedResource = ref(null);
const selectedDate = ref(null);
const selectedTime = ref(null);
const guests = ref(2);

// --- Computed ---
const isIndividualService = computed(() => {
    return ["salud", "belleza"].includes(props.category);
});

// --- Data Generation ---
const days = computed(() => {
    const arr = [];
    const today = new Date();
    for (let i = 0; i < 14; i++) {
        const d = new Date();
        d.setDate(today.getDate() + i);
        arr.push({
            date: d,
            dayName: d
                .toLocaleDateString("es-ES", { weekday: "short" })
                .replace(".", ""),
            dayNum: d.getDate(),
            monthName: d.toLocaleDateString("es-ES", { month: "short" }),
            iso: d.toISOString().split("T")[0],
        });
    }
    return arr;
});

const availableTimeSlots = ref([]);
const isLoadingSlots = ref(false);

const categoryToModel = {
    'gastronomia': 'Restaurant',
    'deportes': 'SportCenter',
    'ocio': 'LeisureCenter',
    'salud': 'HealthCenter',
    'belleza': 'BeautyCenter'
};

const fetchSlots = async () => {
    if (!selectedDate.value || !selectedService.value) return;
    if (props.local.resources && props.local.resources.length > 0 && !selectedResource.value) return;
    
    isLoadingSlots.value = true;
    
    try {
        const response = await axios.get('/api/availability', {
            params: {
                type: categoryToModel[props.category],
                id: props.local.id,
                date: selectedDate.value,
                service_id: selectedService.value.id,
                guests: isIndividualService.value ? 1 : guests.value,
                resource_id: selectedResource.value ? selectedResource.value.id : null
            }
        });
        availableTimeSlots.value = response.data.available_slots;
        
        if (selectedTime.value && !availableTimeSlots.value.includes(selectedTime.value)) {
            selectedTime.value = null;
        }
    } catch (error) {
        console.error("Error fetching available slots:", error);
        availableTimeSlots.value = [];
    } finally {
        isLoadingSlots.value = false;
    }
};

watch([selectedDate, selectedService, guests, selectedResource], () => {
    if (bookingStep.value === 1) {
        fetchSlots();
    }
});

// --- Methods ---
const selectService = (service) => {
    selectedService.value = service;
    if (props.local.resources && props.local.resources.length > 0) {
        selectedResource.value = props.local.resources[0];
    }
    bookingStep.value = 1;
    fetchSlots();
};

const selectResource = (resource) => {
    selectedResource.value = resource;
};

const selectDate = (date) => {
    selectedDate.value = date;
};

const selectTime = (time) => {
    selectedTime.value = time;
};

const incrementGuests = () => {
    if (guests.value < 10) guests.value++;
};
const decrementGuests = () => {
    if (guests.value > 1) guests.value--;
};

const handleBack = () => {
    if (bookingStep.value === 1) {
        bookingStep.value = 0;
    } else {
        emit("back");
    }
};

const confirmBooking = () => {
    if (selectedDate.value && selectedTime.value && selectedService.value) {
        if (props.local.resources && props.local.resources.length > 0 && !selectedResource.value) return;
        
        emit("confirm", {
            service: selectedService.value,
            resource: selectedResource.value,
            date: selectedDate.value,
            time: selectedTime.value,
            guests: isIndividualService.value ? 1 : guests.value,
        });
    }
};

onMounted(() => {
    selectedDate.value = days.value[0].iso;
});
</script>

<template>
    <div class="flex flex-col h-full overflow-hidden">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8 shrink-0">
            <button
                @click="handleBack"
                class="p-3 rounded-2xl bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors group"
            >
                <ChevronLeft
                    class="w-5 h-5 group-hover:-translate-x-1 transition-transform"
                />
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                    {{
                        bookingStep === 0
                            ? "Selecciona un servicio"
                            : "Configura tu cita"
                    }}
                </h2>
                <p class="text-sm text-gray-500">{{ local.name }}</p>
            </div>
        </div>

        <Transition mode="out-in">
            <!-- STEP 0: SERVICE SELECTION -->
            <div
                v-if="bookingStep === 0"
                key="step0"
                class="flex-1 overflow-y-auto pr-2 space-y-4 pb-4"
            >
                <div
                    v-for="service in local.services"
                    :key="service.id"
                    @click="selectService(service)"
                    class="group relative bg-white p-6 rounded-3xl border border-gray-100 hover:border-transparent hover:shadow-2xl transition-all duration-500 cursor-pointer overflow-hidden"
                >
                    <div
                        :class="[
                            'absolute inset-0 opacity-0 group-hover:opacity-5 transition-opacity duration-500',
                            theme.color,
                        ]"
                    ></div>

                    <div class="flex justify-between items-start relative z-10">
                        <div class="max-w-[70%]">
                            <h4
                                class="text-lg font-black text-gray-900 mb-1 group-hover:translate-x-2 transition-transform duration-500"
                            >
                                {{ service.name }}
                            </h4>
                            <p
                                class="text-sm text-gray-500 line-clamp-2 leading-relaxed"
                            >
                                {{ service.description }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                :class="[
                                    'text-xl font-black mb-1',
                                    theme.textAccent,
                                ]"
                            >
                                {{
                                    service.price > 0
                                        ? service.price + "€"
                                        : "Gratis"
                                }}
                            </p>
                            <div
                                class="flex items-center gap-1.5 text-gray-400 justify-end"
                            >
                                <Clock class="w-3.5 h-3.5" />
                                <span class="text-xs font-bold"
                                    >{{ service.duration_minutes }} min</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State for services if none -->
                <div
                    v-if="!local.services || local.services.length === 0"
                    class="flex flex-col items-center justify-center py-12 text-center"
                >
                    <Tag class="w-12 h-12 text-gray-200 mb-4" />
                    <p class="text-gray-400 font-medium">
                        No hay servicios específicos disponibles <br />
                        para este local.
                    </p>
                </div>
            </div>

            <!-- STEP 1: DATE & TIME SELECTION -->
            <div
                v-else-if="bookingStep === 1"
                key="step1"
                class="flex-1 overflow-y-auto pr-2 pb-4"
            >
                <!-- Selected Service Summary -->
                <div
                    class="bg-gray-50 rounded-2xl p-4 mb-8 flex items-center justify-between border border-gray-100"
                >
                    <div class="flex items-center gap-3">
                        <div
                            :class="[
                                'p-2.5 rounded-xl text-white shadow-lg',
                                theme.color,
                            ]"
                        >
                            <Tag class="w-4 h-4" />
                        </div>
                        <div>
                            <p
                                class="text-xs font-black text-gray-400 uppercase tracking-widest"
                            >
                                Servicio
                            </p>
                            <p class="text-sm font-bold text-gray-900">
                                {{ selectedService.name }}
                            </p>
                        </div>
                    </div>
                    <p :class="['text-sm font-black', theme.textAccent]">
                        {{
                            selectedService.price > 0
                                ? selectedService.price + "€"
                                : "Gratis"
                        }}
                    </p>
                </div>

                <!-- Resource Selection -->
                <div v-if="local.resources && local.resources.length > 0" class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">1. Opciones (Obligatorio)</span>
                    </div>
                    <div class="flex gap-3 overflow-x-auto px-2 py-2 -mx-2 -mt-2 pb-4 hide-scrollbar">
                        <button
                            v-for="resource in local.resources"
                            :key="resource.id"
                            @click="selectResource(resource)"
                            :class="[
                                selectedResource?.id === resource.id
                                    ? theme.color + ' text-white shadow-lg'
                                    : 'bg-white border border-gray-100 text-gray-600 hover:border-gray-300',
                            ]"
                            class="px-5 py-3 rounded-xl text-sm font-black transition-all duration-500 whitespace-nowrap"
                        >
                            {{ resource.name }}
                        </button>
                    </div>
                </div>

                <!-- Date Selection -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400"
                            >{{ local.resources && local.resources.length > 0 ? '2' : '1' }}. Selecciona Fecha</span
                        >
                        <span
                            class="text-[10px] font-black px-2 py-1 bg-gray-100 rounded-md text-gray-500 uppercase"
                            >{{ days[0].monthName }} -
                            {{ days[13].monthName }}</span
                        >
                    </div>

                    <div
                        class="flex gap-3 overflow-x-auto px-2 py-2 -mx-2 -mt-2 pb-4 hide-scrollbar snap-x"
                    >
                        <button
                            v-for="day in days"
                            :key="day.iso"
                            @click="selectDate(day.iso)"
                            :class="[
                                selectedDate === day.iso
                                    ? theme.color +
                                      ' text-white shadow-lg'
                                    : 'bg-white border border-gray-100 text-gray-600 hover:border-gray-300',
                            ]"
                            class="flex flex-col items-center justify-center min-w-[75px] h-24 rounded-2xl transition-all duration-500 snap-start"
                        >
                            <span
                                class="text-[10px] font-black uppercase opacity-70 mb-1 leading-none"
                                >{{ day.dayName }}</span
                            >
                            <span class="text-2xl font-black leading-none">{{
                                day.dayNum
                            }}</span>
                        </button>
                    </div>
                </div>

                <!-- Time Selection -->
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400"
                            >{{ local.resources && local.resources.length > 0 ? '3' : '2' }}. Selecciona Hora</span
                        >
                        <Clock class="w-4 h-4 text-gray-300" />
                    </div>

                    <div v-if="isLoadingSlots && availableTimeSlots.length === 0" class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2" :class="theme.textAccent"></div>
                    </div>
                    <div v-else-if="availableTimeSlots.length === 0" class="text-center py-8 text-gray-500">
                        No hay horas disponibles para este día.
                    </div>
                    <div v-else class="grid grid-cols-3 sm:grid-cols-4 gap-3 transition-opacity duration-300" :class="{ 'opacity-50 pointer-events-none': isLoadingSlots }">
                        <button
                            v-for="time in availableTimeSlots"
                            :key="time"
                            @click="selectTime(time)"
                            :class="[
                                selectedTime === time
                                    ? theme.color + ' text-white shadow-lg'
                                    : 'bg-white border border-gray-100 text-gray-600 hover:border-gray-300',
                            ]"
                            class="py-3.5 rounded-xl text-sm font-black transition-all duration-500 active:scale-95"
                        >
                            {{ time }}
                        </button>
                    </div>
                </div>

                <!-- Guests (Only if not individual) -->
                <div v-if="!isIndividualService" class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <span
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400"
                            >{{ local.resources && local.resources.length > 0 ? '4' : '3' }}. ¿Para cuántos?</span
                        >
                        <Users class="w-4 h-4 text-gray-300" />
                    </div>

                    <div
                        class="flex items-center gap-8 bg-white p-4 rounded-3xl border border-gray-100 w-full justify-between sm:w-fit"
                    >
                        <button
                            @click="decrementGuests"
                            class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-900 hover:bg-gray-200 transition-all font-black text-xl hover:scale-110 active:scale-90"
                        >
                            -
                        </button>
                        <div class="flex flex-col items-center min-w-[80px]">
                            <span
                                class="text-3xl font-black text-gray-900 leading-none mb-1"
                                >{{ guests }}</span
                            >
                            <span
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest"
                                >{{ guests > 1 ? "Personas" : "Persona" }}</span
                            >
                        </div>
                        <button
                            @click="incrementGuests"
                            class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-900 hover:bg-gray-200 transition-all font-black text-xl hover:scale-110 active:scale-90"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Step 2 CTA -->
                <div class="pt-8 border-t border-gray-100">
                    <button
                        @click="confirmBooking"
                        :disabled="!selectedDate || !selectedTime"
                        :class="[
                            selectedDate && selectedTime
                                ? 'bg-gray-950 text-white'
                                : 'bg-gray-100 text-gray-400 cursor-not-allowed',
                        ]"
                        class="w-full relative group inline-flex items-center justify-center gap-3 px-8 py-6 rounded-[2rem] overflow-hidden transition-all duration-500"
                    >
                        <span
                            class="relative z-10 font-black text-xl tracking-tight"
                            >Confirmar Reserva</span
                        >
                        <div
                            v-if="selectedDate && selectedTime"
                            :class="[
                                'absolute inset-0 translate-y-full group-hover:translate-y-0 transition-transform duration-700',
                                theme.color,
                            ]"
                        ></div>
                        <ArrowRight
                            class="w-6 h-6 relative z-10 transition-transform duration-500 group-hover:translate-x-2"
                        />
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.v-enter-active,
.v-leave-active {
    transition:
        opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
}

.v-enter-from {
    opacity: 0;
    transform: translateX(20px);
}
.v-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}
</style>
