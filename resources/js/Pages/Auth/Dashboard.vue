<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
    counts: {
        type: Object,
        required: true
    },
    recentProjects: {
        type: Array,
        default: () => []
    },
    recentMessages: {
        type: Array,
        default: () => []
    }
});

defineOptions({ layout: Layout });

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
</script>

<template>
    <Head title="Dashboard - Admin Panel" />

    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Welcome Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary via-indigo-600 to-purple-700 dark:from-gray-800 dark:to-gray-900 border border-primary/20 dark:border-gray-700/80 p-6 sm:p-8 text-white shadow-xl">
            <!-- Subtle background decorative blur circles -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-purple-500/20 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="inline-block px-3 py-1 bg-white/20 dark:bg-gray-700/60 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider mb-3">
                        ⚡ Skynet Admin Workspace
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                        Welcome back, {{ user.name || 'Admin' }}! 👋
                    </h1>
                    <p class="mt-2 text-sm text-gray-100 dark:text-gray-300 max-w-2xl leading-relaxed">
                        Here is what is happening across your platform today. Monitor project showcases, review client inquiries, and manage system operations.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                    <Link 
                        :href="route('admin.projects.create')" 
                        class="px-5 py-3 bg-white text-primary dark:bg-secondary-200 dark:text-gray-900 font-bold text-sm rounded-xl shadow-md hover:bg-gray-50 dark:hover:bg-white transition transform active:scale-95 flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        New Project
                    </Link>

                    <Link 
                        :href="route('admin.contacts.index')" 
                        class="px-5 py-3 bg-white/15 hover:bg-white/25 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-white font-semibold text-sm rounded-xl backdrop-blur-md transition flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Inquiries ({{ counts.messages }})
                    </Link>
                </div>
            </div>
        </div>

        <!-- 4 Key Stat Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1: Our Work / Projects -->
            <Link 
                :href="route('admin.projects.index')"
                class="group bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-300 transform hover:-translate-y-0.5"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Our Work</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ counts.projects }}
                    </div>
                    <div class="mt-1 flex items-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                        <span class="text-green-600 dark:text-green-400 font-bold mr-1">● {{ counts.publishedProjects }}</span> Published
                        <span class="mx-1 text-gray-300 dark:text-gray-600">•</span>
                        <span class="text-amber-600 dark:text-amber-400 font-bold mr-1">○ {{ counts.draftProjects }}</span> Drafts
                    </div>
                </div>
            </Link>

            <!-- Card 2: Contact Inquiries -->
            <Link 
                :href="route('admin.contacts.index')"
                class="group bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-300 transform hover:-translate-y-0.5"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client Inquiries</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ counts.messages }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Total messages received
                    </div>
                </div>
            </Link>

            <!-- Card 3: System Users / Administrators -->
            <Link 
                :href="route('profile.edit')"
                class="group bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-300 transform hover:-translate-y-0.5"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">System Staff</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ counts.users }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        {{ counts.superAdmins }} Super Admins • {{ counts.admins }} Admins
                    </div>
                </div>
            </Link>

            <!-- Card 4: System Activity Logs -->
            <Link 
                :href="route('log.index')"
                class="group bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-300 transform hover:-translate-y-0.5"
            >
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">System Activity Logs</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ counts.logs }}
                    </div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Logged audit entries
                    </div>
                </div>
            </Link>

        </div>

        <!-- Main Section: 2 Columns (2/3 Left, 1/3 Right) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Main Column (2 cols): Recent Projects Showcase -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>Recent Projects Showcase</span>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Latest project additions to Our Work.</p>
                        </div>
                        <Link 
                            :href="route('admin.projects.index')"
                            class="text-xs font-bold text-primary dark:text-secondary-200 hover:underline flex items-center"
                        >
                            View All Projects
                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>

                    <!-- Projects Table -->
                    <div v-if="recentProjects.length > 0" class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                                    <th class="pb-3 pl-2">Project</th>
                                    <th class="pb-3">Service</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-right pr-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-sm">
                                <tr v-for="project in recentProjects" :key="project.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="py-3.5 pl-2">
                                        <div class="flex items-center space-x-3">
                                            <img v-if="project.image_path" :src="'/storage/' + project.image_path" class="w-12 h-10 object-cover rounded-lg shadow-xs flex-shrink-0" alt="Cover" />
                                            <div v-else class="w-12 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-400 font-medium flex-shrink-0">
                                                No Img
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white">{{ project.title }}</div>
                                                <div class="text-xs text-gray-400">{{ project.industry || 'General' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5">
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                            {{ project.service || 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        <span v-if="project.is_published" class="px-2 py-0.5 text-xs font-semibold text-green-700 bg-green-50 dark:bg-green-900/40 dark:text-green-300 rounded-full border border-green-200 dark:border-green-800">
                                            Published
                                        </span>
                                        <span v-else class="px-2 py-0.5 text-xs font-semibold text-amber-700 bg-amber-50 dark:bg-amber-900/40 dark:text-amber-300 rounded-full border border-amber-200 dark:border-amber-800">
                                            Draft
                                        </span>
                                    </td>
                                    <td class="py-3.5 text-right pr-2">
                                        <Link 
                                            :href="route('admin.projects.edit', project.id)" 
                                            class="text-xs font-bold text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                        >
                                            Edit
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="text-sm font-medium">No projects created yet.</p>
                        <Link :href="route('admin.projects.create')" class="mt-3 inline-block text-xs font-bold text-primary hover:underline">
                            + Add First Project
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column (1 col): Recent Client Inquiries Feed & Roles -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Recent Inquiries Card -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>Client Inquiries</span>
                        </h3>
                        <Link 
                            :href="route('admin.contacts.index')"
                            class="text-xs font-bold text-primary dark:text-secondary-200 hover:underline"
                        >
                            View All
                        </Link>
                    </div>

                    <div v-if="recentMessages.length > 0" class="space-y-4">
                        <div 
                            v-for="msg in recentMessages" 
                            :key="msg.id" 
                            class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-700/60 space-y-1.5 transition hover:border-gray-300 dark:hover:border-gray-600"
                        >
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-gray-900 dark:text-white">{{ msg.name }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ new Date(msg.created_at).toLocaleDateString() }}</span>
                            </div>
                            <div class="text-xs text-primary dark:text-secondary-200 font-medium">
                                Service: <span class="font-semibold">{{ msg.service || 'General Inquiry' }}</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2 italic">
                                "{{ msg.message }}"
                            </p>
                        </div>
                    </div>

                    <div v-else class="text-center py-6 text-gray-400 text-xs font-medium">
                        No contact inquiries received yet.
                    </div>
                </div>

                <!-- System Roles Overview -->
                <div class="bg-white dark:bg-gray-800/90 border border-gray-200 dark:border-gray-700/80 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider pb-2 border-b border-gray-100 dark:border-gray-700">
                        System Health & Roles
                    </h3>

                    <div class="flex items-center justify-between text-xs font-semibold py-1">
                        <span class="text-gray-600 dark:text-gray-300">System Status</span>
                        <span class="px-2.5 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-300 font-bold border border-green-200 dark:border-green-800">
                            ● Operational
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold py-1">
                        <span class="text-gray-600 dark:text-gray-300">Super Administrators</span>
                        <span class="px-2.5 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-300 font-bold">
                            {{ counts.superAdmins }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold py-1">
                        <span class="text-gray-600 dark:text-gray-300">Administrators</span>
                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-300 font-bold">
                            {{ counts.admins }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
