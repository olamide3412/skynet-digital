<script setup>
const services = [
    {
        title: 'Computer Networking',
        description: 'We design, install, and manage secure network infrastructures for offices, schools, hospitals, and enterprises.',
        features: [
            'Network design and setup',
            'Wireless and wired network installation',
            'Server configuration',
            'Network security and firewall setup',
            'Troubleshooting and performance optimization',
        ],
    },
    {
        title: 'Product Development and ERP Software',
        description: 'We build custom digital products and deploy enterprise resource planning systems that streamline business operations.',
        features: [
            'Custom web and mobile application development',
            'ERP system implementation and customization',
            'System integration',
            'Software upgrades and maintenance',
            'Business process automation',
        ],
    },
    {
        title: 'Graphic Design and Brand Identity',
        description: 'We help brands communicate clearly and professionally through strong visual design.',
        features: [
            'Logo design',
            'Brand identity systems',
            'Marketing and social media designs',
            'UI design for apps and websites',
            'Print and digital design assets',
        ],
    },
    {
        title: 'Tech Training',
        description: 'We provide hands on training programs designed to equip individuals and teams with practical technology skills.',
        features: [
            'Computer fundamentals, Networking, Software development',
            'UI UX design, Digital tools for business productivity',
             'Formats: Physical classes, Online training, Corporate workshops',
        ],
    },
    {
        title: 'IT Maintenance and Support',
        description: 'We provide ongoing technical support to ensure your systems remain secure, fast, and reliable.',
        features: [
            'Preventive maintenance',
            'Help desk support',
            'System monitoring',
            'Hardware and software troubleshooting',
            'Security updates and backups',
        ],
    },
];
</script>

<template>
    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-12">
                <div v-for="(service, index) in services" :key="index" class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">
                    <div class="lg:col-span-1">
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl">
                            {{ service.title }}
                        </h3>
                    </div>
                    <div class="mt-8 lg:mt-0 lg:col-span-2">
                        <p class="text-lg text-gray-500 dark:text-gray-300">
                            {{ service.description }}
                        </p>
                        <ul class="mt-6 space-y-4">
                            <li v-for="feature in service.features" :key="feature" class="flex">
                                <!-- Heroicon name: solid/check -->
                                <svg class="flex-shrink-0 h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="ml-3 text-base text-gray-500 dark:text-gray-300">{{ feature }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
