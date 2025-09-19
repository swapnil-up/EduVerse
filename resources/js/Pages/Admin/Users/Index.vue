<template>
    <AuthenticatedLayout>
        <Head title="User Management" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                        <p class="text-gray-600 mt-2">Manage all system users</p>
                    </div>
                    <Link :href="route('admin.users.create')"
                          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Add New User
                    </Link>
                </div>

                <!-- Users Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img :src="user.avatar_url" :alt="user.name"
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ user.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ user.email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="getRoleBadgeClass(user.role)">
                                            {{ user.role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatDate(user.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <Link :href="route('admin.users.edit', user.id)"
                                                  class="text-blue-600 hover:text-blue-900">
                                                Edit
                                            </Link>
                                            <button v-if="user.id !== $page.props.auth.user.id"
                                                    @click="confirmDelete(user)"
                                                    class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.links && users.links.length > 3" class="px-6 py-4 border-t">
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-700">
                                Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
                            </p>
                            <div class="flex space-x-1">
                                <Link v-for="link in users.links" :key="link.label"
                                      :href="link.url"
                                      v-html="link.label"
                                      class="px-3 py-2 text-sm rounded-md transition-colors"
                                      :class="link.active 
                                        ? 'bg-blue-600 text-white' 
                                        : link.url 
                                          ? 'bg-white text-gray-700 border hover:bg-gray-50' 
                                          : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps(['users']);

const getRoleBadgeClass = (role) => {
    const classes = {
        admin: 'bg-red-100 text-red-800',
        instructor: 'bg-green-100 text-green-800',
        student: 'bg-blue-100 text-blue-800',
    };
    return classes[role] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};

const confirmDelete = (user) => {
    if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
        router.delete(route('admin.users.destroy', user.id));
    }
};
</script>