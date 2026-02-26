<script setup>
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted, ref } from "vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = [
    {
        title: "Gastronomía",
        description: "Reserva en tu restaurante",
        image: "/images/gastronomia.jpg",
        link: "#",
    },
    {
        title: "Deporte",
        description: "Entrena y mantente en forma",
        image: "/images/deporte.jpg",
        link: "#",
    },
    {
        title: "Salud",
        description: "Cuida de tu bienestar físico",
        image: "/images/salud.jpg",
        link: "#",
    },
    {
        title: "Ocio",
        description: "Disfruta de tu tiempo libre",
        image: "/images/ocio.jpg",
        link: "#",
    },
    {
        title: "Imagen personal",
        description: "Servicios a tu medida",
        image: "/images/personal.jpg",
        link: "#",
    },
];

const cardsRef = ref([]);

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Obtenemos el índice del elemento para aplicar un pequeño retraso extra
                const index = entry.target.getAttribute('data-index');
                setTimeout(() => {
                    entry.target.classList.add('reveal-card');
                }, index * 300); 
                observer.unobserve(entry.target);
            }
        });
    }, { 
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px' // Se activa un poco antes de entrar del todo
    });

    const cards = document.querySelectorAll('.card-animate');
    cards.forEach((card) => observer.observe(card));
});
</script>

<template>
    <MainLayout title="Inicio - ProReserve">
        <!-- Hero Section - Gradiente de Malla Premium (Verde Agua) -->
        <section class="relative min-h-[75vh] flex items-center overflow-hidden bg-white">
            <!-- Fondo de malla (Mesh Gradient) complejo con CSS -->
            <div class="absolute inset-0 opacity-80" style="
                background-color: #ffffff;
                background-image: 
                    radial-gradient(at 0% 0%, #B8D4C8 0px, transparent 50%),
                    radial-gradient(at 100% 0%, #E8F3EF 0px, transparent 50%),
                    radial-gradient(at 100% 100%, #A3C4B5 0px, transparent 50%),
                    radial-gradient(at 0% 100%, #CDE5DB 0px, transparent 50%),
                    radial-gradient(at 50% 50%, #B8D4C8 0px, transparent 50%);
                filter: blur(40px);
            "></div>
            
            <!-- Toque de color verde intenso decorativo -->
            <div class="absolute top-[20%] right-[15%] w-96 h-96 bg-[#8EB6A5]/30 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-[10%] left-[10%] w-[500px] h-[500px] bg-[#B8D4C8]/20 rounded-full blur-[120px]"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-20 w-full relative z-10">
                <div class="max-w-3xl">
                    <h1
                        class="text-7xl md:text-8xl lg:text-9xl font-bold text-gray-900 leading-[0.9] tracking-tighter mb-8"
                    >
                        <span class="title-reveal-wrapper">
                            <span class="title-reveal-text title-delay-1">Reservas</span>
                        </span>
                        <span class="title-reveal-wrapper">
                            <span class="title-reveal-text title-delay-2">simples.</span>
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-800 mb-10 max-w-xl leading-relaxed hero-animate hero-delay-sub">
                        Tu tiempo es valioso. Reserva lo que necesitas de forma rápida y elegante.
                    </p>
                    <div class="flex flex-wrap gap-4 hero-animate hero-delay-btn">
                        <Link
                            href="/register"
                            class="inline-block bg-gray-900 text-white px-10 py-5 rounded-full font-semibold hover:bg-black transition-all duration-300 shadow-2xl shadow-gray-400/50 hover:-translate-y-1"
                        >
                            Comenzar ahora
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section - Blanco -->
        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="mb-10">
                    <h2
                        class="text-5xl md:text-6xl font-bold text-gray-900 mb-6"
                    >
                        Qué podemos<br />hacer por ti.
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl">
                        Selecciona el servicio que necesitas y reserva tu cita.
                    </p>
                </div>

                <!-- Grid de servicios minimalista -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-8 max-w-6xl">
                        <div
                            v-for="(category, index) in categories"
                            :key="category.title"
                            :data-index="index"
                            class="group cursor-pointer card-animate category-card"
                        >
                            <a :href="category.link" class="block">
                                <div
                                    class="aspect-[4/5] overflow-hidden mb-4 bg-gray-100 rounded-2xl image-container"
                                >
                                    <img
                                        class="w-full h-full object-cover"
                                        :src="category.image"
                                        :alt="category.title"
                                        loading="lazy"
                                    />
                                </div>
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-1 card-title"
                                >
                                    {{ category.title }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                                    {{ category.description }}
                                </p>
                                <span
                                    class="inline-flex items-center text-sm text-gray-900 font-semibold group-hover:text-[#8EB6A5] transition-colors"
                                >
                                    Explorar
                                    <svg
                                        class="w-4 h-4 ml-1.5 transition-transform group-hover:translate-x-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2.5"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                                        />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section - Negro -->
        <section class="bg-gray-900 text-white py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-3xl">
                    <h2 class="text-5xl md:text-6xl font-bold mb-6">
                        El método ProReserve.
                    </h2>
                    <p class="text-xl text-gray-300 mb-8 max-w-2xl">
                        Sistema de reservas diseñado para negocios que valoran
                        la simplicidad y la eficiencia.
                    </p>
                    <Link
                        href="/login"
                        class="inline-block bg-[#ffffff] text-gray-900 px-8 py-4 rounded-full font-medium hover:bg-[#adadad] transition-all duration-200"
                    >
                        Iniciar sesión
                    </Link>
                </div>
            </div>
        </section>
    </MainLayout>
</template>



