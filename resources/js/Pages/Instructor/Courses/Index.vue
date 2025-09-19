<template>
    <AuthenticatedLayout>
        <Head title="My Courses" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
                        <p class="text-gray-600 mt-2">Manage your courses and track their performance</p>
                    </div>
                    <Link :href="route('instructor.courses.create')"
                          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Create Course</span>
                    </Link>
                </div>

                <!-- Courses Grid -->
                <div v-if="courses.data.length > 0">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="course in courses.data" :key="course.id" 
                             class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                            
                            <!-- Course Image -->
                            <div class="aspect-video bg-gray-200 rounded-t-lg overflow-hidden">
                                <img :src="course.thumbnail_url" 
                                     :alt="course.title"
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Course Content -->
                            <div class="p-6">
                                <!-- Status Badge -->
                                <div class="flex justify-between items-start mb-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full"
                                          :class="course.status_badge_class">
                                        {{ course.status }}
                                    </span>
                                    <span v-if="course.category" class="text-xs text-gray-500">
                                        {{ course.category.icon }} {{ course.category.name }}
                                    </span>
                                </div>

                                <!-- Course Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ course.title }}
                                </h3>

                                <!-- Short Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    {{ course.short_description }}
                                </p>

                                <!-- Course Meta -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                        </svg>
                                        {{ course.duration_hours ? course.duration_hours + 'h' : 'TBD' }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-8a7 7 0 1114 0 7 7 0 01-14 0z"/>
                                        </svg>
                                        {{ course.formatted_price }}
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <Link :href="route('instructor.courses.show', course.id)"
                                          class="flex-1 bg-gray-100 text-gray-700 px-3 py-2 rounded text-center text-sm hover:bg-gray-200 transition-colors">
                                        View
                                    </Link>
                                    <Link :href="route('instructor.courses.edit', course.id)"
                                          class="flex-1 bg-blue-100 text-blue-700 px-3 py-2 rounded text-center text-sm hover:bg-blue-200 transition-colors">
                                        Edit
                                    </Link>
                                    <button @click="confirmDelete(course)"
                                            class="px-3 py-2 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200 transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="courses.links && courses.links.length > 3" class="mt-8 flex justify-center">
                        <div class="flex space-x-1">
                            <Link v-for="link in courses.links" :key="link.label"
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

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No courses yet</h3>
                    <p class="text-gray-500 mb-6">Create your first course to start teaching</p>
                    <Link :href="route('instructor.courses.create')"
                          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Create Your First Course
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps(['courses']);
console.log("courses prop", props.courses); 

const confirmDelete = (course) => {
    if (confirm(`Are you sure you want to delete "${course.title}"? This action cannot be undone.`)) {
        router.delete(route('instructor.courses.destroy', course.id));
    }
};
</script>