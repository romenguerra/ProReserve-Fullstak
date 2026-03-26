<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Link } from "@inertiajs/vue3";
import { Search, CalendarCheck, CheckCircle, ChevronDown, HelpCircle } from 'lucide-vue-next'; 
import { computed, ref } from "vue";
import { useI18n } from "@/Composables/useI18n";

const { t } = useI18n();

const activeFaq = ref(null);
const toggleFaq = (index) => {
    activeFaq.value = activeFaq.value === index ? null : index;
};

const faqs = computed(() => [
    { q: t('services_page.faq_1_q'), a: t('services_page.faq_1_a') },
    { q: t('services_page.faq_2_q'), a: t('services_page.faq_2_a') },
    { q: t('services_page.faq_3_q'), a: t('services_page.faq_3_a') },
]);

defineProps({
    servicios: {
        type: Array,
        default: () => [],
    },
});

const categorias = computed(() => [
    {
        id: 1,
        nombre: t("home.categories.gastronomy"),
        descripcion: t("home.categories.gastronomy_desc"),
        imagen: "/images/gastronomia.jpg",
        link: "/gastronomia",
    },
    {
        id: 2,
        nombre: t("home.categories.sport"),
        descripcion: t("home.categories.sport_desc"),
        imagen: "/images/deporte.jpg",
        link: "/deportes",
    },
    {
        id: 3,
        nombre: t("home.categories.health"),
        descripcion: t("home.categories.health_desc"),
        imagen: "/images/salud.jpg",
        link: "/salud",
    },
    {
        id: 4,
        nombre: t("home.categories.leisure"),
        descripcion: t("home.categories.leisure_desc"),
        imagen: "/images/ocio.jpg",
        link: "/ocio",
    },
    {
        id: 5,
        nombre: t("home.categories.beauty"),
        descripcion: t("home.categories.beauty_desc"),
        imagen: "/images/beauty-wellness.png",
        link: "/belleza",
    },
]);
</script>

<template>
    <MainLayout :title="$t('services_page.meta_title')">
        <div class="bg-[#F0EEE9] min-h-screen">
            <!-- Hero Section -->
            <section data-nav-theme="light" class="relative flex items-center overflow-hidden pt-32 pb-20">
                <!-- Decorative colored element -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -left-24 w-72 h-72 bg-white/40 rounded-full blur-[80px]"></div>
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="max-w-4xl">
                    <h1
                        class="text-6xl md:text-7xl lg:text-8xl font-bold text-[#0f172a] leading-tight mb-6 tracking-tighter"
                    >
                        <span v-html="$t('services_page.hero_title')"></span>
                    </h1>
                    <p class="text-xl md:text-2xl text-[#0f172a]/70 max-w-2xl leading-relaxed">
                        {{ $t('services_page.hero_subtitle') }}
                    </p>
                </div>
            </div>
            </section>

        <!-- Services Grid Section -->
        <section data-nav-theme="light" class="py-20">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <Link
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        :href="categoria.link"
                        class="group cursor-pointer"
                    >
                        <div
                            class="aspect-[4/3] overflow-hidden mb-4 bg-gray-100 rounded-lg"
                        >
                            <img
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                :src="categoria.imagen"
                                :alt="categoria.nombre"
                            />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ categoria.nombre }}
                        </h3>
                        <p class="text-base text-gray-600 mb-3">
                            {{ categoria.descripcion }}
                        </p>
                        <span
                            class="inline-flex items-center text-sm text-gray-900 font-bold group-hover:text-[#8EB6A5] transition-all"
                        >
                            {{ $t('services_page.explore') }}
                            <svg
                                class="w-4 h-4 ml-1.5 transition-transform group-hover:translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"
                                />
                            </svg>
                        </span>
                    </Link>
                </div>
            </div>
        </section>

        <!-- How it Works Section -->
        <section data-nav-theme="dark" class="py-32 bg-[#0f172a] text-[#F0EEE9] relative overflow-hidden">
            <!-- Decorative blur layers -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white/5 rounded-full blur-[120px]"></div>

            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                <div class="text-center mb-20">
                    <h2 class="text-5xl md:text-6xl font-bold mb-6 tracking-tighter" v-html="$t('services_page.steps_title')"></h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-20 h-20 rounded-3xl bg-white/5 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500 shadow-sm relative border border-white/10">
                            <span class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#F0EEE9] text-[#0f172a] flex items-center justify-center font-bold text-sm">1</span>
                            <Search class="w-10 h-10 text-[#F0EEE9]" stroke-width="1.5" />
                        </div>
                        <h3 class="text-2xl font-bold text-[#F0EEE9] mb-4">{{ $t('services_page.step_1_title') }}</h3>
                        <p class="text-[#F0EEE9]/70 leading-relaxed max-w-xs">{{ $t('services_page.step_1_desc') }}</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-20 h-20 rounded-3xl bg-white/5 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500 shadow-sm relative border border-white/10">
                            <span class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#F0EEE9] text-[#0f172a] flex items-center justify-center font-bold text-sm">2</span>
                            <CalendarCheck class="w-10 h-10 text-[#F0EEE9]" stroke-width="1.5" />
                        </div>
                        <h3 class="text-2xl font-bold text-[#F0EEE9] mb-4">{{ $t('services_page.step_2_title') }}</h3>
                        <p class="text-[#F0EEE9]/70 leading-relaxed max-w-xs">{{ $t('services_page.step_2_desc') }}</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center group">
                        <div class="w-20 h-20 rounded-3xl bg-white/5 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-500 shadow-sm relative border border-white/10">
                            <span class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-[#F0EEE9] text-[#0f172a] flex items-center justify-center font-bold text-sm">3</span>
                            <CheckCircle class="w-10 h-10 text-[#F0EEE9]" stroke-width="1.5" />
                        </div>
                        <h3 class="text-2xl font-bold text-[#F0EEE9] mb-4">{{ $t('services_page.step_3_title') }}</h3>
                        <p class="text-[#F0EEE9]/70 leading-relaxed max-w-xs">{{ $t('services_page.step_3_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section data-nav-theme="dark" class="py-32 bg-[#8EB6A5]">
            <div class="max-w-4xl mx-auto px-6 sm:px-12">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center gap-2 px-6 py-2 rounded-full bg-[#0f172a] text-[#F0EEE9] font-bold text-sm mb-6 shadow-lg shadow-[#0f172a]/20">
                        <HelpCircle class="w-4 h-4" />
                        FAQ
                    </div>
                    <h2 class="text-5xl md:text-6xl font-bold text-[#0f172a] tracking-tighter" v-html="$t('services_page.faq_title')"></h2>
                </div>

                <div class="space-y-6">
                    <div 
                        v-for="(faq, index) in faqs" 
                        :key="index"
                        class="group rounded-3xl bg-[#F0EEE9] transition-all duration-300 overflow-hidden"
                        :class="activeFaq === index ? 'shadow-xl' : 'hover:shadow-lg'"
                    >
                        <button 
                            @click="toggleFaq(index)"
                            class="w-full flex items-center justify-between p-8 text-left transition-colors"
                            :class="activeFaq === index ? 'bg-white/20' : ''"
                        >
                            <span class="text-xl font-bold text-[#0f172a]">{{ faq.q }}</span>
                            <ChevronDown 
                                class="w-6 h-6 text-[#0f172a] transition-transform duration-500"
                                :class="activeFaq === index ? 'rotate-180' : ''"
                            />
                        </button>
                        <Transition
                            enter-active-class="transition-all duration-500 ease-out"
                            enter-from-class="opacity-0 max-h-0"
                            enter-to-class="opacity-100 max-h-96"
                            leave-active-class="transition-all duration-300 ease-in"
                            leave-from-class="opacity-100 max-h-96"
                            leave-to-class="opacity-0 max-h-0"
                        >
                            <div v-if="activeFaq === index" class="px-8 pb-8 text-[#0f172a] text-lg leading-relaxed font-medium">
                                {{ faq.a }}
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </MainLayout>
</template>
