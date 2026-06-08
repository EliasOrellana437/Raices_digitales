<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// CONEXIÓN REAL: Recibimos las ventas desde MySQL Workbench mediante Laravel
defineProps({
    ventas: Array
});

// Mapeo directo de colores en caliente (Inmune a errores de carga/null)
// Ajustado para dar soporte tanto a 'Completado' como a tu por defecto 'pendiente' en minúsculas
const coloresEstado = {
    'Completado': 'bg-green-100 text-green-800',
    'pendiente': 'bg-blue-100 text-blue-800',
    'Por defecto': 'bg-gray-100 text-gray-800'
};
</script>

<template>
    <Head title="Historial de Ventas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Historial de Ventas</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-green-700">Registro de Transacciones Comerciales (En Vivo desde MySQL)</h3>
                    
                    <div v-if="!ventas || ventas.length === 0" class="text-center py-12 text-gray-400">
                        <p class="text-lg font-medium">No se han registrado transacciones comerciales aún.</p>
                        <p class="text-xs mt-1">Realiza un pago desde el carrito para inaugurar esta tabla.</p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Venta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente / Comprador</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Recaudado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado de Transacción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="venta in ventas" :key="venta.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        V-{{ String(venta.id).padStart(3, '0') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        Usuario #{{ venta.user_id || 'Anon' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                        ${{ venta.total ? parseFloat(venta.total).toFixed(2) : '0.00' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                              :class="coloresEstado[venta.estado] || coloresEstado['Por defecto']">
                                            {{ venta.estado || 'Completado' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>