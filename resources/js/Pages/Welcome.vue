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
                }, index * 200); 
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

        <!-- CTA Section - User Focused -->
        <section class="bg-gray-950 text-white py-24 overflow-hidden relative">
            <!-- Decorative blur layers -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white/5 rounded-full blur-[120px]"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="max-w-xl">
                        <h2 class="text-5xl md:text-7xl font-bold mb-8 leading-tight tracking-tighter">
                            Tus planes, <span class="text-[#8EB6A5]">a un solo toque.</span>
                        </h2>
                        <p class="text-xl text-gray-400 mb-10 leading-relaxed">
                            Olvida las esperas al teléfono y los mensajes sin respuesta. Con ProReserve, reservar tu lugar ideal es rápido, sencillo y elegante.
                        </p>
                        
                        <!-- Advantages Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12">
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center flex-shrink-0 border border-white/10 group-hover:border-[#8EB6A5]/50 transition-all duration-300">
                                    <svg class="w-6 h-6 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-white mb-1 group-hover:text-[#8EB6A5] transition-colors">Busca y Descubre</h4>
                                    <p class="text-sm text-gray-500">Encuentra los mejores locales de tu zona con valoraciones reales.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center flex-shrink-0 border border-white/10 group-hover:border-[#8EB6A5]/50 transition-all duration-300">
                                    <svg class="w-6 h-6 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-white mb-1 group-hover:text-[#8EB6A5] transition-colors">Recordatorios</h4>
                                    <p class="text-sm text-gray-500">Recibe notificaciones automáticas para que nunca llegues tarde a tus planes.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <Link
                                href="/register"
                                class="inline-block bg-white text-gray-950 px-10 py-5 rounded-full font-bold hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 shadow-2xl shadow-white/5"
                            >
                                Comenzar ahora
                            </Link>
                        </div>
                    </div>

                    <div class="relative group lg:mt-0 mt-12">
                        <!-- Background decoration -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-[#8EB6A5]/30 to-transparent rounded-[3rem] blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative rounded-[3rem] overflow-hidden border border-white/10 shadow-[0_0_50px_-12px_rgba(255,255,255,0.1)] group-hover:border-[#8EB6A5]/30 transition-all duration-700">
                            <img
                                src="/images/proreserve-lifestyle-v2.png"
                                alt="ProReserve Lifestyle"
                                class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-1000"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>



