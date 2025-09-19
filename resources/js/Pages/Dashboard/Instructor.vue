<template>
    <AuthenticatedLayout>
        <Head title="Instructor Dashboard" />
        
        <div class="py-12">
            <div class="max-w-6xl mx-auto px-4">
                <!-- Header -->
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Instructor Dashboard</h1>
                        <p class="text-gray-600 mt-2">Manage your courses and students</p>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Create New Course
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="bg-blue-500 text-white p-3 rounded-full">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">My Courses</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.my_courses }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="bg-green-500 text-white p-3 rounded-full">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Students</p>
                                <p class="text-2xl font-bold text-gray-900">{{ stats.total_students }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="bg-purple-500 text-white p-3 rounded-full">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Course Rating</p>
                                <p class="text-2xl font-bold text-gray-900">4.8</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Courses -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">My Courses</h3>
                    </div>
                    <div v-if="courses.length > 0" class="p-6">
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div v-for="course in courses" :key="course.id" 
                                 class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ course.title }}</h4>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ course.description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500">
                                        Created {{ formatDate(course.created_at) }}
                                    </span>
                                    <div class="space-x-2">
                                        <button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button>
                                        <button class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-6 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h3>
                        <p class="text-gray-500 mb-4">Create your first course to get started</p>
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                            Create Course
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps(['user', 'stats', 'courses']);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString();
};
</script>