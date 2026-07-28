<script setup>
import { Link } from '@inertiajs/vue3';

const services = [
    {
        title: 'Computer & Enterprise Networking',
        description: 'High-availability network architecture, structured cabling, routing, switching, and corporate Wi-Fi security.',
        gradient: 'from-blue-500 to-indigo-600',
        iconPath: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z'
    },
    {
        title: 'Product & Software Development',
        description: 'Tailored enterprise software, SaaS platforms, API microservices, and mobile application engineering.',
        gradient: 'from-purple-500 to-pink-600',
        iconPath: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'
    },
    {
        title: 'Graphic Design & UI/UX Systems',
        description: 'Brand identity systems, component libraries, and intuitive digital interfaces engineered for maximum user engagement.',
        gradient: 'from-amber-500 to-red-600',
        iconPath: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'
    },
    {
        title: 'Cybersecurity & Threat Audit',
        description: 'Enterprise vulnerability assessments, penetration testing, data encryption, and HIPAA/PCI compliance audits.',
        gradient: 'from-emerald-500 to-teal-600',
        iconPath: 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
    },
    {
        title: 'Corporate IT Training & Capacity Building',
        description: 'Hands-on practical technology training programs for engineering teams, IT staff, and executive leadership.',
        gradient: 'from-blue-600 to-cyan-600',
        iconPath: 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222'
    },
    {
        title: 'Managed IT Maintenance & Cloud Support',
        description: '24/7 proactive monitoring, server maintenance, cloud backup recovery, and dedicated technical helpdesk support.',
        gradient: 'from-indigo-600 to-purple-600',
        iconPath: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
    }
];
</script>

<template>
    <section class="py-20 bg-gray-50 dark:bg-gray-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold text-primary dark:text-secondary-200 bg-primary/10 dark:bg-secondary/20 uppercase tracking-widest">
                    Our Core Capabilities
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Comprehensive Tech Solutions
                </h2>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    End-to-end technology capabilities engineered to accelerate growth, secure digital assets, and optimize enterprise operations.
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="service in services" 
                    :key="service.title" 
                    class="group bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <!-- Icon Badge -->
                        <div 
                            class="w-14 h-14 rounded-2xl bg-gradient-to-tr text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300"
                            :class="service.gradient"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="service.iconPath" />
                            </svg>
                        </div>

                        <!-- Service Title & Description -->
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors leading-snug">
                            {{ service.title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ service.description }}
                        </p>
                    </div>

                    <!-- Learn More Link -->
                    <div class="pt-6 mt-6 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                        <Link :href="route('services')" class="text-xs font-bold text-primary dark:text-secondary-200 group-hover:underline flex items-center">
                            Explore Service Details
                            <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- View All Services CTA -->
            <div class="text-center pt-4">
                <Link 
                    :href="route('services')" 
                    class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:border-primary text-gray-800 dark:text-gray-200 font-bold text-xs rounded-xl shadow-xs hover:shadow transition"
                >
                    <span>View All Services & Capabilities</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </Link>
            </div>

        </div>
    </section>
</template>
