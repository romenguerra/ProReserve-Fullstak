<script setup>
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted, ref, computed } from "vue";
import { useI18n } from "@/Composables/useI18n";

const { t } = useI18n();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const categories = computed(() => [
    {
        id: "gastronomy",
        title: t("home.categories.gastronomy"),
        description: t("home.categories.gastronomy_desc"),
        image: "/images/gastronomia.jpg",
        link: "/gastronomia",
    },
    {
        id: "sport",
        title: t("home.categories.sport"),
        description: t("home.categories.sport_desc"),
        image: "/images/deporte.jpg",
        link: "/deportes",
    },
    {
        id: "health",
        title: t("home.categories.health"),
        description: t("home.categories.health_desc"),
        image: "/images/salud.jpg",
        link: "/salud",
    },
    {
        id: "leisure",
        title: t("home.categories.leisure"),
        description: t("home.categories.leisure_desc"),
        image: "/images/ocio.jpg",
        link: "/ocio",
    },
    {
        id: "beauty",
        title: t("home.categories.beauty"),
        description: t("home.categories.beauty_desc"),
        image: "/images/beauty-wellness.png",
        link: "/belleza",
    },
]);

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
    <MainLayout :title="$t('home.meta_title')">
        <!-- Hero Section - Gradiente de Malla Premium (Verde Agua) -->
        <section class="relative min-h-[60vh] flex items-center overflow-hidden bg-[#F0EEE9]">
            <!-- Fondo de malla (Mesh Gradient) complejo con CSS -->
            <div class="absolute inset-0 opacity-80" style="
                background-color: #F0EEE9;
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
                            <span class="title-reveal-text title-delay-1">{{ $t('home.hero_title_1') }}</span>
                        </span>
                        <span class="title-reveal-wrapper">
                            <span class="title-reveal-text title-delay-2">{{ $t('home.hero_title_2') }}</span>
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-800 mb-10 max-w-xl leading-relaxed hero-animate hero-delay-sub">
                        {{ $t('home.hero_subtitle') }}
                    </p>
                    <div class="flex flex-wrap gap-4 hero-animate hero-delay-btn">
                        <Link
                            href="/register"
                            class="inline-block bg-gray-900 text-white px-10 py-5 rounded-full font-semibold hover:bg-black transition-all duration-300 shadow-2xl shadow-gray-400/50 hover:-translate-y-1"
                        >
                            {{ $t('home.hero_cta') }}
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section - Cloud Dancer -->
        <section class="bg-[#F0EEE9] py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="mb-10">
                    <h2
                        class="text-5xl md:text-6xl font-bold text-gray-900 mb-6"
                        v-html="$t('home.services_title')"
                    >
                    </h2>
                    <p class="text-xl text-gray-600 max-w-2xl">
                        {{ $t('home.services_subtitle') }}
                    </p>
                </div>

                <!-- Grid de servicios minimalista -->
                <div class="flex justify-center">
                    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-8 max-w-6xl">
                        <div
                            v-for="(category, index) in categories"
                            :key="category.id"
                            :data-index="index"
                            class="group cursor-pointer card-animate category-card"
                        >
                            <Link :href="category.link" class="block">
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
                                    {{ $t('home.services_explore') }}
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
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section - User Focused -->
        <section class="bg-[#B8D4C8] text-gray-900 py-24 overflow-hidden relative">
            <!-- Decorative blur layers - Adjusted for light background -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/40 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#8EB6A5]/30 rounded-full blur-[120px]"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="max-w-xl">
                        <h2 class="text-5xl md:text-7xl font-bold mb-8 leading-tight tracking-tighter">
                            {{ $t('home.cta_title_1') }} <span class="text-white drop-shadow-sm">{{ $t('home.cta_title_2') }}</span>
                        </h2>
                        <p class="text-xl text-gray-800 mb-10 leading-relaxed font-medium">
                            {{ $t('home.cta_subtitle') }}
                        </p>
                        
                        <!-- Advantages Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-12">
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0 border border-white/30 group-hover:border-white/60 transition-all duration-300">
                                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $t('home.cta_feature_1_title') }}</h4>
                                    <p class="text-sm text-gray-700">{{ $t('home.cta_feature_1_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0 border border-white/30 group-hover:border-white/60 transition-all duration-300">
                                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-900 mb-1">{{ $t('home.cta_feature_2_title') }}</h4>
                                    <p class="text-sm text-gray-700">{{ $t('home.cta_feature_2_desc') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <Link
                                href="/register"
                                class="inline-block bg-gray-900 text-white px-10 py-5 rounded-full font-bold hover:bg-black transition-all duration-500 shadow-2xl shadow-gray-900/10"
                            >
                                {{ $t('home.hero_cta') }}
                            </Link>
                        </div>
                    </div>

                    <div class="relative group lg:mt-0 mt-12">
                        <!-- Background decoration -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-white/40 to-transparent rounded-[3rem] blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                        
                        <div class="relative rounded-[3rem] overflow-hidden border border-white/40 shadow-2xl group-hover:border-white/60 transition-all duration-700">
                            <img
                                src="/images/proreserve-lifestyle-v2.png"
                                :alt="$t('home.cta_image_alt')"
                                class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-1000"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>



