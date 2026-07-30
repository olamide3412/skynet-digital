<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import Logo from '../../images/logo.png';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import FlashMessages from '@/Components/FlashMessages.vue';

const page = usePage();
const isSidebarOpen = ref(false);
const isCollapsed = ref(false);

onMounted(() => {
    if (typeof window !== 'undefined' && localStorage.getItem('admin_sidebar_collapsed') === 'true') {
        isCollapsed.value = true;
    }
});

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};

const toggleCollapse = () => {
    isCollapsed.value = !isCollapsed.value;
    if (typeof window !== 'undefined') {
        localStorage.setItem('admin_sidebar_collapsed', isCollapsed.value);
    }
};

const navItems = computed(() => {
    const items = [
        {
            name: 'Dashboard',
            href: route('dashboard'),
            active: page.component === 'Auth/Dashboard',
            iconPath: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
        },
        {
            name: 'Our Work',
            href: route('admin.projects.index'),
            active: page.component.startsWith('Admin/Projects'),
            iconPath: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
        },
        {
            name: 'Contact Messages',
            href: route('admin.contacts.index'),
            active: page.component.startsWith('Admin/Contacts'),
            iconPath: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
        },
        {
            name: 'Testimonials',
            href: route('admin.testimonials.index'),
            active: page.component.startsWith('Admin/Testimonials'),
            iconPath: 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'
        }
    ];

    if (page.props.auth.user?.role === 'super administrator') {
        items.push({
            name: 'User Management',
            href: route('admin.users.index'),
            active: page.component.startsWith('Admin/Users'),
            iconPath: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
        });
        items.push({
            name: 'Team Members',
            href: route('admin.team.index'),
            active: page.component.startsWith('Admin/Team'),
            iconPath: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
        });
    }

    items.push(
        {
            name: 'Settings',
            href: route('admin.settings.index'),
            active: page.component.startsWith('Admin/Settings'),
            iconPath: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
        },
        {
            name: 'Logs',
            href: route('log.index'),
            active: page.component.startsWith('Auth/Logs'),
            iconPath: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        },
        {
            name: 'Profile',
            href: route('profile.edit'),
            active: page.component === 'Auth/Profile',
            iconPath: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        }
    );

    return items;
});

const currentPageTitle = computed(() => {
    const activeItem = navItems.value.find(item => item.active);
    return activeItem ? activeItem.name : 'Admin Panel';
});

const user = computed(() => page.props.auth?.user || {});
</script>

<template>
    <div class="h-screen w-screen overflow-hidden flex bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div 
            v-if="isSidebarOpen" 
            @click="closeSidebar"
            class="fixed inset-0 z-40 bg-black/50 md:hidden transition-opacity"
        ></div>

        <!-- Collapsible Sidebar Navigation -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 h-screen bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col flex-shrink-0 transform transition-all duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto"
            :class="[
                isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
                isCollapsed ? 'w-64 md:w-20' : 'w-64'
            ]"
        >
            <!-- Sidebar Header / Logo & Collapse Button -->
            <div class="h-16 flex-shrink-0 flex items-center justify-between px-4 border-b border-gray-200 dark:border-gray-700">
                <Link href="/dashboard" class="flex items-center space-x-3 overflow-hidden group">
                    <div class="p-1 rounded-xl bg-gradient-to-tr from-primary/10 via-purple-500/10 to-blue-500/10 border border-primary/20 dark:border-secondary/30 flex-shrink-0 flex items-center justify-center">
                        <img :src="Logo" alt="Skynet Logo" class="h-8 w-auto flex-shrink-0" />
                    </div>
                    <div v-if="!isCollapsed">
                        <h2 class="text-xs font-black bg-gradient-to-r from-primary via-indigo-600 to-purple-600 dark:from-secondary-200 dark:via-blue-300 dark:to-purple-300 bg-clip-text text-transparent leading-tight whitespace-nowrap">SKYNET DIGITAL</h2>
                        <span class="text-[9px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold block whitespace-nowrap">Admin Panel</span>
                    </div>
                </Link>

                <!-- Desktop Collapse Button -->
                <button 
                    @click="toggleCollapse"
                    class="hidden md:flex items-center justify-center p-1.5 rounded-lg text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    :title="isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                >
                    <svg class="w-5 h-5 transition-transform duration-300" :class="isCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Mobile Close Button -->
                <button @click="closeSidebar" class="md:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Sidebar Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name"
                    :href="item.href"
                    @click="closeSidebar"
                    :title="isCollapsed ? item.name : ''"
                    class="flex items-center py-2.5 text-sm font-medium rounded-lg transition-colors group"
                    :class="[
                        item.active 
                            ? 'bg-primary text-white shadow-sm dark:bg-secondary-200 dark:text-gray-900 font-semibold' 
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/60 hover:text-gray-900 dark:hover:text-white',
                        isCollapsed ? 'justify-center px-0' : 'px-3'
                    ]"
                >
                    <svg 
                        class="w-5 h-5 flex-shrink-0 transition-colors"
                        :class="[
                            item.active ? 'text-white dark:text-gray-900' : 'text-gray-400 dark:text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200',
                            isCollapsed ? '' : 'mr-3'
                        ]"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.iconPath" />
                    </svg>
                    <span v-if="!isCollapsed" class="whitespace-nowrap">{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Sidebar Footer Links (View Site & Logout) -->
            <div class="p-3 border-t border-gray-200 dark:border-gray-700 space-y-1 flex-shrink-0">
                <Link 
                    :href="route('home')"
                    :title="isCollapsed ? 'View Website' : ''"
                    class="flex items-center py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    :class="isCollapsed ? 'justify-center px-0' : 'px-3'"
                >
                    <svg 
                        class="w-5 h-5 text-gray-400 dark:text-gray-400 flex-shrink-0" 
                        :class="isCollapsed ? '' : 'mr-3'"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span v-if="!isCollapsed" class="whitespace-nowrap">View Website</span>
                </Link>

                <Link 
                    :href="route('logout')"
                    method="post"
                    as="button"
                    :title="isCollapsed ? 'Logout' : ''"
                    class="w-full flex items-center py-2 text-sm font-medium text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/30 transition text-left"
                    :class="isCollapsed ? 'justify-center px-0' : 'px-3'"
                >
                    <svg 
                        class="w-5 h-5 text-red-500 dark:text-red-400 flex-shrink-0" 
                        :class="isCollapsed ? '' : 'mr-3'"
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span v-if="!isCollapsed" class="whitespace-nowrap">Logout</span>
                </Link>
            </div>
        </aside>

        <!-- Main Content Area Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            
            <!-- Static Admin Top Header Bar -->
            <header class="flex-shrink-0 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 flex items-center justify-between shadow-sm z-30">
                
                <!-- Left: Sidebar Toggle & Page Title -->
                <div class="flex items-center space-x-3">
                    <button 
                        @click="toggleSidebar" 
                        class="md:hidden p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none"
                        aria-label="Toggle Mobile Sidebar"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Desktop Collapse Toggle Button in Header -->
                    <button 
                        @click="toggleCollapse"
                        class="hidden md:flex items-center justify-center p-2 rounded-lg text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition focus:outline-none"
                        :title="isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'"
                    >
                        <svg class="w-5 h-5 transition-transform duration-300" :class="isCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16" />
                        </svg>
                    </button>
                    
                    <div>
                        <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span>{{ currentPageTitle }}</span>
                        </h1>
                    </div>
                </div>

                <!-- Right: Logged-in User Badge, View Site & Theme Toggle -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    
                    <Link 
                        :href="route('home')" 
                        class="hidden sm:inline-flex items-center text-xs font-semibold px-3 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Website
                    </Link>

                    <!-- Logged-in User Pill -->
                    <div v-if="user && user.name" class="flex items-center space-x-2 pl-2 border-l border-gray-200 dark:border-gray-700">
                        <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs shadow">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                {{ user.name }}
                            </div>
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 capitalize">
                                {{ user.role || 'Administrator' }}
                            </div>
                        </div>
                    </div>

                    <ThemeToggle />
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto min-h-0">
                <slot />
            </main>

            <!-- Toast Messages -->
            <FlashMessages />
        </div>
    </div>
</template>
