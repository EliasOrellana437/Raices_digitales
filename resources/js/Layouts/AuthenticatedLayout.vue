<script setup>
import { ref, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';

// Variables reactivas de control
const mostrarMenuPerfil = ref(false);
const mostrarModalInfoPerfil = ref(false);

// Variable reactiva para almacenar la foto de perfil (guarda el string en Base64 o URL)
const fotoPerfil = ref(null);

// Obtener los datos del usuario logueado directamente desde el Core de Inertia
const { props } = usePage();
const usuarioActivo = props.auth.user;

// Cargar la foto de perfil desde LocalStorage si ya existe una guardada anteriormente
onMounted(() => {
    const fotoGuardada = localStorage.getItem(`foto_perfil_${usuarioActivo.id}`);
    if (fotoGuardada) {
        fotoPerfil.value = fotoGuardada;
    }
});

const toggleMenu = (event) => {
    event.stopPropagation();
    mostrarMenuPerfil.value = !mostrarMenuPerfil.value;
};

const abrirPerfil = () => {
    mostrarMenuPerfil.value = false;
    mostrarModalInfoPerfil.value = true;
};

// Función para procesar y guardar la imagen cargada por el usuario
const cambiarFotoPerfil = (event) => {
    const archivo = event.target.files[0];
    if (archivo) {
        // Validar que sea una imagen
        if (!archivo.type.startsWith('image/')) {
            alert('Por favor, selecciona un archivo de imagen válido.');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            // Guardamos la imagen convertida en texto Base64 en el estado y en LocalStorage
            fotoPerfil.value = e.target.result;
            localStorage.setItem(`foto_perfil_${usuarioActivo.id}`, e.target.result);
        };
        reader.readAsDataURL(archivo);
    }
};

const cerrarSesion = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-emerald-50/20 via-white to-emerald-50/10">
        
        <nav class="bg-white border-b border-emerald-100 shadow-sm sticky top-0 z-40 backdrop-blur-md bg-white/95">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    
                    <div class="flex items-center gap-8">
                        <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-700 to-emerald-900 flex items-center justify-center shadow-md shadow-emerald-200 border-2 border-amber-400 group-hover:rotate-6 transition duration-300">
                                <span class="text-white font-black text-xl tracking-tighter">RD</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-extrabold text-lg text-emerald-950 tracking-tight uppercase leading-none">Raíces</span>
                                <span class="font-bold text-xs text-amber-600 tracking-widest uppercase mt-0.5">Digitales</span>
                            </div>
                        </Link>

                        <div class="hidden md:flex items-center gap-1 h-full pt-1">
                            <Link :href="route('dashboard')" 
                                  class="px-4 py-2 rounded-xl text-sm font-bold transition duration-200"
                                  :class="route().current('dashboard') ? 'bg-emerald-800 text-white shadow-sm shadow-emerald-200' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900'">
                                Inicio
                            </Link>
                            <a href="/cultivos" class="px-4 py-2 rounded-xl text-sm font-bold transition duration-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-900">Mis Cultivos</a>
                            <a href="/catalogo" class="px-4 py-2 rounded-xl text-sm font-bold transition duration-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-900">Mercado / Catálogo</a>
                            <a href="/ventas" class="px-4 py-2 rounded-xl text-sm font-bold transition duration-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-900">Transacciones</a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div class="relative">
                            <button @click="toggleMenu" type="button"
                                    class="flex items-center gap-2 bg-slate-50 border border-slate-200 hover:border-emerald-300 px-4 py-2 rounded-xl transition duration-200 group text-left relative z-50">
                                
                                <img v-if="fotoPerfil" :src="fotoPerfil" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-emerald-300" />
                                <div v-else class="w-8 h-8 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center font-bold text-emerald-800 text-sm">
                                    {{ usuarioActivo.name.charAt(0).toUpperCase() }}
                                </div>

                                <div class="hidden sm:flex flex-col pr-1">
                                    <span class="text-xs font-bold text-slate-700 leading-none">{{ usuarioActivo.name }}</span>
                                    <span class="text-[10px] font-medium text-emerald-600 mt-0.5">Productor Activo</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-emerald-700 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div v-if="mostrarMenuPerfil" @click="mostrarMenuPerfil = false" class="fixed inset-0 z-40 bg-transparent"></div>

                            <div v-if="mostrarMenuPerfil" 
                                 class="absolute right-0 mt-2 w-52 bg-white border border-emerald-100 rounded-2xl shadow-xl py-2 z-50 border-t-2 border-t-emerald-700">
                                <div class="px-4 py-1.5 border-b border-gray-100 text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    Mi Cuenta
                                </div>
                                <button @click="abrirPerfil" type="button"
                                        class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-900 transition flex items-center gap-2">
                                    Ver Mi Perfil
                                </button>
                                <button @click="cerrarSesion" type="button"
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition flex items-center gap-2 border-t border-gray-50">
                                    Cerrar Sesión
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </nav>

        <header class="bg-white border-b border-emerald-50 shadow-sm" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main>
            <slot />
        </main>

        <div v-if="mostrarModalInfoPerfil" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-emerald-100 animate-fade-in">
                
                <div class="bg-gradient-to-r from-emerald-800 to-emerald-950 p-6 text-white relative">
                    <div class="absolute right-4 top-4">
                        <button @click="mostrarModalInfoPerfil = false" class="text-white/70 hover:text-white font-bold text-2xl transition">&times;</button>
                    </div>
                    
                    <div class="flex items-center gap-4 mt-2">
                        <div class="relative group/avatar">
                            <img v-if="fotoPerfil" :src="fotoPerfil" alt="Avatar Grande" class="w-20 h-20 rounded-2xl object-cover border-2 border-amber-400 shadow-md" />
                            <div v-else class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-sm border border-amber-400 flex items-center justify-center text-3xl font-black text-amber-400 shadow-md">
                                {{ usuarioActivo.name.charAt(0).toUpperCase() }}
                            </div>
                            
                            <label class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition cursor-pointer" title="Cambiar foto de perfil">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" accept="image/*" class="hidden" @change="cambiarFotoPerfil" />
                            </label>
                        </div>

                        <div>
                            <h4 class="text-xl font-bold tracking-tight">{{ usuarioActivo.name }}</h4>
                            <span class="bg-amber-400/20 text-amber-300 border border-amber-400/30 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                Productor Verificado
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6 bg-white">
                    <div class="text-center md:text-left">
                        <p class="text-xs text-emerald-700 font-bold bg-emerald-50 px-3 py-1.5 rounded-xl inline-block">
                            Tip: Pasa el mouse sobre tu foto o inicial para actualizarla.
                        </p>
                    </div>

                    <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b pb-2">Información de la Cuenta</h5>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-lg bg-slate-50 p-2 rounded-xl border"></span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-400 uppercase">Correo Electrónico</span>
                                <span class="text-sm font-semibold text-slate-800">{{ usuarioActivo.email }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-lg bg-slate-50 p-2 rounded-xl border"></span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-400 uppercase">Rol Operativo</span>
                                <span class="text-sm font-semibold text-emerald-800">Administrador de Parcelas / Vendedor</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="text-lg bg-slate-50 p-2 rounded-xl border"></span>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-400 uppercase">Zona de Cobertura</span>
                                <span class="text-sm font-semibold text-slate-800">Región Central, El Salvador</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-emerald-50/50 border border-emerald-100/70 p-4 rounded-2xl space-y-2">
                        <h6 class="text-xs font-bold text-emerald-950 flex items-center gap-1">
                            Términos y Condiciones de Uso
                        </h6>
                        <p class="text-[11px] text-emerald-800/80 leading-relaxed font-medium">
                            Como productor activo en Raíces Digitales, te comprometes a publicar productos 100% orgánicos, mantener actualizado tu stock local y garantizar precios justos libres de intermediarios para toda la comunidad agrícola.
                        </p>
                    </div>

                    <button @click="mostrarModalInfoPerfil = false" 
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-4 rounded-xl transition text-sm shadow-sm">
                        Cerrar Ventana
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>