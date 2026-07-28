<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const slides = [
    {
        image: 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        badge: '⚡ Engineering Digital Excellence',
        title: 'Powering Enterprises with',
        highlight: 'Smart Technology Solutions',
        description: 'We design, build, secure, and scale modern digital systems that empower organizations to operate faster, innovate continuously, and dominate their markets.'
    },
    {
        image: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        badge: '🛡️ Enterprise Defense Systems',
        title: 'Cybersecurity &',
        highlight: 'Data Protection Architecture',
        description: 'Protecting your digital assets with enterprise-grade threat detection, active risk mitigation, and automated compliance solutions.'
    },
    {
        image: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
        badge: '☁️ Resilient Multi-Cloud Solutions',
        title: 'Next-Generation',
        highlight: 'Cloud & Kubernetes Infrastructure',
        description: 'Scalable cloud infrastructure delivering 99.9% uptime, lightning-fast application performance, and optimal cost-efficiency.'
    }
];

const currentSlide = ref(0);
let interval;

onMounted(() => {
    interval = setInterval(() => {
        currentSlide.value = (currentSlide.value + 1) % slides.length;
    }, 6000);
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>

<template>
    <section class="relative bg-gray-950 overflow-hidden min-h-[90vh] flex flex-col justify-between pt-24 pb-12">
        
        <!-- Background Image Carousel with Overlay Gradient -->
        <div 
            v-for="(slide, index) in slides" 
            :key="index"
            class="absolute inset-0 transition-opacity duration-1000 ease-in-out pointer-events-none"
            :class="index === currentSlide ? 'opacity-100 z-10' : 'opacity-0 z-0'"
        >
            <img :src="slide.image" alt="Hero background" class="w-full h-full object-cover scale-105 transition-transform duration-10000" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/70 to-gray-950/40"></div>
        </div>

        <!-- Main Hero Content -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-16 w-full my-auto text-center">
            
            <div class="transition-all duration-500 ease-in-out transform space-y-6 max-w-4xl mx-auto" :key="currentSlide">
                
                <!-- Floating Glassmorphic Badge -->
                <div class="inline-block">
                    <span class="px-4 py-1.5 rounded-full text-xs font-bold text-white bg-white/10 dark:bg-gray-800/80 border border-white/20 backdrop-blur-md shadow-lg tracking-wider uppercase">
                        {{ slides[currentSlide].badge }}
                    </span>
                </div>

                <!-- Headline -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight">
                    <span class="block drop-shadow-md">{{ slides[currentSlide].title }}</span>
                    <span class="block bg-gradient-to-r from-secondary-200 via-purple-300 to-indigo-200 bg-clip-text text-transparent drop-shadow-lg mt-1">
                        {{ slides[currentSlide].highlight }}
                    </span>
                </h1>

                <!-- Subtitle -->
                <p class="max-w-2xl mx-auto text-base sm:text-lg lg:text-xl text-gray-300 leading-relaxed font-normal">
                    {{ slides[currentSlide].description }}
                </p>

                <!-- Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link 
                        :href="route('contact')" 
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white font-bold text-sm sm:text-base rounded-2xl shadow-xl hover:shadow-2xl transition transform active:scale-95 flex items-center justify-center"
                    >
                        <span>Get a Quote / Consultation</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>

                    <Link 
                        :href="route('our-work')" 
                        class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold text-sm sm:text-base rounded-2xl backdrop-blur-md transition flex items-center justify-center"
                    >
                        Explore Our Work ↗
                    </Link>
                </div>

            </div>

            <!-- Slide Nav Indicators -->
            <div class="flex justify-center space-x-3 pt-12">
                <button 
                    v-for="(_, index) in slides" 
                    :key="'indicator-'+index"
                    @click="currentSlide = index"
                    class="h-2 rounded-full transition-all duration-300"
                    :class="index === currentSlide ? 'bg-secondary-200 w-10' : 'bg-white/30 hover:bg-white/60 w-3'"
                    :aria-label="'Go to slide ' + (index + 1)"
                ></button>
            </div>

        </div>

        <!-- Integrated KPI Stats Bar -->
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 sm:p-6 rounded-3xl bg-white/10 dark:bg-gray-900/60 backdrop-blur-xl border border-white/10 text-white shadow-2xl">
                
                <div class="text-center p-2">
                    <div class="text-2xl sm:text-3xl font-extrabold text-secondary-200">50+</div>
                    <div class="text-xs font-semibold text-gray-300 mt-0.5">Projects Delivered</div>
                </div>

                <div class="text-center p-2 border-l border-white/10">
                    <div class="text-2xl sm:text-3xl font-extrabold text-secondary-200">99.9%</div>
                    <div class="text-xs font-semibold text-gray-300 mt-0.5">Uptime Guarantee</div>
                </div>

                <div class="text-center p-2 border-l border-white/10">
                    <div class="text-2xl sm:text-3xl font-extrabold text-secondary-200">100%</div>
                    <div class="text-xs font-semibold text-gray-300 mt-0.5">Client Satisfaction</div>
                </div>

                <div class="text-center p-2 border-l border-white/10">
                    <div class="text-2xl sm:text-3xl font-extrabold text-secondary-200">24/7</div>
                    <div class="text-xs font-semibold text-gray-300 mt-0.5">Expert IT Support</div>
                </div>

            </div>
        </div>

    </section>
</template>
