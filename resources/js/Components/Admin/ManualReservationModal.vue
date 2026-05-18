<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { X, Calendar, Clock, Users, FileText, User, Mail, Phone, Store, ChevronDown, Search } from 'lucide-vue-next';

const props = defineProps({
    show: Boolean,
    locals: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['close']);

const form = useForm({
    reservable_id: '',
    reservable_type: '',
    service_id: '',
    resource_id: '',
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    reservation_date: '',
    reservation_time: '',
    guests: 1,
    special_request: '',
    status: 'confirmed'
});

// Dropdown State
const isOpenDropdown = ref(false);
const searchLocalQuery = ref('');

// Availability Slots State
const availableTimeSlots = ref([]);
const isLoadingSlots = ref(false);

const localTypeToModel = {
    'restaurant': 'Restaurant',
    'sport_center': 'SportCenter',
    'health_center': 'HealthCenter',
    'beauty_center': 'BeautyCenter',
    'leisure_center': 'LeisureCenter'
};

// Computed matched local
const selectedLocal = computed(() => {
    return props.locals.find(l => l.id === form.reservable_id && l.type === form.reservable_type);
});

// Filtered locals list
const filteredLocals = computed(() => {
    const query = searchLocalQuery.value.trim().toLowerCase();
    if (!query) return props.locals;
    return props.locals.filter(l => 
        (l.name || '').toLowerCase().includes(query) ||
        (l.category || '').toLowerCase().includes(query)
    );
});

const selectLocalItem = (local) => {
    form.reservable_id = local.id;
    form.reservable_type = local.type;
    isOpenDropdown.value = false;
    searchLocalQuery.value = '';
};

// Reset service when local changes
watch(() => form.reservable_id, () => {
    form.service_id = '';
    form.resource_id = '';
    form.reservation_time = '';
    availableTimeSlots.value = [];
});

// Load available time slots from DB
const fetchSlots = async () => {
    if (!form.reservable_id || !form.reservable_type || !form.reservation_date) {
        availableTimeSlots.value = [];
        return;
    }
    
    isLoadingSlots.value = true;
    try {
        const typeModel = localTypeToModel[form.reservable_type];
        const response = await axios.get('/api/availability', {
            params: {
                type: typeModel,
                id: form.reservable_id,
                date: form.reservation_date,
                service_id: form.service_id || null,
                resource_id: form.resource_id || null,
                guests: form.guests
            }
        });
        availableTimeSlots.value = response.data.available_slots || [];
    } catch (error) {
        console.error("Error al obtener los horarios disponibles:", error);
        availableTimeSlots.value = [];
    } finally {
        isLoadingSlots.value = false;
    }
};

watch(() => [form.reservable_id, form.reservable_type, form.service_id, form.reservation_date, form.guests], () => {
    fetchSlots();
});

const selectTimeSlot = (time) => {
    form.reservation_time = time;
};

const getTodayString = () => {
    return new Date().toISOString().split('T')[0];
};

onMounted(() => {
    form.reservation_date = getTodayString();
});

const submit = () => {
    form.post(route('admin.reservations.storeManual'), {
        onSuccess: () => {
            close();
        }
    });
};

const close = () => {
    form.reset();
    form.reservation_date = getTodayString();
    isOpenDropdown.value = false;
    searchLocalQuery.value = '';
    availableTimeSlots.value = [];
    emit('close');
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
            <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="close"></div>

                <!-- Modal -->
                <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl relative z-10 overflow-hidden border border-gray-100 animate-in zoom-in-95 duration-300">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-600 shadow-lg shadow-orange-100 text-white">
                                <Calendar class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 leading-none mb-1">Crear Reserva Manual</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Registra citas telefónicas o presenciales</p>
                            </div>
                        </div>
                        <button @click="close" class="text-gray-400 hover:text-gray-900 transition-colors p-2 hover:bg-gray-100 rounded-xl">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" novalidate class="p-6 space-y-5 overflow-y-auto max-h-[75vh]">
                        <!-- Local / Establecimiento Buscable -->
                        <div class="space-y-1.5 relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Establecimiento / Local</label>
                            
                            <div class="relative">
                                <button 
                                    type="button" 
                                    @click="isOpenDropdown = !isOpenDropdown" 
                                    class="w-full pl-12 pr-10 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none text-left flex items-center justify-between shadow-sm"
                                >
                                    <span class="flex items-center gap-2">
                                        <Store class="w-4 h-4 text-gray-400" />
                                        <span v-if="selectedLocal" class="text-gray-900">{{ selectedLocal.name }} ({{ selectedLocal.category }})</span>
                                        <span v-else class="text-gray-400">Selecciona un establecimiento...</span>
                                    </span>
                                    <ChevronDown class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="{ 'rotate-180': isOpenDropdown }" />
                                </button>

                                <!-- Dropdown Menu con Filtro de Búsqueda -->
                                <div v-if="isOpenDropdown" class="absolute z-[70] left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl p-4 space-y-3 animate-in fade-in slide-in-from-top-2 duration-300">
                                    <!-- Search Input -->
                                    <div class="relative group">
                                        <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                        <input 
                                            v-model="searchLocalQuery"
                                            type="text" 
                                            placeholder="Buscar establecimiento..." 
                                            class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-xs font-bold outline-none"
                                            @click.stop
                                        />
                                    </div>

                                    <!-- Local List -->
                                    <div class="max-h-48 overflow-y-auto space-y-1 pr-1 custom-scrollbar">
                                        <button 
                                            v-for="local in filteredLocals" 
                                            :key="local.id + '-' + local.type"
                                            type="button"
                                            @click="selectLocalItem(local)"
                                            class="w-full flex items-center justify-between p-3 rounded-xl hover:bg-orange-50/50 hover:text-orange-600 transition-all text-left font-bold text-xs"
                                            :class="[form.reservable_id === local.id && form.reservable_type === local.type ? 'bg-orange-50 text-orange-600' : 'text-gray-700']"
                                        >
                                            <span>{{ local.name }}</span>
                                            <span class="text-[10px] uppercase px-2 py-0.5 rounded bg-gray-100 text-gray-400 font-bold">{{ local.category }}</span>
                                        </button>
                                        <div v-if="filteredLocals.length === 0" class="text-center py-4 text-xs text-gray-400 font-bold">
                                            No se encontraron locales.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-if="form.errors.reservable_id" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.reservable_id }}</p>
                        </div>

                        <!-- Servicio (dependiente del local) -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Servicio</label>
                            <div class="relative group">
                                <FileText class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                <select 
                                    v-model="form.service_id"
                                    :disabled="!form.reservable_id"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none appearance-none disabled:opacity-50"
                                >
                                    <option value="" disabled>Selecciona un servicio...</option>
                                    <option 
                                        v-for="service in (selectedLocal?.services || [])" 
                                        :key="service.id" 
                                        :value="service.id"
                                    >
                                        {{ service.name }} ({{ service.price }}€)
                                    </option>
                                </select>
                            </div>
                            <p v-if="form.errors.service_id" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.service_id }}</p>
                        </div>

                        <!-- Recurso (dependiente del local) -->
                        <div class="space-y-1.5" v-if="selectedLocal?.resources?.length > 0">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Recurso (Ej. Mesa/Pista)</label>
                            <div class="relative group">
                                <Store class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                <select 
                                    v-model="form.resource_id"
                                    :disabled="!form.reservable_id"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none appearance-none disabled:opacity-50"
                                >
                                    <option value="" disabled>Selecciona un recurso (Opcional si hay servicio general)...</option>
                                    <option 
                                        v-for="resource in selectedLocal.resources" 
                                        :key="resource.id" 
                                        :value="resource.id"
                                    >
                                        {{ resource.name }}
                                    </option>
                                </select>
                            </div>
                            <p v-if="form.errors.resource_id" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.resource_id }}</p>
                        </div>

                        <!-- Datos del Cliente -->
                        <div class="border-t border-gray-50 pt-4">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-3">Datos del Cliente (Sin registrar)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre</label>
                                    <div class="relative group">
                                        <User class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                        <input 
                                            v-model="form.customer_name"
                                            type="text" 
                                            placeholder="Ej. Juan Pérez"
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none"
                                        />
                                    </div>
                                    <p v-if="form.errors.customer_name" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.customer_name }}</p>
                                </div>

                                <!-- Teléfono -->
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Teléfono</label>
                                    <div class="relative group">
                                        <Phone class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                        <input 
                                            v-model="form.customer_phone"
                                            type="tel" 
                                            placeholder="Ej. 600 000 000"
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none"
                                        />
                                    </div>
                                    <p v-if="form.errors.customer_phone" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.customer_phone }}</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5 mt-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico (Opcional)</label>
                                <div class="relative group">
                                    <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                    <input 
                                        v-model="form.customer_email"
                                        type="email" 
                                        placeholder="Ej. cliente@ejemplo.com"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none"
                                    />
                                </div>
                                <p v-if="form.errors.customer_email" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.customer_email }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 ml-1 font-bold">Si se introduce, se le enviará un correo automático de confirmación.</p>
                            </div>
                        </div>

                        <!-- Cargar Disponibilidad de Fecha y Hora -->
                        <div class="border-t border-gray-50 pt-4">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-3">Detalles de Disponibilidad</h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Fecha -->
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Fecha</label>
                                    <div class="relative group">
                                        <Calendar class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                        <input 
                                            v-model="form.reservation_date"
                                            type="date" 
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none"
                                        />
                                    </div>
                                    <p v-if="form.errors.reservation_date" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.reservation_date }}</p>
                                </div>

                                <!-- Aforo / Personas -->
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Personas / Aforo</label>
                                    <div class="relative group">
                                        <Users class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-orange-600 transition-colors" />
                                        <input 
                                            v-model="form.guests"
                                            type="number" 
                                            min="1"
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-sm font-bold outline-none"
                                        />
                                    </div>
                                    <p v-if="form.errors.guests" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.guests }}</p>
                                </div>
                            </div>

                            <!-- Selector de Horas Disponibles (Desde base de datos) -->
                            <div class="space-y-2 mt-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 flex items-center gap-1.5">
                                    <Clock class="w-3.5 h-3.5" />
                                    Horarios Disponibles (Cargados del Local)
                                </label>

                                <!-- Loading Slots -->
                                <div v-if="isLoadingSlots" class="flex items-center gap-2 text-xs font-bold text-gray-400 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-orange-600 border-t-transparent"></div>
                                    Consultando disponibilidad en la base de datos...
                                </div>

                                <!-- No Local or Service selected notice -->
                                <div v-else-if="!form.reservable_id || !form.service_id" class="text-xs font-bold text-gray-400 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    Selecciona un local y servicio primero para cargar sus horarios de la base de datos.
                                </div>

                                <!-- Empty / Closed notice -->
                                <div v-else-if="availableTimeSlots.length === 0" class="text-xs font-bold text-red-500 p-4 bg-red-50/50 rounded-2xl border border-red-100">
                                    No hay horarios disponibles para este día (el local podría estar cerrado, completo, o no tener turnos definidos).
                                </div>

                                <!-- Slots Grid -->
                                <div v-else class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                    <button
                                        v-for="time in availableTimeSlots"
                                        :key="time"
                                        type="button"
                                        @click="selectTimeSlot(time)"
                                        :class="[
                                            form.reservation_time === time
                                                ? 'bg-orange-600 text-white shadow-lg shadow-orange-100 border-orange-600'
                                                : 'bg-gray-50 text-gray-700 border-gray-100 hover:bg-gray-100 hover:border-gray-200'
                                        ]"
                                        class="py-2.5 rounded-xl border text-xs font-black transition-all text-center"
                                    >
                                        {{ time }}
                                    </button>
                                </div>
                                <p v-if="form.errors.reservation_time" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.reservation_time }}</p>
                            </div>
                        </div>

                        <!-- Estado y Notas -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-50 pt-4">
                            <!-- Estado inicial -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Estado de la Reserva</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button 
                                        type="button"
                                        @click="form.status = 'pending'"
                                        :class="[
                                            form.status === 'pending' 
                                                ? 'bg-amber-600 text-white shadow-lg shadow-amber-100 border-amber-600' 
                                                : 'bg-gray-50 text-gray-400 border-gray-100 hover:bg-gray-100'
                                        ]"
                                        class="py-2.5 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all"
                                    >
                                        Pendiente
                                    </button>
                                    <button 
                                        type="button"
                                        @click="form.status = 'confirmed'"
                                        :class="[
                                            form.status === 'confirmed' 
                                                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100 border-emerald-600' 
                                                : 'bg-gray-50 text-gray-400 border-gray-100 hover:bg-gray-100'
                                        ]"
                                        class="py-2.5 rounded-xl border text-[10px] font-black uppercase tracking-widest transition-all"
                                    >
                                        Confirmada
                                    </button>
                                </div>
                            </div>

                            <!-- Petición Especial -->
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Notas / Observaciones</label>
                                <textarea 
                                    v-model="form.special_request"
                                    rows="2"
                                    placeholder="Ej. Mesa cerca de la ventana..."
                                    class="w-full p-3 bg-gray-50 border border-gray-100 rounded-xl focus:bg-white focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all text-xs font-bold outline-none resize-none"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 flex gap-3 border-t border-gray-50">
                            <button 
                                type="button"
                                @click="close"
                                class="flex-1 px-6 py-4 border border-gray-100 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                :disabled="form.processing || !form.reservation_time"
                                class="flex-1 px-6 py-4 bg-orange-600 hover:bg-orange-700 shadow-xl shadow-orange-100 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all disabled:opacity-50"
                            >
                                {{ form.processing ? 'Creando...' : 'Crear Reserva' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #E5E7EB;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #D1D5DB;
}
</style>
