<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { UserRound, Bell} from 'lucide-vue-next'; 
import { useI18n } from "@/Composables/useI18n";

const { currentLocale, setLocale } = useI18n();

const toggleLanguage = () => {
    const nextLocale = currentLocale.value === 'es' ? 'en' : 'es';
    setLocale(nextLocale);
}; 

const mobileMenuOpen = ref(false);
const profileMenuOpen = ref(false);
const isVisible = ref(true);
const lastScrollY = ref(0);

const handleScroll = () => {
    const currentScrollY = window.scrollY;
    
    // Si estamos al principio de la página, siempre visible
    if (currentScrollY < 10) {
        isVisible.value = true;
        lastScrollY.value = currentScrollY;
        return;
    }

    // Si deslizamos hacia abajo, ocultar
    if (currentScrollY > lastScrollY.value && currentScrollY > 100) {
        isVisible.value = false;
    } 
    // Si deslizamos hacia arriba, mostrar
    else if (currentScrollY < lastScrollY.value) {
        isVisible.value = true;
    }

    lastScrollY.value = currentScrollY;
};

const currentTheme = ref('navy'); // 'navy' or 'beige'
let observer = null;

onMounted(() => {
    window.addEventListener("scroll", handleScroll);

    // Smart Navbar Logic
    const options = {
        root: null,
        rootMargin: '-80px 0px -80% 0px', // Detect as it hits the top
        threshold: 0
    };

    observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const theme = entry.target.getAttribute('data-nav-theme');
                if (theme === 'dark') {
                    currentTheme.value = 'beige';
                } else if (theme === 'light') {
                    currentTheme.value = 'navy';
                }
            }
        });
    }, options);

    document.querySelectorAll('[data-nav-theme]').forEach((section) => {
        observer.observe(section);
    });
});

onUnmounted(() => {
    window.removeEventListener("scroll", handleScroll);
    if (observer) observer.disconnect();
});

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const toggleProfileMenu = () => {
    profileMenuOpen.value = !profileMenuOpen.value;
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
};

defineProps({
    user: Object,
});
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
                    class="absolute inset-y-0 left-0 flex items-center sm:hidden"
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
                    class="flex flex-1 items-center justify-center sm:items-center sm:justify-start"
                >
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link href="/">
                            <img
                                src="/images/logo-proreserve.png"
                                alt="ProReserve"
                                class="h-14 w-auto transition-all duration-500"
                                :class="currentTheme === 'navy' ? 'brightness-0 invert' : ''"
                            />
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden sm:ml-10 sm:flex sm:items-center">
                        <div class="flex items-center space-x-8">
                            <Link
                                href="/"
                                :class="[
                                    $page.url === '/'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-bold flex items-center transition-all duration-500',
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
                                    'nav-link-item px-4 py-2 text-lg font-bold flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.services') }}
                            </Link>
                            <Link
                                href="/contacto"
                                :class="[
                                    $page.url === '/contacto'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-bold flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.contact') }}
                            </Link>
                            <Link
                                href="/calendario"
                                :class="[
                                    $page.url === '/calendario'
                                        ? (currentTheme === 'navy' ? 'text-[#F0EEE9] border-b-2 border-[#F0EEE9]' : 'text-[#0f172a] border-b-2 border-[#0f172a]')
                                        : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:text-[#0f172a]'),
                                    'nav-link-item px-4 py-2 text-lg font-bold flex items-center transition-all duration-500',
                                ]"
                            >
                                {{ $t('nav.calendar') }}
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Right side: Notifications & Profile -->
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
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
                    <div class="relative ml-3">
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
                                    href="/configuracion"
                                    class="block px-4 py-2 text-sm transition-colors duration-500"
                                    :class="currentTheme === 'navy' 
                                        ? 'text-[#F0EEE9]/80 hover:bg-white/5 hover:text-[#F0EEE9]' 
                                        : 'text-[#0f172a]/80 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'"
                                >
                                    {{ $t('nav.settings') }}
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
                            </div>
                        </Transition>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div v-if="mobileMenuOpen" class="sm:hidden border-t" :class="currentTheme === 'navy' ? 'border-white/5' : 'border-[#0f172a]/5'">
                <div class="space-y-1 px-4 pt-2 pb-6 transition-all duration-500" :class="currentTheme === 'navy' ? 'bg-[#0f172a]' : 'bg-[#F0EEE9]'">
                    <Link
                        href="/"
                        @click="closeMobileMenu"
                        :class="[
                            $page.url === '/'
                                ? (currentTheme === 'navy' ? 'bg-white/5 text-[#F0EEE9]' : 'bg-[#0f172a]/5 text-[#0f172a]')
                                : (currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]'),
                            'block rounded-xl px-4 py-4 text-base font-bold transition-all duration-500',
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
                            'block rounded-xl px-4 py-4 text-base font-bold transition-all duration-500',
                        ]"
                    >
                        {{ $t('nav.services') }}
                    </Link>
                    <Link
                        href="/contacto"
                        @click="closeMobileMenu"
                        :class="[
                            currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]',
                             'block rounded-xl px-4 py-4 text-base font-bold transition-all duration-500'
                        ]"
                    >
                        {{ $t('nav.contact') }}
                    </Link>
                    <Link
                        href="/calendario"
                        @click="closeMobileMenu"
                         :class="[
                            currentTheme === 'navy' ? 'text-[#F0EEE9]/70 hover:bg-white/5 hover:text-[#F0EEE9]' : 'text-[#0f172a]/70 hover:bg-[#0f172a]/5 hover:text-[#0f172a]',
                             'block rounded-xl px-4 py-4 text-base font-bold transition-all duration-500'
                        ]"
                    >
                        {{ $t('nav.calendar') }}
                    </Link>
                </div>
            </div>
        </Transition>
    </nav>
</template>
