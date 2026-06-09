<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

// Recibimos los cultivos del controlador
defineProps({
    cultivos: Array
});

const mostrarModal = ref(false);
const editandoId = ref(null);

// Formulario robusto con todos los campos necesarios
const form = useForm({
    nombre: '',
    fecha_siembra: '',
    estado: 'En desarrollo',
    tiempo_estimado: '',
    descripcion: ''
});

// Función inteligente para guardar o actualizar
const guardarCultivo = () => {
    if (editandoId.value) {
        form.put(route('cultivos.update', editandoId.value), {
            onSuccess: () => cerrarModal(),
        });
    } else {
        form.post(route('cultivos.store'), {
            onSuccess: () => cerrarModal(),
        });
    }
};

const abrirEditar = (cultivo) => {
    editandoId.value = cultivo.id;
    form.nombre = cultivo.nombre;
    form.fecha_siembra = cultivo.fecha_siembra;
    form.estado = cultivo.estado;
    form.tiempo_estimado = cultivo.tiempo_estimado;
    form.descripcion = cultivo.descripcion;
    mostrarModal.value = true;
};

const eliminarCultivo = (id) => {
    if (confirm('¿Seguro que deseas eliminar este cultivo?')) {
        router.delete(route('cultivos.destroy', id));
    }
};

const cerrarModal = () => {
    mostrarModal.value = false;
    editandoId.value = null;
    form.reset();
};
</script>

<template>
    <Head title="Gestión de Cultivos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Siembras</h2>
                <button @click="mostrarModal = true" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow">
                    + Nuevo Cultivo
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiempo Est.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="cultivo in cultivos" :key="cultivo.id">
                                <td class="px-6 py-4 font-bold">{{ cultivo.nombre }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ cultivo.fecha_siembra }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                        {{ cultivo.estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ cultivo.tiempo_estimado }} días</td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="abrirEditar(cultivo)" class="text-indigo-600 hover:text-indigo-900 font-bold mr-3">Editar</button>
                                    <button @click="eliminarCultivo(cultivo.id)" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="mostrarModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-2xl p-6 w-full max-w-lg">
                <h3 class="text-xl font-bold mb-4">{{ editandoId ? 'Editar Cultivo' : 'Nuevo Cultivo' }}</h3>
                <form @submit.prevent="guardarCultivo" class="space-y-4">
                    <input v-model="form.nombre" placeholder="Nombre del cultivo" class="w-full border-gray-300 rounded-lg" required />
                    <input v-model="form.fecha_siembra" type="date" class="w-full border-gray-300 rounded-lg" required />
                    <select v-model="form.estado" class="w-full border-gray-300 rounded-lg">
                        <option>En desarrollo</option>
                        <option>Cosechado</option>
                        <option>En pausa</option>
                    </select>
                    <input v-model="form.tiempo_estimado" type="number" placeholder="Tiempo estimado (días)" class="w-full border-gray-300 rounded-lg" />
                    <textarea v-model="form.descripcion" placeholder="Descripción" class="w-full border-gray-300 rounded-lg"></textarea>
                    
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="cerrarModal" class="px-4 py-2 text-gray-600">Cancelar</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>