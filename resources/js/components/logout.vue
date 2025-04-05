<template>
    <button @click="logout">Logout</button>
</template>

<script setup>
import { router } from '@inertiajs/vue3';


const logout = () => {
    try {
        router.post('/logout');
    } catch (error) {
        console.error('Logout failed:', error);
    }
};
</script>
