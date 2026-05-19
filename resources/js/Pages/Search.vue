<script setup>
import { ref } from "vue";
import { Link, Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import RestaurantCard from "@/Components/RestaurantCard.vue";
import SportCenterCard from "@/Components/SportCenterCard.vue";
import BeautyCard from "@/Components/BeautyCard.vue";
import HealthCard from "@/Components/HealthCard.vue";
import LeisureCard from "@/Components/LeisureCard.vue";
import LocalDetailModal from "@/Components/LocalDetailModal.vue";
import { Search as SearchIcon } from 'lucide-vue-next';

const props = defineProps({
    results: {
        type: Array,
        required: true,
    },
    query: {
        type: String,
        required: true,
    }
});

const showModal = ref(false);
const selectedLocal = ref(null);
const activeCategory = ref('');

const openDetailModal = (local, category) => {
    selectedLocal.value = local;
    activeCategory.value = category;
    showModal.value = true;
};

const getCardComponent = (category) => {
    switch (category) {
        case 'gastronomia': return RestaurantCard;
        case 'deportes': return SportCenterCard;
        case 'belleza': return BeautyCard;
        case 'salud': return HealthCard;
        case 'ocio': return LeisureCard;
        default: return null;
    }
};

const getLocalProp = (category) => {
    switch (category) {
        case 'gastronomia': return 'restaurant';
        case 'deportes': return 'sportCenter';
        case 'belleza': return 'center';
        case 'salud': return 'center';
        case 'ocio': return 'center';
        default: return 'local';
    }
};
</script>

<template>
    <MainLayout :title="`${$t('search.meta_title')} - ${query}`">
        <div class="bg-[#F0EEE9] min-h-screen">
            <!-- Hero Section -->
            <section class="relative pt-24 pb-12 md:pb-24 bg-[#E8F3EF] overflow-hidden">
                <div class="absolute inset-0 opacity-40" style="
                    background: radial-gradient(at 0% 0%, #B8D4C8 0%, transparent 50%),
                                radial-gradient(at 100% 100%, #F0EEE9 0%, transparent 50%);
                "></div>
                
                <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 relative z-10">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div>
                            <span class="inline-block px-4 py-1.5 rounded-full bg-[#8EB6A5]/10 text-[#5D8A77] text-xs font-bold uppercase tracking-widest mb-4">
                                {{ $t('search.meta_title') }}
                            </span>
                            <h1 class="text-4xl md:text-6xl font-bold text-gray-900 tracking-tighter">
                                {{ $t('search.title') }} <span class="text-[#8EB6A5]">"{{ query }}"</span>
                            </h1>
                        </div>
                        <div class="text-gray-500 font-medium">
                            {{ $t('search.found').replace('{count}', results.length) }}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Results Grid -->
            <section class="py-8 md:py-16">
                <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                    <div v-if="results.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-16">
                        <template v-for="(item, index) in results" :key="`${item.category}-${item.id}-${index}`">
                            <component 
                                :is="getCardComponent(item.category)"
                                v-bind="{ [getLocalProp(item.category)]: item }"
                                :index="index"
                                @open-modal="openDetailModal(item, item.category)"
                                class="reveal-card-animation"
                                :style="{ animationDelay: `${(index % 4) * 75}ms` }"
                            />
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="flex flex-col items-center justify-center py-32 text-center">
                        <div class="w-24 h-24 rounded-full bg-white/50 flex items-center justify-center mb-8 shadow-inner">
                            <SearchIcon class="w-10 h-10 text-gray-300" />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $t('search.no_results') }}</h3>
                        <p class="text-gray-500 max-w-md mb-12">{{ $t('search.explore_more') }}</p>
                        
                        <div class="flex flex-wrap justify-center gap-4">
                            <Link :href="route('gastronomia')" class="px-6 py-3 bg-white rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-all">{{ $t('home.categories.gastronomy') }}</Link>
                            <Link :href="route('deportes')" class="px-6 py-3 bg-white rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-all">{{ $t('home.categories.sport') }}</Link>
                            <Link :href="route('ocio')" class="px-6 py-3 bg-white rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-all">{{ $t('home.categories.leisure') }}</Link>
                            <Link :href="route('salud')" class="px-6 py-3 bg-white rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-all">{{ $t('home.categories.health') }}</Link>
                            <Link :href="route('belleza')" class="px-6 py-3 bg-white rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-all">{{ $t('home.categories.beauty') }}</Link>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Detail Modal -->
        <LocalDetailModal
            :show="showModal"
            :local="selectedLocal"
            :category="activeCategory"
            @close="showModal = false"
        />
    </MainLayout>
</template>

<style scoped>
@keyframes revealCard {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.reveal-card-animation {
    opacity: 0;
    animation: revealCard 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
