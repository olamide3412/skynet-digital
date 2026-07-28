<script setup>
import { Link } from '@inertiajs/vue3';

const services = [
    {
        title: 'Computer & Enterprise Networking',
        description: 'We design, deploy, and manage secure, high-speed network infrastructures tailored for corporate offices, schools, hospitals, and enterprise environments.',
        iconPath: 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        gradient: 'from-blue-500 to-indigo-600',
        features: [
            'Enterprise network topology & structured cabling',
            'High-density wireless & wired network architecture',
            'On-premise server deployment & domain controller setup',
            'Next-Gen Firewall security & threat prevention',
            '24/7 network performance monitoring & latency optimization'
        ]
    },
    {
        title: 'Product Development & ERP Systems',
        description: 'We engineer custom web applications, SaaS platforms, and deploy scalable Enterprise Resource Planning (ERP) systems that automate business workflows.',
        iconPath: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
        gradient: 'from-purple-500 to-pink-600',
        features: [
            'Full-stack custom web & mobile application engineering',
            'ERP software deployment, customization & module setup',
            'Microservices architecture & RESTful API integrations',
            'Legacy system modernization & cloud migration',
            'Automated workflow & business process engineering'
        ]
    },
    {
        title: 'Graphic Design & Brand Identity Systems',
        description: 'We craft cohesive visual identity systems, corporate branding guidelines, and user interface design systems that build trust and engagement.',
        iconPath: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
        gradient: 'from-amber-500 to-red-600',
        features: [
            'Corporate logo design & brand style guides',
            'UI/UX design systems for web apps & mobile products',
            'Marketing collateral, pitch decks & corporate reports',
            'High-converting social media visual assets',
            'Print-ready vector assets & package design'
        ]
    },
    {
        title: 'Corporate Tech Training & Workshops',
        description: 'Hands-on practical technology training programs engineered to upskill individuals, corporate teams, and executive leadership.',
        iconPath: 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222',
        gradient: 'from-blue-600 to-cyan-600',
        features: [
            'Computer networking & cybersecurity fundamentals',
            'Software engineering & web development bootcamps',
            'UI/UX design & product prototyping workshops',
            'Digital tools & business productivity automation',
            'Flexible formats: In-person, live online & corporate cohort'
        ]
    },
    {
        title: 'IT Infrastructure Maintenance & Support',
        description: 'Proactive 24/7 technical support, preventative hardware maintenance, and server updates to guarantee maximum system uptime.',
        iconPath: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
        gradient: 'from-indigo-600 to-purple-600',
        features: [
            'Preventative system maintenance & hardware diagnostics',
            'Dedicated IT helpdesk & remote troubleshooting',
            'Real-time server monitoring & automated disaster backups',
            'Operating system patching & security updates',
            'SLA-backed emergency technical response'
        ]
    }
];
</script>

<template>
    <div class="py-16 bg-white dark:bg-gray-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="space-y-8">
                <div 
                    v-for="(service, index) in services" 
                    :key="index"
                    class="bg-gray-50 dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 sm:p-12 shadow-sm hover:shadow-lg transition flex flex-col lg:flex-row gap-8 lg:items-center justify-between"
                >
                    <!-- Left: Title & Gradient Icon -->
                    <div class="lg:w-2/5 space-y-4">
                        <div 
                            class="w-14 h-14 rounded-2xl bg-gradient-to-tr text-white flex items-center justify-center shadow-md"
                            :class="service.gradient"
                        >
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="service.iconPath" />
                            </svg>
                        </div>

                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                            {{ service.title }}
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ service.description }}
                        </p>

                        <div class="pt-2">
                            <Link 
                                :href="route('contact')"
                                class="inline-flex items-center text-xs font-bold px-4 py-2 rounded-xl bg-primary text-white hover:bg-primary-dark transition shadow-xs"
                            >
                                <span>Inquire About This Service</span>
                                <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </Link>
                        </div>
                    </div>

                    <!-- Right: Features List -->
                    <div class="lg:w-3/5 bg-white dark:bg-gray-900/80 border border-gray-200/60 dark:border-gray-700/60 rounded-2xl p-6 sm:p-8 space-y-3">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Key Service Capabilities</h4>
                        <ul class="space-y-3">
                            <li 
                                v-for="feature in service.features" 
                                :key="feature" 
                                class="flex items-start text-xs sm:text-sm text-gray-700 dark:text-gray-200 font-medium"
                            >
                                <div class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="leading-snug">{{ feature }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
</template>
