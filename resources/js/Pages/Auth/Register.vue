<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Registro de Productor" />

    <div class="min-h-screen flex flex-col sm:flex-row bg-white">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-emerald-800 via-emerald-950 to-slate-950 p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-700/20 rounded-full blur-2xl"></div>
            
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-md border border-amber-400">
                    <span class="text-emerald-950 font-black text-sm">RD</span>
                </div>
                <span class="text-white font-extrabold tracking-wider uppercase text-sm">Raíces Digitales</span>
            </div>

            <div class="space-y-4 relative z-10 max-w-md">
                <h2 class="text-4xl font-extrabold text-white leading-tight">
                    Únete a la red agrícola del futuro.
                </h2>
                <p class="text-emerald-200/80 text-sm leading-relaxed">
                    Regístrate de forma gratuita para empezar a controlar tus tiempos de siembra y comercializar tus productos con otros productores del país.
                </p>
            </div>

            <p class="text-xs text-emerald-400/60 relative z-10">
                &copy; 2026 Raíces Digitales - El Salvador.
            </p>
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-slate-50/50">
            <div class="w-full max-w-md space-y-6 bg-white p-8 sm:p-10 rounded-3xl border border-emerald-50 shadow-sm">
                
                <div class="space-y-2 text-center md:text-left">
                    <h3 class="text-2xl font-black text-slate-800">Crear Nueva Cuenta</h3>
                    <p class="text-sm text-gray-500">Completa tus datos para dar de alta tu perfil de productor.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nombre Completo</label>
                        <input v-model="form.name" type="text" required autofocus autocomplete="name"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800" 
                            placeholder="Ej. Juan Pérez" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.name">{{ form.errors.name }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Correo Electrónico</label>
                        <input v-model="form.email" type="email" required autocomplete="username"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800" 
                            placeholder="juan@correo.com" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contraseña</label>
                        <input v-model="form.password" type="password" required autocomplete="new-password"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800" 
                            placeholder="••••••••" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.password">{{ form.errors.password }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Confirmar Contraseña</label>
                        <input v-model="form.password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm text-gray-800" 
                            placeholder="••••••••" />
                        <span class="text-xs text-red-500 mt-1 block" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</span>
                    </div>

                    <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-bold py-3 px-4 rounded-xl transition duration-200 shadow-md shadow-emerald-100 mt-2">
                        Registrarme
                    </button>

                    <p class="text-center text-xs text-slate-500 font-medium pt-2">
                        ¿Ya tienes una cuenta? 
                        <Link :href="route('login')" class="text-emerald-700 hover:text-emerald-900 font-bold transition">Ingresa aquí</Link>
                    </p>
                </form>

            </div>
        </div>

    </div>
</template>