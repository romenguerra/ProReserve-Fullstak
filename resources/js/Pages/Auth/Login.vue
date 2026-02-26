<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

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

const loginSlides = [
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
                    <swiper-slide v-for="(slide, index) in loginSlides" :key="index" class="relative">
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

<style scoped>
:deep(.swiper-pagination-bullet) {
    background: white;
    opacity: 0.5;
}
:deep(.swiper-pagination-bullet-active) {
    background: white;
    opacity: 1;
    width: 24px;
    border-radius: 4px;
    transition: all 0.3s;
}
</style>

