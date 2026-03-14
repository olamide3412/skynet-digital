<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const form = useForm({});

const deleteProject = (id) => {
    if (confirm('Are you sure you want to delete this project?')) {
        form.delete(route('admin.projects.destroy', id));
    }
};
</script>

<template>
    <Head title="Manage Projects" />

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                Manage Projects
            </h2>
            <Link :href="route('admin.projects.create')" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                Add Project
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg rounded-lg p-6">
            <div v-if="projects.length === 0" class="text-gray-500 dark:text-gray-400 text-center py-6">
                No projects found. Add one to get started.
            </div>
            
            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="project in projects" :key="project.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img v-if="project.image_path" :src="'/storage/' + project.image_path" class="h-12 w-20 object-cover rounded" alt="Project Image" />
                                <span v-else class="text-gray-400 text-sm">No image</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ project.title }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ project.industry }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ project.service || 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="route('admin.projects.edit', project.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</Link>
                                <button @click="deleteProject(project.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
