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

const submit = () => {
    // Simulación de envío
    isSubmitted.value = true;
    setTimeout(() => {
        isSubmitted.value = false;
        form.reset();
    }, 5000);
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
                <!-- Decorative colored element -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#8EB6A5]/10 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 -left-24 w-72 h-72 bg-white/40 rounded-full blur-[80px]"></div>
                
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
                            <div class="animate-on-scroll bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white/50 hover:shadow-xl hover:shadow-[#8EB6A5]/5 transition-all duration-500" data-index="2">
                                <div class="w-14 h-14 bg-[#8EB6A5] rounded-2xl flex items-center justify-center shadow-lg shadow-[#8EB6A5]/20 mb-6">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Escríbenos</h3>
                                <p class="text-[#4A6358] font-medium font-serif italic">hola@proreserve.com</p>
                                <p class="text-xs text-[#8EB6A5] font-bold uppercase tracking-wider mt-3">24/7 Soporte digital</p>
                            </div>

                            <div class="animate-on-scroll bg-white/60 backdrop-blur-md p-8 rounded-[2rem] border border-white/50 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500" data-index="3">
                                <div class="w-14 h-14 bg-gray-900 rounded-2xl flex items-center justify-center shadow-lg shadow-gray-200/50 mb-6">
                                    <svg class="w-7 h-7 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Ubicación</h3>
                                <p class="text-gray-600">Calle Serrano, 42</p>
                                <p class="text-gray-600">28001 Madrid, España</p>
                            </div>

                            <!-- Social links -->
                            <div class="animate-on-scroll pt-4" data-index="4">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 ml-1">Nuestra comunidad</p>
                                <div class="flex gap-4">
                                    <a v-for="social in ['𝕏', 'IG', 'LI', 'FB']" :key="social" href="#" class="w-12 h-12 bg-white/80 rounded-2xl flex items-center justify-center text-gray-400 hover:bg-[#8EB6A5] hover:text-white hover:border-[#8EB6A5] transition-all duration-500 border border-white shadow-sm hover:shadow-lg hover:shadow-[#8EB6A5]/20">
                                        {{ social }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <div class="lg:col-span-8 animate-on-scroll" data-index="5">
                            <div class="bg-white rounded-[3rem] border border-white/50 p-10 sm:p-14 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.08)] relative overflow-hidden">
                                <!-- Subtle background pulse -->
                                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-[#E8F3EF]/50 rounded-full blur-[80px]"></div>
                                
                                <form @submit.prevent="submit" class="relative z-10 space-y-10">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                                        <div class="space-y-4">
                                            <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Tu Nombre</label>
                                            <input 
                                                v-model="form.name"
                                                type="text" 
                                                required
                                                placeholder="Cómo te llamas"
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-0 py-4 focus:border-b-[#8EB6A5] transition-all outline-none text-lg placeholder:text-gray-300"
                                            />
                                        </div>
                                        <div class="space-y-4">
                                            <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Tu Email</label>
                                            <input 
                                                v-model="form.email"
                                                type="email" 
                                                required
                                                placeholder="tu@email.com"
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-0 py-4 focus:border-b-[#8EB6A5] transition-all outline-none text-lg placeholder:text-gray-300"
                                            />
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Motivo del contacto</label>
                                        <div class="relative">
                                            <select 
                                                v-model="form.subject"
                                                required
                                                class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-0 py-4 focus:border-b-[#8EB6A5] transition-all outline-none text-lg appearance-none cursor-pointer"
                                            >
                                                <option value="" disabled selected>Selecciona un motivo</option>
                                                <option value="user">Soy usuario y necesito ayuda</option>
                                                <option value="business">Tengo un negocio y quiero unirme</option>
                                                <option value="other">Otro asunto</option>
                                            </select>
                                            <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="text-sm font-black text-gray-900 tracking-wider uppercase ml-1">Tu Mensaje</label>
                                        <textarea 
                                            v-model="form.message"
                                            rows="4" 
                                            required
                                            placeholder="Cuéntanos más detalles sobre lo que necesitas..."
                                            class="w-full bg-transparent border-transparent border-b-2 border-b-gray-100 px-0 py-4 focus:border-b-[#8EB6A5] transition-all outline-none text-lg resize-none placeholder:text-gray-300"
                                        ></textarea>
                                    </div>

                                    <div class="flex items-center pt-6">
                                        <button 
                                            type="submit"
                                            class="group bg-gray-900 text-white font-black px-12 py-6 rounded-full hover:bg-black transition-all duration-300 shadow-2xl shadow-gray-200 flex items-center gap-4 text-lg"
                                        >
                                            <span v-if="!isSubmitted">Enviar mensaje</span>
                                            <span v-else>Mensaje enviado</span>
                                            
                                            <div class="w-8 h-8 bg-[#8EB6A5] rounded-full flex items-center justify-center transform group-hover:rotate-12 transition-transform">
                                                <svg v-if="!isSubmitted" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                                <svg v-else class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
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
</style>
