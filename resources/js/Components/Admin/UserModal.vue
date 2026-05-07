<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { X, UserPlus, Mail, Lock, ShieldCheck, Edit as UserEdit } from 'lucide-vue-next';

const props = defineProps({
    show: Boolean,
    user: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close']);

const form = useForm({
    id: null,
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'cliente'
});

// Detectar si estamos editando para rellenar el formulario
watch(() => props.user, (newUser) => {
    if (newUser) {
        form.id = newUser.id;
        form.name = newUser.name;
        form.email = newUser.email;
        form.role = newUser.roles?.[0]?.name || 'cliente';
        form.password = '';
        form.password_confirmation = '';
    } else {
        form.reset();
        form.id = null;
    }
}, { immediate: true });

const submit = () => {
    if (form.id) {
        // EDITAR
        form.put(route('admin.users.update', form.id), {
            onSuccess: () => {
                form.reset();
                emit('close');
            }
        });
    } else {
        // CREAR
        form.post(route('admin.users.store'), {
            onSuccess: () => {
                form.reset();
                emit('close');
            }
        });
    }
};

const close = () => {
    form.reset();
    emit('close');
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="close"></div>

                <!-- Modal -->
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden border border-gray-100 animate-in zoom-in-95 duration-300">
                    <!-- Header -->
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <div :class="[form.id ? 'bg-orange-600 shadow-orange-100' : 'bg-indigo-600 shadow-indigo-100']" class="w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <UserEdit v-if="form.id" class="w-5 h-5" />
                                <UserPlus v-else class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 leading-none mb-1">{{ form.id ? 'Editar Usuario' : 'Nuevo Usuario' }}</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ form.id ? 'Modifica los datos del perfil' : 'Acceso directo al sistema' }}</p>
                            </div>
                        </div>
                        <button @click="close" class="text-gray-400 hover:text-gray-900 transition-colors p-2 hover:bg-gray-100 rounded-xl">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="p-6 space-y-5 overflow-y-auto max-h-[70vh]">
                        <!-- Nombre -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nombre Completo</label>
                            <div class="relative group">
                                <UserPlus class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-600 transition-colors" />
                                <input 
                                    v-model="form.name"
                                    type="text" 
                                    required
                                    placeholder="Ej. Juan Pérez"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-sm font-bold"
                                />
                            </div>
                            <p v-if="form.errors.name" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.name }}</p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                            <div class="relative group">
                                <Mail class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-600 transition-colors" />
                                <input 
                                    v-model="form.email"
                                    type="email" 
                                    required
                                    placeholder="correo@ejemplo.com"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-sm font-bold"
                                />
                            </div>
                            <p v-if="form.errors.email" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.email }}</p>
                        </div>

                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                                {{ form.id ? 'Nueva Contraseña (opcional)' : 'Contraseña' }}
                            </label>
                            <div class="relative group">
                                <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-600 transition-colors" />
                                <input 
                                    v-model="form.password"
                                    type="password" 
                                    :required="!form.id"
                                    placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-sm font-bold"
                                />
                            </div>
                            <p v-if="form.errors.password" class="text-red-500 text-[10px] font-black uppercase mt-1 ml-1">{{ form.errors.password }}</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Confirmar Contraseña</label>
                            <div class="relative group">
                                <Lock class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-600 transition-colors" />
                                <input 
                                    v-model="form.password_confirmation"
                                    type="password" 
                                    :required="!form.id || form.password"
                                    placeholder="••••••••"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-2 border-gray-50 rounded-xl focus:bg-white focus:border-indigo-600 focus:ring-0 transition-all text-sm font-bold"
                                />
                            </div>
                        </div>

                        <!-- Rol -->
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Rol del Usuario</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button 
                                    type="button"
                                    v-for="role in ['cliente', 'empresa', 'admin']"
                                    :key="role"
                                    @click="form.role = role"
                                    :class="[
                                        form.role === role 
                                            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 border-indigo-600' 
                                            : 'bg-gray-50 text-gray-400 border-gray-50 hover:bg-gray-100'
                                    ]"
                                    class="py-2.5 rounded-xl border-2 text-[10px] font-black uppercase tracking-widest transition-all"
                                >
                                    {{ role }}
                                </button>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-4 flex gap-3">
                            <button 
                                type="button"
                                @click="close"
                                class="flex-1 px-6 py-4 border-2 border-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit"
                                :disabled="form.processing"
                                :class="[form.id ? 'bg-orange-600 hover:bg-orange-700 shadow-orange-100' : 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-100']"
                                class="flex-1 px-6 py-4 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-xl disabled:opacity-50"
                            >
                                {{ form.processing ? 'Guardando...' : (form.id ? 'Guardar Cambios' : 'Crear Usuario') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
