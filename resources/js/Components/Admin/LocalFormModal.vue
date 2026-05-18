<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { 
    X, Store, MapPin, Phone, AlignLeft, Clock, Globe, 
    Mail, Camera, Trash2, Plus, Users, CreditCard, CheckCircle2, ChevronRight
} from 'lucide-vue-next';
import AddressAutocomplete from '@/Components/AddressAutocomplete.vue';

const props = defineProps({
    show: Boolean,
    local: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);

const fileInput = ref(null);
const imagePreview = ref(null);

const form = useForm({
    id: null,
    type: 'restaurant',
    name: '',
    local_type: '',
    description: '',
    phone: '',
    email: '',
    website: '',
    image: null,
    smoking_area: false,
    has_parking: false,
    has_emergency: false,
    address: '',
    city: '',
    postal_code: '',
    latitude: null,
    longitude: null,
    schedules: [
        { day_of_week: 1, name: 'Lunes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 2, name: 'Martes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 3, name: 'Miércoles', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 4, name: 'Jueves', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 5, name: 'Viernes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 6, name: 'Sábado', is_closed: false, opening_time: '10:00', closing_time: '23:00' },
        { day_of_week: 7, name: 'Domingo', is_closed: true, opening_time: '10:00', closing_time: '23:00' },
    ],
    resources: [{ 
        name: 'Salón Principal', 
        capacity: 10,
        resource_type: 'exclusive_unit',
        unit_count: 5,
        unit_capacity: 4,
        max_guests_per_booking: 8
    }],
    services: [{ name: 'Servicio Principal', duration_minutes: 60, price: 0 }],
});

// Sincronización con el local seleccionado (Modo Edición)
watch(() => props.local, (newLocal) => {
    if (newLocal) {
        form.id = newLocal.id;
        form.type = newLocal.type;
        form.name = newLocal.name;
        form.local_type = newLocal.local_type || '';
        form.description = newLocal.description || '';
        form.phone = newLocal.phone || '';
        form.email = newLocal.email || '';
        form.website = newLocal.website || '';
        form.address = newLocal.address || '';
        form.city = newLocal.city || '';
        form.postal_code = newLocal.postal_code || '';
        form.latitude = newLocal.latitude;
        form.longitude = newLocal.longitude;
        form.smoking_area = !!newLocal.smoking_area;
        form.has_parking = !!newLocal.has_parking;
        form.has_emergency = !!newLocal.has_emergency;
        
        if (newLocal.schedules?.length) form.schedules = JSON.parse(JSON.stringify(newLocal.schedules));
        if (newLocal.resources?.length) form.resources = JSON.parse(JSON.stringify(newLocal.resources));
        if (newLocal.services?.length) form.services = JSON.parse(JSON.stringify(newLocal.services));
        
        imagePreview.value = newLocal.image;
    } else {
        form.reset();
        imagePreview.value = null;
    }
}, { immediate: true });

// Lógica idéntica a CreateLocal.vue
const addResource = () => form.resources.push({ 
    name: '', 
    capacity: 1,
    resource_type: 'exclusive_unit',
    unit_count: 1,
    unit_capacity: 4,
    max_guests_per_booking: 4
});
const removeResource = (index) => form.resources.length > 1 && form.resources.splice(index, 1);
const addService = () => form.services.push({ name: '', duration_minutes: 60, price: 0 });
const removeService = (index) => form.services.length > 1 && form.services.splice(index, 1);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => imagePreview.value = e.target.result;
        reader.readAsDataURL(file);
    }
};

const handleLocationSelected = (locationData) => {
    form.latitude = locationData.latitude;
    form.longitude = locationData.longitude;
    form.address = locationData.place.formatted_address;
    
    locationData.place.address_components?.forEach(component => {
        if (component.types.includes('locality')) form.city = component.long_name;
        if (component.types.includes('postal_code')) form.postal_code = component.long_name;
    });
};

const submit = () => {
    if (form.id) {
        // En edición usamos POST con _method=PUT para soportar archivos
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.locales.update', { id: form.id, type: form.type }), {
            forceFormData: true,
            onSuccess: () => emit('close')
        });
    } else {
        form.post(route('locales.store'), {
            forceFormData: true,
            onSuccess: () => emit('close')
        });
    }
};

const localTypeLabel = computed(() => {
    const labels = {
        restaurant: 'Tipo de Cocina / Especialidad',
        sport_center: 'Tipo de Instalación',
        health_center: 'Especialidad Médica',
        beauty_center: 'Servicio Estético',
        leisure_center: 'Tipo de Ocio'
    };
    return labels[form.type] || 'Especialidad';
});
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
            <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-md" @click="emit('close')"></div>

                <div class="bg-white rounded-3xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.2)] w-full max-w-5xl relative z-10 overflow-hidden border border-gray-100 animate-in zoom-in-95 duration-500 flex flex-col max-h-[90vh]">
                    <!-- Header -->
                    <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-white shrink-0">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[1.8rem] flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                                <Store class="w-8 h-8" />
                            </div>
                            <div>
                                <h3 class="font-black text-3xl text-gray-900 leading-tight">
                                    {{ form.id ? 'Editar Negocio' : 'Nuevo Establecimiento' }}
                                </h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ form.id ? `ID: ${form.type}#${form.id}` : 'Configuración de alta' }}</p>
                            </div>
                        </div>
                        <button @click="emit('close')" class="text-gray-300 hover:text-gray-900 transition-all p-4 hover:bg-gray-50 rounded-3xl">
                            <X class="w-8 h-8" />
                        </button>
                    </div>

                    <!-- Scrollable Area -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <form @submit.prevent="submit" class="p-10 space-y-16">
                            
                            <!-- 1. IDENTIDAD Y FOTO -->
                            <section class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                                <div class="lg:col-span-4">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 block ml-1">Imagen Principal</label>
                                    <div @click="fileInput.click()" class="relative aspect-[4/5] rounded-[2.5rem] overflow-hidden bg-gray-50 border border-dashed border-gray-200 hover:border-indigo-400 transition-all cursor-pointer group shadow-inner">
                                        <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-indigo-600/10 opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-[2px]">
                                            <Camera class="w-12 h-12 text-indigo-600" />
                                        </div>
                                        <input ref="fileInput" type="file" class="hidden" @change="handleImageChange" accept="image/*" />
                                    </div>
                                </div>

                                <div class="lg:col-span-8 space-y-8">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2 col-span-2 md:col-span-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Categoría</label>
                                            <select v-model="form.type" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 font-bold text-sm appearance-none cursor-pointer">
                                                <option value="restaurant">Restaurante / Gastronomía</option>
                                                <option value="sport_center">Centro Deportivo / Gimnasio</option>
                                                <option value="health_center">Centro de Salud / Clínica</option>
                                                <option value="beauty_center">Centro de Belleza / Peluquería</option>
                                                <option value="leisure_center">Centro de Ocio</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2 col-span-2 md:col-span-1">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">{{ localTypeLabel }}</label>
                                            <input v-model="form.local_type" type="text" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 font-bold text-sm" placeholder="Ej: Pizzería, Crossfit..." />
                                        </div>
                                        <div class="space-y-2 col-span-2">
                                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre Comercial</label>
                                            <input v-model="form.name" type="text" required class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500/20 font-black text-xl" />
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Descripción Breve</label>
                                        <textarea v-model="form.description" rows="4" class="w-full p-6 bg-gray-50 border-none rounded-[2rem] focus:ring-2 focus:ring-indigo-500/20 font-bold text-sm leading-relaxed"></textarea>
                                    </div>
                                </div>
                            </section>

                            <!-- 2. UBICACIÓN INTELIGENTE -->
                            <section class="space-y-8">
                                <h4 class="text-xs font-black text-indigo-600 uppercase tracking-[0.3em] border-b border-indigo-50 pb-4 flex items-center gap-3">
                                    <MapPin class="w-5 h-5" /> Ubicación y Datos de Contacto
                                </h4>
                                <div class="p-8 bg-indigo-50/40 rounded-[2.5rem] border border-indigo-100/50 shadow-inner">
                                    <AddressAutocomplete v-model="form.address" @place_changed="handleLocationSelected" />
                                    <div v-if="form.latitude" class="mt-4 flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest bg-emerald-50 w-fit px-4 py-2 rounded-full border border-emerald-100">
                                        <CheckCircle2 class="w-3 h-3" /> Posicionamiento GPS validado
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Teléfono</label>
                                        <input v-model="form.phone" type="tel" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl font-bold text-sm" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Público</label>
                                        <input v-model="form.email" type="email" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl font-bold text-sm" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Sitio Web</label>
                                        <input v-model="form.website" type="url" class="w-full px-6 py-5 bg-gray-50 border-none rounded-2xl font-bold text-sm" />
                                    </div>
                                </div>
                            </section>

                            <!-- 3. CONFIGURACIÓN DE RESERVAS (ZONAS Y SERVICIOS) -->
                            <section class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                <!-- Recursos (Zonas) -->
                                <div class="space-y-8 bg-orange-50/20 p-8 rounded-[2.5rem] border border-orange-100/50">
                                    <div class="flex justify-between items-center border-b border-orange-100 pb-4">
                                        <h4 class="text-xs font-black text-orange-600 uppercase tracking-[0.3em] flex items-center gap-3">
                                            <Users class="w-5 h-5" /> Recursos (Zonas)
                                        </h4>
                                        <button type="button" @click="addResource" class="w-10 h-10 bg-orange-600 text-white rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-orange-100">
                                            <Plus class="w-6 h-6" />
                                        </button>
                                    </div>
                                    <div class="space-y-6">
                                        <div v-for="(res, idx) in form.resources" :key="'res-'+idx" class="p-6 bg-white rounded-2xl shadow-sm border border-orange-50 animate-in slide-in-from-right-4 duration-300">
                                            <div class="flex justify-between items-start mb-4">
                                                <input v-model="res.name" placeholder="Ej: Terraza, Salón..." class="flex-1 px-5 py-4 bg-gray-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-orange-500/20" />
                                                <button v-if="form.resources.length > 1" @click="removeResource(idx)" type="button" class="ml-4 text-red-400 hover:text-red-600 p-2 transition-colors">
                                                    <Trash2 class="w-5 h-5" />
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="space-y-2 col-span-1 md:col-span-2">
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipo de Reserva</label>
                                                    <select v-model="res.resource_type" class="w-full px-5 py-3 bg-gray-50 border-none rounded-xl font-bold text-sm text-gray-700">
                                                        <option value="exclusive_unit">Exclusivo por unidad (Mesas, pistas, habitaciones)</option>
                                                        <option value="shared">Compartido por plazas (Clases, eventos)</option>
                                                    </select>
                                                </div>

                                                <template v-if="res.resource_type === 'exclusive_unit'">
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nº de Unidades</label>
                                                        <input v-model="res.unit_count" type="number" min="1" placeholder="Ej: 8 mesas" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-black text-gray-700" />
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Personas por unidad</label>
                                                        <input v-model="res.unit_capacity" type="number" min="1" placeholder="Ej: 4 personas" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-black text-gray-700" />
                                                    </div>
                                                </template>

                                                <div class="space-y-2 col-span-1 md:col-span-2">
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Máx. personas por reserva</label>
                                                    <input v-model="res.max_guests_per_booking" type="number" min="1" class="w-full px-4 py-3 bg-gray-50 border-none rounded-xl text-sm font-black text-gray-700" />
                                                    <p v-if="res.resource_type === 'exclusive_unit'" class="text-[10px] text-gray-400 mt-1 ml-1 leading-tight">
                                                        Al reservar, se bloquearán tantas unidades como se necesiten (ej: si reservan {{ res.max_guests_per_booking }} personas y caben {{ res.unit_capacity }} por unidad, se bloquearán {{ Math.ceil((res.max_guests_per_booking || 1) / (res.unit_capacity || 1)) }} unidades).
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Servicios -->
                                <div class="space-y-8 bg-emerald-50/20 p-8 rounded-[2.5rem] border border-emerald-100/50">
                                    <div class="flex justify-between items-center border-b border-emerald-100 pb-4">
                                        <h4 class="text-xs font-black text-emerald-600 uppercase tracking-[0.3em] flex items-center gap-3">
                                            <CreditCard class="w-5 h-5" /> Servicios
                                        </h4>
                                        <button type="button" @click="addService" class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center hover:scale-110 transition-transform shadow-lg shadow-emerald-100">
                                            <Plus class="w-6 h-6" />
                                        </button>
                                    </div>
                                    <div class="space-y-5">
                                        <div v-for="(srv, idx) in form.services" :key="'srv-'+idx" class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-50 space-y-4 animate-in slide-in-from-right-4 duration-300">
                                            <input v-model="srv.name" placeholder="Nombre del servicio (Ej: Cena)" class="w-full bg-gray-50 border-none rounded-xl text-sm font-bold" />
                                            <div class="flex gap-4">
                                                <select v-model="srv.duration_minutes" class="flex-1 bg-gray-50 border-none rounded-xl text-xs font-bold appearance-none">
                                                    <option :value="30">30 min</option>
                                                    <option :value="60">1 hora</option>
                                                    <option :value="90">1.5 horas</option>
                                                    <option :value="120">2 horas</option>
                                                </select>
                                                <div class="w-28 relative">
                                                    <input v-model="srv.price" type="number" placeholder="0.00" class="w-full bg-gray-50 border-none rounded-xl text-sm font-black pr-8" />
                                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">€</span>
                                                </div>
                                                <button v-if="form.services.length > 1" @click="removeService(idx)" type="button" class="text-red-400 hover:text-red-600 px-1">
                                                    <Trash2 class="w-5 h-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- 4. HORARIOS SEMANALES -->
                            <section class="space-y-8">
                                <h4 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em] border-b border-gray-100 pb-4 flex items-center gap-3">
                                    <Clock class="w-5 h-5" /> Configuración de Horarios
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="(day, idx) in form.schedules" :key="idx" class="flex items-center gap-6 p-5 bg-gray-50 rounded-2xl border border-gray-100 transition-all hover:bg-white hover:shadow-md group">
                                        <div class="w-24 font-black text-sm text-gray-700">{{ day.name }}</div>
                                        <label class="flex items-center cursor-pointer relative">
                                            <input type="checkbox" v-model="day.is_closed" class="sr-only peer" />
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                            <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-gray-400 peer-checked:text-red-500">
                                                {{ day.is_closed ? 'Cerrado' : 'Abierto' }}
                                            </span>
                                        </label>
                                        <div v-if="!day.is_closed" class="flex items-center gap-3 flex-1 justify-end opacity-100 group-hover:opacity-100 transition-opacity">
                                            <input type="time" v-model="day.opening_time" class="p-2 bg-white border-none rounded-lg text-xs font-black shadow-sm" />
                                            <span class="text-gray-300 font-bold">a</span>
                                            <input type="time" v-model="day.closing_time" class="p-2 bg-white border-none rounded-lg text-xs font-black shadow-sm" />
                                        </div>
                                        <div v-else class="flex-1 text-right text-[10px] font-bold text-gray-300 italic uppercase tracking-tighter">Descanso semanal</div>
                                    </div>
                                </div>
                            </section>

                            <!-- 5. EXTRAS -->
                            <section class="space-y-6">
                                <h4 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] border-b border-gray-50 pb-4">Características Adicionales</h4>
                                <div class="flex flex-wrap gap-4">
                                    <button type="button" @click="form.smoking_area = !form.smoking_area" :class="form.smoking_area ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'bg-gray-50 text-gray-400'" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95">
                                        Zona Fumadores
                                    </button>
                                    <button type="button" @click="form.has_parking = !form.has_parking" :class="form.has_parking ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'bg-gray-50 text-gray-400'" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95">
                                        Aparcamiento
                                    </button>
                                    <button type="button" @click="form.has_emergency = !form.has_emergency" :class="form.has_emergency ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100' : 'bg-gray-50 text-gray-400'" class="px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95">
                                        Urgencias 24h
                                    </button>
                                </div>
                            </section>

                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="p-10 border-t border-gray-50 bg-white flex gap-6 shrink-0 shadow-[0_-10px_40px_rgba(0,0,0,0.02)]">
                        <button type="button" @click="emit('close')" class="flex-1 px-10 py-6 border border-gray-100 text-gray-400 rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] hover:bg-gray-50 transition-all">
                            Descartar
                        </button>
                        <button @click="submit" :disabled="form.processing" class="flex-[2] px-12 py-6 bg-indigo-600 text-white rounded-[2rem] text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 hover:scale-[1.02] active:scale-95 transition-all shadow-2xl shadow-indigo-100 disabled:opacity-50">
                            {{ form.processing ? 'Guardando...' : (form.id ? 'Guardar Cambios' : 'Finalizar Alta') }}
                        </button>
                    </div>
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
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}
</style>
