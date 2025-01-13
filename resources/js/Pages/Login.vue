<template>
    <div class="login-controller">
        <h1>Login</h1>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email</label>
                <input
                    type="email"
                    v-model="email"
                    id="email"
                    placeholder="Enter your email"
                    required
                />
            </div>
            <div>
                <label for="password">Password</label>
                <input
                    type="password"
                    v-model="password"
                    id="password"
                    placeholder="Enter your password"
                    required
                />
            </div>
            <button type="submit">Login</button>
            <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '../components/apiClient';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const login = async () => {
    try {
        const response = await apiClient.post('/login', { email: email.value, password: password.value });
        console.log('Login successful:', response.data);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        router.push('/dashboard');
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Login failed';
    }
};
</script>

<style scoped>
.login-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
}
.error {
    color: red;
}
</style>