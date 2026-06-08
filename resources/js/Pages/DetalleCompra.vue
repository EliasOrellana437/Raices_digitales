<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Lista reactiva que manejará MÚLTIPLES productos en el carrito
const productosEnCarrito = ref([]);
const costoEnvio = ref(5.00); // Costo fijo de envío en El Salvador

// Totales reactivos globales de la compra
const totalProductos = ref("0.00");
const granTotal = ref("0.00");

// Función para calcular la suma de todos los productos y sus cantidades
const calcularTotalesGlobales = () => {
    if (productosEnCarrito.value.length > 0) {
        let subtotal = productosEnCarrito.value.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        totalProductos.value = subtotal.toFixed(2);
        granTotal.value = (subtotal + costoEnvio.value).toFixed(2);
    } else {
        totalProductos.value = "0.00";
        granTotal.value = "0.00";
    }
};

// Cargar los productos almacenados en el LocalStorage al abrir la pantalla
onMounted(() => {
    const memoria = localStorage.getItem('carrito_raices');
    if (memoria) {
        productosEnCarrito.value = JSON.parse(memoria);
        calcularTotalesGlobales();
    }
});

// Guardar los cambios de cantidad o eliminación en la memoria del navegador
const sincronizarMemoria = () => {
    localStorage.setItem('carrito_raices', JSON.stringify(productosEnCarrito.value));
    calcularTotalesGlobales();
};

// Controles de cantidad por producto individual (+ / -)
const incrementar = (item) => {
    if (item.cantidad < 10) {
        item.cantidad++;
        sincronizarMemoria();
    }
};

const iconDecrementar = (item) => {
    if (item.cantidad > 1) {
        item.cantidad--;
        sincronizarMemoria();
    }
};

// Eliminar un producto del carrito
const eliminarProducto = (id) => {
    if (confirm('¿Deseas quitar este producto de tu orden?')) {
        productosEnCarrito.value = productosEnCarrito.value.filter(item => item.id !== id);
        sincronizarMemoria();
    }
};

// Formulario reactivo para capturar dirección y método de pago
const form = useForm({
    direccion: '',
    metodo_pago: 'pago_contra_entrega',
});

// Función para confirmar la compra e interconectarla con el historial de Ventas
const finalizarCompra = () => {
    if (!form.direccion) {
        alert('Por favor, ingresa una dirección de envío.');
        return;
    }
    
    // 1. Obtener el historial actual de ventas de la memoria
    let historialVentas = JSON.parse(localStorage.getItem('historial_ventas_raices')) || [];
    
    // 2. Calcular el nuevo ID incremental (asumiendo que V-001 y V-002 ya existen fijas)
    const siguienteNumero = historialVentas.length + 3;
    const nuevoIdVenta = `V-00${siguienteNumero}`;
    
    // 3. Crear el objeto de la nueva venta real basada en el total del carrito
    const nuevaVenta = {
        id_venta: nuevoIdVenta,
        cliente: 'Cliente Local (Tú)',
        total: parseFloat(granTotal.value),
        estado: 'Pendiente' // Queda pendiente de entrega en la finca
    };
    
    // 4. Guardar en el almacenamiento compartido que lee la vista Ventas.vue
    historialVentas.push(nuevaVenta);
    localStorage.setItem('historial_ventas_raices', JSON.stringify(historialVentas));

    // Alerta de confirmación al usuario
    alert(`¡Pedido realizado con éxito!\nSe enviará a: ${form.direccion}\nMétodo de pago: ${form.metodo_pago}\nTotal cancelado: $${granTotal.value}\n\nTu orden fue registrada en el historial como ${nuevoIdVenta}.`);
    
    // 5. Limpiar por completo el carrito de compras y recargar la vista
    localStorage.removeItem('carrito_raices');
    productosEnCarrito.value = [];
    calcularTotalesGlobales();
    
    // Redirigir usando el router de Inertia hacia la sección de Ventas para ver el resultado
    router.visit('/ventas');
};
</script>

<template>
    <Head title="Detalle de su Compra" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Carrito de Compras / Checkout</h2>
                <div class="flex items-center gap-4">
                    <a href="/catalogo" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-xl transition text-sm shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Seguir Añadiendo Productos
                    </a>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    
                    <div v-if="productosEnCarrito.length > 0" class="space-y-8">
                        <h3 class="text-lg font-bold text-green-700 border-b pb-2">Productos Añadidos</h3>
                        
                        <div v-for="item in productosEnCarrito" :key="item.id" class="flex flex-col sm:flex-row items-center gap-6 bg-gray-50 p-4 rounded-xl relative border mb-2">
                            <img :src="item.imagen" :alt="item.nombre" class="w-24 h-24 rounded-lg object-cover border bg-white">
                            
                            <div class="flex-1 text-center sm:text-left">
                                <h4 class="text-xl font-bold text-gray-900">{{ item.nombre }}</h4>
                                <p class="text-xs text-gray-500">Vendido por: {{ item.vendedor }}</p>
                                <p class="text-sm font-semibold text-green-600 mt-1">Precio Unitario: ${{ item.precio.toFixed(2) }}</p>
                            </div>

                            <div class="flex items-center border rounded-lg bg-white overflow-hidden shadow-sm">
                                <button @click="iconDecrementar(item)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 font-bold transition text-gray-700">-</button>
                                <span class="px-4 font-semibold text-gray-900 w-12 text-center">{{ item.cantidad }}</span>
                                <button @click="incrementar(item)" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 font-bold transition text-gray-700">+</button>
                            </div>

                            <div class="text-right min-w-[80px]">
                                <p class="text-xl font-bold text-gray-900">
                                    ${{ (item.precio * item.cantidad).toFixed(2) }}
                                </p>
                            </div>

                            <button @click="eliminarProducto(item.id)" class="absolute top-2 right-2 sm:static text-red-500 hover:text-red-700 p-2 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 border-t pt-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Datos de Entrega</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Dirección de Envío en El Salvador</label>
                                        <textarea v-model="form.direccion" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Ej. Avenida Roosevelt, Barrio El Centro, San Miguel, casa #12" required></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                        <select v-model="form.metodo_pago" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                            <option value="pago_contra_entrega">Pago Contra Entrega (Efectivo)</option>
                                            <option value="transferencia">Transferencia Agrícola / Cuscatlán</option>
                                            <option value="chivo_wallet">Chivo Wallet / Bitcoin</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                        <a href="/catalogo" class="w-full sm:w-1/2 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-4 rounded-xl transition shadow-sm border border-gray-200 flex items-center justify-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            Seguir Comprando
                                        </a>
                                        <button @click="finalizarCompra" class="w-full sm:w-1/2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition shadow">
                                            Confirmar Pedido
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-6 rounded-2xl border flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen del Pago</h3>
                                    <div class="space-y-3 text-sm text-gray-600">
                                        <div class="flex justify-between">
                                            <span>Subtotal de productos añadidos:</span>
                                            <span class="font-semibold text-gray-900">${{ totalProductos }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Costo de envío a domicilio:</span>
                                            <span class="font-semibold text-gray-900">${{ costoEnvio.toFixed(2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t pt-4 mt-4 flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900">Total General:</span>
                                    <span class="text-2xl font-bold text-green-600">${{ granTotal }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-center py-16 space-y-4">
                        <div class="inline-flex p-4 bg-gray-100 rounded-full text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700">Tu carrito está vacío</h3>
                        <p class="text-gray-500 max-w-sm mx-auto">Regresa al catálogo para seleccionar los productos frescos que deseas adquirir de otros productores.</p>
                        <a href="/catalogo" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-xl transition shadow-sm">
                            Explorar Catálogo
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>