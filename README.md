# Sistema de Gestión para Restaurante de Comida Rápida (Laravel)

Este proyecto es un sistema de gestión desarrollado con el framework PHP Laravel, diseñado para apoyar las operaciones de un restaurante de comida rápida. Utiliza MySQL como base de datos y está optimizado para funcionar con XAMPP en entornos de desarrollo local.

## Características Principales

* **Gestión de Pedidos:** [Puedes añadir aquí funcionalidades específicas como creación, seguimiento, o estado de pedidos].
* **Menú Dinámico:** [Aquí podrías mencionar la capacidad de añadir, editar o visualizar elementos del menú].
* **Autenticación de Usuarios:** Acceso seguro para personal del restaurante (administradores, empleados, etc.).
* **Panel de Control (Dashboard):** Visión general de las operaciones del restaurante.
* **Interfaz de Usuario:** Diseño moderno y responsivo para una experiencia fluida.

## Requisitos del Sistema

Asegúrate de tener instalado y configurado lo siguiente:

* **XAMPP:** Incluye Apache, MySQL y PHP.
* **PHP:** Versión 8.2 o superior (incluido con XAMPP).
* **Composer:** Gestor de dependencias de PHP.
* **Node.js & NPM:** Para compilar los assets de frontend (CSS, JavaScript).

## Instalación y Configuración

Sigue estos pasos para poner el proyecto en marcha en tu entorno local con XAMPP:

1.  **Clona el repositorio:**
    ```bash
    git clone https://github.com/Systemtresdos/Proyecto-sin-docker.git
    ```
    * **Nota:** Después de clonar, mueve la carpeta del proyecto a `C:\xampp\htdocs\` (o la ruta equivalente de tu instalación de XAMPP).

2.  **Instala las dependencias de PHP:**
    Abre la terminal en la raíz de tu proyecto y ejecuta:
    ```bash
    composer install
    ```

3.  **Configura el archivo de entorno (`.env`):**
    Copia el archivo de ejemplo y genera una clave de aplicación:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Edita el archivo `.env` para configurar tu conexión a la base de datos (con XAMPP, los valores por defecto suelen ser los siguientes):
    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=restaurante
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    * **Importante:** Crea una base de datos vacía con el nombre que especifiques en `DB_DATABASE` usando phpMyAdmin (accediendo vía `http://localhost/phpmyadmin/` en tu navegador).

4.  **Ejecuta las migraciones de la base de datos:**
    Esto creará las tablas necesarias en tu base de datos:
    ```bash
    php artisan migrate
    # si gustas puedes cread tus propios seeders con el siguiente comando
    # php artisan db:seed
    ```

5.  **Instala las dependencias de frontend y compila los assets:**
    Si el proyecto utiliza componentes de frontend como Tailwind CSS, React, Vue.js, etc., necesitarás compilar los archivos CSS y JavaScript:
    ```bash
    npm install
    npm run dev # Para desarrollo (observa cambios), o npm run build para producción
    ```

6.  **Inicia el servidor de desarrollo de Laravel:**
    Asegúrate de que Apache y MySQL estén corriendo en tu panel de control de XAMPP. Luego, en la terminal de tu proyecto, ejecuta:
    ```bash
    php artisan serve
    ```
    Ahora deberías poder acceder a la aplicación en `http://127.0.0.1:8000` en tu navegador.



