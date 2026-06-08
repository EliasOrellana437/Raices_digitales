<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión" />

    <div class="min-h-screen flex flex-col sm:flex-row bg-white">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-emerald-800 via-emerald-950 to-slate-950 p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-700/20 rounded-full blur-2xl"></div>
            <div class="absolute right-20 top-20 w-40 h-40 bg-amber-500/10 rounded-full blur-xl"></div>
            
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-md border border-amber-400">
                    <span class="text-emerald-950 font-black text-sm">RD</span>
                </div>
                <span class="text-white font-extrabold tracking-wider uppercase text-sm">Raíces Digitales</span>
            </div>

            <div class="space-y-4 relative z-10 max-w-md">
                <h2 class="text-4xl font-extrabold text-white leading-tight">
                    Tecnología que impulsa el agro local.
                </h2>
                <p class="text-emerald-200/80 text-sm leading-relaxed">
                    Gestiona tus cultivos, publica en el mercado y analiza tus transacciones desde una sola plataforma unificada.
                </p>
            </div>

            <p class="text-xs text-emerald-400/60 relative z-10">
                &copy; 2026 Raíces Digitales - El Salvador.
            </p>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-slate-50/50">
            <div class="w-full max-w-md space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-emerald-50 shadow-sm">
                
                <div class="space-y-2 text-center md:text-left">
                    <h3 class="text-2xl font-black text-slate-800">Ingreso al Sistema</h3>
                    <p class="text-sm text-gray-500">Introduce tus credenciales para acceder a la finca digital.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Correo Electrónico</label>
                        <input v-model="form.email" type="email" autocomplete="username" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800 placeholder-gray-400" 
                            placeholder="ejemplo@correo.com" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Contraseña</label>
                            <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition">
                                ¿La olvidaste?
                            </Link>
                        </div>
                        <input v-model="form.password" type="password" autocomplete="current-password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800 placeholder-gray-400" 
                            placeholder="••••••••" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.password">{{ form.errors.password }}</span>
                    </div>

                    <div class="flex items-center">
                        <input v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 h-4 w-4" />
                        <span class="ml-2 text-xs font-medium text-slate-600">Mantener sesión activa</span>
                    </div>

                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 shadow-md shadow-emerald-100 flex items-center justify-center">
                        Iniciar Sesión
                    </button>
                    
                    <p class="text-center text-xs text-slate-500 font-medium">
                        ¿Aún no tienes cuenta? 
                        <Link :href="route('register')" class="text-emerald-700 hover:text-emerald-900 font-bold transition">Regístrate aquí</Link>
                    </p>
                </form>

            </div>
        </div>

    </div>
</template>