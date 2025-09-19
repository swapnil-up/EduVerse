<template>
    <AuthenticatedLayout>
        <Head title="Create Course" />
        
        <div class="py-12">
            <div class="max-w-4xl mx-auto px-4">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center space-x-4 mb-4">
                        <Link :href="route('instructor.courses.index')" 
                              class="text-gray-600 hover:text-gray-900">
                            ← Back to Courses
                        </Link>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Course</h1>
                    <p class="text-gray-600 mt-2">Fill in the details to create your course</p>
                </div>

                <!-- Course Form -->
                <div class="bg-white rounded-lg shadow">
                    <form @submit.prevent="submitForm" enctype="multipart/form-data">
                        <div class="p-6 space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                                
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Course Title *
                                        </label>
                                        <input 
                                            v-model="form.title"
                                            type="text"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.title }"
                                            placeholder="Enter course title..."
                                        >
                                        <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Short Description *
                                        </label>
                                        <textarea 
                                            v-model="form.short_description"
                                            rows="3"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.short_description }"
                                            placeholder="Brief description of your course (max 500 characters)..."
                                        ></textarea>
                                        <p v-if="form.errors.short_description" class="text-red-500 text-sm mt-1">{{ form.errors.short_description }}</p>
                                        <p class="text-gray-500 text-sm mt-1">{{ form.short_description?.length || 0 }}/500 characters</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Category *
                                        </label>
                                        <select 
                                            v-model="form.category_id"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.category_id }"
                                        >
                                            <option value="">Select a category</option>
                                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                                {{ category.icon }} {{ category.name }}
                                            </option>
                                        </select>
                                        <p v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Difficulty Level *
                                        </label>
                                        <select 
                                            v-model="form.level"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.level }"
                                        >
                                            <option value="beginner">Beginner</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="advanced">Advanced</option>
                                        </select>
                                        <p v-if="form.errors.level" class="text-red-500 text-sm mt-1">{{ form.errors.level }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Price ($) *
                                        </label>
                                        <input 
                                            v-model="form.price"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.price }"
                                            placeholder="0.00"
                                        >
                                        <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
                                        <p class="text-gray-500 text-sm mt-1">Set to 0 for free course</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Estimated Duration (hours)
                                        </label>
                                        <input 
                                            v-model="form.duration_hours"
                                            type="number"
                                            min="1"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            :class="{ 'border-red-500': form.errors.duration_hours }"
                                            placeholder="10"
                                        >
                                        <p v-if="form.errors.duration_hours" class="text-red-500 text-sm mt-1">{{ form.errors.duration_hours }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Image -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Image</h3>
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                    <input 
                                        ref="thumbnailInput"
                                        type="file"
                                        accept="image/*"
                                        class="hidden"
                                        @change="handleThumbnailChange"
                                    >
                                    
                                    <div v-if="thumbnailPreview">
                                        <img :src="thumbnailPreview" alt="Course thumbnail" class="w-32 h-32 object-cover rounded-lg mx-auto mb-4">
                                        <button 
                                            type="button"
                                            @click="removeThumbnail"
                                            class="text-red-600 hover:text-red-800 text-sm"
                                        >
                                            Remove Image
                                        </button>
                                    </div>
                                    
                                    <div v-else>
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-gray-600 mb-2">Upload course thumbnail</p>
                                        <button 
                                            type="button"
                                            @click="$refs.thumbnailInput.click()"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                                        >
                                            Choose Image
                                        </button>
                                    </div>
                                </div>
                                <p v-if="form.errors.thumbnail" class="text-red-500 text-sm mt-1">{{ form.errors.thumbnail }}</p>
                            </div>

                            <!-- Detailed Description -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detailed Description</h3>
                                
                                <textarea 
                                    v-model="form.description"
                                    rows="8"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    :class="{ 'border-red-500': form.errors.description }"
                                    placeholder="Provide a comprehensive description of your course content, what students will learn, and any other relevant information..."
                                ></textarea>
                                <p v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</p>
                            </div>

                            <!-- Learning Outcomes -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">What Students Will Learn</h3>
                                
                                <div class="space-y-3">
                                    <div v-for="(outcome, index) in form.what_you_learn" :key="index" class="flex items-center space-x-3">
                                        <input 
                                            v-model="form.what_you_learn[index]"
                                            type="text"
                                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Students will be able to..."
                                        >
                                        <button 
                                            type="button"
                                            @click="removeOutcome(index)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    
                                    <button 
                                        type="button"
                                        @click="addOutcome"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        + Add Learning Outcome
                                    </button>
                                </div>
                            </div>

                            <!-- Requirements -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Requirements</h3>
                                
                                <div class="space-y-3">
                                    <div v-for="(requirement, index) in form.requirements" :key="index" class="flex items-center space-x-3">
                                        <input 
                                            v-model="form.requirements[index]"
                                            type="text"
                                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Basic knowledge of..."
                                        >
                                        <button 
                                            type="button"
                                            @click="removeRequirement(index)"
                                            class="text-red-600 hover:text-red-800"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                    
                                    <button 
                                        type="button"
                                        @click="addRequirement"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                    >
                                        + Add Requirement
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-between">
                            <Link :href="route('instructor.courses.index')" 
                                  class="px-4 py-2 text-gray-600 hover:text-gray-900">
                                Cancel
                            </Link>
                            
                            <div class="space-x-3">
                                <button 
                                    type="submit"
                                    :disabled="processing"
                                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50 transition-colors"
                                >
                                    <span v-if="processing">Creating...</span>
                                    <span v-else>Create Course</span>
                                </button>
                            </div>
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
import { ref } from 'vue';

const props = defineProps(['categories']);

// Form handling
const form = useForm({
    title: '',
    short_description: '',
    description: '',
    category_id: '',
    price: 0,
    level: 'beginner',
    duration_hours: '',
    requirements: [''],
    what_you_learn: [''],
    thumbnail: null,
});

const thumbnailPreview = ref(null);
const processing = ref(false);

// File handling
const handleThumbnailChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.thumbnail = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            thumbnailPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeThumbnail = () => {
    form.thumbnail = null;
    thumbnailPreview.value = null;
};

// Dynamic fields
const addRequirement = () => {
    form.requirements.push('');
};

const removeRequirement = (index) => {
    form.requirements.splice(index, 1);
};

const addOutcome = () => {
    form.what_you_learn.push('');
};

const removeOutcome = (index) => {
    form.what_you_learn.splice(index, 1);
};

// Form submission
const submitForm = () => {
    // Clean up empty requirements and outcomes
    form.requirements = form.requirements.filter(req => req.trim() !== '');
    form.what_you_learn = form.what_you_learn.filter(outcome => outcome.trim() !== '');
    
    processing.value = true;
    form.post(route('instructor.courses.store'), {
        onFinish: () => processing.value = false,
    });
};
</script>