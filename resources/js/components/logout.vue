<template>
    <button @click="logout">Logout</button>
</template>

<script setup>
import { useRouter } from 'vue-router';
import apiClient from '../components/apiClient';

const router = useRouter();

const logout = async () => {
    try {
        await apiClient.post('/logout');
        localStorage.removeItem('user');
        router.push('/login'); 
    } catch (error) {
        console.error('Logout failed:', error);
    }
};
</script>
