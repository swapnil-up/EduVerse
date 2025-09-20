<template>
    <AuthenticatedLayout>
        <Head title="Browse Courses" />
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Discover Amazing Courses</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Expand your skills with our comprehensive collection of courses taught by expert instructors
                    </p>
                </div>

                <!-- Search and Filters -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <div class="grid md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <input 
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Search courses..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select 
                                v-model="searchForm.category"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.icon }} {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Level Filter -->
                        <div>
                            <select 
                                v-model="searchForm.level"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Levels</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div>
                            <select 
                                v-model="searchForm.price"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">All Prices</option>
                                <option value="free">Free</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div>
                            <button 
                                @click="applyFilters"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Search
                            </button>
                        </div>
                    </div>

                    <!-- Active Filters & Sort -->
                    <div class="flex flex-wrap items-center justify-between mt-4 pt-4 border-t">
                        <div class="flex flex-wrap gap-2">
                            <!-- Active Filter Tags -->
                            <span v-if="activeFilters.category" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm flex items-center">
                                {{ getCategoryName(activeFilters.category) }}
                                <button @click="removeFilter('category')" class="ml-2 hover:text-blue-600">✕</button>
                            </span>
                            <span v-if="activeFilters.level" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                                {{ activeFilters.level }}
                                <button @click="removeFilter('level')" class="ml-2 hover:text-green-600">✕</button>
                            </span>
                            <span v-if="activeFilters.price" class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm flex items-center">
                                {{ activeFilters.price === 'free' ? 'Free' : 'Paid' }}
                                <button @click="removeFilter('price')" class="ml-2 hover:text-purple-600">✕</button>
                            </span>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="mt-2 md:mt-0">
                            <select 
                                v-model="searchForm.sort"
                                @change="applyFilters"
                                class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="latest">Latest</option>
                                <option value="popular">Popular</option>
                                <option value="title">Title A-Z</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">
                        Showing {{ courses.from || 0 }} to {{ courses.to || 0 }} of {{ courses.total || 0 }} courses
                    </p>
                    <button 
                        @click="clearAllFilters"
                        class="text-blue-600 hover:text-blue-800 text-sm"
                    >
                        Clear All Filters
                    </button>
                </div>

                <!-- Courses Grid -->
                <div v-if="courses.data.length > 0">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        <div v-for="course in courses.data" :key="course.id" 
                             class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                            
                            <!-- Course Image -->
                            <div class="aspect-video bg-gray-200 rounded-t-lg overflow-hidden">
                                <Link :href="route('courses.show', course.slug)">
                                    <img :src="course.thumbnail_url" 
                                         :alt="course.title"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </Link>
                            </div>

                            <!-- Course Content -->
                            <div class="p-4">
                                <!-- Category & Level -->
                                <div class="flex justify-between items-center mb-2">
                                    <span v-if="course.category" 
                                          class="px-2 py-1 text-xs rounded-full"
                                          :style="{ backgroundColor: course.category.color + '20', color: course.category.color }">
                                        {{ course.category.icon }} {{ course.category.name }}
                                    </span>
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full capitalize">
                                        {{ course.level }}
                                    </span>
                                </div>

                                <!-- Course Title -->
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <Link :href="route('courses.show', course.slug)" class="hover:text-blue-600">
                                        {{ course.title }}
                                    </Link>
                                </h3>

                                <!-- Instructor -->
                                <p class="text-sm text-gray-600 mb-2">
                                    by {{ course.instructor.name }}
                                </p>

                                <!-- Short Description -->
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
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
                                    <span class="font-semibold text-lg" :class="course.price > 0 ? 'text-blue-600' : 'text-green-600'">
                                        {{ course.formatted_price }}
                                    </span>
                                </div>

                                <!-- Enroll Button -->
                                <Link :href="route('courses.show', course.slug)"
                                      class="w-full bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition-colors block">
                                    View Course
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="courses.links && courses.links.length > 3" class="flex justify-center">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No courses found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search criteria</p>
                    <button 
                        @click="clearAllFilters"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors"
                    >
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, computed } from 'vue';

const props = defineProps(['courses', 'categories', 'filters']);

const searchForm = reactive({
    search: props.filters?.search || '',
    category: props.filters?.category || '',
    level: props.filters?.level || '',
    price: props.filters?.price || '',
    sort: props.filters?.sort || 'latest',
});

const activeFilters = computed(() => {
    return {
        category: props.filters?.category || '',
        level: props.filters?.level || '',
        price: props.filters?.price || '',
    };
});

const applyFilters = () => {
    const query = {};
    
    if (searchForm.search) query.search = searchForm.search;
    if (searchForm.category) query.category = searchForm.category;
    if (searchForm.level) query.level = searchForm.level;
    if (searchForm.price) query.price = searchForm.price;
    if (searchForm.sort !== 'latest') query.sort = searchForm.sort;

    router.get(route('courses.index'), query, {
        preserveState: true,
        replace: true,
    });
};

const removeFilter = (filterType) => {
    searchForm[filterType] = '';
    applyFilters();
};

const clearAllFilters = () => {
    searchForm.search = '';
    searchForm.category = '';
    searchForm.level = '';
    searchForm.price = '';
    searchForm.sort = 'latest';
    
    router.get(route('courses.index'), {}, {
        preserveState: true,
        replace: true,
    });
};

const getCategoryName = (categoryId) => {
    const category = props.categories.find(c => c.id == categoryId);
    return category ? `${category.icon} ${category.name}` : '';
};
</script>