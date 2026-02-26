<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};

</script>

<template>
    <Head title="Recuperar Contraseña" />

    <div class="auth-page-wrapper">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden p-8 lg:p-12">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <Link href="/">
                    <img src="/images/logo-proreserve.png" alt="ProReserve" class="h-16 w-auto" />
                </Link>
            </div>

            <div class="max-w-sm mx-auto">
                <h1 class="text-2xl font-bold text-[#111827] mb-2 text-center">
                    ¿Olvidaste tu contraseña?
                </h1>
                <p class="text-gray-600 mb-8 text-center text-sm">
                    No hay problema. Solo indícanos tu dirección de correo electrónico y te enviaremos un enlace para restablecerla.
                </p>

                <div v-if="status" class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-medium border border-green-100 italic text-center">
                    {{ status }}
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <InputLabel for="email" value="Correo electrónico" class="text-gray-700 font-medium mb-2" />
                        <TextInput id="email" type="email"
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                            v-model="form.email" required autofocus autocomplete="username" placeholder="tu@email.com" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <button type="submit"
                        class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-semibold py-3 rounded-xl transition-colors duration-200 disabled:opacity-50"
                        :disabled="form.processing">
                        Enviar enlace
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <Link :href="route('login')" class="text-sm text-gray-600 hover:text-[#b8d4c8] font-medium transition-colors">
                        ← Volver al login
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

