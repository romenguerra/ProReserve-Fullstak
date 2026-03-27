<script setup>
import Navigation from '@/Components/Navigation.vue';

defineProps({
    user: Object,
});
</script>

<template>
    <div class="min-h-screen bg-[#F0EEE9]" data-nav-theme="light">
        <Navigation :user="$page.props.auth.user" />

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
