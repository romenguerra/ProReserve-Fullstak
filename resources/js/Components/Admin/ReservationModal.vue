<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { Calendar, Clock, Users, MessageSquare, Save, X, Trash2 } from 'lucide-vue-next';

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
        form.guests = newRes.guests;
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
        <div class="bg-white rounded-3xl overflow-hidden shadow-2xl">
            <!-- Header -->
            <div class="bg-indigo-600 p-8 text-white flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-black tracking-tighter">Gestionar Reserva</h3>
                    <p class="text-indigo-100 text-xs font-bold mt-1 opacity-80" v-if="reservation">
                        Cliente: {{ reservation.user?.name }}
                    </p>
                </div>
                <button @click="closeModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
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
                            :class="form.status === 'confirmed' ? 'bg-emerald-50 border-emerald-500 text-emerald-700 shadow-sm' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 font-black text-xs transition-all"
                        >
                            <div :class="form.status === 'confirmed' ? 'bg-emerald-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                            Confirmado
                        </button>
                        <button 
                            type="button"
                            @click="form.status = 'pending'"
                            :class="form.status === 'pending' ? 'bg-amber-50 border-amber-500 text-amber-700 shadow-sm' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 font-black text-xs transition-all"
                        >
                            <div :class="form.status === 'pending' ? 'bg-amber-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                            Pendiente
                        </button>
                        <button 
                            type="button"
                            @click="form.status = 'cancelled'"
                            :class="form.status === 'cancelled' ? 'bg-red-50 border-red-500 text-red-700 shadow-sm' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 font-black text-xs transition-all"
                        >
                            <div :class="form.status === 'cancelled' ? 'bg-red-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                            Cancelado
                        </button>
                        <button 
                            type="button"
                            @click="form.status = 'completed'"
                            :class="form.status === 'completed' ? 'bg-blue-50 border-blue-500 text-blue-700 shadow-sm' : 'bg-gray-50 border-transparent text-gray-400 hover:bg-gray-100'"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-2xl border-2 font-black text-xs transition-all"
                        >
                            <div :class="form.status === 'completed' ? 'bg-blue-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full"></div>
                            Completado
                        </button>
                    </div>
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
                                class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20"
                            />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Hora</label>
                        <div class="relative">
                            <Clock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" />
                            <input 
                                type="time" 
                                v-model="form.reservation_time"
                                class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20"
                            />
                        </div>
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
                            class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20"
                        />
                    </div>
                </div>

                <!-- Notas -->
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Notas especiales</label>
                    <div class="relative">
                        <MessageSquare class="absolute left-4 top-4 w-4 h-4 text-indigo-500" />
                        <textarea 
                            v-model="form.special_request"
                            placeholder="Peticiones especiales del cliente..."
                            class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 h-24 resize-none"
                        ></textarea>
                    </div>
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
        </div>
    </Modal>
</template>
