<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const productos = [
    { id: 1, nombre: 'Aguacate Hass', precio: 15.50, stock: 50, imagen: 'https://via.placeholder.com/150?text=Aguacate' },
    { id: 2, nombre: 'Café de Altura (500g)', precio: 45.00, stock: 20, imagen: 'https://via.placeholder.com/150?text=Cafe' },
    { id: 3, nombre: 'Miel de Abeja Pura', precio: 30.00, stock: 15, imagen: 'https://via.placeholder.com/150?text=Miel' },
];
</script>

<template>
    <Head title="Catálogo de Productos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catálogo Orgánico</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="prod in productos" :key="prod.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <img :src="prod.imagen" class="w-full h-48 object-cover" />
                        <div class="p-4">
                            <h4 class="text-lg font-bold text-gray-900">{{ prod.nombre }}</h4>
                            <p class="text-green-600 font-bold text-xl mt-2">${{ prod.precio }}</p>
                            <p class="text-sm text-gray-500">Stock disponible: {{ prod.stock }}</p>
                            <button class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">
                                Añadir al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>