<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register" />

    <div
        class="min-h-screen bg-gradient-to-br from-[#b8d4c8] to-[#a3c4b5] flex items-center justify-center p-4"
    >
        <div
            class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex"
        >
            <!-- Left Side - Form -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <Link href="/">
                        <img
                            src="/images/logo-proreserve.png"
                            alt="ProReserve"
                            class="h-16 w-auto"
                        />
                    </Link>
                </div>

                <div class="max-w-md mx-auto">
                    <h1
                        class="text-3xl font-bold text-[#111827] mb-2 text-center"
                    >
                        Crear Cuenta
                    </h1>
                    <p class="text-gray-600 mb-8 text-center">
                        Únete a ProReserve y gestiona tus reservas
                    </p>

                    <!-- Login Tabs -->
                    <div class="flex gap-4 mb-8">
                        <Link
                            :href="route('login')"
                            class="flex-1 py-3 text-center border-2 border-gray-300 text-gray-600 rounded-xl font-medium hover:border-[#b8d4c8] hover:text-[#b8d4c8] transition-all"
                        >
                            Iniciar Sesión
                        </Link>
                        <button
                            type="button"
                            class="flex-1 py-3 text-center bg-[#111827] text-white rounded-xl font-medium"
                        >
                            Registrarse
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <InputLabel
                                for="name"
                                value="Nombre completo"
                                class="text-gray-700 font-medium mb-2"
                            />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Ingresa tu nombre"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="email"
                                value="Correo electrónico"
                                class="text-gray-700 font-medium mb-2"
                            />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                placeholder="tu@email.com"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="password"
                                value="Contraseña"
                                class="text-gray-700 font-medium mb-2"
                            />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="password_confirmation"
                                value="Confirmar contraseña"
                                class="text-gray-700 font-medium mb-2"
                            />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-semibold py-3 rounded-xl transition-colors duration-200 disabled:opacity-50"
                            :disabled="form.processing"
                        >
                            Crear Cuenta
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <Link
                            :href="route('login')"
                            class="text-sm text-gray-600 hover:text-[#b8d4c8] font-medium"
                        >
                            ¿Ya tienes cuenta? Inicia sesión aquí
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div
                class="hidden lg:block lg:w-1/2 relative bg-gradient-to-br from-[#b8d4c8] to-[#a3c4b5] overflow-hidden"
            >
                <div
                    class="absolute inset-0 flex flex-col items-center justify-center p-12 text-[#111827] z-10"
                >
                    <div class="max-w-md text-center space-y-6">
                        <h2 class="text-4xl font-bold leading-tight">
                            Gestiona tus reservas de forma profesional
                        </h2>
                        <p class="text-xl text-gray-700">
                            Sistema diseñado para negocios que valoran la
                            simplicidad y la eficiencia
                        </p>
                        <div class="flex justify-center gap-3 pt-4">
                            <div
                                class="w-3 h-3 bg-[#111827] rounded-full"
                            ></div>
                            <div
                                class="w-3 h-3 bg-[#111827]/50 rounded-full"
                            ></div>
                            <div
                                class="w-3 h-3 bg-[#111827]/50 rounded-full"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg
                        class="w-full h-full"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <defs>
                            <pattern
                                id="grid"
                                width="40"
                                height="40"
                                patternUnits="userSpaceOnUse"
                            >
                                <path
                                    d="M 40 0 L 0 0 0 40"
                                    fill="none"
                                    stroke="#111827"
                                    stroke-width="1"
                                />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
