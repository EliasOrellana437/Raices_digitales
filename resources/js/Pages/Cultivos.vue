<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Recibimos los cultivos desde el controlador
defineProps({
    cultivos: Array
});

const mostrarModal = ref(false);

const form = useForm({
    nombre: '',
    fecha_siembra: '',
    descripcion: '',
});

const guardarCultivo = () => {
    form.post(route('cultivos.store'), {
        onSuccess: () => {
            mostrarModal.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Mis Cultivos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de Cultivos</h2>
                <button @click="mostrarModal = true" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow">
                    + Agregar Nuevo Cultivo
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-green-700">Mis Siembras Actuales</h3>
                    
                    <div v-if="cultivos.length === 0" class="text-center py-4 text-gray-500">
                        No hay cultivos registrados. ¡Presiona el botón verde para empezar!
                    </div>

                    <table v-else class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="cultivo in cultivos" :key="cultivo.id">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ cultivo.nombre }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ cultivo.fecha_siembra }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ cultivo.estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</button>
                                    <button class="text-red-600 hover:text-red-900">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="mostrarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4">Registrar nueva siembra</h3>
                <form @submit.prevent="guardarCultivo">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input v-model="form.nombre" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input v-model="form.fecha_siembra" type="date" class="w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="mostrarModal = false" class="text-gray-500">Cancelar</button>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>