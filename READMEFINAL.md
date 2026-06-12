# Raíces Digitales

Sistema de Mercado Orgánico diseñado para la gestión integral de transacciones comerciales, desde el carrito de compras hasta la persistencia y validación de datos.

## Integrantes
- [Elias Ernesto Orellana Vasquez] - Código: [SMSS211023]
- [Ariel Adolfo Diaz Sosa] - Código: [SMSS109224]
- [Cecia Madai Avalos Diaz] - Código: [SMSS063623]

## Descripción
Este proyecto implementa un módulo transaccional robusto utilizando una arquitectura desacoplada. Permite a los usuarios autenticados consolidar sus compras de productos orgánicos, gestionar datos logísticos de entrega mediante interfaces modales y procesar cobros de manera asíncrona garantizando la integridad de los datos.

## Gestor de Base de Datos
El proyecto utiliza **MySQL** (gestionado a través de MySQL Workbench) para el almacenamiento persistente de transacciones, aplicando esquemas relacionales con llaves foráneas y restricciones de integridad.

## Instalación
Para poner en marcha el proyecto en un entorno local, sigue estos pasos:

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/EliasOrellana437/Raices_digitales.git](https://github.com/EliasOrellana437/Raices_digitales.git)
   cd Raices_digitales

   Instalar dependencias:

Bash
composer install
npm install
Configuración:

Copia el archivo .env.example a .env y configura tus credenciales de base de datos MySQL.

Genera la clave de la aplicación: php artisan key:generate

Base de Datos:

Asegúrate de tener MySQL ejecutándose.

Ejecuta las migraciones para crear la estructura de tablas:

Bash
php artisan migrate
Ejecución:

Bash
php artisan serve
npm run dev
