<template>
    <MainLayout title="Alta de Establecimiento (Avanzado)">
        <div class="min-h-screen bg-[#F8F9FB] font-sans pb-24">
            
            <!-- HERO HEADER SECTION -->
            <section class="relative pt-16 pb-32 bg-[#0f172a] text-[#F0EEE9] overflow-hidden">
                <!-- Ambient Glow Blobs -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute -top-[10%] -right-[5%] w-[600px] h-[600px] rounded-full bg-[#8EB6A5]/15 blur-[120px] animate-pulse"></div>
                    <div class="absolute top-[20%] -left-[10%] w-[500px] h-[500px] rounded-full bg-indigo-600/10 blur-[130px]"></div>
                </div>

                <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
                    <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-white/90 font-bold text-xs uppercase tracking-[0.2em] mb-6 backdrop-blur-md">
                        <Sparkles class="w-4 h-4 text-[#8EB6A5]" />
                        ProReserve Partners
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight mb-4">
                        Alta de Establecimiento
                    </h1>
                    <p class="text-lg text-white/60 leading-relaxed max-w-2xl mx-auto font-medium">
                        Configura las características, recursos, horarios y servicios de tu local para empezar a gestionar tus citas automatizadas de inmediato.
                    </p>
                </div>
            </section>

            <!-- MAIN FORM CONTAINER -->
            <div class="max-w-4xl mx-auto px-6 -mt-16 relative z-20">
                <div class="bg-white/90 backdrop-blur-xl border border-white/60 shadow-[0_30px_100px_rgba(0,0,0,0.04)] rounded-[2.5rem] p-8 md:p-12">
                    
                    <form @submit.prevent="submitForm" novalidate class="space-y-12">
                        
                        <!-- SECCIÓN 1: TIPO Y DATOS BÁSICOS -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg shrink-0">
                                    01
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Información General</h3>
                                    <p class="text-xs text-gray-400 font-bold mt-0.5">Elige la categoría y datos principales de tu negocio</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Categoría Principal *</label>
                                    <div class="relative">
                                        <select 
                                            v-model="form.type" 
                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 appearance-none outline-none font-bold text-gray-900 cursor-pointer"
                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.type}"
                                        >
                                            <option value="" disabled>Selecciona una categoría...</option>
                                            <option value="restaurant">Restaurante / Gastronomía</option>
                                            <option value="sport_center">Centro Deportivo / Gimnasio</option>
                                            <option value="health_center">Centro de Salud / Clínica</option>
                                            <option value="beauty_center">Centro de Belleza / Peluquería</option>
                                            <option value="leisure_center">Centro de Ocio</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    <p v-if="form.errors.type" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.type }}
                                    </p>
                                </div>
                                
                                <div v-if="form.type" class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">{{ localTypeLabel }}</label>
                                    <input 
                                        v-model="form.local_type" 
                                        type="text" 
                                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900" 
                                        :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.local_type}"
                                        :placeholder="localTypePlaceholder" 
                                    />
                                    <p v-if="form.errors.local_type" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.local_type }}
                                    </p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nombre del Establecimiento *</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.name" 
                                            type="text" 
                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl pl-12 pr-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900" 
                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.name}"
                                            placeholder="Ej: Barbería Clásica Roma, Restaurante El Lago..." 
                                        />
                                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-indigo-500" :class="{'text-red-500': form.errors.name}">
                                            <Store class="w-5 h-5" />
                                        </div>
                                    </div>
                                    <p v-if="form.errors.name" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Descripción Corta</label>
                                    <textarea 
                                        v-model="form.description" 
                                        rows="3" 
                                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900 resize-none"
                                        placeholder="Una pequeña introducción que verán tus clientes al reservar..."
                                    ></textarea>
                                </div>

                                <!-- Foto del Local -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Foto de Portada</label>
                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-6">
                                        <div v-if="imagePreview" class="relative w-full sm:w-36 h-36 rounded-3xl overflow-hidden border-2 border-indigo-100 shadow-lg shrink-0 group animate-in fade-in duration-300">
                                            <img :src="imagePreview" class="w-full h-full object-cover" />
                                            <button 
                                                @click="removeImage" 
                                                type="button" 
                                                class="absolute top-2 right-2 bg-red-500/90 text-white p-2 rounded-xl hover:bg-red-600 transition-colors shadow-lg backdrop-blur-sm"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <div 
                                            class="flex-1 border-2 border-dashed border-gray-200 rounded-3xl p-8 hover:border-indigo-400 hover:bg-indigo-50/20 transition-all cursor-pointer group relative flex flex-col items-center justify-center text-center"
                                            @click="$refs.fileInput.click()"
                                        >
                                            <input 
                                                type="file" 
                                                ref="fileInput" 
                                                class="hidden" 
                                                @change="handleImageChange" 
                                                accept="image/*"
                                            />
                                            <UploadCloud class="w-10 h-10 text-gray-400 group-hover:text-indigo-500 mb-2 transition-colors duration-300" />
                                            <p class="text-sm text-gray-700 font-black group-hover:text-indigo-600 transition-colors duration-300">
                                                Haz clic para subir una foto de portada
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">Soporta JPG, PNG y AVIF (Máx. 2MB)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: UBICACIÓN Y CONTACTO -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-black text-lg shrink-0">
                                    02
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Ubicación y Contacto</h3>
                                    <p class="text-xs text-gray-400 font-bold mt-0.5">Dirección en el mapa e información pública de comunicación</p>
                                </div>
                            </div>

                            <div 
                                class="p-6 bg-indigo-50/30 border border-indigo-100/50 rounded-3xl space-y-4 transition-all duration-300"
                                :class="{'border-red-300 bg-red-50/10': form.errors.address}"
                            >
                                <label class="block text-xs font-black uppercase tracking-widest text-indigo-900/60" :class="{'text-red-900/60': form.errors.address}">Buscador de Dirección del Local *</label>
                                <AddressAutocomplete v-model="form.address" @place_changed="handleLocationSelected" />
                                <div v-if="form.latitude && !form.errors.address" class="flex items-center gap-2 text-xs text-emerald-600 font-black pt-1 animate-in fade-in duration-300">
                                    <CheckCircle2 class="w-4 h-4" />
                                    Ubicación exacta guardada correctamente en el mapa.
                                </div>
                                <p v-if="form.errors.address" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                    <AlertCircle class="w-4 h-4 shrink-0" />
                                    {{ form.errors.address }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Teléfono Público *</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.phone" 
                                            type="tel" 
                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl pl-12 pr-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900" 
                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.phone}"
                                            placeholder="Ej: +34 912 345 678"
                                        />
                                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400" :class="{'text-red-500': form.errors.phone}">
                                            <Phone class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p v-if="form.errors.phone" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.phone }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Email Público *</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.email" 
                                            type="email" 
                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl pl-12 pr-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900" 
                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.email}"
                                            placeholder="contacto@tunegocio.com"
                                        />
                                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400" :class="{'text-red-500': form.errors.email}">
                                            <Mail class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p v-if="form.errors.email" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.email }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Sitio Web</label>
                                    <div class="relative">
                                        <input 
                                            v-model="form.website" 
                                            type="url" 
                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl pl-12 pr-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-300 outline-none font-bold text-gray-900" 
                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors.website}"
                                            placeholder="https://tunegocio.com"
                                        />
                                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-gray-400" :class="{'text-red-500': form.errors.website}">
                                            <Globe class="w-4 h-4" />
                                        </div>
                                    </div>
                                    <p v-if="form.errors.website" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in duration-300">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors.website }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: HORARIOS SEMANALES -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg shrink-0">
                                    03
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Horarios Semanales</h3>
                                    <p class="text-xs text-gray-400 font-bold mt-0.5">Establece qué días abres y en qué rango de horas</p>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 border border-gray-100 rounded-3xl p-6 md:p-8 space-y-4">
                                <div 
                                    v-for="(day, index) in form.schedules" 
                                    :key="index" 
                                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white rounded-2xl border border-gray-50 shadow-sm transition-all duration-300"
                                    :class="{'border-red-200 bg-red-50/5': form.errors[`schedules.${index}`]}"
                                >
                                    <div class="w-28 font-black text-gray-800 text-sm tracking-tight capitalize">{{ day.name }}</div>
                                    
                                    <div class="flex flex-wrap items-center gap-6">
                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input 
                                                type="checkbox" 
                                                v-model="day.is_closed" 
                                                class="sr-only peer"
                                                @change="form.clearErrors(`schedules.${index}`)"
                                            />
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                                            <span class="ml-3 text-xs font-black uppercase tracking-wider text-gray-500 peer-checked:text-red-500">
                                                Cerrado
                                            </span>
                                        </label>

                                        <div v-if="!day.is_closed" class="flex items-center gap-2 animate-in fade-in duration-300">
                                            <input 
                                                type="time" 
                                                v-model="day.opening_time" 
                                                class="bg-gray-50/50 border border-gray-200/80 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all" 
                                                :class="{'border-red-500 bg-red-50/10': form.errors[`schedules.${index}`]}"
                                                @input="form.clearErrors(`schedules.${index}`)"
                                            />
                                            <span class="text-xs text-gray-400 font-bold">a</span>
                                            <input 
                                                type="time" 
                                                v-model="day.closing_time" 
                                                class="bg-gray-50/50 border border-gray-200/80 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all" 
                                                :class="{'border-red-500 bg-red-50/10': form.errors[`schedules.${index}`]}"
                                                @input="form.clearErrors(`schedules.${index}`)"
                                            />
                                        </div>
                                        <div v-else class="text-xs font-bold text-gray-400 italic">
                                            Día de descanso
                                        </div>
                                    </div>
                                    <p v-if="form.errors[`schedules.${index}`]" class="w-full text-xs text-red-500 font-bold flex items-center gap-1 mt-1 animate-in fade-in">
                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                        {{ form.errors[`schedules.${index}`] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CARACTERÍSTICAS ADICIONALES -->
                        <div v-if="form.type" class="p-6 bg-indigo-50/30 border border-indigo-100/50 rounded-3xl animate-in fade-in duration-500">
                            <h4 class="font-black text-indigo-950/80 mb-4 text-xs uppercase tracking-widest">Características Opcionales</h4>
                            <div class="flex flex-wrap gap-6">
                                <label v-if="form.type === 'restaurant'" class="flex items-center cursor-pointer select-none">
                                    <input type="checkbox" v-model="form.smoking_area" class="rounded-xl border-gray-300 text-indigo-600 focus:ring-indigo-500/20 w-5 h-5" />
                                    <span class="ml-3 text-sm font-bold text-gray-700">Zona de Fumadores</span>
                                </label>

                                <label v-if="['sport_center', 'leisure_center', 'health_center'].includes(form.type)" class="flex items-center cursor-pointer select-none">
                                    <input type="checkbox" v-model="form.has_parking" class="rounded-xl border-gray-300 text-indigo-600 focus:ring-indigo-500/20 w-5 h-5" />
                                    <span class="ml-3 text-sm font-bold text-gray-700">Aparcamiento Privado</span>
                                </label>

                                <label v-if="form.type === 'health_center'" class="flex items-center cursor-pointer select-none">
                                    <input type="checkbox" v-model="form.has_emergency" class="rounded-xl border-gray-300 text-indigo-600 focus:ring-indigo-500/20 w-5 h-5" />
                                    <span class="ml-3 text-sm font-bold text-gray-700">Servicio de Urgencias 24h</span>
                                </label>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: RECURSOS Y SERVICIOS -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-lg shrink-0">
                                    04
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-gray-900 tracking-tight">Recursos y Servicios</h3>
                                    <p class="text-xs text-gray-400 font-bold mt-0.5">Zonas reservables de tu establecimiento y servicios que ofreces</p>
                                </div>
                            </div>

                            <!-- RECURSOS -->
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <h4 class="text-sm font-black text-gray-800 uppercase tracking-wide">
                                        Zonas o Recursos Reservables (Ej: Salones, Pistas, Mesas)
                                    </h4>
                                    <button 
                                        type="button" 
                                        @click="addResource" 
                                        class="bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-5 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 flex items-center gap-2 border border-indigo-100/50"
                                    >
                                        <Plus class="w-4 h-4" />
                                        Añadir Recurso
                                    </button>
                                </div>
                                
                                <div class="space-y-4">
                                    <div 
                                        v-for="(resource, index) in form.resources" 
                                        :key="'res-'+index" 
                                        class="p-6 bg-gray-50/50 border border-gray-100 rounded-3xl shadow-sm relative group animate-in fade-in duration-300 transition-all duration-300"
                                        :class="{'border-red-300 bg-red-50/5': form.errors[`resources.${index}.name`] || form.errors[`resources.${index}.unit_count`] || form.errors[`resources.${index}.unit_capacity`] || form.errors[`resources.${index}.max_guests_per_booking`]}"
                                    >
                                        <div class="absolute top-4 right-4" v-if="form.resources.length > 1">
                                            <button 
                                                type="button" 
                                                @click="removeResource(index)" 
                                                class="text-red-500 hover:text-red-700 bg-white border border-red-100 p-2.5 rounded-xl hover:bg-red-50 transition-colors shadow-sm"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <div class="max-w-md">
                                                <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nombre Público del Recurso</label>
                                                <input 
                                                    v-model="resource.name" 
                                                    type="text" 
                                                    class="w-full bg-white border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-gray-900" 
                                                    :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors[`resources.${index}.name`]}"
                                                    placeholder="Ej: Terraza Exterior, Mesa VIP 1, Pista Cubierta A"
                                                    @input="form.clearErrors(`resources.${index}.name`)"
                                                />
                                                <p v-if="form.errors[`resources.${index}.name`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                    <AlertCircle class="w-4 h-4 shrink-0" />
                                                    {{ form.errors[`resources.${index}.name`] }}
                                                </p>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-2xl border border-gray-100">
                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Tipo de Reserva del Recurso</label>
                                                    <select 
                                                        v-model="resource.resource_type" 
                                                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-gray-900 cursor-pointer"
                                                    >
                                                        <option value="exclusive_unit">Unidad Exclusiva (Se reserva por completo para un grupo)</option>
                                                        <option value="shared">Compartido por plazas (Varios clientes reservan plazas individuales)</option>
                                                    </select>
                                                </div>

                                                <template v-if="resource.resource_type === 'exclusive_unit'">
                                                    <div>
                                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nº de Unidades Disponibles</label>
                                                        <input 
                                                            v-model="resource.unit_count" 
                                                            type="number" 
                                                            min="1" 
                                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-gray-900" 
                                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors[`resources.${index}.unit_count`]}"
                                                            @input="form.clearErrors(`resources.${index}.unit_count`)"
                                                        />
                                                        <p v-if="form.errors[`resources.${index}.unit_count`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                            <AlertCircle class="w-4 h-4 shrink-0" />
                                                            {{ form.errors[`resources.${index}.unit_count`] }}
                                                        </p>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Capacidad por Unidad (Personas)</label>
                                                        <input 
                                                            v-model="resource.unit_capacity" 
                                                            type="number" 
                                                            min="1" 
                                                            class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-gray-900" 
                                                            :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors[`resources.${index}.unit_capacity`]}"
                                                            @input="form.clearErrors(`resources.${index}.unit_capacity`)"
                                                        />
                                                        <p v-if="form.errors[`resources.${index}.unit_capacity`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                            <AlertCircle class="w-4 h-4 shrink-0" />
                                                            {{ form.errors[`resources.${index}.unit_capacity`] }}
                                                        </p>
                                                    </div>
                                                </template>

                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Límite de Personas por Reserva</label>
                                                    <input 
                                                        v-model="resource.max_guests_per_booking" 
                                                        type="number" 
                                                        min="1" 
                                                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-2xl px-5 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-gray-900" 
                                                        :class="{'border-red-500 focus:ring-red-500/10 focus:border-red-500 bg-red-50/10': form.errors[`resources.${index}.max_guests_per_booking`]}"
                                                        @input="form.clearErrors(`resources.${index}.max_guests_per_booking`)"
                                                    />
                                                    <p v-if="form.errors[`resources.${index}.max_guests_per_booking`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                        <AlertCircle class="w-4 h-4 shrink-0" />
                                                        {{ form.errors[`resources.${index}.max_guests_per_booking`] }}
                                                    </p>
                                                    <p v-if="resource.resource_type === 'exclusive_unit' && !form.errors[`resources.${index}.max_guests_per_booking`]" class="text-xs text-gray-400 font-medium mt-2 flex items-center gap-1.5">
                                                        <Info class="w-3.5 h-3.5 text-indigo-500" />
                                                        Se bloquearán automáticamente las unidades necesarias (reserva para {{ resource.max_guests_per_booking || 1 }} personas / {{ resource.unit_capacity || 1 }} por unidad = {{ Math.ceil((resource.max_guests_per_booking || 1) / (resource.unit_capacity || 1)) }} unidades bloqueadas).
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SERVICIOS -->
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <h4 class="text-sm font-black text-gray-800 uppercase tracking-wide">
                                        Servicios Disponibles (Citas o actividades que el cliente reserva)
                                    </h4>
                                    <button 
                                        type="button" 
                                        @click="addService" 
                                        class="bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-5 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 flex items-center gap-2 border border-indigo-100/50"
                                    >
                                        <Plus class="w-4 h-4" />
                                        Añadir Servicio
                                    </button>
                                </div>

                                <div class="bg-gray-50/50 border border-gray-100 rounded-3xl p-6 md:p-8 space-y-4">
                                    <div 
                                        v-for="(service, index) in form.services" 
                                        :key="'srv-'+index" 
                                        class="flex flex-col md:flex-row gap-4 items-stretch md:items-end bg-white p-4 rounded-2xl border border-gray-50 shadow-sm animate-in fade-in duration-300 transition-all duration-300"
                                        :class="{'border-red-300 bg-red-50/5': form.errors[`services.${index}.name`] || form.errors[`services.${index}.price`]}"
                                    >
                                        <div class="flex-1">
                                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nombre del Servicio</label>
                                            <input 
                                                v-model="service.name" 
                                                type="text" 
                                                class="w-full bg-gray-50/50 border border-gray-200/80 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all" 
                                                :class="{'border-red-500 bg-red-50/10': form.errors[`services.${index}.name`]}"
                                                placeholder="Ej: Menú Degustación, Corte + Lavado, Masaje Corporal"
                                                @input="form.clearErrors(`services.${index}.name`)"
                                            />
                                            <p v-if="form.errors[`services.${index}.name`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                <AlertCircle class="w-4 h-4 shrink-0" />
                                                {{ form.errors[`services.${index}.name`] }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-36">
                                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Duración</label>
                                            <div class="relative">
                                                <select 
                                                    v-model="service.duration_minutes" 
                                                    class="w-full bg-gray-50/50 border border-gray-200/80 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all cursor-pointer appearance-none animate-in duration-300"
                                                >
                                                    <option value="30">30 min</option>
                                                    <option value="60">1 hora</option>
                                                    <option value="90">1.5 horas</option>
                                                    <option value="120">2 horas</option>
                                                    <option value="180">3 horas</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                                                    <Clock class="w-3.5 h-3.5" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-full md:w-36">
                                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Precio (€)</label>
                                            <input 
                                                v-model="service.price" 
                                                type="number" 
                                                min="0" 
                                                step="0.5" 
                                                class="w-full bg-gray-50/50 border border-gray-200/80 rounded-xl px-4 py-3 text-xs font-bold text-gray-900 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all" 
                                                :class="{'border-red-500 bg-red-50/10': form.errors[`services.${index}.price`]}"
                                                placeholder="Gratis o precio"
                                                @input="form.clearErrors(`services.${index}.price`)"
                                            />
                                            <p v-if="form.errors[`services.${index}.price`]" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1.5 animate-in fade-in">
                                                <AlertCircle class="w-4 h-4 shrink-0" />
                                                {{ form.errors[`services.${index}.price`] }}
                                            </p>
                                        </div>
                                        <div class="flex justify-end items-center pb-2 pl-2">
                                            <button 
                                                v-if="form.services.length > 1" 
                                                type="button" 
                                                @click="removeService(index)" 
                                                class="text-red-500 hover:text-red-700 bg-red-50 p-2.5 rounded-xl hover:bg-red-100 transition-colors shadow-sm"
                                            >
                                                <Trash2 class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Enviar -->
                        <div class="flex justify-end pt-8 border-t border-gray-100">
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto bg-[#0f172a] text-white hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-100/50 py-5 px-10 rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 transform active:scale-95 cursor-pointer shadow-lg"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Guardando Establecimiento...</span>
                                <span v-else class="flex items-center gap-2">
                                    Completar Registro de Local
                                    <ChevronRight class="w-4 h-4" />
                                </span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import AddressAutocomplete from '@/Components/AddressAutocomplete.vue';
import { 
    Store,
    Calendar,
    Clock,
    MapPin,
    Phone,
    Mail,
    Globe,
    Plus,
    Trash2,
    UploadCloud,
    CheckCircle2,
    Info,
    Sparkles,
    ChevronRight,
    AlertCircle
} from 'lucide-vue-next';

const localTypeLabel = computed(() => {
    switch (form.type) {
        case 'restaurant': return 'Tipo de Local / Especialidad *';
        case 'sport_center': return 'Tipo de Instalación *';
        case 'health_center': return 'Tipo de Centro Médico *';
        case 'beauty_center': return 'Tipo de Centro Estético *';
        case 'leisure_center': return 'Tipo de Centro de Ocio *';
        default: return 'Tipo de Local / Especialidad *';
    }
});

const localTypePlaceholder = computed(() => {
    switch (form.type) {
        case 'restaurant': return 'Ej: Asador, Pizzería, Italiano...';
        case 'sport_center': return 'Ej: Crossfit, Gimnasio, Pádel...';
        case 'health_center': return 'Ej: Clínica Dental, Hospital, Fisioterapia...';
        case 'beauty_center': return 'Ej: Peluquería, Spa, Barbería...';
        case 'leisure_center': return 'Ej: Cine, Bolera, Pub...';
        default: return '';
    }
});

const fileInput = ref(null);
const imagePreview = ref(null);

const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    if (fileInput.value) fileInput.value.value = '';
};

const form = useForm({
    type: '',
    name: '',
    local_type: '',
    description: '',
    phone: '',
    email: '',
    website: '',
    image: null,
    smoking_area: false,
    has_parking: false,
    has_emergency: false,
    
    // Address
    address: '',
    city: '',
    postal_code: '',
    latitude: null,
    longitude: null,

    // Schedules
    schedules: [
        { day_of_week: 1, name: 'Lunes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 2, name: 'Martes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 3, name: 'Miércoles', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 4, name: 'Jueves', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 5, name: 'Viernes', is_closed: false, opening_time: '09:00', closing_time: '21:00' },
        { day_of_week: 6, name: 'Sábado', is_closed: false, opening_time: '10:00', closing_time: '23:00' },
        { day_of_week: 7, name: 'Domingo', is_closed: true, opening_time: '10:00', closing_time: '23:00' },
    ],

    // Resources
    resources: [
        { 
            name: 'Salón Principal', 
            capacity: 10,
            resource_type: 'exclusive_unit',
            unit_count: 5,
            unit_capacity: 4,
            max_guests_per_booking: 8
        }
    ],

    // Services
    services: [
        { name: 'Servicio Principal', duration_minutes: 60, price: 0 }
    ],
});

const addResource = () => {
    form.resources.push({ 
        name: '', 
        capacity: 1,
        resource_type: 'exclusive_unit',
        unit_count: 1,
        unit_capacity: 4,
        max_guests_per_booking: 4
    });
};

const removeResource = (index) => {
    if (form.resources.length > 1) {
        form.resources.splice(index, 1);
        form.clearErrors(`resources.${index}.name`);
        form.clearErrors(`resources.${index}.unit_count`);
        form.clearErrors(`resources.${index}.unit_capacity`);
        form.clearErrors(`resources.${index}.max_guests_per_booking`);
    }
};

const addService = () => {
    form.services.push({ name: '', duration_minutes: 60, price: 0 });
};

const removeService = (index) => {
    if (form.services.length > 1) {
        form.services.splice(index, 1);
        form.clearErrors(`services.${index}.name`);
        form.clearErrors(`services.${index}.price`);
    }
};

const handleLocationSelected = (locationData) => {
    form.latitude = locationData.latitude;
    form.longitude = locationData.longitude;
    form.address = locationData.place.formatted_address || form.address;
    form.clearErrors('address');
    
    const addressComponents = locationData.place.address_components;
    if (addressComponents) {
        for (const component of addressComponents) {
            const types = component.types;
            if (types.includes('locality')) {
                form.city = component.long_name;
            }
            if (types.includes('postal_code')) {
                form.postal_code = component.long_name;
            }
        }
    }
};

const validateForm = () => {
    form.clearErrors();
    let hasErrors = false;

    // Validate Category Principal
    if (!form.type) {
        form.setError('type', 'La categoría principal es obligatoria para clasificar tu local.');
        hasErrors = true;
    }
    
    // Validate Specialized Local Type
    if (form.type && !form.local_type) {
        form.setError('local_type', 'Este campo de especialidad es obligatorio.');
        hasErrors = true;
    }
    
    // Validate Name
    if (!form.name || form.name.trim() === '') {
        form.setError('name', 'El nombre del establecimiento es un campo requerido.');
        hasErrors = true;
    }
    
    // Validate Location/Address
    if (!form.address || !form.latitude || !form.longitude) {
        form.setError('address', 'Debes buscar y seleccionar una dirección exacta del local en el buscador para posicionarlo en el mapa.');
        hasErrors = true;
    }
    
    // Validate Phone (Required)
    if (!form.phone || form.phone.trim() === '') {
        form.setError('phone', 'El teléfono público del local es obligatorio.');
        hasErrors = true;
    } else {
        const phoneRegex = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
        if (!phoneRegex.test(form.phone)) {
            form.setError('phone', 'Introduce un número de teléfono válido.');
            hasErrors = true;
        }
    }
    
    // Validate Email (Required)
    if (!form.email || form.email.trim() === '') {
        form.setError('email', 'El correo electrónico público del local es obligatorio.');
        hasErrors = true;
    } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(form.email)) {
            form.setError('email', 'El correo electrónico introducido no tiene un formato válido.');
            hasErrors = true;
        }
    }
    
    // Validate Website URL format (if provided)
    if (form.website && form.website.trim() !== '') {
        const urlRegex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i;
        if (!urlRegex.test(form.website)) {
            form.setError('website', 'La dirección web introducida no tiene un formato correcto.');
            hasErrors = true;
        }
    }

    // Validate Schedules times
    form.schedules.forEach((day, index) => {
        if (!day.is_closed) {
            if (!day.opening_time || !day.closing_time) {
                form.setError(`schedules.${index}`, `Debes definir hora de apertura y cierre para el ${day.name}.`);
                hasErrors = true;
            } else {
                const openVal = day.opening_time.replace(':', '');
                const closeVal = day.closing_time.replace(':', '');
                if (parseInt(openVal) >= parseInt(closeVal)) {
                    form.setError(`schedules.${index}`, 'La hora de cierre debe ser posterior a la de apertura.');
                    hasErrors = true;
                }
            }
        }
    });

    // Validate Resources
    form.resources.forEach((res, index) => {
        if (!res.name || res.name.trim() === '') {
            form.setError(`resources.${index}.name`, 'El nombre público de este recurso es obligatorio.');
            hasErrors = true;
        }
        if (res.resource_type === 'exclusive_unit') {
            if (!res.unit_count || res.unit_count < 1) {
                form.setError(`resources.${index}.unit_count`, 'Debes asignar al menos 1 unidad disponible.');
                hasErrors = true;
            }
            if (!res.unit_capacity || res.unit_capacity < 1) {
                form.setError(`resources.${index}.unit_capacity`, 'La capacidad mínima por unidad debe ser 1.');
                hasErrors = true;
            }
        }
        if (!res.max_guests_per_booking || res.max_guests_per_booking < 1) {
            form.setError(`resources.${index}.max_guests_per_booking`, 'El límite debe permitir al menos a 1 persona.');
            hasErrors = true;
        }
    });

    // Validate Services
    form.services.forEach((srv, index) => {
        if (!srv.name || srv.name.trim() === '') {
            form.setError(`services.${index}.name`, 'El nombre de este servicio es obligatorio.');
            hasErrors = true;
        }
        if (srv.price !== undefined && srv.price !== null && srv.price < 0) {
            form.setError(`services.${index}.price`, 'El precio del servicio no puede ser inferior a 0.');
            hasErrors = true;
        }
    });

    return !hasErrors;
};

const submitForm = () => {
    if (!validateForm()) {
        // Smooth scroll to first error
        setTimeout(() => {
            const firstErrorEl = document.querySelector('.border-red-500, .border-red-300');
            if (firstErrorEl) {
                firstErrorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
        return;
    }

    form.post(route('locales.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<style scoped>
.animate-in {
    animation: animate-in 0.4s ease-out;
}
@keyframes animate-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
