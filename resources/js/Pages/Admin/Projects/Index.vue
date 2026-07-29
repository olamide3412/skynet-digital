<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

defineOptions({ layout: AdminLayout });

const page = usePage();
const currentUser = computed(() => page.props.auth?.user || {});

const form = useForm({});
const searchQuery = ref('');
const statusFilter = ref('all'); // 'all', 'published', 'draft'
const viewMode = ref('table'); // 'table' or 'grid'

const publishedCount = computed(() => props.projects.filter(p => p.is_published).length);
const draftCount = computed(() => props.projects.filter(p => !p.is_published).length);

const filteredProjects = computed(() => {
    return props.projects.filter(project => {
        // Status filter
        if (statusFilter.value === 'published' && !project.is_published) return false;
        if (statusFilter.value === 'draft' && project.is_published) return false;

        // Search query filter
        if (!searchQuery.value.trim()) return true;
        const q = searchQuery.value.toLowerCase().trim();
        return (
            (project.title && project.title.toLowerCase().includes(q)) ||
            (project.industry && project.industry.toLowerCase().includes(q)) ||
            (project.service && project.service.toLowerCase().includes(q)) ||
            (project.category && project.category.toLowerCase().includes(q)) ||
            (project.user && project.user.name && project.user.name.toLowerCase().includes(q))
        );
    });
});

// Ownership & Role Permission Helpers
const canEditProject = (project) => {
    if (!currentUser.value) return false;
    if (currentUser.value.role === 'super administrator') return true;
    if (!project.user_id) return true; // Legacy project without assigned author
    return project.user_id === currentUser.value.id;
};

const canDeleteProject = (project) => {
    if (!currentUser.value) return false;
    return currentUser.value.role === 'super administrator';
};

const getInitials = (name) => {
    if (!name) return 'SA';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

const formatDescriptionSnippet = (desc) => {
    if (!desc) return '';
    let text = desc;
    
    // Strip markdown headings and bullet symbols
    text = text.replace(/^#+\s+/gm, '');
    text = text.replace(/^\s*[-*]\s+/gm, '');
    text = text.replace(/^>\s+/gm, '');
    
    // Parse bold (**text**)
    text = text.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900 dark:text-white">$1</strong>');
    
    // Parse italic (*text*)
    text = text.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');
    
    // Parse strikethrough (~~text~~)
    text = text.replace(/~~(.*?)~~/g, '<del class="line-through opacity-75">$1</del>');
    
    // Parse markdown links [text](url) -> text
    text = text.replace(/\[(.*?)\]\((.*?)\)/g, '$1');

    return text;
};

const togglePublish = (project) => {
    if (!canEditProject(project)) return;
    form.patch(route('admin.projects.toggle-publish', project.id), {
        preserveScroll: true,
    });
};

const deleteProject = (id) => {
    if (!currentUser.value || currentUser.value.role !== 'super administrator') return;
    if (confirm('Are you sure you want to delete this project post? This action cannot be undone.')) {
        form.delete(route('admin.projects.destroy', id));
    }
};
</script>

<template>
    <Head title="Our Work Management - Admin" />

    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header & Action Row -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Our Work Management</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage, publish, edit, and categorize client projects for public showcase.</p>
            </div>
            <div>
                <Link 
                    :href="route('admin.projects.create')" 
                    class="inline-flex items-center px-5 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition transform active:scale-95"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    New Project
                </Link>
            </div>
        </div>

        <!-- Metric Summary Chips -->
        <div class="flex flex-wrap items-center gap-3">
            <button 
                @click="statusFilter = 'all'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 border shadow-xs"
                :class="statusFilter === 'all' 
                    ? 'bg-primary text-white border-primary shadow-sm' 
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60'"
            >
                <span>All Projects</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'all' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200'">{{ projects.length }}</span>
            </button>

            <button 
                @click="statusFilter = 'published'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 border shadow-xs"
                :class="statusFilter === 'published' 
                    ? 'bg-green-600 text-white border-green-600 shadow-sm' 
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60'"
            >
                <span>● Published</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'published' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300'">{{ publishedCount }}</span>
            </button>

            <button 
                @click="statusFilter = 'draft'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 border shadow-xs"
                :class="statusFilter === 'draft' 
                    ? 'bg-amber-600 text-white border-amber-600 shadow-sm' 
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60'"
            >
                <span>○ Drafts (Hidden)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px]" :class="statusFilter === 'draft' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300'">{{ draftCount }}</span>
            </button>
        </div>

        <!-- Controls Toolbar: Search & View Toggle -->
        <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Live Search Input -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by title, industry, author..."
                    class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900/90 border border-gray-300 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition"
                />
                <button 
                    v-if="searchQuery" 
                    @click="searchQuery = ''" 
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                >
                    Clear
                </button>
            </div>

            <!-- View Switcher (Table vs Grid) -->
            <div class="flex items-center space-x-1.5 self-end sm:self-auto bg-gray-100 dark:bg-gray-900 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                <button 
                    @click="viewMode = 'table'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition flex items-center space-x-1"
                    :class="viewMode === 'table' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-xs' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
                    title="Table View"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>Table</span>
                </button>

                <button 
                    @click="viewMode = 'grid'"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition flex items-center space-x-1"
                    :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-xs' : 'text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white'"
                    title="Grid Cards View"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span>Cards</span>
                </button>
            </div>

        </div>

        <!-- Mode 1: Table View -->
        <div v-if="viewMode === 'table'" class="bg-white dark:bg-gray-800/90 overflow-hidden shadow-sm rounded-2xl border border-gray-200 dark:border-gray-700/80 p-3 sm:p-6">
            
            <div v-if="filteredProjects.length === 0" class="text-center py-12 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">No matching projects found.</p>
                <p class="text-xs text-gray-400 mt-1">Try adjusting your search query or filter chips.</p>
            </div>
            
            <div v-else class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-900/60 text-[10px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-3 sm:px-4 py-3">Cover</th>
                            <th scope="col" class="px-3 sm:px-4 py-3">Title</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 hidden md:table-cell">Created By</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 hidden sm:table-cell">Service</th>
                            <th scope="col" class="px-3 sm:px-4 py-3">Status</th>
                            <th scope="col" class="px-3 sm:px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                        <tr v-for="project in filteredProjects" :key="project.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <!-- Image -->
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                                <img v-if="project.image_path" :src="'/storage/' + project.image_path" class="h-10 w-16 sm:h-12 sm:w-20 object-cover rounded-xl shadow-xs border border-gray-200 dark:border-gray-700" alt="Project Image" />
                                <div v-else class="h-10 w-16 sm:h-12 sm:w-20 rounded-xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-[10px] text-gray-400 font-medium">
                                    No Image
                                </div>
                            </td>

                            <!-- Title -->
                            <td class="px-3 sm:px-4 py-3 max-w-[180px] sm:max-w-xs">
                                <div class="font-bold text-gray-900 dark:text-white text-xs sm:text-sm line-clamp-2">{{ project.title }}</div>
                                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ project.industry || 'General' }}
                                </div>
                                <!-- Author shown on small screens only (fallback when Created By column is hidden) -->
                                <div class="flex items-center gap-1.5 mt-1.5 md:hidden">
                                    <div class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[9px] font-extrabold flex-shrink-0">
                                        {{ getInitials(project.user?.name) }}
                                    </div>
                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium truncate">{{ project.user ? project.user.name : 'System' }}</span>
                                </div>
                            </td>

                            <!-- Created By (dedicated column, hidden on small screens) -->
                            <td class="px-3 sm:px-4 py-3 hidden md:table-cell">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-extrabold text-xs text-white shadow-sm"
                                        :style="{ background: project.user ? 'linear-gradient(135deg, #6366f1, #8b5cf6)' : 'linear-gradient(135deg, #64748b, #94a3b8)' }"
                                    >
                                        {{ getInitials(project.user?.name) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-900 dark:text-white">
                                            {{ project.user ? project.user.name : 'System' }}
                                        </div>
                                        
                                    </div>
                                </div>
                            </td>

                            <!-- Service & Category -->
                            <td class="px-3 sm:px-4 py-3 hidden sm:table-cell">
                                <div class="flex flex-col space-y-1">
                                    <span class="px-2.5 py-0.5 inline-flex text-[11px] leading-4 font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200 border border-blue-200 dark:border-blue-800 w-max">
                                        {{ project.service || 'General Service' }}
                                    </span>
                                    <span v-if="project.category" class="text-[10px] text-gray-500 dark:text-gray-400 font-medium pl-1">
                                        📁 {{ project.category }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap">
                                <span v-if="project.is_published" class="px-2 py-0.5 sm:px-2.5 sm:py-1 inline-flex text-[10px] sm:text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-200 border border-green-200 dark:border-green-800">
                                    ● Published
                                </span>
                                <span v-else class="px-2 py-0.5 sm:px-2.5 sm:py-1 inline-flex text-[10px] sm:text-xs leading-4 font-semibold rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200 border border-amber-200 dark:border-amber-800">
                                    ○ Draft
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-3 sm:px-4 py-3 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <a 
                                        v-if="project.is_published"
                                        :href="route('our-work.show', project.id)"
                                        target="_blank"
                                        class="inline-flex items-center text-[10px] sm:text-xs font-semibold px-2 py-1 rounded-lg text-gray-600 border border-gray-300 hover:bg-gray-100 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition"
                                        title="Preview live page"
                                    >
                                        🌐 View
                                    </a>

                                    <template v-if="canEditProject(project)">
                                        <button 
                                            @click="togglePublish(project)" 
                                            class="text-[10px] sm:text-xs font-semibold px-2 py-1 rounded-lg transition border shadow-xs"
                                            :class="project.is_published 
                                                ? 'text-amber-700 border-amber-300 hover:bg-amber-50 dark:text-amber-300 dark:border-amber-700 dark:hover:bg-amber-950/40' 
                                                : 'text-green-700 border-green-300 hover:bg-green-50 dark:text-green-300 dark:border-green-700 dark:hover:bg-green-950/40'"
                                        >
                                            {{ project.is_published ? 'Unpublish' : 'Publish' }}
                                        </button>

                                        <Link :href="route('admin.projects.edit', project.id)" class="inline-flex items-center text-[10px] sm:text-xs font-semibold px-2 py-1 rounded-lg text-indigo-600 border border-indigo-200 hover:bg-indigo-50 dark:text-indigo-400 dark:border-indigo-800 dark:hover:bg-indigo-950/40 transition">
                                            Edit
                                        </Link>
                                    </template>

                                    <template v-if="canDeleteProject(project)">
                                        <button @click="deleteProject(project.id)" class="text-[10px] sm:text-xs font-semibold px-2 py-1 rounded-lg text-red-600 border border-red-200 hover:bg-red-50 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-950/40 transition">
                                            Delete
                                        </button>
                                    </template>
                                    
                                    <template v-if="!canEditProject(project) && !canDeleteProject(project)">
                                        <span class="text-[10px] text-gray-400 font-semibold italic px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                            🔒 {{ project.user ? project.user.name : 'Author' }}
                                        </span>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mode 2: Grid Cards View -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div v-if="filteredProjects.length === 0" class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-400">
                <p class="text-base font-semibold text-gray-600 dark:text-gray-300">No matching projects found.</p>
                <p class="text-xs text-gray-400 mt-1">Try adjusting your search query or filter chips.</p>
            </div>

            <div 
                v-for="project in filteredProjects" 
                :key="project.id"
                class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group"
            >
                <div>
                    <!-- Card Image Banner -->
                    <div class="relative h-48 w-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <img v-if="project.image_path" :src="'/storage/' + project.image_path" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" :alt="project.title" />
                        <div v-else class="w-full h-full flex items-center justify-center text-xs text-gray-400 font-medium">
                            No Cover Image
                        </div>

                        <!-- Status Badge Overlay -->
                        <div class="absolute top-3 right-3">
                            <span v-if="project.is_published" class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-600 text-white shadow-md">
                                ● Published
                            </span>
                            <span v-else class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-600 text-white shadow-md">
                                ○ Draft
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 space-y-3">
                        <div>
                            <div class="text-xs font-semibold text-primary dark:text-secondary-200 uppercase tracking-wider">
                                {{ project.service || 'Service' }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-0.5 line-clamp-1">
                                {{ project.title }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Industry: <span class="text-gray-700 dark:text-gray-300 font-medium">{{ project.industry || 'General' }}</span>
                            </p>

                            <!-- Author Pill -->
                            <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700/60">
                                <div class="w-6 h-6 rounded-full flex-shrink-0 flex items-center justify-center font-extrabold text-[10px] text-white shadow-sm"
                                    :style="{ background: project.user ? 'linear-gradient(135deg, #6366f1, #8b5cf6)' : 'linear-gradient(135deg, #64748b, #94a3b8)' }"
                                >
                                    {{ getInitials(project.user?.name) }}
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ project.user ? project.user.name : 'System' }}</span>
                                    
                                </div>
                            </div>
                        </div>

                        <p 
                            v-if="project.description" 
                            v-html="formatDescriptionSnippet(project.description)"
                            class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 leading-relaxed"
                        ></p>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900/60 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-between gap-2">
                    <template v-if="canEditProject(project)">
                        <button 
                            @click="togglePublish(project)" 
                            class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition"
                            :class="project.is_published 
                                ? 'text-amber-700 border-amber-300 bg-amber-50 hover:bg-amber-100 dark:text-amber-300 dark:border-amber-700 dark:bg-amber-950/30' 
                                : 'text-green-700 border-green-300 bg-green-50 hover:bg-green-100 dark:text-green-300 dark:border-green-700 dark:bg-green-950/30'"
                        >
                            {{ project.is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </template>

                    <div class="flex items-center space-x-2">
                        <Link v-if="canEditProject(project)" :href="route('admin.projects.edit', project.id)" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Edit
                        </Link>
                        <span v-if="canEditProject(project) && canDeleteProject(project)" class="text-gray-300 dark:text-gray-600">•</span>
                        <button v-if="canDeleteProject(project)" @click="deleteProject(project.id)" class="text-xs font-bold text-red-600 dark:text-red-400 hover:underline">
                            Delete
                        </button>
                    </div>

                    <template v-if="!canEditProject(project) && !canDeleteProject(project)">
                        <span class="w-full text-center text-xs text-gray-400 font-semibold italic">
                            🔒 Created by {{ project.user ? project.user.name : 'Author' }}
                        </span>
                    </template>
                </div>

            </div>

        </div>

    </div>
</template>
