<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { UserRound, Bell} from 'lucide-vue-next'; 

const mobileMenuOpen = ref(false);
const profileMenuOpen = ref(false);

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
    <nav class="relative bg-white border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-6 sm:px-12 lg:px-16">
            <div class="relative flex h-24 items-center justify-between">
                <!-- Mobile menu button -->
                <div
                    class="absolute inset-y-0 left-0 flex items-center sm:hidden"
                >
                    <button
                        type="button"
                        @click="toggleMobileMenu"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-2 focus:-outline-offset-1 focus:outline-blue-500"
                    >
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
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
                                class="h-14 w-auto"
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
                                        ? 'nav-link-active'
                                        : 'text-gray-600 hover:text-gray-900',
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center',
                                ]"
                            >
                                Inicio
                            </Link>
                            <Link
                                :href="route('servicios')"
                                :class="[
                                    $page.url.startsWith('/servicios')
                                        ? 'nav-link-active'
                                        : 'text-gray-600 hover:text-gray-900',
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center',
                                ]"
                            >
                                Servicios
                            </Link>
                            <Link
                                href="/contacto"
                                :class="[
                                    $page.url === '/contacto'
                                        ? 'nav-link-active'
                                        : 'text-gray-600 hover:text-gray-900',
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center',
                                ]"
                            >
                                Contacto
                            </Link>
                            <Link
                                href="/calendario"
                                :class="[
                                    $page.url === '/calendario'
                                        ? 'nav-link-active'
                                        : 'text-gray-600 hover:text-gray-900',
                                    'nav-link-item px-4 py-2 text-lg font-medium flex items-center',
                                ]"
                            >
                                Calendario
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Right side: Notifications & Profile -->
                <div
                    class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0"
                >
                    <!-- Notifications -->
                    <button
                        type="button"
                        class="relative rounded-full p-1 text-gray-600 hover:text-gray-900 focus:outline-2 focus:outline-offset-2 focus:outline-blue-500"
                    >
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>

                        <Bell 
        :size="24"
        stroke-width="1.5"
        class="size-6"
    />
                        <!-- <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            class="size-6"
                        >
                            <path
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg> -->
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <button
                            @click="toggleProfileMenu"
                            class="relative flex rounded-full focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                        >
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">Open user menu</span>
                            <img v-if="user?.name"
                                :src="`https://ui-avatars.com/api/?name=${user.name}&background=9ca3af&color=fff&length=1`"
                                alt=""
                                class="size-8 rounded-full bg-white outline -outline-offset-1 outline-gray-200"
                            />
                            <UserRound v-else
                                :size="28"
                                class="size-7 text-gray-500"
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
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                @click="profileMenuOpen = false"
                            >
                                <Link
                                    :href="route('profile.edit')"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Tu perfil
                                </Link>
                                <Link
                                    href="/configuracion"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Configuración
                                </Link>
                                <Link
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Cerrar sesión
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
            <div v-if="mobileMenuOpen" class="sm:hidden">
                <div class="space-y-2 px-4 pt-4 pb-4 bg-gray-50">
                    <Link
                        href="/"
                        @click="closeMobileMenu"
                        :class="[
                            $page.url === '/'
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600',
                            'block rounded-md px-4 py-3 text-base font-medium transition-all duration-200',
                        ]"
                    >
                        Inicio
                    </Link>
                    <Link
                        :href="route('servicios')"
                        @click="closeMobileMenu"
                        :class="[
                            $page.url.startsWith('/servicios')
                                ? 'bg-blue-50 text-blue-600'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600',
                            'block rounded-md px-4 py-3 text-base font-medium transition-all duration-200',
                        ]"
                    >
                        Servicios
                    </Link>
                    <Link
                        href="/contacto"
                        @click="closeMobileMenu"
                        class="block rounded-md px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition-all duration-200"
                    >
                        Contacto
                    </Link>
                    <Link
                        href="/calendario"
                        @click="closeMobileMenu"
                        class="block rounded-md px-4 py-3 text-base font-medium text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition-all duration-200"
                    >
                        Calendario
                    </Link>
                </div>
            </div>
        </Transition>
    </nav>
</template>
