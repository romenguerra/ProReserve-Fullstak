<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

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

const registerSlides = [
    {
        image: '/images/register-hero.png',
        title: 'Gestiona tus reservas de forma profesional',
        description: 'Sistema diseñado para negocios que valoran la simplicidad y la eficiencia'
    },
    {
        image: '/images/gastronomia.jpg',
        title: 'Potencia tu restaurante',
        description: 'Optimiza tus mesas y ofrece la mejor experiencia a tus comensales'
    },
    {
        image: '/images/bienestar.jpg',
        title: 'Tu centro de bienestar organizado',
        description: 'Gestiona citas y clientes con total facilidad desde un solo lugar'
    }
];
</script>

<template>
    <Head title="Register" />

    <div class="auth-page-wrapper">
        <div class="auth-card">
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
                            class="flex-1 py-3 text-center btn-outline"
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

            <!-- Right Side - Image Carousel -->
            <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
                <swiper
                    :modules="[Autoplay, Pagination, EffectFade]"
                    :slides-per-view="1"
                    :effect="'fade'"
                    :loop="true"
                    :autoplay="{
                        delay: 4000,
                        disableOnInteraction: false,
                    }"
                    :pagination="{ clickable: false }"
                    :allow-touch-move="false"
                    class="h-full w-full"
                >
                    <swiper-slide v-for="(slide, index) in registerSlides" :key="index" class="relative">
                        <div 
                            class="absolute inset-0 bg-cover bg-center transition-transform duration-[4000ms] hover:scale-110"
                            :style="{ backgroundImage: `url(${slide.image})` }"
                        ></div>
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#111827]/80 via-[#111827]/40 to-[#111827]/20 z-10"></div>
                        
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-12 text-white z-20">
                            <div class="max-w-md text-center space-y-6">
                                <h2 class="text-4xl font-bold leading-tight drop-shadow-lg">
                                    {{ slide.title }}
                                </h2>
                                <p class="text-xl text-gray-100 drop-shadow-md">
                                    {{ slide.description }}
                                </p>
                            </div>
                        </div>
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </div>
</template>

