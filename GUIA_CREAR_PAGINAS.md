# 🚀 Guía Rápida: Crear Páginas con Vue + Inertia.js

## 📌 Comparación Rápida

### Con Blade (ANTES)
```bash
1. Crear archivo: resources/views/mi-pagina.blade.php
2. Agregar ruta: Route::get('/ruta', fn() => view('mi-pagina'));
```

### Con Vue + Inertia (AHORA)
```bash
1. Crear archivo: resources/js/Pages/MiPagina.vue
2. Agregar ruta: Route::get('/ruta', fn() => Inertia::render('MiPagina'));
```

---

## 🎯 Procedimiento Completo (Paso a Paso)

### PASO 1: Crear el Componente Vue

Ubicación: `resources/js/Pages/TuPagina.vue`

```vue
<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Link } from '@inertiajs/vue3';

// Recibir datos desde el backend (opcional)
defineProps({
    datos: Array,
    usuario: Object
});

// Variables locales (JavaScript reactivo)
const items = ['Item 1', 'Item 2', 'Item 3'];

// Funciones
const saludar = () => {
    alert('¡Hola desde Vue!');
};
</script>

<template>
    <MainLayout title="Mi Página">
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold">Mi Página</h1>
            
            <!-- Loop con v-for (equivalente a @foreach en Blade) -->
            <ul>
                <li v-for="item in items" :key="item">
                    {{ item }}
                </li>
            </ul>
            
            <!-- Botón con evento -->
            <button @click="saludar" class="bg-blue-500 text-white px-4 py-2 rounded">
                Click me
            </button>
            
            <!-- Link de Inertia (no recarga la página) -->
            <Link href="/" class="text-blue-600">
                Volver al inicio
            </Link>
        </div>
    </MainLayout>
</template>
```

### PASO 2: Agregar la Ruta

Ubicación: `routes/web.php`

```php
use Inertia\Inertia;

// Ruta simple (sin datos)
Route::get('/mi-pagina', function () {
    return Inertia::render('MiPagina');
});

// Ruta con datos del backend
Route::get('/mi-pagina', function () {
    return Inertia::render('MiPagina', [
        'datos' => ['a', 'b', 'c'],
        'usuario' => auth()->user(),
    ]);
});

// Ruta con nombre (recomendado)
Route::get('/mi-pagina', function () {
    return Inertia::render('MiPagina');
})->name('mi-pagina');

// Ruta protegida (requiere autenticación)
Route::get('/mi-pagina', function () {
    return Inertia::render('MiPagina');
})->middleware(['auth'])->name('mi-pagina');
```

### PASO 3: Agregar al Menú (Opcional)

Ubicación: `resources/js/Components/Navigation.vue`

```vue
<!-- Usando route() para rutas con nombre -->
<Link :href="route('mi-pagina')">
    Mi Página
</Link>

<!-- Usando URL directa -->
<Link href="/mi-pagina">
    Mi Página
</Link>
```

---

## 📚 Equivalencias Blade → Vue

| Blade | Vue + Inertia | Descripción |
|-------|---------------|-------------|
| `@extends('layouts.app')` | `<MainLayout>` | Layout/plantilla |
| `@section('content')` | `<slot>` | Contenido |
| `{{ $variable }}` | `{{ variable }}` | Imprimir variable |
| `@if($condicion)` | `v-if="condicion"` | Condicional |
| `@foreach($items as $item)` | `v-for="item in items"` | Bucle |
| `@auth` | `v-if="$page.props.auth.user"` | Usuario autenticado |
| `{{ route('name') }}` | `:href="route('name')"` | Rutas con nombre |
| `<a href="/ruta">` | `<Link href="/ruta">` | Enlaces |
| `<form action="...">` | `form.post(route(...))` | Formularios |

---

## 🎨 Estructura de un Componente Vue

```vue
<!-- 1. SCRIPT: Lógica JavaScript -->
<script setup>
import { ref, computed } from 'vue';

// Variables reactivas
const contador = ref(0);

// Funciones
const incrementar = () => {
    contador.value++;
};

// Propiedades computadas
const doble = computed(() => contador.value * 2);

// Props (datos del backend)
defineProps({
    titulo: String,
    items: Array
});
</script>

<!-- 2. TEMPLATE: HTML -->
<template>
    <MainLayout title="Mi Página">
        <div>
            <h1>{{ titulo }}</h1>
            <p>Contador: {{ contador }}</p>
            <p>Doble: {{ doble }}</p>
            <button @click="incrementar">+1</button>
        </div>
    </MainLayout>
</template>

<!-- 3. STYLES (opcional): CSS local -->
<style scoped>
h1 {
    color: blue;
}
</style>
```

---

## 🔗 Navegación entre Páginas

```vue
<script setup>
import { Link, router } from '@inertiajs/vue3';
</script>

<template>
    <!-- Método 1: Componente Link (recomendado) -->
    <Link href="/servicios" class="text-blue-600">
        Ver Servicios
    </Link>
    
    <!-- Método 2: Con nombre de ruta -->
    <Link :href="route('servicios')">
        Ver Servicios
    </Link>
    
    <!-- Método 3: Programático -->
    <button @click="router.visit('/servicios')">
        Ir a Servicios
    </button>
</template>
```

---

## 📝 Formularios con Inertia

```vue
<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    nombre: '',
    email: '',
    mensaje: ''
});

const enviar = () => {
    form.post(route('contacto.enviar'), {
        onSuccess: () => {
            alert('¡Mensaje enviado!');
            form.reset();
        },
        onError: (errors) => {
            console.log('Errores:', errors);
        }
    });
};
</script>

<template>
    <form @submit.prevent="enviar">
        <input v-model="form.nombre" placeholder="Nombre" />
        <div v-if="form.errors.nombre" class="text-red-500">
            {{ form.errors.nombre }}
        </div>
        
        <button type="submit" :disabled="form.processing">
            Enviar
        </button>
    </form>
</template>
```

---

## ✅ Checklist para Crear una Página

- [ ] Crear archivo `.vue` en `resources/js/Pages/`
- [ ] Importar `MainLayout` si necesitas nav + footer
- [ ] Agregar la ruta en `routes/web.php`
- [ ] Usar `Inertia::render('NombrePagina')`
- [ ] (Opcional) Agregar enlace en Navigation.vue
- [ ] Probar visitando la URL

---

## 🎯 Ejemplos Rápidos

### Página Simple
```vue
<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
    <MainLayout title="Contacto">
        <div class="container mx-auto p-6">
            <h1>Página de Contacto</h1>
        </div>
    </MainLayout>
</template>
```

Ruta:
```php
Route::get('/contacto', fn() => Inertia::render('Contacto'));
```

### Página con Datos del Backend
```vue
<script setup>
defineProps({
    productos: Array
});
</script>

<template>
    <MainLayout title="Productos">
        <div class="grid grid-cols-3 gap-4">
            <div v-for="producto in productos" :key="producto.id">
                {{ producto.nombre }}
            </div>
        </div>
    </MainLayout>
</template>
```

Ruta:
```php
Route::get('/productos', function () {
    return Inertia::render('Productos', [
        'productos' => Producto::all()
    ]);
});
```

---

## 🚨 Errores Comunes

### ❌ Error: "Page not found"
**Causa:** El nombre del archivo no coincide con el que usas en `Inertia::render()`
**Solución:** 
- Archivo: `resources/js/Pages/MiPagina.vue`
- Ruta: `Inertia::render('MiPagina')` ← Sin .vue

### ❌ Error: "Cannot read properties of undefined"
**Causa:** Intentas acceder a una prop que no existe
**Solución:** Define la prop con `defineProps()`

### ❌ La navegación recarga toda la página
**Causa:** Usas `<a>` en lugar de `<Link>`
**Solución:** Usa `<Link>` de Inertia para navegación SPA

---

## 💡 Tips Pro

1. **Layouts:** Usa `MainLayout` para páginas con nav + footer
2. **Rutas nombradas:** Usa `->name('nombre')` y `route('nombre')` 
3. **Props:** Todo lo que pases en el array de `Inertia::render()` estará en `defineProps()`
4. **Links:** Usa siempre `<Link>` de Inertia, no `<a>`
5. **Usuario actual:** Accede con `$page.props.auth.user`
6. **Compilar:** Ejecuta `npm run dev` mientras desarrollas

---

## 🎓 Resumen Ultra-Rápido

```bash
# 1. Crear componente
resources/js/Pages/MiPagina.vue

# 2. Agregar ruta
routes/web.php → Inertia::render('MiPagina')

# 3. ¡Listo! Visita /mi-pagina
```

**¡Así de simple!** 🎉
