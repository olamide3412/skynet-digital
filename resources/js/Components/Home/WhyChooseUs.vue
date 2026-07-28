<script setup>
const pillars = [
    {
        title: 'Certified & Experienced Engineers',
        description: 'Our engineering team consists of certified architects, full-stack developers, and security specialists with years of hands-on industry experience.',
        iconPath: 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'
    },
    {
        title: 'Business-Aligned Architecture',
        description: 'We don’t just write code; we design tech systems that directly align with your business growth, operational efficiency, and revenue goals.',
        iconPath: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'
    },
    {
        title: 'Transparent & Predictable Pricing',
        description: 'No hidden fees or unexpected costs. We provide clear, detailed proposals and milestone-based pricing for total peace of mind.',
        iconPath: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    },
    {
        title: 'Rapid Emergency Response & Uptime',
        description: 'We understand system downtime costs money. Our proactive monitoring and dedicated support ensure fast response times when you need us most.',
        iconPath: 'M13 10V3L4 14h7v7l9-11h-7z'
    },
    {
        title: 'Long-Term Strategic Partnership',
        description: 'We act as long-term technology partners, continuously maintaining, updating, and optimizing your software as your business expands.',
        iconPath: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
    }
];
</script>

<template>
    <section class="py-20 bg-white dark:bg-gray-950 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold text-primary dark:text-secondary-200 bg-primary/10 dark:bg-secondary/20 uppercase tracking-widest">
                    Why Choose Skynet Digital
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Engineering Trust & Proven Results
                </h2>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Why leading businesses and organizations trust Skynet Digital Limited for their critical technology infrastructure.
                </p>
            </div>

            <!-- Pillars Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="item in pillars" 
                    :key="item.title"
                    class="p-8 rounded-3xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 space-y-4 group"
                >
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.iconPath" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors leading-snug">
                        {{ item.title }}
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        {{ item.description }}
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>
