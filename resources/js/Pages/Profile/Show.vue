<template>
    <AuthenticatedLayout>
        <Head title="My Profile" />
        
        <div class="py-12">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white shadow rounded-lg p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                        <Link 
                            :href="route('profile.edit')"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                        >
                            Edit Profile
                        </Link>
                    </div>

                    <!-- Profile Content -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Avatar Section -->
                        <div class="text-center">
                            <img 
                                :src="user.avatar_url" 
                                :alt="user.name"
                                class="w-32 h-32 rounded-full mx-auto mb-4 object-cover"
                            >
                            <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                            <p class="text-gray-600 capitalize">{{ user.role }}</p>
                        </div>

                        <!-- Profile Info -->
                        <div class="md:col-span-2">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="mt-1 text-gray-900">{{ user.email }}</p>
                                </div>
                                
                                <div v-if="user.phone">
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="mt-1 text-gray-900">{{ user.phone }}</p>
                                </div>
                                
                                <div v-if="user.date_of_birth">
                                    <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                    <p class="mt-1 text-gray-900">{{ formatDate(user.date_of_birth) }}</p>
                                </div>
                                
                                <div v-if="user.city || user.country">
                                    <label class="block text-sm font-medium text-gray-700">Location</label>
                                    <p class="mt-1 text-gray-900">
                                        {{ [user.city, user.country].filter(Boolean).join(', ') }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="user.bio" class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                <p class="text-gray-900 leading-relaxed">{{ user.bio }}</p>
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
import { Head, Link } from '@inertiajs/vue3';

defineProps(['user']);

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString();
};
</script>