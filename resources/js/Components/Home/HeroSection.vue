<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const slides = [
    {
        image: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        title: 'Powering Businesses with',
        highlight: 'Smart Technology Solutions',
        description: 'We design, build, secure, and maintain modern digital systems that help organizations grow, operate faster, and stay competitive.'
    },
    {
        image: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        title: 'Cybersecurity &',
        highlight: 'Data Protection',
        description: 'Securing your digital assets with enterprise-grade threat detection, risk management, and compliance solutions.'
    },
    {
        image: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        title: 'Next-Generation',
        highlight: 'Cloud Infrastructure',
        description: 'Scalable cloud computing resources ensuring high availability, speed, and cost-efficiency for your growing business.'
    }
];

const currentSlide = ref(0);
let interval;

onMounted(() => {
    interval = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % slides.length;
    }, 5000);
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>

<template>
    <section class="relative bg-gray-900 overflow-hidden h-screen min-h-[600px] flex items-center">
        <!-- Background Slider -->
        <div 
            v-for="(slide, index) in slides" 
            :key="index"
            class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
            :class="index === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'">
            <img :src="slide.image" alt="Hero background" class="w-full h-full object-cover">
            <!-- Overlay to make text readable -->
            <div class="absolute inset-0 bg-black/60 dark:bg-black/70"></div>
        </div>

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 w-full">
            <div class="text-center transition-all duration-500 ease-in-out transform" :key="currentSlide">
                <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl drop-shadow-md">
                    <span class="block">{{ slides[currentSlide].title }}</span>
                    <span class="block text-primary">{{ slides[currentSlide].highlight }}</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-200 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl drop-shadow">
                    {{ slides[currentSlide].description }}
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <Link :href="route('contact')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary-dark transition md:py-4 md:text-lg md:px-10">
                            Get a Quote
                        </Link>
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <Link :href="route('portfolio')" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50 transition md:py-4 md:text-lg md:px-10">
                            View Our Work
                        </Link>
                    </div>
                </div>
            </div>
            
            <!-- Slide Indicators -->
            <div class="absolute bottom-10 left-0 right-0 z-30 flex justify-center space-x-3">
                <button 
                    v-for="(_, index) in slides" 
                    :key="'nav-'+index"
                    @click="currentSlide = index"
                    class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="index === currentSlide ? 'bg-primary w-8' : 'bg-white/50 hover:bg-white/80'">
                </button>
            </div>
        </div>
    </section>
</template>
