<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const ventas = [
    { id: 'V-001', cliente: 'Juan Pérez', total: 150.00, fecha: '2025-05-10', estado: 'Completado' },
    { id: 'V-002', cliente: 'Maria López', total: 45.00, fecha: '2025-05-11', estado: 'Pendiente' },
];
</script>

<template>
    <Head title="Mis Ventas" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historial de Ventas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="pb-3 font-bold text-gray-600">ID Venta</th>
                                <th class="pb-3 font-bold text-gray-600">Cliente</th>
                                <th class="pb-3 font-bold text-gray-600">Total</th>
                                <th class="pb-3 font-bold text-gray-600">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in ventas" :key="venta.id" class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-4 text-gray-800">{{ venta.id }}</td>
                                <td class="py-4 text-gray-600">{{ venta.cliente }}</td>
                                <td class="py-4 font-bold text-green-700">${{ venta.total }}</td>
                                <td class="py-4 text-sm font-semibold text-blue-600">{{ venta.estado }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>