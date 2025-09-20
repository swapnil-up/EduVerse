<template>
    <AuthenticatedLayout>

        <Head :title="course.title" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">

                <!-- Course Header -->
                <div class="grid lg:grid-cols-3 gap-8 mb-12">
                    <!-- Left Column - Course Info -->
                    <div class="lg:col-span-2">
                        <!-- Breadcrumb -->
                        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                            <Link :href="route('courses.index')" class="hover:text-gray-700">Courses</Link>
                            <span>›</span>
                            <span v-if="course.category" class="hover:text-gray-700">
                                {{ course.category.name }}
                            </span>
                            <span>›</span>
                            <span class="text-gray-900">{{ course.title }}</span>
                        </div>

                        <!-- Course Title & Meta -->
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ course.title }}</h1>

                        <p class="text-xl text-gray-600 mb-6">{{ course.short_description }}</p>

                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <div class="flex items-center">
                                <img :src="course.instructor.avatar_url" :alt="course.instructor.name"
                                    class="w-10 h-10 rounded-full object-cover mr-3">
                                <div>
                                    <p class="text-sm text-gray-600">Created by</p>
                                    <p class="font-semibold text-gray-900">{{ course.instructor.name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                                Last updated {{ formatDate(course.updated_at) }}
                            </div>

                            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm capitalize">
                                {{ course.level }}
                            </span>
                        </div>

                        <!-- Course Image -->
                        <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden mb-8">
                            <img :src="course.thumbnail_url" :alt="course.title" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Right Column - Purchase Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                            <!-- Price -->
                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold"
                                    :class="course.price > 0 ? 'text-blue-600' : 'text-green-600'">
                                    {{ course.formatted_price }}
                                </div>
                                <p v-if="course.price > 0" class="text-sm text-gray-500 mt-1">One-time payment</p>
                            </div>

                            <!-- Enroll Button -->
                            <button
                                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-4">
                                {{ course.price > 0 ? 'Enroll Now' : 'Enroll for Free' }}
                            </button>

                            <!-- Course Stats -->
                            <div class="space-y-3 text-sm border-t pt-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration</span>
                                    <span class="font-medium">{{ course.duration_hours ? course.duration_hours + 'hours'
                                        : 'Self-paced' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Level</span>
                                    <span class="font-medium capitalize">{{ course.level }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Category</span>
                                    <span class="font-medium">{{ course.category?.name || 'General' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Language</span>
                                    <span class="font-medium">English</span>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="border-t pt-4 mt-4">
                                <h4 class="font-semibold text-gray-900 mb-3">This course includes:</h4>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                        </svg>
                                        Lifetime access
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                        </svg>
                                        Mobile and desktop access
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                        </svg>
                                        Certificate of completion
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                        </svg>
                                        Direct instructor access
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Content Tabs -->
                <div class="bg-white rounded-lg shadow">
                    <div class="border-b">
                        <nav class="flex space-x-8 px-6">
                            <button v-for="tab in tabs" :key="tab.id" @click="activeTab = tab.id"
                                class="py-4 text-sm font-medium border-b-2 transition-colors" :class="activeTab === tab.id
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700'">
                                {{ tab.name }}
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <!-- About Tab -->
                        <div v-if="activeTab === 'about'" class="prose max-w-none">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">About this course</h3>
                            <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                                {{ course.description }}
                            </div>
                        </div>

                        <!-- What You'll Learn Tab -->
                        <div v-if="activeTab === 'learn'" class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">What you'll learn</h3>
                            <div v-if="course.what_you_learn && course.what_you_learn.length > 0"
                                class="grid md:grid-cols-2 gap-4">
                                <div v-for="(outcome, index) in course.what_you_learn" :key="index"
                                    class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    <span class="text-gray-700">{{ outcome }}</span>
                                </div>
                            </div>
                            <p v-else class="text-gray-500 italic">Learning outcomes will be updated soon.</p>
                        </div>

                        <!-- Requirements Tab -->
                        <div v-if="activeTab === 'requirements'" class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Requirements</h3>
                            <div v-if="course.requirements && course.requirements.length > 0">
                                <ul class="space-y-3">
                                    <li v-for="(requirement, index) in course.requirements" :key="index"
                                        class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                        </svg>
                                        <span class="text-gray-700">{{ requirement }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div v-else class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    <span class="text-green-700 font-medium">No prior experience required!</span>
                                </div>
                                <p class="text-green-600 text-sm mt-1">This course is designed for beginners and covers
                                    everything from the basics.</p>
                            </div>
                        </div>

                        <!-- Instructor Tab -->
                        <div v-if="activeTab === 'instructor'" class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Meet your instructor</h3>

                            <div class="flex items-start space-x-4">
                                <img :src="course.instructor.avatar_url" :alt="course.instructor.name"
                                    class="w-20 h-20 rounded-full object-cover">

                                <div class="flex-1">
                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ course.instructor.name }}
                                    </h4>
                                    <p v-if="course.instructor.bio" class="text-gray-600 mb-4">{{ course.instructor.bio
                                    }}</p>
                                    <p v-else class="text-gray-500 italic mb-4">Experienced instructor passionate about
                                        teaching.</p>

                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                            </svg>
                                            1 Course <!-- This will be dynamic later -->
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                            </svg>
                                            0 Students <!-- This will be dynamic later -->
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Courses -->
                <div v-if="relatedCourses.length > 0" class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Courses</h2>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="relatedCourse in relatedCourses" :key="relatedCourse.id"
                            class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">

                            <div class="aspect-video bg-gray-200 rounded-t-lg overflow-hidden">
                                <Link :href="route('courses.show', relatedCourse.slug)">
                                <img :src="relatedCourse.thumbnail_url" :alt="relatedCourse.title"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </Link>
                            </div>

                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <Link :href="route('courses.show', relatedCourse.slug)" class="hover:text-blue-600">
                                    {{ relatedCourse.title }}
                                    </Link>
                                </h3>

                                <p class="text-sm text-gray-600 mb-2">by {{ relatedCourse.instructor.name }}</p>

                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full capitalize">
                                        {{ relatedCourse.level }}
                                    </span>
                                    <span class="font-semibold"
                                        :class="relatedCourse.price > 0 ? 'text-blue-600' : 'text-green-600'">
                                        {{ relatedCourse.formatted_price }}
                                    </span>
                                </div>
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
import { ref } from 'vue';

defineProps(['course', 'relatedCourses']);

const activeTab = ref('about');

const tabs = [
    { id: 'about', name: 'About' },
    { id: 'learn', name: 'What you\'ll learn' },
    { id: 'requirements', name: 'Requirements' },
    { id: 'instructor', name: 'Instructor' },
];

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>