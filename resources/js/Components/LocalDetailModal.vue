<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from "vue";
import BookingCalendar from "@/Components/BookingCalendar.vue";
import { usePage, router } from "@inertiajs/vue3";
import {
    CheckCircle2,
    Calendar as CalendarIcon,
    Clock,
    Users,
    X,
    Sparkles,
} from "lucide-vue-next";

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
        default: "servicio",
    },
});

const emit = defineEmits(["close"]);

// --- State ---
const step = ref(0); // 0: Info, 1: Booking, 2: Success
const bookingData = ref(null);

const close = () => {
    emit("close");
    // Reset step after animation
    setTimeout(() => {
        step.value = 0;
        bookingData.value = null;
    }, 300);
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));
onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

// Manejo del scroll del body
watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
            step.value = 0; // Reset step when opening
        } else {
            document.body.style.overflow = null;
        }
    },
);

// Helper para colores según categoría
const getCategoryTheme = (cat) => {
    switch (cat) {
        case "gastronomia":
            return {
                label: "Gastronomía",
                color: "bg-orange-500",
                textTitle: "text-orange-900",
                textAccent: "text-orange-500",
                bgMuted: "bg-orange-50",
            };
        case "deportes":
            return {
                label: "Deportes",
                color: "bg-blue-500",
                textTitle: "text-blue-900",
                textAccent: "text-blue-500",
                bgMuted: "bg-blue-50",
            };
        case "ocio":
            return {
                label: "Ocio",
                color: "bg-indigo-500",
                textTitle: "text-indigo-900",
                textAccent: "text-indigo-500",
                bgMuted: "bg-indigo-50",
            };
        case "salud":
            return {
                label: "Salud",
                color: "bg-teal-500",
                textTitle: "text-teal-900",
                textAccent: "text-teal-500",
                bgMuted: "bg-teal-50",
            };
        case "belleza":
            return {
                label: "Imagen Personal",
                color: "bg-rose-500",
                textTitle: "text-rose-900",
                textAccent: "text-rose-500",
                bgMuted: "bg-rose-50",
            };
        default:
            return {
                label: "Servicio",
                color: "bg-gray-800",
                textTitle: "text-gray-900",
                textAccent: "text-gray-800",
                bgMuted: "bg-gray-100",
            };
    }
};

const formatTypeName = (type) => {
    if (!type) return null;
    const titles = {
        asador: "Asador",
        japones: "Japonés",
        mediterraneo: "Mediterráneo",
        italiano: "Italiano",
        steakhouse: "Steakhouse",
        saludable: "Saludable",
        gimnasio: "Gimnasio",
        padel: "Pádel",
        natacion: "Natación",
        crossfit: "Crossfit",
        yoga: "Yoga",
        "clinica-estetica": "Clínica Estética",
        "clinica-medica": "Clínica Médica",
        hospital: "Hospital",
        barberia: "Barbería",
        peluqueria: "Peluquería",
        "salon-belleza": "Salón de Belleza",
        spa: "Spa",
        cine: "Cine",
        bolera: "Bolera",
        "parque-atracciones": "Parque de Atracciones",
        museo: "Museo",
    };
    return titles[type] || type.charAt(0).toUpperCase() + type.slice(1);
};

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth.user);

const handleStartBooking = () => {
    if (!isAuthenticated.value) {
        router.visit(route('login'));
    } else {
        step.value = 1;
    }
};

const handleConfirmBooking = (data) => {
    bookingData.value = data;
    
    router.post('/reservas', {
        local_id: props.local.id,
        category: props.category,
        service_id: data.service.id,
        reservation_date: data.date,
        reservation_time: data.time,
        guests: data.guests,
    }, {
        preserveState: true,
        onSuccess: () => {
            step.value = 2; // Success
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const formatDate = (dateString) => {
    const d = new Date(dateString);
    return d.toLocaleDateString("es-ES", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
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

                <!-- Modal Content -->
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
                        class="bg-white rounded-[3rem] shadow-2xl overflow-hidden transform transition-all w-full max-w-6xl max-h-[90vh] flex flex-col relative z-50 border border-white/20"
                    >
                        <div
                            v-if="local"
                            class="flex flex-col md:flex-row h-full overflow-hidden"
                        >
                            <!-- Botón cerrar -->
                            <button
                                @click="close"
                                class="absolute top-6 right-6 z-50 bg-white/50 backdrop-blur-xl hover:bg-white text-gray-900 p-3 rounded-2xl shadow-xl border border-white/40 transition-all duration-300 group hover:rotate-90"
                            >
                                <X class="w-6 h-6" />
                            </button>

                            <!-- Lado Izquierdo: Imagen (Ocupa menos cuando estamos en booking) -->
                            <div
                                :class="[
                                    step === 0
                                        ? 'md:w-1/2'
                                        : 'md:w-1/3 hidden md:block',
                                ]"
                                class="relative min-h-[300px] md:min-h-0 bg-gray-100 shrink-0 transition-[width] duration-700 cubic-bezier(0.16, 1, 0.3, 1) will-change-[width]"
                            >
                                <img
                                    :src="local.image || '/images/salud.avif'"
                                    :alt="local.name"
                                    class="absolute inset-0 w-full h-full object-cover"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80"
                                ></div>

                                <div
                                    class="absolute bottom-10 left-10 flex flex-col gap-4"
                                >
                                    <span
                                        :class="[
                                            'px-5 py-2 rounded-full text-xs font-black uppercase tracking-[0.2em] text-white backdrop-blur-xl shadow-2xl border border-white/20 w-fit',
                                            getCategoryTheme(category).color,
                                        ]"
                                    >
                                        {{
                                            formatTypeName(local.type) ||
                                            getCategoryTheme(category).label
                                        }}
                                    </span>
                                    <h2
                                        v-if="step > 0"
                                        class="text-3xl font-black text-white leading-tight"
                                    >
                                        {{ local.name }}
                                    </h2>
                                </div>
                            </div>

                            <!-- Lado Derecho: Contenido Dinámico -->
                            <div
                                :class="[
                                    step === 0 ? 'md:w-1/2' : 'md:w-2/3 w-full',
                                ]"
                                class="p-8 sm:p-12 md:p-16 flex flex-col overflow-y-auto transition-[width] duration-700 cubic-bezier(0.16, 1, 0.3, 1) will-change-[width] bg-white"
                            >
                                <Transition mode="out-in">
                                    <!-- PASO 0: DETALLES -->
                                    <div
                                        v-if="step === 0"
                                        key="details"
                                        class="flex flex-col h-full"
                                    >
                                        <!-- Rating -->
                                        <div
                                            class="flex items-center gap-2 mb-6"
                                            v-if="local.rating_average > 0"
                                        >
                                            <div class="flex">
                                                <svg
                                                    v-for="i in 5"
                                                    :key="i"
                                                    class="w-4 h-4"
                                                    :class="
                                                        i <=
                                                        Math.round(
                                                            local.rating_average,
                                                        )
                                                            ? 'text-yellow-400 fill-current'
                                                            : 'text-gray-200'
                                                    "
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                            </div>
                                            <span
                                                class="font-black text-gray-900 ml-1"
                                                >{{
                                                    local.rating_average
                                                }}</span
                                            >
                                            <span
                                                class="text-gray-400 text-sm font-bold"
                                                >({{
                                                    local.rating_count
                                                }}
                                                reviews)</span
                                            >
                                        </div>

                                        <h1
                                            :class="[
                                                'text-5xl md:text-6xl font-black mb-8 leading-[0.95] tracking-tighter',
                                                getCategoryTheme(category)
                                                    .textTitle,
                                            ]"
                                        >
                                            {{ local.name }}
                                        </h1>

                                        <p
                                            class="text-xl text-gray-500 mb-12 leading-relaxed font-medium"
                                        >
                                            {{
                                                local.description ||
                                                "Descubre nuestro increíble centro y disfruta de una atención espectacular con los mejores profesionales."
                                            }}
                                        </p>

                                        <!-- Grid de Info -->
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12"
                                        >
                                            <div class="flex items-start gap-4">
                                                <div
                                                    :class="[
                                                        'p-4 rounded-2xl bg-gray-50 shadow-sm border border-gray-100',
                                                        getCategoryTheme(
                                                            category,
                                                        ).textAccent,
                                                    ]"
                                                >
                                                    <CalendarIcon
                                                        class="w-6 h-6"
                                                    />
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1"
                                                    >
                                                        Ubicación
                                                    </p>
                                                    <p
                                                        class="text-sm font-bold text-gray-900 leading-snug"
                                                    >
                                                        {{ local.address }},
                                                        {{ local.city }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-4">
                                                <div
                                                    :class="[
                                                        'p-4 rounded-2xl bg-gray-50 shadow-sm border border-gray-100',
                                                        getCategoryTheme(
                                                            category,
                                                        ).textAccent,
                                                    ]"
                                                >
                                                    <Clock class="w-6 h-6" />
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1"
                                                    >
                                                        Horario
                                                    </p>
                                                    <p
                                                        class="text-sm font-bold text-gray-900"
                                                    >
                                                        {{
                                                            local.opening_time?.substring(
                                                                0,
                                                                5,
                                                            ) || "09:00"
                                                        }}
                                                        -
                                                        {{
                                                            local.closing_time?.substring(
                                                                0,
                                                                5,
                                                            ) || "22:00"
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botón CTA -->
                                        <div class="mt-auto">
                                            <button
                                                @click="handleStartBooking"
                                                class="w-full relative group inline-flex items-center justify-center gap-3 px-8 py-6 bg-gray-950 text-white rounded-[2rem] overflow-hidden transition-all duration-500 shadow-2xl shadow-gray-950/20 hover:scale-[1.02]"
                                            >
                                                <span
                                                    class="relative z-10 font-black text-xl tracking-tight"
                                                    >Reservar Cita Ahora</span
                                                >
                                                <div
                                                    :class="[
                                                        'absolute inset-0 translate-y-full group-hover:translate-y-0 transition-transform duration-500',
                                                        getCategoryTheme(
                                                            category,
                                                        ).color,
                                                    ]"
                                                ></div>
                                                <svg
                                                    class="w-6 h-6 relative z-10 transition-transform duration-500 group-hover:translate-x-2"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="3"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- PASO 1: CALENDARIO -->
                                    <div
                                        v-else-if="step === 1"
                                        key="booking"
                                        class="h-full"
                                    >
                                        <BookingCalendar
                                            :local="local"
                                            :category="category"
                                            :theme="getCategoryTheme(category)"
                                            @back="step = 0"
                                            @confirm="handleConfirmBooking"
                                        />
                                    </div>

                                    <!-- PASO 2: SUCCESS -->
                                    <div
                                        v-else-if="step === 2"
                                        key="success"
                                        class="flex flex-col items-center justify-center h-full text-center py-10"
                                    >
                                        <div class="relative mb-10">
                                            <div
                                                :class="[
                                                    'absolute inset-0 blur-[60px] opacity-40 rounded-full animate-pulse',
                                                    getCategoryTheme(category)
                                                        .color,
                                                ]"
                                            ></div>
                                            <div
                                                class="relative w-32 h-32 rounded-[2.5rem] bg-gray-950 flex items-center justify-center shadow-3xl text-white overflow-hidden"
                                            >
                                                <CheckCircle2
                                                    class="w-16 h-16 relative z-10 text-emerald-400"
                                                />
                                                <div
                                                    :class="[
                                                        'absolute inset-0 opacity-20',
                                                        getCategoryTheme(
                                                            category,
                                                        ).color,
                                                    ]"
                                                ></div>
                                            </div>

                                        </div>

                                        <h2
                                            class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tighter leading-none"
                                        >
                                            ¡Cita reservada!
                                        </h2>
                                        <p
                                            class="text-xl text-gray-500 font-medium mb-12 max-w-sm"
                                        >
                                            Tu reserva en
                                            <span
                                                class="text-gray-900 font-black"
                                                >{{ local.name }}</span
                                            >
                                            ha sido confirmada correctamente.
                                        </p>

                                        <div
                                            class="bg-gray-50 rounded-[2rem] p-8 w-full border border-gray-100 mb-12 text-left"
                                        >
                                            <h4
                                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6"
                                            >
                                                Detalles de la reserva
                                            </h4>
                                            <div class="space-y-6">
                                                <div
                                                    class="flex items-center gap-4"
                                                >
                                                    <div
                                                        :class="[
                                                            'p-3 rounded-xl text-white',
                                                            getCategoryTheme(
                                                                category,
                                                            ).color,
                                                        ]"
                                                    >
                                                        <Tag class="w-5 h-5" />
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1"
                                                        >
                                                            Servicio
                                                        </p>
                                                        <span
                                                            class="font-bold text-gray-900"
                                                            >{{
                                                                bookingData
                                                                    .service
                                                                    .name
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="grid grid-cols-2 gap-4"
                                                >
                                                    <div
                                                        class="flex items-center gap-4"
                                                    >
                                                        <CalendarIcon
                                                            class="w-5 h-5 text-gray-400"
                                                        />
                                                        <span
                                                            class="font-bold text-gray-900 capitalize"
                                                            >{{
                                                                formatDate(
                                                                    bookingData.date,
                                                                )
                                                            }}</span
                                                        >
                                                    </div>
                                                    <div
                                                        class="flex items-center gap-4"
                                                    >
                                                        <Clock
                                                            class="w-5 h-5 text-gray-400"
                                                        />
                                                        <span
                                                            class="font-bold text-gray-900"
                                                            >{{
                                                                bookingData.time
                                                            }}
                                                            h</span
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    v-if="
                                                        ![
                                                            'salud',
                                                            'belleza',
                                                        ].includes(category)
                                                    "
                                                    class="flex items-center gap-4"
                                                >
                                                    <Users
                                                        class="w-5 h-5 text-gray-400"
                                                    />
                                                    <span
                                                        class="font-bold text-gray-900"
                                                        >{{
                                                            bookingData.guests
                                                        }}
                                                        person{{
                                                            bookingData.guests >
                                                            1
                                                                ? "as"
                                                                : "a"
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <button
                                            @click="close"
                                            class="w-full py-6 bg-gray-950 text-white rounded-[2rem] font-black text-lg shadow-xl hover:scale-[1.02] transition-all"
                                        >
                                            Entendido
                                        </button>
                                    </div>
                                </Transition>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition:
        opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1),
        transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform, opacity;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.tracking-tighter {
    letter-spacing: -0.05em;
}

.shadow-3xl {
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.25);
}

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}
.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
