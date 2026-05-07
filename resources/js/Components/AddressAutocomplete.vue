<template>
    <div class="relative">
        <label class="block text-sm font-bold text-gray-700 mb-2">Dirección del Local</label>
        <div class="relative group">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </span>
            <input
                type="text"
                v-model="query"
                @input="handleInput"
                placeholder="Busca una dirección (Ej: Calle Mayor 1, Madrid)..."
                class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-100 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-300 shadow-sm"
                autocomplete="off"
            />
            
            <!-- Cargador -->
            <div v-if="loading" class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Resultados -->
        <div 
            v-if="results.length > 0 && showDropdown" 
            class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden max-h-72 overflow-y-auto"
        >
            <button
                v-for="(result, index) in results"
                :key="index"
                @click="selectResult(result)"
                type="button"
                class="w-full text-left px-6 py-4 hover:bg-indigo-50 transition-colors border-b border-gray-50 last:border-0 flex items-start gap-4 group"
            >
                <div class="mt-1 p-2 rounded-lg bg-gray-50 group-hover:bg-white transition-colors">
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900 leading-none mb-1">{{ result.display_name.split(',')[0] }}</p>
                    <p class="text-xs text-gray-500 line-clamp-1">{{ result.display_name }}</p>
                </div>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    modelValue: String
});

const emit = defineEmits(['update:modelValue', 'place_changed']);

const query = ref(props.modelValue || '');
const results = ref([]);
const loading = ref(false);
const showDropdown = ref(false);
let timeout = null;

const handleInput = () => {
    clearTimeout(timeout);
    showDropdown.value = true;
    
    if (query.value.length < 3) {
        results.value = [];
        return;
    }

    timeout = setTimeout(async () => {
        loading.value = true;
        try {
            // Usamos el servicio gratuito Nominatim de OpenStreetMap
            const response = await axios.get(`https://nominatim.openstreetmap.org/search`, {
                params: {
                    q: query.value,
                    format: 'json',
                    addressdetails: 1,
                    limit: 5,
                    countrycodes: 'es' // Limitado a España para mejor precisión
                }
            });
            results.value = response.data;
        } catch (error) {
            console.error("Error geocoding:", error);
        } finally {
            loading.value = false;
        }
    }, 500);
};

const selectResult = (result) => {
    const mainAddress = result.display_name.split(',')[0];
    query.value = result.display_name;
    showDropdown.value = false;
    results.value = [];

    emit('update:modelValue', result.display_name);
    
    // Mapeamos al formato que espera CreateLocal.vue
    emit('place_changed', {
        latitude: parseFloat(result.lat),
        longitude: parseFloat(result.lon),
        place: {
            address_components: [
                { types: ['locality'], long_name: result.address.city || result.address.town || result.address.village || '' },
                { types: ['postal_code'], long_name: result.address.postcode || '' }
            ]
        }
    });
};

// Cerrar dropdown al hacer clic fuera
const closeDropdown = (e) => {
    if (!e.target.closest('.relative')) {
        showDropdown.value = false;
    }
};

onMounted(() => window.addEventListener('click', closeDropdown));
onUnmounted(() => window.removeEventListener('click', closeDropdown));
</script>
