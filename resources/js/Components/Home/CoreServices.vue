<script setup>
import { Link } from '@inertiajs/vue3';

const services = [
    {
        title: 'Computer Networking',
        description: 'Secure and scalable network setup for offices and enterprises.',
        icon: 'fas fa-network-wired', // Placeholder icon class
    },
    {
        title: 'Product Development',
        description: 'Custom software and business systems built for efficiency.',
        icon: 'fas fa-laptop-code',
    },
    {
        title: 'Graphic Design',
        description: 'Visual identities that communicate trust and professionalism.',
        icon: 'fas fa-pen-nib',
    },
    {
        title: 'Tech Training',
        description: 'Practical technology skills for individuals and teams.',
        icon: 'fas fa-chalkboard-teacher',
    },
    {
        title: 'IT Maintenance',
        description: 'Reliable support to keep your systems running smoothly.',
        icon: 'fas fa-tools',
    },
];
</script>

<template>
    <section class="py-12 bg-gray-50 dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Our Core Services</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Comprehensive Tech Solutions
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-300 lg:mx-auto">
                    We offer a wide range of services to meet all your technology needs.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-x-8 md:gap-y-10">
                    <div v-for="service in services" :key="service.title" class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                                <!-- Icon placeholder using FontAwesome classes if available, or simple text -->
                                <i :class="service.icon"></i>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ service.title }}</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-300">
                            {{ service.description }}
                        </dd>
                    </div>
                </dl>
            </div>
             <div class="mt-10 text-center">
                 <Link :href="route('services')" class="text-primary font-medium hover:text-primary-dark">
                    View All Services <span aria-hidden="true">&rarr;</span>
                 </Link>
            </div>
        </div>
    </section>
</template>
