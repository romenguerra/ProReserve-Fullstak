<template>
    <footer class="bg-[#0f172a] text-white border-t border-white/10">
        <div class="mx-auto max-w-7xl px-6 sm:px-12 lg:px-16 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Columna 1: Marca y descripción -->
                <div class="flex flex-col space-y-6">
                    <div class="flex items-center space-x-3">
                        <img
                            src="/images/logo-proreserve.avif"
                            :alt="$t('footer.logo_alt')"
                            class="h-10 w-auto brightness-0 invert"
                        />
                        <span class="text-2xl font-bold text-white tracking-tighter"
                            >{{ $t('footer.brand_name') }}</span
                        >
                    </div>
                    <p class="text-base text-gray-400 leading-relaxed max-w-sm">
                        {{ $t('footer.description') }}
                    </p>
                </div>

                <!-- Columna 2: Enlaces rápidos -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-6">
                        {{ $t('footer.quick_links') }}
                    </h3>
                    <ul class="space-y-4">
                        <li>
                            <Link
                                :href="route('home')"
                                class="text-gray-400 hover:text-[#8EB6A5] transition-colors duration-300 flex items-center group"
                            >
                                <span class="h-px w-0 group-hover:w-4 bg-[#8EB6A5] mr-0 group-hover:mr-2 transition-all duration-300"></span>
                                {{ $t('footer.nav_home') }}
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('servicios')"
                                class="text-gray-400 hover:text-[#8EB6A5] transition-colors duration-300 flex items-center group"
                            >
                                <span class="h-px w-0 group-hover:w-4 bg-[#8EB6A5] mr-0 group-hover:mr-2 transition-all duration-300"></span>
                                {{ $t('footer.nav_services') }}
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('contacto')"
                                class="text-gray-400 hover:text-[#8EB6A5] transition-colors duration-300 flex items-center group"
                            >
                                <span class="h-px w-0 group-hover:w-4 bg-[#8EB6A5] mr-0 group-hover:mr-2 transition-all duration-300"></span>
                                {{ $t('footer.nav_contact') }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Boletín / Dashboard CTA -->
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-6">
                        <template v-if="!user">
                            {{ $t('footer.subscribe_title') }}
                        </template>
                        <template v-else-if="isClient">
                            {{ $t('footer.client_title') }}
                        </template>
                        <template v-else>
                            {{ $t('footer.dashboard_title') }}
                        </template>
                    </h3>
                    <p class="text-sm text-gray-400 mb-6 font-medium">
                        <template v-if="!user">
                            {{ $t('footer.subscribe_desc') }}
                        </template>
                        <template v-else-if="isClient">
                            {{ $t('footer.client_desc') }}
                        </template>
                        <template v-else>
                            {{ $t('footer.dashboard_desc') }}
                        </template>
                    </p>
                    <div class="flex flex-col space-y-4">
                        <Link
                            v-if="!user"
                            :href="route('register')"
                            class="bg-[#8EB6A5] hover:bg-[#7aa592] text-white text-center font-bold py-4 px-8 rounded-2xl transition-all duration-300 shadow-xl shadow-[#8EB6A5]/20 hover:-translate-y-1 block w-full"
                        >
                            {{ $t('footer.subscribe_button') }}
                        </Link>
                        <Link
                            v-else-if="isClient"
                            :href="route('reservas.index')"
                            class="bg-[#8EB6A5] hover:bg-[#7aa592] text-white text-center font-bold py-4 px-8 rounded-2xl transition-all duration-300 shadow-xl shadow-[#8EB6A5]/20 hover:-translate-y-1 block w-full"
                        >
                            {{ $t('footer.client_button') }}
                        </Link>
                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="bg-[#8EB6A5] hover:bg-[#7aa592] text-white text-center font-bold py-4 px-8 rounded-2xl transition-all duration-300 shadow-xl shadow-[#8EB6A5]/20 hover:-translate-y-1 block w-full"
                        >
                            {{ $t('footer.dashboard_button') }}
                        </Link>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-white/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center"
            >
                <p class="text-sm text-gray-500">
                    &copy; {{ currentYear }} {{ $t('footer.copyright') }}
                </p>
                <div class="flex space-x-6 mt-6 md:mt-0">
                    <a
                        href="https://instagram.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-500 hover:text-white transition-colors duration-300"
                    >
                        <span class="sr-only">{{ $t('footer.social_instagram') }}</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a
                        href="https://x.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-500 hover:text-white transition-colors duration-300"
                    >
                        <span class="sr-only">{{ $t('footer.social_twitter') }}</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
                        </svg>
                    </a>
                    <a
                        href="https://facebook.com"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-gray-500 hover:text-white transition-colors duration-300"
                    >
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.248h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const currentYear = computed(() => new Date().getFullYear());

const user = computed(() => page.props.auth?.user);
const isClient = computed(() => {
    if (!user.value) return false;
    const userRoles = user.value.roles || [];
    return userRoles.length === 0 || userRoles.includes('cliente');
});
</script>
