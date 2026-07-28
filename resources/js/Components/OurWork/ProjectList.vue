<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    }
});

const searchQuery = ref('');
const activeCategory = ref('All');

const categoryTabs = [
    'All',
    'Web Application',
    'Frontend',
    'Backend & API',
    'UI/UX & Branding',
    'Mobile App',
    'Cloud & DevOps'
];

const filteredProjects = computed(() => {
    return props.projects.filter(project => {
        // Category / Service Tab Filter
        if (activeCategory.value !== 'All') {
            const cat = activeCategory.value.toLowerCase();
            const pService = (project.service || '').toLowerCase();
            const pCategory = (project.category || '').toLowerCase();
            
            if (cat === 'web application' && !pService.includes('web') && !pCategory.includes('website')) return false;
            if (cat === 'frontend' && !pService.includes('front')) return false;
            if (cat === 'backend & api' && !pService.includes('back') && !pService.includes('api')) return false;
            if (cat === 'ui/ux & branding' && !pService.includes('ui') && !pService.includes('graphic')) return false;
            if (cat === 'mobile app' && !pService.includes('mobile') && !pCategory.includes('mobile')) return false;
            if (cat === 'cloud & devops' && !pService.includes('cloud') && !pService.includes('devops')) return false;
        }

        // Search Filter
        if (!searchQuery.value.trim()) return true;
        const q = searchQuery.value.toLowerCase().trim();
        return (
            (project.title && project.title.toLowerCase().includes(q)) ||
            (project.industry && project.industry.toLowerCase().includes(q)) ||
            (project.service && project.service.toLowerCase().includes(q)) ||
            (project.category && project.category.toLowerCase().includes(q)) ||
            (project.description && project.description.toLowerCase().includes(q))
        );
    });
});
</script>

<template>
    <div class="relative bg-gray-50 dark:bg-gray-900 min-h-screen pt-20 pb-24 px-4 sm:px-6 lg:px-8">
        
        <!-- Background Ambient Glow -->
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-12">
            
            <!-- Hero Header Section -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-widest border border-primary/20 dark:border-secondary/30">
                    ⚡ Proven Track Record & Technical Excellence
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                    Our Work & Case Studies
                </h1>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Explore how we architect scalable web applications, enterprise SaaS platforms, resilient cloud infrastructure, and mobile experiences across global industries.
                </p>
            </div>

            <!-- Controls: Category Filter Tabs & Live Search -->
            <div class="space-y-6">
                
                <!-- Category Filter Pills -->
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <button 
                        v-for="cat in categoryTabs" 
                        :key="cat"
                        @click="activeCategory = cat"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition duration-200 border shadow-xs"
                        :class="activeCategory === cat 
                            ? 'bg-primary text-white border-primary shadow-md dark:bg-secondary-200 dark:text-gray-900 dark:border-secondary-200' 
                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-primary hover:text-primary dark:hover:text-white'"
                    >
                        {{ cat }}
                    </button>
                </div>

                <!-- Live Search Bar -->
                <div class="max-w-md mx-auto relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search projects by title, industry, or tech stack..."
                        class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-sm"
                    />
                    <button 
                        v-if="searchQuery" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                    >
                        Clear
                    </button>
                </div>

            </div>

            <!-- Empty State -->
            <div v-if="filteredProjects.length === 0" class="text-center py-16 bg-white dark:bg-gray-800/80 rounded-3xl border border-gray-200 dark:border-gray-700/80 p-8 shadow-sm">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">No projects found</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Try selecting another category filter or clearing your search term.</p>
                <button 
                    @click="activeCategory = 'All'; searchQuery = ''" 
                    class="mt-4 px-4 py-2 bg-primary text-white text-xs font-bold rounded-xl shadow transition hover:bg-primary-dark"
                >
                    Reset Filters
                </button>
            </div>

            <!-- Project Cards Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <div 
                    v-for="project in filteredProjects" 
                    :key="project.id" 
                    class="flex flex-col bg-white dark:bg-gray-800/90 rounded-3xl overflow-hidden border border-gray-200/80 dark:border-gray-700/80 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group"
                >
                    <!-- Card Image Banner -->
                    <div class="relative h-56 w-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <img 
                            v-if="project.image_path" 
                            :src="'/storage/' + project.image_path" 
                            :alt="project.title" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500" 
                        />
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gradient-to-br from-gray-800 to-gray-900">
                            <svg class="w-12 h-12 mb-1 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-xs font-medium">Skynet Digital Project</span>
                        </div>

                        <!-- Service Pill Overlay -->
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold text-white uppercase bg-black/60 backdrop-blur-md shadow-xs border border-white/10">
                                {{ project.service || 'Web Solution' }}
                            </span>
                        </div>

                        <!-- Media Attachments Counter Badge Overlay -->
                        <div v-if="project.media && project.media.length > 0" class="absolute bottom-3 right-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold text-white bg-primary/90 backdrop-blur-md shadow-xs flex items-center gap-1">
                                <span>📷 {{ project.media.length }} Gallery Items</span>
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors leading-snug">
                                {{ project.title }}
                            </h3>

                            <p v-if="project.description" class="text-xs text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
                                {{ project.description }}
                            </p>
                        </div>

                        <!-- Card Footer Metadata & Actions -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-400 uppercase tracking-wider block">Industry</span>
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ project.industry || 'General' }}</span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <a 
                                    v-if="project.demo_url" 
                                    :href="project.demo_url" 
                                    target="_blank" 
                                    rel="noopener"
                                    class="text-xs font-semibold px-2.5 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition flex items-center"
                                    title="Visit Demo"
                                >
                                    Demo ↗
                                </a>

                                <Link 
                                    :href="route('our-work.show', project.id)" 
                                    class="inline-flex items-center text-xs font-bold px-3 py-1.5 rounded-lg bg-primary text-white dark:bg-secondary-200 dark:text-gray-900 hover:opacity-90 transition shadow-xs"
                                >
                                    View Project
                                    <svg class="ml-1.5 w-3.5 h-3.5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</template>
