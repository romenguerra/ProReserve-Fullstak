# <p align="center">🗓️ ProReserve 📅</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Vue.js-3.0-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue.js" />
  <img src="https://img.shields.io/badge/Inertia.js-v1-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="InertiaJS" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/macOS-Compatible-000000?style=for-the-badge&logo=apple&logoColor=white" alt="macOS" />
</p>

---

## 🌟 La Gestión Inteligente de tus Citas

**ProReserve** es un ecosistema digital Full-Stack diseñado para transformar la reserva de servicios locales (ocio, gastronomía, salud, deportes) en una experiencia fluida y moderna. Con un diseño premium adaptado, eliminamos las esperas y agilizamos las conexiones entre usuarios y comercios.

---

## 🚀 Funcionalidades Destacadas

### 📅 Motor de Reservas Dinámico
Planifica sin complicaciones mediante un sistema visual intuitivo:
- **Selección de Fechas:** Validaciones reactivas de disponibilidad horaria.
- **Filtros Inteligentes:** Segmentación de locales por intereses o categorías.

### ✉️ Notificaciones Transaccionales (Async)
- **Emails en Segundo Plano:** Uso de colas de procesos para optimizar el rendimiento.
- **Recuperación de Claves Segura:** Flujos protegidos para la tranquilidad del cliente.

### 🔒 Privacidad Robusta
- **Roles & Permisos:** Respaldado por middlewares y scopes avanzados.

---

## 🎨 Filosofía de Diseño: Premium Dark Navy

- **Identidad Visual:** Colores sobrios (Azul Marino Profundo, Grises Suaves) para otorgar confianza y seriedad.
- **Micro-interacciones:** Transiciones CSS fluidas y respuestas elásticas al pasar el puntero.
- **Responsive Total:** Lectura cómoda desde terminales móviles y tablets.

---

## 🛠️ Stack Tecnológico Pro

| Componente | Tecnología |
| :--- | :--- |
| **Framework Backend** | Laravel 11 (PHP 8.2+) |
| **Framework Frontend** | InertiaJS + Vue.js 3 (Composition API) |
| **Diseño y Estilos** | Tailwind CSS + CSS Nativo |
| **Base de Datos** | MySQL |
| **Colas / Procesos** | Laravel Queues (Database driver) |

---

## 🏁 Inicio Rápido (Guía macOS 🍎)

### Requisitos Previos
- **Homebrew** instalado en tu Mac.
- PHP 8.2+, Composer, Node.js 18+ y MySQL.

### Instalación

1. **Clonación del repositorio**:
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd ProReserve-Fullstak
   ```

2. **Configuración Local**:
   ```bash
   composer install
   pnpm install  # O npm install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migraciones**:
   ```bash
   php artisan migrate --seed
   ```

4. **Despegue (Terminales Independientes)**:
   - *Terminal 1 (Server):* `php artisan serve`
   - *Terminal 2 (Front):* `pnpm dev`
   - *Terminal 3 (Emails):* `php artisan queue:work`

---

<p align="center">
  Creado con dedicación para potenciar negocios locales. 🚀
</p>
