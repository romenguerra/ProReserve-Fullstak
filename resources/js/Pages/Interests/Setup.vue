<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

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

    <div class="min-h-screen relative flex flex-col justify-center items-center overflow-hidden bg-white py-12">
        <!-- Mesh Gradient Background -->
        <div class="absolute inset-0 opacity-40 pointer-events-none" style="
            background-image: 
                radial-gradient(at 0% 0%, #B8D4C8 0px, transparent 50%),
                radial-gradient(at 100% 0%, #E8F3EF 0px, transparent 50%),
                radial-gradient(at 100% 100%, #A3C4B5 0px, transparent 50%),
                radial-gradient(at 0% 100%, #CDE5DB 0px, transparent 50%);
            filter: blur(60px);
        "></div>

        <div class="w-full sm:max-w-3xl relative z-10 px-6">
            <div class="bg-white/80 backdrop-blur-xl border border-gray-100 p-8 sm:p-12 rounded-[3rem] shadow-2xl shadow-gray-200/50">
                <div class="mb-12 text-center">
                    <h2 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight tracking-tighter">
                        Personaliza tu <span class="text-[#8EB6A5]">experiencia.</span>
                    </h2>
                    <p class="text-lg text-gray-500 mt-4 max-w-md mx-auto">Selecciona los temas que más te gustan para que podamos recomendarte los mejores planes.</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-12">
                        <div
                            v-for="(interest, index) in interests"
                            :key="interest.id"
                            :data-index="index"
                            @click="toggleInterest(interest.id)"
                            class="group cursor-pointer bg-white border border-gray-100 rounded-[2rem] p-6 text-center transition-all duration-300 hover:shadow-lg hover:shadow-gray-200/50"
                            :class="{
                                'ring-2 ring-[#8EB6A5] bg-[#8EB6A5]/5 border-[#8EB6A5]': selectedInterests.includes(interest.id),
                                'hover:border-[#8EB6A5]/30': !selectedInterests.includes(interest.id)
                            }"
                        >
                            <div class="text-4xl mb-3 transform transition-transform group-hover:scale-110 duration-300">
                                {{ interest.icon }}
                            </div>
                            <span class="font-bold text-gray-900 group-hover:text-[#8EB6A5] transition-colors">
                                {{ interest.name }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                        <button 
                            @click="skip" 
                            type="button"
                            class="text-gray-400 font-bold hover:text-gray-900 transition-colors order-2 sm:order-1"
                        >
                            Omitir este paso
                        </button>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-auto bg-gray-900 text-white font-bold px-10 py-5 rounded-full hover:bg-black transition-all duration-300 shadow-xl shadow-gray-400/20 disabled:opacity-50 order-1 sm:order-2"
                        >
                            {{ form.processing ? 'Guardando...' : 'Guardar y Continuar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
