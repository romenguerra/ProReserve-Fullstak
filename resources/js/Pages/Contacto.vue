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

    <MainLayout>
        <!-- Hero Section - Same Mesh Style as Welcome -->
        <section class="relative min-h-[60vh] flex items-center overflow-hidden bg-white pt-24">

            
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
        <section class="bg-white py-24 pb-32">
            <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    
                    <!-- Contact Info Cards -->
                    <div class="lg:col-span-4 space-y-8">
                        <div class="animate-on-scroll bg-gray-50/50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500" data-index="2">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6">
                                <svg class="w-6 h-6 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Escríbenos</h3>
                            <p class="text-gray-600">hola@proreserve.com</p>
                            <p class="text-sm text-gray-400 mt-2">Respondemos en menos de 24h</p>
                        </div>

                        <div class="animate-on-scroll bg-gray-50/50 p-8 rounded-3xl border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500" data-index="3">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-6">
                                <svg class="w-6 h-6 text-[#8EB6A5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Ubicación</h3>
                            <p class="text-gray-600">Calle Serrano, 42</p>
                            <p class="text-gray-600">28001 Madrid, España</p>
                        </div>

                        <!-- Social links -->
                        <div class="animate-on-scroll pt-4" data-index="4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 ml-1">Síguenos en redes</p>
                            <div class="flex gap-3">
                                <a v-for="social in ['𝕏', 'IG', 'LI', 'FB']" :key="social" href="#" class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-500 hover:bg-[#8EB6A5] hover:text-white transition-all duration-300 border border-gray-100">
                                    {{ social }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="lg:col-span-8 animate-on-scroll" data-index="5">
                        <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 sm:p-12 shadow-[0_20px_50px_rgba(0,0,0,0.04)]">
                            <form @submit.prevent="submit" class="space-y-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-gray-900 ml-1">Nombre</label>
                                        <input 
                                            v-model="form.name"
                                            type="text" 
                                            required
                                            placeholder="Cómo te llamas"
                                            class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-[#8EB6A5]/10 focus:border-[#8EB6A5] transition-all outline-none"
                                        />
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-sm font-bold text-gray-900 ml-1">Email</label>
                                        <input 
                                            v-model="form.email"
                                            type="email" 
                                            required
                                            placeholder="tu@email.com"
                                            class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-[#8EB6A5]/10 focus:border-[#8EB6A5] transition-all outline-none"
                                        />
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="text-sm font-bold text-gray-900 ml-1">Asunto</label>
                                    <select 
                                        v-model="form.subject"
                                        required
                                        class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-[#8EB6A5]/10 focus:border-[#8EB6A5] transition-all outline-none appearance-none"
                                    >
                                        <option value="" disabled selected>Selecciona un motivo</option>
                                        <option value="user">Soy usuario y necesito ayuda</option>
                                        <option value="business">Tengo un negocio y quiero unirme</option>
                                        <option value="other">Otro asunto</option>
                                    </select>
                                </div>

                                <div class="space-y-3">
                                    <label class="text-sm font-bold text-gray-900 ml-1">Mensaje</label>
                                    <textarea 
                                        v-model="form.message"
                                        rows="5" 
                                        required
                                        placeholder="Cuéntanos más detalles..."
                                        class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-[#8EB6A5]/10 focus:border-[#8EB6A5] transition-all outline-none resize-none"
                                    ></textarea>
                                </div>

                                <div class="flex items-center justify-between gap-6">
                                    <button 
                                        type="submit"
                                        class="bg-gray-900 text-white font-bold px-10 py-5 rounded-full hover:bg-black transition-all duration-300 shadow-xl shadow-gray-200 flex items-center gap-2"
                                    >
                                        {{ isSubmitted ? 'Enviado con éxito' : 'Enviar mensaje' }}
                                        <svg v-if="!isSubmitted" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                        <svg v-else class="w-5 h-5 text-[#8EB6A5]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
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
