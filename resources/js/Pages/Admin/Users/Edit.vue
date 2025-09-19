<template>
    <AuthenticatedLayout>
        <Head :title="`Edit User: ${user.name}`" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <div class="bg-white rounded-lg shadow p-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit User: {{ user.name }}</h1>

                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name" v-model="form.name"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" v-model="form.email"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</div>
                        </div>

                        <div class="mb-6">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role" v-model="form.role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option disabled value="">Select a role</option>
                                <option value="admin">Admin</option>
                                <option value="instructor">Instructor</option>
                                <option value="student">Student</option>
                            </select>
                            <div v-if="form.errors.role" class="text-sm text-red-600 mt-1">{{ form.errors.role }}</div>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <Link :href="indexRoute"
                                  class="text-gray-600 hover:text-gray-900 transition-colors">
                                Cancel
                            </Link>
                            <button type="submit" :disabled="form.processing"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    updateRoute: String,
    indexRoute: String,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const submit = () => {
    form.patch(props.updateRoute);
};
</script>