<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({ productos: Array });

const mostrarModalPublicar = ref(false);
const mostrarCarrito = ref(false); 
const mostrarModalEnvio = ref(false);
const carrito = ref([]);
const datosEnvio = ref({ direccion: '', telefono: '', notas: '' });

onMounted(() => {
    carrito.value = JSON.parse(localStorage.getItem('carrito_raices')) || [];
});

const form = useForm({
    nombre: '',
    precio: '',
    descripcion: '',
    imagen: '',
    stock: ''
});

const publicarProducto = () => {
    form.post(route('productos.store'), {
        onSuccess: () => {
            form.reset();
            mostrarModalPublicar.value = false;
        }
    });
};

const agregarAlCarrito = (prod) => {
    if (prod.stock <= 0) {
        alert("¡Lo sentimos, este producto está agotado!");
        return;
    }
    const existe = carrito.value.find(item => item.id === prod.id);
    if (existe) {
        if (existe.cantidad < prod.stock) {
            existe.cantidad++;
        } else {
            alert("No hay suficiente stock disponible.");
            return;
        }
    } else {
        carrito.value.push({ ...prod, cantidad: 1 });
    }
    localStorage.setItem('carrito_raices', JSON.stringify(carrito.value));
    mostrarCarrito.value = true;
};

const eliminarDelCarrito = (id) => {
    carrito.value = carrito.value.filter(item => item.id !== id);
    localStorage.setItem('carrito_raices', JSON.stringify(carrito.value));
};

const calcularTotalCarrito = () => {
    return carrito.value.reduce((sum, item) => sum + (item.precio * item.cantidad), 0).toFixed(2);
};

const abrirMenuEnvio = () => {
    mostrarCarrito.value = false;
    mostrarModalEnvio.value = true;
};

const procesarVentaBD = () => {
    router.post(route('ventas.store'), {
        items: carrito.value, 
        total: parseFloat(calcularTotalCarrito()),
        datos_envio: datosEnvio.value
    }, {
        onSuccess: () => {
            carrito.value = [];
            localStorage.removeItem('carrito_raices');
            mostrarModalEnvio.value = false;
            alert('¡Compra realizada y stock actualizado!');
            window.location.href = route('ventas');
        }
    });
};
</script>

<template>
    <Head title="Catálogo Orgánico" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catálogo Orgánico</h2>
                <button @click="mostrarCarrito = true" class="relative bg-green-600 text-white font-bold py-2 px-4 rounded-xl">
                    🛒 Carrito ({{ carrito.length }})
                </button>
                <button @click="mostrarModalPublicar = true" class="bg-blue-600 text-white py-2 px-4 rounded-xl">+ Producto</button>
            </div>
        </template>

        <div class="py-12 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <div v-for="prod in productos" :key="prod.id" class="bg-white p-4 rounded-xl shadow">
                <img :src="prod.imagen" class="h-40 w-full object-cover rounded-lg">
                <h4 class="font-bold text-lg mt-2">{{ prod.nombre }}</h4>
                <p class="text-green-600">${{ prod.precio }}</p>
                <p class="text-sm">Stock: {{ prod.stock }}</p>
                <button @click="agregarAlCarrito(prod)" class="w-full bg-green-600 text-white mt-2 py-2 rounded">Añadir</button>
            </div>
        </div>

        <div v-if="mostrarCarrito" class="fixed inset-0 bg-black/50 z-50 flex justify-end">
            <div class="bg-white w-full max-w-sm p-6">
                <h3 class="font-bold text-lg">Carrito</h3>
                <div v-for="item in carrito" :key="item.id" class="flex justify-between py-2">
                    {{ item.nombre }} ({{ item.cantidad }}) - ${{ (item.precio * item.cantidad).toFixed(2) }}
                    <button @click="eliminarDelCarrito(item.id)" class="text-red-500">X</button>
                </div>
                <button @click="abrirMenuEnvio" class="w-full bg-blue-600 text-white py-2 mt-4 rounded">Pagar ${{ calcularTotalCarrito() }}</button>
                <button @click="mostrarCarrito = false" class="w-full mt-2 text-gray-500">Cerrar</button>
            </div>
        </div>

        <div v-if="mostrarModalEnvio" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white p-6 rounded-2xl w-full max-w-sm">
                <h3 class="font-bold">Datos de Envío</h3>
                <input v-model="datosEnvio.direccion" placeholder="Dirección" class="w-full border p-2 mt-2" />
                <button @click="procesarVentaBD" class="w-full bg-green-600 text-white py-2 mt-4 rounded">Confirmar Compra</button>
            </div>
        </div>

        <div v-if="mostrarModalPublicar" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white p-6 rounded-2xl w-full max-w-md">
                <form @submit.prevent="publicarProducto" class="space-y-3">
                    <input v-model="form.nombre" placeholder="Nombre" class="w-full border p-2" required />
                    <input v-model="form.precio" type="number" placeholder="Precio" class="w-full border p-2" required />
                    <input v-model="form.stock" type="number" placeholder="Stock" class="w-full border p-2" required />
                    <input v-model="form.imagen" placeholder="URL Imagen" class="w-full border p-2" />
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Guardar</button>
                    <button type="button" @click="mostrarModalPublicar = false" class="w-full text-gray-500">Cancelar</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>