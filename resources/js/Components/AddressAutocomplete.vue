<template>
  <div>
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div class="relative">
      <!-- Icono de mapa para darle estilo -->
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
        </svg>
      </div>
      <input
        ref="inputRef"
        type="text"
        class="pl-10 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :placeholder="placeholder"
        v-model="internalValue"
      />
    </div>
    <p class="mt-1 text-xs text-gray-500">
      Asegúrate de seleccionar una dirección de la lista desplegable de Google para obtener la ubicación exacta.
    </p>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { setOptions, importLibrary } from '@googlemaps/js-api-loader';

const props = defineProps({
  modelValue: String, 
  label: {
    type: String,
    default: 'Dirección del Local'
  },
  placeholder: {
    type: String,
    default: 'Escribe la calle y selecciona...'
  }
});

const emit = defineEmits(['update:modelValue', 'place_changed']);

const inputRef = ref(null);
const internalValue = ref(props.modelValue || '');

// Sincronizar cambios externos
watch(() => props.modelValue, (newVal) => {
  if (newVal !== internalValue.value) {
    internalValue.value = newVal || '';
  }
});

// Sincronizar cambios manuales del input
watch(internalValue, (newVal) => {
  emit('update:modelValue', newVal);
});

onMounted(async () => {
  const apiKey = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;
  
  if (!apiKey) {
    console.error("Falta VITE_GOOGLE_MAPS_API_KEY en el archivo .env");
    return;
  }

  try {
    // Configuración global (solo una vez)
    if (typeof window !== 'undefined' && !window.__googleMapsCallback) {
        setOptions({
            apiKey: apiKey,
            version: "weekly",
            libraries: ["maps", "marker", "places"]
        });
        window.__googleMapsCallback = true;
    }

    const { Autocomplete } = await importLibrary("places");
    // Cargamos también la librería core para acceder a event.addListener si fuera necesario, 
    // aunque Autocomplete usa un addListener directamente.

    // Inicializar el Autocomplete de Google
    const autocomplete = new Autocomplete(inputRef.value, {
      types: ['address'], // Queremos buscar direcciones (calles, plazas, etc)
    });

    // Evitar que al darle 'Enter' en el dropdown se envíe el formulario por error
    inputRef.value.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
      }
    });

    // Escuchar el evento cuando el usuario selecciona un lugar del dropdown
    autocomplete.addListener('place_changed', () => {
      const place = autocomplete.getPlace();
      
      if (!place.geometry || !place.geometry.location) {
        // El usuario escribió algo pero no seleccionó nada del dropdown
        return;
      }

      // Extraemos la información relevante
      const lat = place.geometry.location.lat();
      const lng = place.geometry.location.lng();
      const formattedAddress = place.formatted_address;

      // Actualizamos el input con la dirección bonita de Google
      internalValue.value = formattedAddress;

      // Emitimos un evento personalizado con las coordenadas y datos extra
      emit('place_changed', {
        address: formattedAddress,
        latitude: lat,
        longitude: lng,
        place: place
      });
    });
  } catch (e) {
    console.error("Error al cargar Google Maps API: ", e);
  }
});
</script>
