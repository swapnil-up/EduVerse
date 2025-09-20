<template>
    <AuthenticatedLayout>
        <Head title="Category Management" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Category Management</h1>
                        <p class="text-gray-600 mt-2">Organize courses with categories</p>
                    </div>
                    <Link :href="route('admin.categories.create')"
                          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Add New Category
                    </Link>
                </div>

                <!-- Categories Grid -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="category in categories.data" :key="category.id" 
                         class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        
                        <!-- Category Header -->
                        <div class="p-6 border-b">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xl"
                                         :style="{ backgroundColor: category.color }">
                                        {{ category.icon || '📚' }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ category.name }}</h3>
                                        <p class="text-sm text-gray-500">{{ category.courses_count }} courses</p>
                                    </div>
                                </div>
                                
                                <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                      :class="category.is_active 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800'">
                                    {{ category.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <p v-if="category.description" class="text-gray-600 text-sm mt-3">
                                {{ category.description }}
                            </p>
                        </div>

                        <!-- Category Actions -->
                        <div class="p-6">
                            <div class="flex space-x-2">
                                <Link :href="route('admin.categories.edit', category.id)"
                                      class="flex-1 bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm hover:bg-blue-200 transition-colors">
                                    Edit
                                </Link>
                                <button v-if="category.courses_count === 0"
                                        @click="confirmDelete(category)"
                                        class="px-3 py-2 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200 transition-colors">
                                    Delete
                                </button>
                                <span v-else class="px-3 py-2 bg-gray-100 text-gray-500 rounded text-sm cursor-not-allowed">
                                    Delete
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="categories.data.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                    <p class="text-gray-500 mb-4">Create your first category to organize courses</p>
                    <Link :href="route('admin.categories.create')"
                          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Create Category
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="categories.links && categories.links.length > 3" class="mt-8 flex justify-center">
                    <div class="flex space-x-1">
                        <Link v-for="link in categories.links" :key="link.label"
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps(['categories']);

const confirmDelete = (category) => {
    if (confirm(`Are you sure you want to delete "${category.name}"? This action cannot be undone.`)) {
        router.delete(route('admin.categories.destroy', category.id));
    }
};
</script>