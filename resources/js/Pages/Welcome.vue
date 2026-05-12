<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Bienvenido a Raíces Digitales" />

    <div class="relative min-h-screen bg-gray-50 selection:bg-green-500 selection:text-white">
        <nav v-if="canLogin" class="p-6 text-right z-10 relative bg-white shadow-sm">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-gray-600 hover:text-green-600 font-semibold">Ir al Panel de Control</Link>
            <template v-else>
                <Link :href="route('login')" class="text-gray-600 hover:text-green-600 font-semibold">Iniciar Sesión</Link>
                <Link :href="route('register')" class="ml-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">Registrarse</Link>
            </template>
        </nav>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16 flex flex-col items-center text-center">
            <div class="mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🌱</span>
                </div>
                <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Raíces Digitales</h1>
                <p class="text-xl text-gray-600 max-w-2xl">
                    Conectando la tecnología con el campo. Gestiona tus cultivos, vende productos orgánicos y apoya la agricultura local.
                </p>
            </div>

            <div class="flex gap-4">
                <Link :href="route('register')" class="bg-green-600 text-white px-8 py-3 rounded-full text-lg font-bold hover:bg-green-700 shadow-lg transition">Empezar ahora</Link>
                <a href="#info" class="bg-white text-green-600 border border-green-600 px-8 py-3 rounded-full text-lg font-bold hover:bg-green-50 transition">Saber más</a>
            </div>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-green-700 mb-2">Mis Cultivos</h3>
                    <p class="text-gray-500 text-sm">Monitorea el crecimiento y salud de tus siembras en tiempo real.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-green-700 mb-2">Ventas</h3>
                    <p class="text-gray-500 text-sm">Vende tus productos directamente al consumidor final sin intermediarios.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-green-700 mb-2">Comunidad</h3>
                    <p class="text-gray-500 text-sm">Únete a la red de agricultores digitales más grande de la región.</p>
                </div>
            </div>
        </div>
    </div>
</template>