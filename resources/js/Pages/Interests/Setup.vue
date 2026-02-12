<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    interests: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    interests: [],
});

const selectedInterests = ref([]);

const toggleInterest = (id) => {
    if (selectedInterests.value.includes(id)) {
        selectedInterests.value = selectedInterests.value.filter((i) => i !== id);
    } else {
        selectedInterests.value.push(id);
    }
    // No asignamos directamente a form.interests aquí para mantener la reactividad limpia
};

const submit = () => {
    form.interests = selectedInterests.value;
    form.post(route('interests.store'), {
        onFinish: () => form.reset('interests'),
    });
};

const skip = () => {
    form.post(route('interests.store'));
};
</script>

<template>
    <Head title="Selecciona tus intereses" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">¡Bienvenido!</h2>
                <p class="text-gray-600 mt-2">Selecciona los temas que te interesan para personalizar tu experiencia.</p>
            </div>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    <div
                        v-for="interest in interests"
                        :key="interest.id"
                        @click="toggleInterest(interest.id)"
                        class="cursor-pointer border rounded-lg p-4 text-center transition-all duration-200"
                        :class="{
                            'bg-indigo-50 border-indigo-500 ring-2 ring-indigo-200': selectedInterests.includes(interest.id),
                            'bg-white border-gray-200 hover:border-indigo-300': !selectedInterests.includes(interest.id)
                        }"
                    >
                        <div class="text-3xl mb-2">{{ interest.icon }}</div>
                        <span class="font-medium text-gray-700">{{ interest.name }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <SecondaryButton @click="skip" type="button">
                        Omitir por ahora
                    </SecondaryButton>

                    <PrimaryButton
                        class="ml-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Guardar y Continuar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
