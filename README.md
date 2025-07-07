# TaskFlow

- [Español](#Español)

- [English](#english)

## Español

---

TaskFlow (PHP desde cero con mini-framework MVC y Active Record)
Proyecto de gestión de tareas desarrollado con PHP puro, que incluye un mini-framework MVC propio pensado para facilitar la arquitectura del código y la escalabilidad. Además, implementa un ORM básico usando Active Record para interactuar con la base de datos.
El proyecto cuenta con funcionalidades de autenticación con confirmación por correo, CRUD completo de tareas y estilos con Sass.

Este proyecto fue clave para entender cómo funcionan frameworks profesionales como Laravel y para fortalecer mis habilidades en desarrollo backend.

## Funcionalidades principales:

Registro de usuario con confirmación por correo electrónico ✉️

- Inicio de sesión seguro 🔐

- CRUD completo de tareas 📝

- Marcar tareas como completadas ✅

- Eliminar tareas 🗑️

- Interfaz responsiva

## Tecnologías utilizadas:

- PHP (sin frameworks)

- MySQL

- Sass (para los estilos)

## Instalación y ejecución

**1. Clona el repositorio**

```bash
 git clone https://github.com/Pex-Dev/TaskFlow.git
 cd taskflow
```

**2. Configura la base de datos e importa db.sql**

**3. Copiar archivo env**

```bash
cp .env.example .env
```

**4. Para iniciar el servidor PHP, entra a la carpeta public**

```bash
cd public
php -S localhost:3000
```

**5. En otra terminal, inicia la compilación de estilos con Gulp**

```bash
npm run dev
```

**6. Abre tu navegador en http://localhost:3000 para usar la aplicación.**

## English

---

TaskFlow (PHP from scratch with custom MVC mini-framework and Active Record)
Task management project developed with pure PHP, including a custom-built MVC mini-framework designed to facilitate code architecture and scalability. It also implements a basic Active Record ORM to interact with the database.
The project features user authentication with email confirmation, full CRUD for tasks, and styling with Sass.

This project was key to understanding how professional frameworks like Laravel work and helped strengthen my backend development skills.

## Main Features:

- User registration with email confirmation ✉️

- Secure login 🔐

- Full task CRUD 📝

- Mark tasks as completed ✅

- Delete tasks 🗑️

- Responsive interface

## Technologies Used:

- PHP (no frameworks)

- MySQL

- Sass (for styles)

## Installation and Running

**1. Clone the repository**

```bash
 git clone https://github.com/Pex-Dev/TaskFlow.git
 cd taskflow
```

**Configure the database and import db.sql**

**3. Copy the env file**

```bash
cp .env.example .env
```

**4. To start the PHP server, enter the public folder**

```bash
cd public
php -S localhost:3000
```

**5. In another terminal, start the styles compilation with Gulp**

```bash
npm run dev
```

**6. Open your browser at http://localhost:3000 to use the app**
