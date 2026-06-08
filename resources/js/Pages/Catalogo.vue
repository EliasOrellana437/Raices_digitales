<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Recibimos las propiedades (los productos de MySQL) desde Laravel
defineProps({
    productos: Array
});

// Variables reactivas de control de interfaz
const mostrarModalPublicar = ref(false);
const mostrarCarrito = ref(false); 
const mostrarModalEnvio = ref(false); // 🔹 NUEVO: Controla el menú hermoso de envío
const carrito = ref([]);

// Variables reactivas para el formulario de envío estético
const datosEnvio = ref({
    direccion: '',
    telefono: '',
    notas: ''
});

// Cargar el carrito desde LocalStorage al inicializar el componente
onMounted(() => {
    carrito.value = JSON.parse(localStorage.getItem('carrito_raices')) || [];
});

// Formulario reactivo oficial de Inertia para crear productos en MySQL
const form = useForm({
    nombre: '',
    precio: '',
    descripcion: '',
    imagen: ''
});

// Enviar el formulario a Laravel para persistir en la Base de Datos
const publicarProducto = () => {
    form.post(route('productos.store'), {
        onSuccess: () => {
            form.reset();
            mostrarModalPublicar.value = false;
        }
    });
};

// Eliminar un registro físicamente de MySQL usando su ID
const eliminarMiProducto = (id) => {
    if (confirm('¿Estás seguro de que deseas retirar este producto de la base de datos?')) {
        router.delete(route('productos.destroy', id));
    }
};

// Agregar ítems al carrito local de forma reactiva
const agregarAlCarrito = (prod) => {
    const existe = carrito.value.find(item => item.id === prod.id);

    if (existe) {
        existe.cantidad++;
    } else {
        carrito.value.push({
            id: prod.id,
            nombre: prod.nombre,
            precio: parseFloat(prod.precio),
            vendedor: prod.vendedor,
            imagen: prod.imagen,
            cantidad: 1
        });
    }

    localStorage.setItem('carrito_raices', JSON.stringify(carrito.value));
    mostrarCarrito.value = true; // Despliega el panel lateral automáticamente
};

// Remover un producto individual del carrito flotante
const eliminarDelCarrito = (id) => {
    carrito.value = carrito.value.filter(item => item.id !== id);
    localStorage.setItem('carrito_raices', JSON.stringify(carrito.value));
};

// Calcular el total monetario acumulado en el carrito
const calcularTotalCarrito = () => {
    return carrito.value.reduce((sum, item) => sum + (item.precio * item.cantidad), 0).toFixed(2);
};

// 🔹 NUEVA LÓGICA: Abre el menú de envío antes de tocar la BD
const abrirMenuEnvio = () => {
    mostrarCarrito.value = false; // Cierra el carrito lateral
    mostrarModalEnvio.value = true; // Abre el modal de datos de envío
};

// Enviar la información de la compra a la base de datos MySQL (Se ejecuta desde el menú de envío)
const procesarVentaBD = () => {
    console.log("Enviando venta a MySQL con total:", calcularTotalCarrito());

    router.post(route('ventas.store'), {
        total: parseFloat(calcularTotalCarrito()),
        cliente_nombre: 'Consumidor Final'
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // 1. Limpiamos almacenamiento local
            carrito.value = [];
            localStorage.removeItem('carrito_raices');
            mostrarModalEnvio.value = false;
            
            // 2. Alertamos el éxito
            alert('¡Compra procesada y guardada con éxito en la Base de Datos!');
            
            // 3. Redirección limpia al historial
            window.location.href = route('ventas');
        },
        onError: (errors) => {
            console.error("Error al guardar en MySQL:", errors);
            alert('Ocurrió un inconveniente al procesar la venta.');
        }
    });
};
</script>

<template>
    <Head title="Catálogo Orgánico" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catálogo Orgánico Mercado</h2>
                <div class="flex gap-4">
                    <button @click="mostrarCarrito = true" class="relative bg-green-100 hover:bg-green-200 text-green-800 font-bold py-2 px-4 rounded-xl transition flex items-center gap-2">
                         Carrito
                        <span v-if="carrito.length > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                            {{ carrito.reduce((sum, item) => sum + item.cantidad, 0) }}
                        </span>
                    </button>

                    <button @click="mostrarModalPublicar = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl transition shadow text-sm flex items-center gap-1">
                        + Publicar mi Producto
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-bold mb-6 text-green-700">Productos Disponibles (En Vivo desde MySQL)</h3>
                    
                    <div v-if="productos.length === 0" class="text-center py-12 text-gray-400">
                        <p class="text-lg font-medium">No hay productos en la base de datos aún.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="prod in productos" :key="prod.id" class="bg-white border rounded-2xl shadow-sm hover:shadow-md transition overflow-hidden flex flex-col justify-between relative">
                            
                            <button v-if="prod.vendedor.includes('(Tú)')" 
                                    @click="eliminarMiProducto(prod.id)"
                                    class="absolute top-3 right-3 bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-xl shadow-md transition z-20">
                                X
                            </button>

                            <div>
                                <img :src="prod.imagen" :alt="prod.nombre" class="w-full h-48 object-cover">
                                <div class="p-6 pb-0">
                                    <h4 class="text-xl font-bold text-gray-900">{{ prod.nombre }}</h4>
                                    <p class="text-xs italic mb-2" :class="prod.vendedor.includes('(Tú)') ? 'text-blue-600 font-bold' : 'text-gray-500'">
                                        Vendió por: {{ prod.vendedor }}
                                    </p>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-2xl font-semibold text-green-600">${{ parseFloat(prod.precio).toFixed(2) }}</span>
                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Stock: {{ prod.stock }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ prod.descripcion }}</p>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <button @click="agregarAlCarrito(prod)" 
                                   class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-sm">
                                    Añadir al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="mostrarCarrito" class="fixed inset-0 bg-black bg-opacity-50 flex justify-end z-50">
            <div class="bg-white w-full max-w-md h-full shadow-2xl p-6 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center border-b pb-4 mb-4">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">🛒 Mi Carrito</h3>
                        <button @click="mostrarCarrito = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                    </div>

                    <div v-if="carrito.length === 0" class="text-center py-12 text-gray-400">
                        <p class="text-lg">Tu carrito está vacío.</p>
                        <p class="text-xs mt-1">¡Explora el mercado y añade productos orgánicos!</p>
                    </div>

                    <div class="space-y-4 overflow-y-auto max-h-[60vh] pr-2">
                        <div v-for="item in carrito" :key="item.id" class="flex items-center gap-4 bg-gray-50 p-3 rounded-xl border">
                            <img :src="item.imagen" class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-900 text-sm">{{ item.nombre }}</h5>
                                <p class="text-xs text-gray-500">Cant: {{ item.cantidad }}</p>
                                <p class="text-sm font-semibold text-green-600">${{ (item.precio * item.cantidad).toFixed(2) }}</p>
                            </div>
                            <button @click="eliminarDelCarrito(item.id)" class="text-red-500 hover:text-red-700 text-sm font-medium px-2">
                                Quitar
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="carrito.length > 0" class="border-t pt-4 space-y-4">
                    <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                        <span>Total a Pagar:</span>
                        <span class="text-green-600">${{ calcularTotalCarrito() }}</span>
                    </div>
                    <button @click="abrirMenuEnvio" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow">
                        Proceder al Pago 
                    </button>
                </div>
            </div>
        </div>

        <div v-if="mostrarModalEnvio" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2"> Datos de Envío y Facturación</h3>
                    <button @click="mostrarModalEnvio = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>
                
                <div class="mb-4 bg-green-50 p-3 rounded-xl border border-green-200 flex justify-between items-center">
                    <span class="text-sm font-medium text-green-800">Monto total a cancelar:</span>
                    <span class="text-lg font-bold text-green-700">${{ calcularTotalCarrito() }}</span>
                </div>

                <form @submit.prevent="procesarVentaBD" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dirección de Entrega *</label>
                        <input v-model="datosEnvio.direccion" type="text" placeholder="Ej. Residencial ... Calle ..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono de Contacto *</label>
                        <input v-model="datosEnvio.telefono" type="tel" placeholder="Ej. 7777-7777" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notas Especiales / Referencias</label>
                        <textarea v-model="datosEnvio.notas" rows="2" placeholder="Ej. Portón color verde, dejar en recepción..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 border-t pt-3">
                        <button type="button" @click="mostrarModalEnvio = false" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg">Atrás</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-bold shadow transition">
                            Confirmar Compra Real 
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="mostrarModalPublicar" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-900">Publicar Nuevo Producto (A MySQL)</h3>
                    <button @click="mostrarModalPublicar = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                </div>
                
                <form @submit.prevent="publicarProducto" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre del Producto *</label>
                        <input v-model="form.nombre" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio de Venta ($) *</label>
                        <input v-model="form.precio" type="number" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URL de la Imagen (Opcional)</label>
                        <input v-model="form.imagen" type="url" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="https://..." />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea v-model="form.descripcion" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 border-t pt-3">
                        <button type="button" @click="mostrarModalPublicar = false" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg">Cancelar</button>
                        <button type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold">
                            Guardar en BD
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </AuthenticatedLayout>
</template>