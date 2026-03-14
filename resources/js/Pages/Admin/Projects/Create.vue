<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    industry: '',
    service: '',
    description: '',
    demo_url: '',
    image_path: null,
});

const submit = () => {
    form.post(route('admin.projects.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Create Project" />

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                Create Project
            </h2>
            <Link :href="route('admin.projects.index')" class="text-gray-600 hover:underline dark:text-gray-400">
                Back to Projects
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg rounded-lg p-6">
            <form @submit.prevent="submit" class="space-y-6">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input id="title" v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required />
                    <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                </div>

                <div>
                    <label for="industry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Industry</label>
                    <input id="industry" v-model="form.industry" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary focus:ring-opacity-50" />
                    <div v-if="form.errors.industry" class="text-red-500 text-sm mt-1">{{ form.errors.industry }}</div>
                </div>

                <div>
                    <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service / Category</label>
                    <input id="service" v-model="form.service" type="text" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary focus:ring-opacity-50" />
                    <div v-if="form.errors.service" class="text-red-500 text-sm mt-1">{{ form.errors.service }}</div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <textarea id="description" v-model="form.description" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary focus:ring-opacity-50"></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                </div>

                <div>
                    <label for="demo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Demo URL (Optional)</label>
                    <input id="demo_url" v-model="form.demo_url" type="url" placeholder="https://example.com" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md shadow-sm focus:border-primary focus:ring-opacity-50" />
                    <div v-if="form.errors.demo_url" class="text-red-500 text-sm mt-1">{{ form.errors.demo_url }}</div>
                </div>

                <div>
                    <label for="image_path" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Project Image (Max 2MB)</label>
                    <input id="image_path" type="file" @input="form.image_path = $event.target.files[0]" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark" />
                    <div v-if="form.errors.image_path" class="text-red-500 text-sm mt-1">{{ form.errors.image_path }}</div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark disabled:opacity-50">
                        Save Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
