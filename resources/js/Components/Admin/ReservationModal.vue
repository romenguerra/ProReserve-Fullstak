<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { Calendar, Clock, Users, MessageSquare, Save, X, Trash2, Store, FileText, Phone, Mail } from 'lucide-vue-next';

const props = defineProps({
    show: Boolean,
    reservation: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    reservation_date: '',
    reservation_time: '',
    status: 'pending',
    guests: 1,
    special_request: '',
});

watch(() => props.reservation, (newRes) => {
    if (newRes) {
        form.reservation_date = newRes.reservation_date;
        form.reservation_time = newRes.reservation_time;
        form.status = newRes.status;
        form.guests = newRes.guests || 1;
        form.special_request = newRes.special_request || '';
    }
}, { immediate: true });

const submit = () => {
    form.put(route('admin.reservations.update', props.reservation.id), {
        onSuccess: () => closeModal(),
    });
};

const deleteReservation = () => {
    if (confirm('¿Estás seguro de que deseas eliminar esta reserva? Esta acción no se puede deshacer.')) {
        form.delete(route('admin.reservations.destroy', props.reservation.id), {
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="lg">
        <div class="bg-indigo-600 p-8 text-white flex justify-between items-start relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="relative z-10 w-full">
                <h3 class="text-2xl font-black tracking-tighter">Gestionar Reserva</h3>
                
                <div class="mt-4 space-y-1" v-if="reservation">
                    <div class="flex flex-col gap-1 mb-3">
                        <p class="text-indigo-100 text-sm font-bold flex items-center gap-2">
                            <Users class="w-4 h-4 opacity-70" />
                            Cliente: <span class="text-white">{{ reservation.user?.name || reservation.customer_name || 'Sin nombre' }}</span>
                        </p>
                        <div class="flex items-center gap-4 pl-6 text-[10px] text-indigo-200 font-bold tracking-wide">
                            <span v-if="reservation.user?.phone || reservation.customer_phone" class="flex items-center gap-1.5">
                                <Phone class="w-3 h-3" />
                                {{ reservation.user?.phone || reservation.customer_phone }}
                            </span>
                            <span v-if="reservation.user?.email || reservation.customer_email" class="flex items-center gap-1.5">
                                <Mail class="w-3 h-3" />
                                {{ reservation.user?.email || reservation.customer_email }}
                            </span>
                        </div>
                    </div>
                    <p class="text-indigo-100 text-xs font-medium flex items-center gap-2 opacity-80" v-if="reservation.reservable">
                        <Store class="w-3.5 h-3.5" />
                        Establecimiento: {{ reservation.reservable.name }}
                    </p>
                    <p class="text-indigo-100 text-xs font-medium flex items-center gap-2 opacity-80">
                        <FileText class="w-3.5 h-3.5" />
                        Detalle: {{ reservation.service?.name || reservation.resource?.name || 'Reserva Básica' }}
                    </p>
                </div>
            </div>
            <button @click="closeModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors relative z-10 shrink-0 ml-4">
                <X class="w-6 h-6" />
            </button>
        </div>

        <form @submit.prevent="submit" class="p-8 space-y-6">
            <!-- Estado -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Estado de la Reserva</label>
                <div class="grid grid-cols-2 gap-3">
                    <button 
                        type="button"
                        @click="form.status = 'confirmed'"
                        :class="form.status === 'confirmed' ? 'bg-emerald-50 border-emerald-200 text-emerald-700 shadow-sm ring-2 ring-emerald-500/10' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                        class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border font-black text-xs transition-all"
                    >
                        <div :class="form.status === 'confirmed' ? 'bg-emerald-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                        Confirmado
                    </button>
                    <button 
                        type="button"
                        @click="form.status = 'pending'"
                        :class="form.status === 'pending' ? 'bg-amber-50 border-amber-200 text-amber-700 shadow-sm ring-2 ring-amber-500/10' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                        class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border font-black text-xs transition-all"
                    >
                        <div :class="form.status === 'pending' ? 'bg-amber-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                        Pendiente
                    </button>
                    <button 
                        type="button"
                        @click="form.status = 'cancelled'"
                        :class="form.status === 'cancelled' ? 'bg-red-50 border-red-200 text-red-700 shadow-sm ring-2 ring-red-500/10' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                        class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border font-black text-xs transition-all"
                    >
                        <div :class="form.status === 'cancelled' ? 'bg-red-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                        Cancelado
                    </button>
                    <button 
                        type="button"
                        @click="form.status = 'completed'"
                        :class="form.status === 'completed' ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm ring-2 ring-blue-500/10' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                        class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border font-black text-xs transition-all"
                    >
                        <div :class="form.status === 'completed' ? 'bg-blue-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                        Completado
                    </button>
                </div>
                <p v-if="form.errors.status" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.status }}</p>
            </div>

            <!-- Fecha y Hora -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Fecha</label>
                    <div class="relative">
                        <Calendar class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" />
                        <input 
                            type="date" 
                            v-model="form.reservation_date"
                            class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 outline-none"
                        />
                    </div>
                    <p v-if="form.errors.reservation_date" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.reservation_date }}</p>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Hora</label>
                    <div class="relative">
                        <Clock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" />
                        <input 
                            type="time" 
                            v-model="form.reservation_time"
                            class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 outline-none"
                        />
                    </div>
                    <p v-if="form.errors.reservation_time" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.reservation_time }}</p>
                </div>
            </div>

            <!-- Invitados -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Número de Personas</label>
                <div class="relative">
                    <Users class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" />
                    <input 
                        type="number" 
                        v-model="form.guests"
                        min="1"
                        class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 outline-none"
                    />
                </div>
                <p v-if="form.errors.guests" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.guests }}</p>
            </div>

            <!-- Notas -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Notas especiales</label>
                <div class="relative">
                    <MessageSquare class="absolute left-4 top-4 w-4 h-4 text-indigo-500" />
                    <textarea 
                        v-model="form.special_request"
                        placeholder="Peticiones especiales del cliente..."
                        class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 h-24 resize-none outline-none"
                    ></textarea>
                </div>
                <p v-if="form.errors.special_request" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.special_request }}</p>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 flex items-center gap-3">
                <button 
                    type="button"
                    @click="deleteReservation"
                    class="p-4 bg-red-50 text-red-600 rounded-2xl hover:bg-red-100 transition-colors"
                    title="Eliminar reserva"
                >
                    <Trash2 class="w-5 h-5" />
                </button>
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="flex-1 bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:scale-100"
                >
                    <Save class="w-5 h-5" />
                    Guardar Cambios
                </button>
            </div>
        </form>
    </Modal>
</template>
