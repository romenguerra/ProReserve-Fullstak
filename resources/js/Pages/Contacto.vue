<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted, ref } from "vue";

const form = useForm({
    name: "",
    email: "",
    subject: "",
    message: "",
});

const isSubmitted = ref(false);
const isProcessing = ref(false);
const isEmailCopied = ref(false);

const copyEmail = () => {
    navigator.clipboard.writeText('hola@proreserve.com');
    isEmailCopied.value = true;
    setTimeout(() => {
        isEmailCopied.value = false;
    }, 2000);
};

const submit = () => {
    isProcessing.value = true;
    
    // Simulación de envío
    setTimeout(() => {
        isProcessing.value = false;
        isSubmitted.value = true;
        
        setTimeout(() => {
            isSubmitted.value = false;
            form.reset();
        }, 5000);
    }, 1500);
};

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                const index = entry.target.getAttribute('data-index') || 0;
                setTimeout(() => {
                    entry.target.classList.add('reveal-card');
                }, index * 150); 
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach((el) => observer.observe(el));
});
</script>

<template>
    <Head title="Contacto - ProReserve" />

    <MainLayout title="Contacto - ProReserve">
        <div class="bg-[#F0EEE9] min-h-screen"> <!-- Pantone Cloud Dancer Background -->
            <!-- Hero Section -->
            <section class="relative min-h-[50vh] flex items-center overflow-hidden pt-24 pb-12">
                <!-- Decorative colored elements -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[100px] animate-float"></div>
                <div class="absolute top-1/2 -left-24 w-72 h-72 bg-white/40 rounded-full blur-[80px] animate-float-delayed"></div>
                
                <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 w-full relative z-10">
                    <div class="max-w-4xl">
                        <h1 class="text-7xl md:text-8xl lg:text-9xl font-bold text-gray-900 leading-[0.9] tracking-tighter mb-8 animate-on-scroll">
                            <span class="block">Hablemos</span>
                            <span class="text-[#8EB6A5]">contigo.</span>
                        </h1>
                        <p class="text-xl md:text-2xl text-gray-700 max-w-xl leading-relaxed animate-on-scroll" data-index="1">
                            Ya seas un usuario buscando ayuda o un negocio con ganas de innovar, estamos aquí para escucharte.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Main Content Section -->
            <section class="py-12 pb-32">
                <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                        
                        <!-- Contact Info Cards -->
                        <div class="lg:col-span-4 space-y-6">
                            <div class="animate-on-scroll bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white/50 hover:shadow-xl hover:shadow-[#8EB6A5]/5 transition-all duration-500 group" data-index="2">
                                <div class="w-14 h-14 bg-[#8EB6A5] rounded-2xl flex items-center justify-center shadow-lg shadow-[#8EB6A5]/20 mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Escríbenos</h3>
                                <div class="relative inline-block">
                                    <button @click="copyEmail" class="text-[#4A6358] font-medium font-serif italic hover:text-[#8EB6A5] transition-colors flex items-center gap-2 group/btn">
                                        hola@proreserve.com
                                        <svg class="w-4 h-4 opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 002-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 00-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                                    </button>
                                    <Transition name="fade">
                                        <span v-if="isEmailCopied" class="absolute -top-10 left-0 bg-gray-900 text-white text-[10px] py-1 px-3 rounded-full font-bold whitespace-nowrap">¡Copiado!</span>
                                    </Transition>
                                </div>
                                <p class="text-xs text-[#8EB6A5] font-bold uppercase tracking-wider mt-3">24/7 Soporte digital</p>
                            </div>

                            <a href="https://maps.google.com/?q=Calle+Serrano+42+Madrid" target="_blank" class="block animate-on-scroll bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white/50 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 group" data-index="3">
                                <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center shadow-lg shadow-gray-200/50 mb-6 group-hover:scale-110 transition-transform">
                                    <svg class="w-7 h-7 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Ubicación</h3>
                                <p class="text-gray-600">Calle Serrano, 42</p>
                                <p class="text-gray-600 mb-4">28001 Madrid, España</p>
                                <span class="text-xs text-[#8EB6A5] font-bold uppercase tracking-wider flex items-center gap-2">
                                    Ver en mapa 
                                    <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </span>
                            </a>

                            <!-- Social links -->
                            <div class="animate-on-scroll pt-4" data-index="4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 ml-1">Nuestra comunidad</p>
                                <div class="flex gap-4">
                                    <a href="#" class="w-12 h-12 bg-white/80 rounded-2xl flex items-center justify-center text-gray-500 hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 border border-white shadow-sm hover:shadow-lg hover:shadow-[#8EB6A5]/20 hover:-translate-y-1" title="Twitter / X">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-white/80 rounded-2xl flex items-center justify-center text-gray-500 hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 border border-white shadow-sm hover:shadow-lg hover:shadow-[#8EB6A5]/20 hover:-translate-y-1" title="Instagram">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-white/80 rounded-2xl flex items-center justify-center text-gray-500 hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 border border-white shadow-sm hover:shadow-lg hover:shadow-[#8EB6A5]/20 hover:-translate-y-1" title="LinkedIn">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    </a>
                                    <a href="#" class="w-12 h-12 bg-white/80 rounded-2xl flex items-center justify-center text-gray-500 hover:bg-[#8EB6A5] hover:text-white transition-all duration-500 border border-white shadow-sm hover:shadow-lg hover:shadow-[#8EB6A5]/20 hover:-translate-y-1" title="Facebook">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.248h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <div class="lg:col-span-8 animate-on-scroll" data-index="5">
                            <div class="bg-white rounded-[3rem] border border-white/50 p-10 sm:p-14 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.08)] relative overflow-hidden group">
                                <!-- Subtle background pulse -->
                                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-[#E8F3EF]/50 rounded-full blur-[80px] group-hover:scale-110 transition-transform duration-1000"></div>
                                
                                <!-- Success Message Overlay -->
                                <Transition name="fade">
                                    <div v-if="isSubmitted" class="absolute inset-0 z-20 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center text-center p-12">
                                        <div class="w-20 h-20 bg-[#8EB6A5] rounded-full flex items-center justify-center mb-6 scale-up-center">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <h3 class="text-3xl font-bold text-gray-900 mb-4">¡Mensaje enviado!</h3>
                                        <p class="text-lg text-gray-600 max-w-sm">Gracias por contactar con nosotros. Te responderemos en menos de 24 horas.</p>
                                    </div>
                                </Transition>

                                <form @submit.prevent="submit" class="relative z-10 space-y-10" :class="{ 'opacity-20 pointer-events-none': isSubmitted }">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                                        <div class="space-y-4">
                                            <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Tu Nombre</label>
                                            <input 
                                                v-model="form.name"
                                                type="text" 
                                                required
                                                placeholder="Cómo te llamas"
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-2 py-4 focus:border-b-[#8EB6A5] focus:bg-[#8EB6A5]/[0.02] transition-all outline-none text-lg placeholder:text-gray-300"
                                            />
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Tu Email</label>
                                            <input 
                                                v-model="form.email"
                                                type="email" 
                                                required
                                                placeholder="tu@email.com"
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-2 py-4 focus:border-b-[#8EB6A5] focus:bg-[#8EB6A5]/[0.02] transition-all outline-none text-lg placeholder:text-gray-300"
                                            />
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Motivo del contacto</label>
                                        <div class="relative">
                                            <select 
                                                v-model="form.subject"
                                                required
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-2 py-4 focus:border-b-[#8EB6A5] focus:bg-[#8EB6A5]/[0.02] transition-all outline-none text-lg appearance-none cursor-pointer"
                                            >
                                                <option value="" disabled selected>Selecciona un motivo</option>
                                                <option value="user">Soy usuario y necesito ayuda</option>
                                                <option value="business">Tengo un negocio y quiero unirme</option>
                                                <option value="other">Otro asunto</option>
                                            </select>
                                            <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex justify-between items-end ml-1">
                                            <label class="text-sm font-black text-gray-900 tracking-wider uppercase">Tu Mensaje</label>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" :class="{ 'text-[#8EB6A5]': form.message.length > 0 }">
                                                {{ form.message.length }} / 500
                                            </span>
                                        </div>
                                        <textarea 
                                            v-model="form.message"
                                            rows="4" 
                                            required
                                            maxlength="500"
                                            placeholder="Cuéntanos más detalles sobre lo que necesitas..."
                                            class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-2 py-4 focus:border-b-[#8EB6A5] focus:bg-[#8EB6A5]/[0.02] transition-all outline-none text-lg resize-none placeholder:text-gray-300"
                                        ></textarea>
                                    </div>

                                    <!-- Privacy Checkbox -->
                                    <div class="flex items-start gap-3 ml-1 group/check">
                                        <div class="relative flex items-center h-5">
                                            <input 
                                                id="privacy" 
                                                type="checkbox" 
                                                required
                                                class="w-5 h-5 border-2 border-gray-200 rounded text-[#8EB6A5] focus:ring-[#8EB6A5] cursor-pointer transition-colors"
                                            />
                                        </div>
                                        <label for="privacy" class="text-sm text-gray-500 leading-tight cursor-pointer group-hover/check:text-gray-700 transition-colors">
                                            Acepto la <a href="#" class="text-[#8EB6A5] font-bold hover:underline">política de privacidad</a> y el tratamiento de mis datos.
                                        </label>
                                    </div>

                                    <div class="flex items-center pt-6">
                                        <button 
                                            type="submit"
                                            :disabled="isProcessing"
                                            class="group bg-gray-900 text-white font-black px-12 py-6 rounded-full hover:bg-black transition-all duration-300 shadow-2xl shadow-gray-200 flex items-center gap-4 text-lg disabled:opacity-70 disabled:cursor-not-allowed"
                                        >
                                            <span>{{ isProcessing ? 'Enviando...' : 'Enviar mensaje' }}</span>
                                            
                                            <div class="w-8 h-8 bg-[#8EB6A5] rounded-full flex items-center justify-center transform group-hover:rotate-12 transition-transform relative">
                                                <!-- Spinner -->
                                                <svg v-if="isProcessing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <!-- Arrow icon -->
                                                <svg v-else class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </MainLayout>
</template>

<style scoped>
/* Las clases animate-on-scroll y reveal-card se basan en el sistema de Welcome.vue */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

.reveal-card {
    opacity: 1;
    transform: translateY(0);
}

/* Transitions for success state */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.scale-up-center {
    animation: scale-up-center 0.4s cubic-bezier(0.39, 0.575, 0.565, 1) both;
}

@keyframes scale-up-center {
    0% {
        transform: scale(0.5);
    }
    100% {
        transform: scale(1);
    }
}

.animate-float {
    animation: float 15s ease-in-out infinite;
}

.animate-float-delayed {
    animation: float 18s ease-in-out infinite 2s;
}

@keyframes float {
    0%, 100% { transform: translateY(0) scale(1); }
    50% { transform: translateY(-40px) scale(1.1); }
}
</style>
