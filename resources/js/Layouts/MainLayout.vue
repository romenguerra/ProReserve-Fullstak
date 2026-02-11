<script setup>
import { Head } from '@inertiajs/vue3';
import Navigation from '@/Components/Navigation.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    title: {
        type: String,
        default: 'ProReserve'
    }
});
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="title" />

        <Navigation :user="$page.props.auth?.user" />

        <main>
            <slot />
        </main>

        <Footer />
    </div>
</template>
