<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Panel de Control" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-emerald-950 leading-tight">
                        Panel de Control Agrícola
                    </h2>
                    <p class="text-sm text-emerald-700 font-medium">Bienvenido de vuelta a la gestión de tu finca tecnológica.</p>
                </div>
                <span class="bg-amber-100 text-amber-800 border border-amber-200 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Sincronizado con Finca Local
                </span>
            </div>
        </template>

        <div class="py-10 bg-gradient-to-b from-emerald-50/40 via-white to-white min-h-[calc(100vh-140px)]">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                <div class="bg-white border border-emerald-100 shadow-sm rounded-3xl p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                    
                    <div class="space-y-4 flex-1 relative z-10">
                        <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-md w-fit inline-block">
                            Ecosistema Integrado
                        </span>
                        <h3 class="text-3xl font-extrabold text-slate-800 leading-tight">
                            Optimiza tus tierras con datos inteligentes
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed max-w-2xl">
                            Esta plataforma unifica el monitoreo agronómico en tiempo real de tus parcelas con un ecosistema comercial de comercio justo. Desde aquí puedes supervisar ciclos biológicos y vender sin intermediarios a nivel nacional.
                        </p>
                    </div>

                    <div class="w-32 h-32 md:w-40 md:h-40 shrink-0 bg-emerald-50 rounded-2xl flex items-center justify-center p-2 border border-emerald-100/50 shadow-inner relative z-10">
                        <img src="/images/image.png" alt="Logo Raíces Digitales" class="w-full h-full object-contain fallback-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" />
                        
                        <div class="hidden flex-col items-center justify-center text-center text-emerald-800 font-bold text-xs p-1">
                            <span></span>
                            <span class="text-[10px] text-gray-400 font-normal mt-1">Coloca tu logo en public/images/logo.png</span>
                        </div>
                    </div>

                    <div class="absolute -left-10 -top-10 w-40 h-40 bg-emerald-600/5 rounded-full blur-xl"></div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-2 px-2">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Accesos Rápidos de Gestión
                        </h4>
                        <div class="h-[1px] bg-gray-200 flex-1"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        <a href="/cultivos" class="flex items-center justify-between bg-white hover:bg-emerald-50/60 border border-gray-200 hover:border-emerald-200 p-5 rounded-2xl transition duration-200 shadow-sm group">
                            <div class="flex items-center gap-3">
                                <span class="text-xl bg-emerald-50 p-2.5 rounded-xl group-hover:bg-white transition">🌱</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-gray-800 group-hover:text-emerald-950">Mis Cultivos</span>
                                    <span class="text-xs text-gray-400 font-medium">Control de siembras y parcelas</span>
                                </div>
                            </div>
                            <span class="text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition duration-200">&rarr;</span>
                        </a>

                        <a href="/catalogo" class="flex items-center justify-between bg-white hover:bg-emerald-50/60 border border-gray-200 hover:border-emerald-200 p-5 rounded-2xl transition duration-200 shadow-sm group">
                            <div class="flex items-center gap-3">
                                <span class="text-xl bg-emerald-50 p-2.5 rounded-xl group-hover:bg-white transition">🛒</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-gray-800 group-hover:text-emerald-950">Mercado / Catálogo</span>
                                    <span class="text-xs text-gray-400 font-medium">Comercialización directa</span>
                                </div>
                            </div>
                            <span class="text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition duration-200">&rarr;</span>
                        </a>

                        <a href="/ventas" class="flex items-center justify-between bg-white hover:bg-emerald-50/60 border border-gray-200 hover:border-emerald-200 p-5 rounded-2xl transition duration-200 shadow-sm group">
                            <div class="flex items-center gap-3">
                                <span class="text-xl bg-emerald-50 p-2.5 rounded-xl group-hover:bg-white transition">📊</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-gray-800 group-hover:text-emerald-950">Transacciones</span>
                                    <span class="text-xs text-gray-400 font-medium">Historial de pedidos y ventas</span>
                                </div>
                            </div>
                            <span class="text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition duration-200">&rarr;</span>
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>