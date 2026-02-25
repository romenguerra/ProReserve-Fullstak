<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<!-- 
<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div>
                    <Link
                        :href="route('register')"
                        class="text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        ¿No tienes cuenta? Regístrate aquí
                    </Link>
                </div>

                <div class="flex items-center">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 me-4"
                    >
                        ¿Olvidaste tu contraseña?
                    </Link>

                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Iniciar sesión
                    </PrimaryButton>
                </div>
            </div>
        </form>
    </GuestLayout>
</template> -->


<template>

    <Head title="Log in" />

    <div
        class="min-h-screen bg-gradient-to-tr from-[#9ec4b3] via-[#e5f0eb] to-[#70a891] flex items-center justify-center p-4">
        <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden flex">
            <!-- Left Side - Form -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <Link href="/">
                        <img src="/images/logo-proreserve.png" alt="ProReserve" class="h-16 w-auto" />
                    </Link>
                </div>

                <div class="max-w-md mx-auto">
                    <h1 class="text-3xl font-bold text-[#111827] mb-2 text-center">
                        Iniciar Sesión
                    </h1>
                    <p class="text-gray-600 mb-8 text-center">
                        Inicia sesión en ProReserve y gestiona tus reservas
                    </p>

                    <!-- Login Tabs -->
                    <div class="flex gap-4 mb-8">
                        <button type="button"
                            class="flex-1 py-3 text-center bg-[#111827] text-white rounded-xl font-medium">
                            Iniciar Sesión
                        </button>
                        <Link :href="route('register')"
                            class="flex-1 py-3 text-center border-2 border-gray-300 text-gray-600 rounded-xl font-medium hover:border-[#b8d4c8] hover:text-[#b8d4c8] transition-all">
                            Registrarse
                        </Link>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-5">

                        <div>
                            <InputLabel for="email" value="Correo electrónico" class="text-gray-700 font-medium mb-2" />
                            <TextInput id="email" type="email"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.email" required autocomplete="username" placeholder="tu@email.com" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Contraseña" class="text-gray-700 font-medium mb-2" />
                            <TextInput id="password" type="password"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#b8d4c8] focus:border-transparent"
                                v-model="form.password" required autocomplete="new-password" placeholder="••••••••" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <button type="submit"
                            class="w-full bg-[#111827] hover:bg-[#1f2937] text-white font-semibold py-3 rounded-xl transition-colors duration-200 disabled:opacity-50"
                            :disabled="form.processing">
                            Iniciar Sesión
                        </button>
                    </form>

                    <div class="mt-6 text-center flex flex-col gap-4">
                        <Link :href="route('register')" class="text-sm text-gray-600 hover:text-[#b8d4c8] font-medium">
                            ¿No tienes cuenta? Registrate aquí
                        </Link>
                        
                        <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 me-4">

                        ¿Olvidaste tu contraseña?
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right Side - Image -->
            <div
                class="hidden lg:block lg:w-1/2 relative bg-[url('/images/register-hero.png')] bg-cover bg-center overflow-hidden">
                <!-- Overlay para mejorar legibilidad -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#111827]/80 via-[#111827]/40 to-[#111827]/20 z-0">
                </div>
                <div class="absolute inset-0 flex flex-col items-center justify-center p-12 text-white z-10">
                    <div class="max-w-md text-center space-y-6">
                        <h2 class="text-4xl font-bold leading-tight drop-shadow-lg">
                            Gestiona tus reservas de forma profesional
                        </h2>
                        <p class="text-xl text-gray-100 drop-shadow-md">
                            Sistema diseñado para negocios que valoran la
                            simplicidad y la eficiencia
                        </p>
                        <div class="flex justify-center gap-3 pt-4">
                            <div class="w-3 h-3 bg-white rounded-full"></div>
                            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
                            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Pattern Removed -->
            </div>
        </div>
    </div>
</template>
