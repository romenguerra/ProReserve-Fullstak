# Migración de Blade a Vue + Inertia.js

## 📋 Resumen de la Adaptación

Se han migrado todos los archivos Blade a componentes Vue para trabajar con Inertia.js.

## 🔄 Mapeo de Archivos

### Layouts
| Blade (Antes) | Vue + Inertia (Ahora) |
|--------------|---------------------|
| `layouts/app.blade.php` | `Layouts/MainLayout.vue` |
| `layouts/navigation.blade.php` | `Components/Navigation.vue` |
| `layouts/footer.blade.php` | `Components/Footer.vue` |

### Páginas
| Blade (Antes) | Vue + Inertia (Ahora) |
|--------------|---------------------|
| `usuario/perfil.blade.php` | `Pages/Profile/Show.vue` |
| `welcome.blade.php` | `Pages/Welcome.vue` |

## 📁 Estructura de Directorios

```
resources/js/
├── Components/          # Componentes reutilizables
│   ├── Navigation.vue   # Barra de navegación
│   └── Footer.vue       # Pie de página
├── Layouts/             # Layouts de página
│   ├── MainLayout.vue   # Layout principal (con nav + footer)
│   ├── AuthenticatedLayout.vue  # Layout para usuarios autenticados
│   └── GuestLayout.vue  # Layout para invitados
└── Pages/               # Páginas de la aplicación
    ├── Welcome.vue      # Página de inicio
    ├── Dashboard.vue    # Dashboard
    └── Profile/
        ├── Edit.vue     # Editar perfil (ya existía)
        └── Show.vue     # Ver perfil (NUEVO)
```

## 🎯 Cómo Usar los Componentes

### 1. MainLayout (reemplaza app.blade.php)

**Antes (Blade):**
```php
@extends('layouts.app')

@section('title', 'Mi Página')

@section('content')
    <!-- contenido -->
@endsection
```

**Ahora (Vue):**
```vue
<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
    <MainLayout title="Mi Página">
        <!-- contenido -->
    </MainLayout>
</template>
```

### 2. Navigation Component

**Características:**
- ✅ Menú responsive con hamburguesa en móvil
- ✅ Dropdown de perfil con transiciones
- ✅ Resaltado de ruta activa usando `$page.url`
- ✅ Links de Inertia para navegación SPA
- ✅ Logo interactivo

**Props:**
- `user`: Objeto del usuario autenticado

### 3. Footer Component

**Características:**
- ✅ Logo y marca
- ✅ Copyright dinámico (año actual)
- ✅ Links a redes sociales
- ✅ Responsive

## 🚀 Rutas Configuradas

```php
// Página de inicio (pública)
GET / → Welcome.vue

// Dashboard (requiere autenticación)
GET /dashboard → Dashboard.vue

// Perfil (requiere autenticación)
GET /perfil → Profile/Show.vue  // Ver perfil
GET /profile → Profile/Edit.vue // Editar perfil
PATCH /profile → ProfileController@update
DELETE /profile → ProfileController@destroy
```

## 🔧 Principales Diferencias

### Blade vs Vue

| Aspecto | Blade | Vue + Inertia |
|---------|-------|---------------|
| **Sintaxis** | `@if`, `@foreach`, `{{ }}` | `v-if`, `v-for`, `{{ }}` |
| **Directivas** | `@extends`, `@section`, `@yield` | `<slot>`, props |
| **Enlaces** | `<a href="{{ route('...') }}">` | `<Link :href="route('...')">` |
| **Formularios** | `@csrf`, `<form action="...">` | `useForm()`, `form.post()` |
| **Estado activo** | `{{ request()->is(...) ? 'active' : '' }}` | `:class="[$page.url === '/' ? 'active' : '']"` |
| **Autenticación** | `@auth`, `{{ auth()->user()->name }}` | `$page.props.auth.user` |

### Ejemplo de Migración

**Blade:**
```php
<nav>
    <a href="{{ route('home') }}" 
       class="{{ request()->is('/') ? 'active' : '' }}">
        Inicio
    </a>
    @auth
        <span>{{ auth()->user()->name }}</span>
    @endauth
</nav>
```

**Vue:**
```vue
<nav>
    <Link 
        :href="route('home')" 
        :class="[$page.url === '/' ? 'active' : '']"
    >
        Inicio
    </Link>
    <span v-if="$page.props.auth.user">
        {{ $page.props.auth.user.name }}
    </span>
</nav>
```

## 💡 Buenas Prácticas

### 1. Usar Composables para Lógica Reutilizable
```javascript
// composables/useAuth.js
export function useAuth() {
    const user = computed(() => usePage().props.auth.user);
    const isAuthenticated = computed(() => !!user.value);
    return { user, isAuthenticated };
}
```

### 2. Componentes Reactivos
```vue
<script setup>
import { ref, computed } from 'vue';

const isOpen = ref(false);
const toggleMenu = () => isOpen.value = !isOpen.value;
</script>
```

### 3. Props Tipadas
```vue
<script setup>
defineProps({
    user: Object,
    title: {
        type: String,
        default: 'ProReserve'
    }
});
</script>
```

### 4. Formularios con Inertia
```vue
<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: ''
});

const submit = () => {
    form.post(route('profile.update'));
};
</script>
```

## 🎨 Estilos Tailwind

Los estilos se mantienen igual (Tailwind CSS), pero ahora en componentes Vue:

```vue
<template>
    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
        <!-- contenido -->
    </div>
</template>
```

## 🔄 Transiciones Vue

Las transiciones ahora usan el componente `<Transition>` de Vue:

```vue
<Transition
    enter-active-class="transition ease-out duration-100"
    enter-from-class="transform opacity-0 scale-95"
    enter-to-class="transform opacity-100 scale-100"
    leave-active-class="transition ease-in duration-75"
    leave-from-class="transform opacity-100 scale-100"
    leave-to-class="transform opacity-0 scale-95"
>
    <div v-if="isOpen">
        <!-- contenido con transición -->
    </div>
</Transition>
```

## 📝 Notas Importantes

1. **No uses archivos .blade.php** excepto `app.blade.php` (punto de entrada)
2. **Importa componentes**: `import ComponentName from '@/path'`
3. **Usa `<Link>` de Inertia** en lugar de `<a>` para navegación SPA
4. **Accede al usuario**: `$page.props.auth.user`
5. **Rutas**: `route('nombre.ruta')` funciona igual que en Blade

## 🚀 Próximos Pasos

- [ ] Agregar imágenes en `public/images/`
- [ ] Crear controladores para nuevas rutas
- [ ] Implementar autenticación completa
- [ ] Añadir más páginas (Servicios, Contacto, Calendario)
- [ ] Configurar rutas protegidas por roles
