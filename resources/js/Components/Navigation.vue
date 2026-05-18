<script setup>
import { ref, onMounted, onUnmounted, watch } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { UserRound, Bell, LayoutDashboard, Search } from 'lucide-vue-next'; 
import { useI18n } from "@/Composables/useI18n";
import { usePermissions } from "@/Composables/usePermissions";

const { currentLocale, setLocale } = useI18n();
const { isAdmin, canAccessDashboard } = usePermissions();

const toggleLanguage = () => {
    const nextLocale = currentLocale.value === 'es' ? 'en' : 'es';
    const currentPath = window.location.pathname;
    const pathParts = currentPath.split('/');
    
    if (pathParts.length > 1 && (pathParts[1] === 'es' || pathParts[1] === 'en')) {
        pathParts[1] = nextLocale;
    }
    
    const newUrl = pathParts.join('/') + window.location.search;
    router.visit(newUrl);
}; 

const searchQuery = ref('');
const mobileMenuOpen = ref(false);
const profileMenuOpen = ref(false);
const isVisible = ref(true);
const lastScrollY = ref(0);

let debounceTimeout = null;
watch(searchQuery, (newVal) => {
    if (debounceTimeout) clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        router.get(route('search'), { q: newVal }, {
            preserveState: true,
            preserveScroll: true,
            replace: true
        });
    }, 150);
});

const handleSearch = () => {
    router.get(route('search'), { q: searchQuery.value });
};

const handleEscape = (e) => {
    searchQuery.value = '';
    e.target.blur();
};

const handleScroll = () => {
    if (mobileMenuOpen.value || profileMenuOpen.value) return;
    const currentScrollY = window.scrollY;
    
    if (currentScrollY < 10) {
        isVisible.value = true;
        lastScrollY.value = currentScrollY;
        return;
    }

    if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        isVisible.value = false;
    } else if (currentScrollY < lastScrollY.value) {
        isVisible.value = true;
    }
    lastScrollY.value = currentScrollY;
};

const currentTheme = ref('navy');
let observer = null;
const dropdownRef = ref(null);

const closeDropdownOnClickOutside = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        profileMenuOpen.value = false;
    }
};

onMounted(() => {
    // Sincronizar búsqueda con URL
    const q = new URLSearchParams(window.location.search).get('q');
    if (q) searchQuery.value = q;

    window.addEventListener("scroll", handleScroll);
    window.addEventListener("click", closeDropdownOnClickOutside);

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const theme = entry.target.getAttribute('data-nav-theme');
                currentTheme.value = theme === 'dark' ? 'beige' : 'navy';
            }
        });
    }, { rootMargin: '-80px 0px -80% 0px' });

    document.querySelectorAll('[data-nav-theme]').forEach((section) => observer.observe(section));
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
    window.removeEventListener("click", closeDropdownOnClickOutside);
    if (observer) observer.disconnect();
});

const toggleMobileMenu = () => mobileMenuOpen.value = !mobileMenuOpen.value;
const toggleProfileMenu = () => profileMenuOpen.value = !profileMenuOpen.value;
const closeMobileMenu = () => mobileMenuOpen.value = false;

defineProps({ user: Object });
</script>

<template>
    <nav 
        class="sticky top-0 z-50 transition-all duration-500 ease-in-out border-b"
        :class="[
            isVisible ? 'translate-y-0' : '-translate-y-full',
            currentTheme === 'navy' 
                ? 'bg-[#0f172a] border-white/5' 
                : 'bg-[#F0EEE9] border-[#0f172a]/5'
        ]"
    >
        <div class="mx-auto max-w-7xl px-6 sm:px-12 lg:px-16">
            <div class="relative flex h-24 items-center justify-between">
                <!-- Mobile menu button -->
                <div
                    class="absolute inset-y-0 left-0 flex items-center lg:hidden"
                >
                    <button
                        type="button"
                        @click="toggleMobileMenu"
                        class="relative inline-flex items-center justify-center rounded-md p-2 transition-all duration-500 focus:outline-none"
                        :class="currentTheme === 'navy' 
                            ? 'text-[#F0EEE9]/70 hover:bg-white/10 hover:text-[#F0EEE9]' 
                            : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                    >
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">{{ $t('nav.open_menu') }}</span>
                        <!-- Icon hamburger -->
                        <svg
                            v-if="!mobileMenuOpen"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            class="size-6"
                        >
                            <path
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <!-- Icon close -->
                        <svg
                            v-else
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            class="size-6"
                        >
                            <path
                                d="M6 18 18 6M6 6l12 12"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </div>

                <div
                    class="flex flex-1 items-center justify-start pl-10 sm:pl-0 sm:items-center lg:justify-start"
                >
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('home')">
                            <img
                                src="/images/logo-proreserve.avif"
                                alt="ProReserve"
                                class="h-14 w-auto transition-all duration-500"
                                :class="currentTheme === 'navy' ? 'brightness-0 invert' : ''"
                            />
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:ml-8 lg:flex lg:items-center">
                        <div class="flex items-center space-x-6">
                            <Link
                                :href="route('home')"
                                :class="[
                                    $page.url === '/'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.home') }}
                            </Link>
                            <Link
                                :href="route('servicios')"
                                :class="[
                                    $page.url.startsWith('/servicios')
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.services') }}
                            </Link>
                            <Link
                                :href="route('contacto')"
                                :class="[
                                    $page.url === '/contacto'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.contact') }}
                            </Link>
                            <Link
                                :href="route('reservas.index')"
                                :class="[
                                    $page.url === '/reservas'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.calendar') }}
                            </Link>
                            <!-- Admin Dashboard Link -->
                            <Link
                                v-if="canAccessDashboard()"
                                :href="route('dashboard')"
                                :class="[
                                    $page.url === '/dashboard'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center transition-all duration-500',
                                ]"
                            >
                                <LayoutDashboard class="w-5 h-5 mr-2" />
                                Dashboard
                            </Link>
                        </div>
                    </div>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex flex-1 max-w-xs ml-8">
                        <form @submit.prevent="handleSearch" class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Search 
                                    :size="18" 
                                    :class="currentTheme === 'navy' ? 'text-[#F0EEE9]/40' : 'text-[#0f172a]/40'"
                                />
                            </div>
                            <input 
                                v-model="searchQuery"
                                @keydown.escape="handleEscape"
                                type="text" 
                                :placeholder="$t('nav.search_placeholder')"
                                class="block w-full pl-10 pr-3 py-2 border rounded-full text-sm transition-all duration-500 focus:ring-1 focus:ring-[#8EB6A5] focus:border-[#8EB6A5] focus:outline-none"
                                :class="currentTheme === 'navy' 
                                    ? 'bg-white/5 border-white/10 text-[#F0EEE9] placeholder-[#F0EEE9]/30 focus:bg-[#0f172a]/40' 
                                    : 'bg-[#0f172a]/5 border-[#0f172a]/10 text-[#0f172a] placeholder-[#0f172a]/30 focus:bg-white'"
                            >
                        </form>
                    </div>
                </div>

                <!-- Right side: Notifications & Profile -->
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 lg:static lg:inset-auto lg:ml-6 lg:pr-0"
                >
                    <!-- Language Switcher -->
                    <button
                        @click="toggleLanguage"
                        class="flex items-center justify-center w-10 h-10 mr-2 rounded-full border transition-all duration-500 text-[10px] font-black tracking-tighter"
                        :class="currentTheme === 'navy' 
                           ? 'border-white/10 text-[#F0EEE9]/60 hover:bg-white/10 hover:text-[#F0EEE9]' 
                           : 'border-[#0f172a]/10 text-[#0f172a]/60 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                        title="Cambiar idioma"
                    >
                        {{ currentLocale.toUpperCase() }}
                    </button>

                    <!-- Notifications -->
                    <button
                        type="button"
                        class="relative rounded-full p-1 transition-colors duration-500 focus:outline-none"
                        :class="currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'"
                    >
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">{{ $t('nav.view_notifications') }}</span>

                        <Bell 
                            :size="24"
                            stroke-width="1.5"
                            class="size-6"
                        />
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3" ref="dropdownRef">
                        <button
                            @click="toggleProfileMenu"
                            class="relative flex rounded-full focus:outline-none"
                        >
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">{{ $t('nav.open_user_menu') }}</span>
                            <img v-if="user?.name"
                                :src="`https://ui-avatars.com/api/?name=${user.name}&background=${currentTheme === 'navy' ? 'F0EEE9' : '0f172a'}&color=${currentTheme === 'navy' ? '0f172a' : 'F0EEE9'}&length=1`"
                                alt=""
                                class="size-8 rounded-full transition-all duration-500"
                                :class="currentTheme === 'navy' ? 'bg-[#0f172a] border border-white/10' : 'bg-[#F0EEE9] border border-[#0f172a]/10'"
                            />
                            <UserRound v-else
                                :size="28"
                                class="size-7 transition-colors duration-500"
                                :class="currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'"
                                stroke-width="1.5"
                            />
                        </button>

                        <!-- Dropdown menu -->
                        <Transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div
                                v-if="profileMenuOpen"
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-xl backdrop-blur-md border py-2 shadow-2xl focus:outline-none transition-all duration-500"
                                :class="currentTheme === 'navy' 
                                    ? 'bg-[#0f172a]/95 border-white/10' 
                                    : 'bg-[#F0EEE9]/95 border-[#0f172a]/10'"
                                @click="profileMenuOpen = false"
                            >
                                <template v-if="user?.name">
                                    <Link
                                        v-if="canAccessDashboard()"
                                        :href="route('dashboard')"
                                        class="block px-4 py-2 text-sm transition-colors duration-500"
                                        :class="currentTheme === 'navy' 
                                            ? 'text-[#F0EEE9]/80 hover:bg-white/5 hover:text-[#F0EEE9]' 
                                            : 'text-[#0f172a]/80 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                                    >
                                        Dashboard
                                    </Link>
                                    <Link
                                        :href="route('profile.edit')"
                                        class="block px-4 py-2 text-sm transition-colors duration-500"
                                        :class="currentTheme === 'navy' 
                                            ? 'text-[#F0EEE9]/80 hover:bg-white/5 hover:text-[#F0EEE9]' 
                                            : 'text-[#0f172a]/80 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                                    >
                                        {{ $t('nav.profile') }}
                                    </Link>
                                    <Link
                                        :href="route('reservas.index')"
                                        class="block px-4 py-2 text-sm transition-colors duration-500"
                                        :class="currentTheme === 'navy' 
                                            ? 'text-[#F0EEE9]/80 hover:bg-white/5 hover:text-[#F0EEE9]' 
                                            : 'text-[#0f172a]/80 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                                    >
                                        {{ $t('nav.calendar') }}
                                    </Link>

                                    <div 
                                        class="h-px my-1 mx-2"
                                        :class="currentTheme === 'navy' ? 'bg-white/10' : 'bg-[#0f172a]/10'"
                                    ></div>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="block w-full text-left px-4 py-2 text-sm transition-colors duration-500"
                                        :class="currentTheme === 'navy' ? 'text-red-400 hover:bg-red-500/10' : 'text-red-600 hover:bg-red-50'"
                                    >
                                        {{ $t('nav.logout') }}
                                    </Link>
                                </template>
                                <template v-else>
                                    <Link
                                        :href="route('login')"
                                        class="block px-4 py-2 text-sm transition-colors duration-500 font-semibold"
                                        :class="currentTheme === 'navy' 
                                            ? 'text-[#F0EEE9] hover:bg-white/5' 
                                            : 'text-[#0f172a] hover:bg-[#0f172a]/5'"
                                    >
                                        {{ $t('login') || 'Iniciar sesión' }}
                                    </Link>
                                    <Link
                                        :href="route('register')"
                                        class="block px-4 py-2 text-sm transition-colors duration-500"
                                        :class="currentTheme === 'navy' 
                                            ? 'text-[#F0EEE9]/80 hover:bg-white/5 hover:text-[#F0EEE9]' 
                                            : 'text-[#0f172a]/80 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                                    >
                                        {{ $t('register') || 'Registrarse' }}
                                    </Link>
                                </template>
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>

        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div v-if="mobileMenuOpen" class="lg:hidden border-t absolute top-full left-0 w-full shadow-2xl" :class="currentTheme === 'navy' ? 'border-white/5' : 'border-[#0f172a]/5'">
                <div class="space-y-1 px-4 pt-2 pb-6 transition-all duration-500" :class="currentTheme === 'navy' ? 'bg-[#0f172a]' : 'bg-[#F0EEE9]'">
                    <!-- Search Bar (Mobile) -->
                    <div class="px-2 py-3">
                        <form @submit.prevent="handleSearch" class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <Search 
                                    :size="18" 
                                    :class="currentTheme === 'navy' ? 'text-[#F0EEE9]/40' : 'text-[#0f172a]/40'"
                                />
                            </div>
                            <input 
                                v-model="searchQuery"
                                @keydown.escape="handleEscape"
                                type="text" 
                                :placeholder="$t('nav.search_placeholder')"
                                class="block w-full pl-10 pr-3 py-3 border rounded-xl text-base transition-all duration-500 focus:ring-1 focus:ring-[#8EB6A5] focus:border-[#8EB6A5] focus:outline-none"
                                :class="currentTheme === 'navy' 
                                    ? 'bg-white/5 border-white/10 text-[#F0EEE9] placeholder-[#F0EEE9]/30 focus:bg-[#0f172a]/40' 
                                    : 'bg-[#0f172a]/5 border-[#0f172a]/10 text-[#0f172a] placeholder-[#0f172a]/30 focus:bg-white'"
                            >
                        </form>
                    </div>
                    <Link
                        :href="route('home')"
                        @click="closeMobileMenu"
                        :class="[
                            $page.url === '/'
                                ? (currentTheme === 'navy' ? 'bg-white/5 text-[#F0EEE9]' : 'bg-[#0f172a]/5 text-[#0f172a]')
                                : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'),
                            'block rounded-xl px-4 py-4 text-base font-medium transition-all duration-500',
                        ]"
                    >
                        {{ $t('nav.home') }}
                    </Link>
                    <Link
                        :href="route('servicios')"
                        @click="closeMobileMenu"
                        :class="[
                            $page.url.startsWith('/servicios')
                                ? (currentTheme === 'navy' ? 'bg-white/5 text-[#F0EEE9]' : 'bg-[#0f172a]/5 text-[#0f172a]')
                                : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'),
                            'block rounded-xl px-4 py-4 text-base font-medium transition-all duration-500',
                        ]"
                    >
                        {{ $t('nav.services') }}
                    </Link>
                    <Link
                        :href="route('contacto')"
                        @click="closeMobileMenu"
                        :class="[
                            currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]',
                             'block rounded-xl px-4 py-4 text-base font-medium transition-all duration-500'
                        ]"
                    >
                        {{ $t('nav.contact') }}
                    </Link>
                    <Link
                        :href="route('reservas.index')"
                        @click="closeMobileMenu"
                         :class="[
                            $page.url === '/reservas'
                                ? (currentTheme === 'navy' ? 'bg-white/5 text-[#F0EEE9]' : 'bg-[#0f172a]/5 text-[#0f172a]')
                                : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'),
                             'block rounded-xl px-4 py-4 text-base font-medium transition-all duration-500'
                        ]"
                    >
                        {{ $t('nav.calendar') }}
                    </Link>
                    <!-- Mobile Admin Dashboard -->
                    <Link
                        v-if="canAccessDashboard()"
                        :href="route('dashboard')"
                        @click="closeMobileMenu"
                         :class="[
                            currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]',
                             'block rounded-xl px-4 py-4 text-base font-medium transition-all duration-500'
                        ]"
                    >
                        Dashboard
                    </Link>
                </div>
            </div>
        </Transition>
    </nav>
</template>
