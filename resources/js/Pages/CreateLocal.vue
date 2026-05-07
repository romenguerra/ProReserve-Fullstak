<template>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                <div class="mb-8 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Alta de Establecimiento (Avanzado)</h2>
                    <p class="text-gray-500 mt-1">
                        Completa toda la información de tu negocio.
                    </p>
                </div>

                <form @submit.prevent="submitForm">
                    <!-- SECCIÓN 1: TIPO Y DATOS BÁSICOS -->
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2">1. Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría Principal *</label>
                            <select v-model="form.type" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled>Selecciona una categoría...</option>
                                <option value="restaurant">Restaurante / Gastronomía</option>
                                <option value="sport_center">Centro Deportivo / Gimnasio</option>
                                <option value="health_center">Centro de Salud / Clínica</option>
                                <option value="beauty_center">Centro de Belleza / Peluquería</option>
                                <option value="leisure_center">Centro de Ocio</option>
                            </select>
                        </div>
                        
                        <div v-if="form.type">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ localTypeLabel }}</label>
                            <input v-model="form.local_type" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required :placeholder="localTypePlaceholder" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Establecimiento *</label>
                            <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción corta</label>
                            <textarea v-model="form.description" rows="3" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <!-- Foto del Local -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Portada</label>
                            <div class="flex items-center gap-6">
                                <div v-if="imagePreview" class="relative w-32 h-32 rounded-2xl overflow-hidden border-2 border-indigo-100 shadow-md shrink-0">
                                    <img :src="imagePreview" class="w-full h-full object-cover" />
                                    <button @click="removeImage" type="button" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <div 
                                    class="flex-1 border-2 border-dashed border-gray-300 rounded-2xl p-6 hover:border-indigo-400 hover:bg-indigo-50/30 transition-all cursor-pointer group relative"
                                    @click="$refs.fileInput.click()"
                                >
                                    <input 
                                        type="file" 
                                        ref="fileInput" 
                                        class="hidden" 
                                        @change="handleImageChange" 
                                        accept="image/*"
                                    />
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <svg class="w-10 h-10 text-gray-400 group-hover:text-indigo-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-sm text-gray-600 font-bold group-hover:text-indigo-700 transition-colors">Haz clic para subir una foto</p>
                                        <p class="text-xs text-gray-400 mt-1">Soporta JPG, PNG y AVIF (Máx. 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: UBICACIÓN Y CONTACTO -->
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2 mt-8">2. Ubicación y Contacto</h3>
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <AddressAutocomplete v-model="form.address" @place_changed="handleLocationSelected" />
                        <div v-if="form.latitude" class="mt-2 text-sm text-green-600 font-semibold">✓ Ubicación exacta detectada.</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input v-model="form.phone" type="tel" class="w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Público</label>
                            <input v-model="form.email" type="email" class="w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sitio Web</label>
                            <input v-model="form.website" type="url" class="w-full border-gray-300 rounded-md shadow-sm" />
                        </div>
                    </div>

                    <!-- SECCIÓN 3: HORARIOS SEMANALES -->
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2 mt-8">3. Horarios Semanales</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <div v-for="(day, index) in form.schedules" :key="index" class="flex items-center gap-4 mb-3 pb-3 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                            <div class="w-24 font-medium text-gray-700">{{ day.name }}</div>
                            <label class="flex items-center cursor-pointer w-24">
                                <input type="checkbox" v-model="day.is_closed" class="rounded border-gray-300 text-red-500 shadow-sm" />
                                <span class="ml-2 text-sm text-gray-600" :class="{'text-red-500 font-bold': day.is_closed}">Cerrado</span>
                            </label>
                            <div v-if="!day.is_closed" class="flex items-center gap-2 flex-1">
                                <input type="time" v-model="day.opening_time" class="border-gray-300 rounded-md shadow-sm text-sm" required />
                                <span class="text-gray-500">a</span>
                                <input type="time" v-model="day.closing_time" class="border-gray-300 rounded-md shadow-sm text-sm" required />
                            </div>
                            <div v-else class="flex-1 text-sm text-gray-400 italic">
                                Sin horario (Día de descanso)
                            </div>
                        </div>
                    </div>

                    <!-- CARACTERÍSTICAS ESPECÍFICAS -->
                    <div v-if="form.type" class="mb-6 p-4 bg-indigo-50/50 rounded-lg border border-indigo-100">
                        <h4 class="font-bold text-indigo-800 mb-3 text-sm">Características del Local</h4>
                        <div class="flex flex-wrap gap-6">
                            <label v-if="form.type === 'restaurant'" class="flex items-center">
                                <input type="checkbox" v-model="form.smoking_area" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                                <span class="ml-2 text-sm text-gray-700">Zona de Fumadores permitida</span>
                            </label>

                            <label v-if="['sport_center', 'leisure_center', 'health_center'].includes(form.type)" class="flex items-center">
                                <input type="checkbox" v-model="form.has_parking" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                                <span class="ml-2 text-sm text-gray-700">Aparcamiento Privado</span>
                            </label>

                            <label v-if="form.type === 'health_center'" class="flex items-center">
                                <input type="checkbox" v-model="form.has_emergency" class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                                <span class="ml-2 text-sm text-gray-700">Urgencias 24h</span>
                            </label>
                        </div>
                    </div>

                    <!-- SECCIÓN 4: RECURSOS Y SERVICIOS -->
                    <h3 class="text-lg font-semibold text-indigo-700 mb-4 border-b pb-2 mt-8">4. Configuración de Reservas</h3>
                    
                    <!-- RECURSOS -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-700">Recursos a Reservar (Zonas donde el cliente hará la reserva)</h4>
                            <button type="button" @click="addResource" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Añadir Recurso
                            </button>
                        </div>
                        
                        <div v-for="(resource, index) in form.resources" :key="'res-'+index" class="flex gap-4 mb-3 items-end">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nombre Público (Ej: Salón 1, Terraza, Pista 3)</label>
                                <input v-model="resource.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <div class="w-32">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Mesas/Capacidad</label>
                                <input v-model="resource.capacity" type="number" min="1" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <div class="w-10 pb-2">
                                <button v-if="form.resources.length > 1" type="button" @click="removeResource(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SERVICIOS -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-gray-700">Servicios Ofrecidos</h4>
                            <button type="button" @click="addService" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Añadir Servicio
                            </button>
                        </div>

                        <div v-for="(service, index) in form.services" :key="'srv-'+index" class="flex flex-wrap md:flex-nowrap gap-4 mb-4 items-end border-b border-gray-200 pb-4 last:border-0 last:pb-0 last:mb-0">
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Nombre (Ej: Cena, Menú Degustación, Masaje)</label>
                                <input v-model="service.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <div class="w-full md:w-32">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Duración (min)</label>
                                <select v-model="service.duration_minutes" class="w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="30">30 min</option>
                                    <option value="60">1 hora</option>
                                    <option value="90">1.5 horas</option>
                                    <option value="120">2 horas</option>
                                    <option value="180">3 horas</option>
                                </select>
                            </div>
                            <div class="w-full md:w-32">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Precio (€)</label>
                                <input v-model="service.price" type="number" min="0" step="0.5" class="w-full border-gray-300 rounded-md shadow-sm" />
                            </div>
                            <div class="w-10 pb-2 flex justify-center md:justify-start">
                                <button v-if="form.services.length > 1" type="button" @click="removeService(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Botón Enviar -->
                    <div class="flex justify-end pt-4 border-t">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-md transition-colors text-lg shadow-lg" :disabled="form.processing">
                            <span v-if="form.processing">Guardando Establecimiento...</span>
                            <span v-else>Completar Registro de Local</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AddressAutocomplete from '@/Components/AddressAutocomplete.vue';

const localTypeLabel = computed(() => {
    switch (form.type) {
        case 'restaurant': return 'Tipo de Local / Especialidad *';
        case 'sport_center': return 'Tipo de Instalación *';
        case 'health_center': return 'Tipo de Centro Médico *';
        case 'beauty_center': return 'Tipo de Centro Estético *';
        case 'leisure_center': return 'Tipo de Centro de Ocio *';
        default: return 'Tipo de Local / Especialidad *';
    }
});

const localTypePlaceholder = computed(() => {
    switch (form.type) {
        case 'restaurant': return 'Ej: Asador, Pizzería, Italiano...';
        case 'sport_center': return 'Ej: Crossfit, Gimnasio, Pádel...';
        case 'health_center': return 'Ej: Clínica Dental, Hospital, Fisioterapia...';
        case 'beauty_center': return 'Ej: Peluquería, Spa, Barbería...';
        case 'leisure_center': return 'Ej: Cine, Bolera, Pub...';
        default: return '';
    }
});
const fileInput = ref(null);
const imagePreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};
const form = useForm({
    type: '',
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
    
    // Address
    address: '',
    city: '',
    postal_code: '',
    latitude: null,
    longitude: null,

    // Schedules
    schedules: [
        { day_of_week: 1, name: 'Lunes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 2, name: 'Martes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 3, name: 'Miércoles', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 4, name: 'Jueves', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 5, name: 'Viernes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 6, name: 'Sábado', is_closed: false, opening_time: '10:00', closing_time: '23:00' },
        { day_of_week: 7, name: 'Domingo', is_closed: true, opening_time: '10:00', closing_time: '23:00' },
    ],

    // Resources
    resources: [
        { name: 'Salón Principal', capacity: 10 }
    ],

    // Services
    services: [
        { name: 'Servicio Principal', duration_minutes: 60, price: 0 }
    ],
});

const addResource = () => {
    form.resources.push({ name: '', capacity: 1 });
};

const removeResource = (index) => {
    if (form.resources.length > 1) {
        form.resources.splice(index, 1);
    }
};

const addService = () => {
    form.services.push({ name: '', duration_minutes: 60, price: 0 });
};

const removeService = (index) => {
    if (form.services.length > 1) {
        form.services.splice(index, 1);
    }
};

const handleLocationSelected = (locationData) => {
    form.latitude = locationData.latitude;
    form.longitude = locationData.longitude;
    
    const addressComponents = locationData.place.address_components;
    if (addressComponents) {
        for (const component of addressComponents) {
            const types = component.types;
            if (types.includes('locality')) {
                form.city = component.long_name;
            }
            if (types.includes('postal_code')) {
                form.postal_code = component.long_name;
            }
        }
    }
};

const submitForm = () => {
    if (!form.latitude || !form.longitude) {
        alert("Por favor, selecciona la dirección exacta desde el buscador de mapas.");
        return;
    }

    form.post(route('locales.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>
