<script setup>
import { computed } from 'vue';

const props = defineProps({
    teamMembers: {
        type: Array,
        default: () => [],
    },
});

// Fallback initial generator
const getInitials = (name) => {
    if (!name) return 'TM';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 3).toUpperCase();
};
</script>

<template>
    <section v-if="teamMembers && teamMembers.length > 0" class="py-20 bg-gray-50 dark:bg-gray-900 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold text-primary dark:text-secondary-200 bg-primary/10 dark:bg-secondary/20 uppercase tracking-widest">
                    Leadership & Talent
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Meet Our Leadership Team
                </h2>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    The passionate engineers, designers, and innovators behind Skynet Digital Limited.
                </p>
            </div>

            <!-- Dynamic Responsive Team Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="member in teamMembers" 
                    :key="member.id || member.name"
                    class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 text-center space-y-4 group flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <!-- Profile Photo OR Initials Avatar Badge -->
                        <div v-if="member.image_path" class="mx-auto w-24 h-24 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-lg group-hover:scale-105 transition-transform duration-300">
                            <img :src="'/storage/' + member.image_path" :alt="member.name" class="w-full h-full object-cover" />
                        </div>
                        <div 
                            v-else 
                            class="mx-auto w-24 h-24 rounded-full bg-gradient-to-tr text-white font-extrabold text-2xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300 border-4 border-white dark:border-gray-800"
                            :class="member.gradient || 'from-primary to-indigo-600'"
                        >
                            {{ member.initials || getInitials(member.name) }}
                        </div>

                        <div>
                            <h3 class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors">
                                {{ member.name }}
                            </h3>
                            <p class="text-xs font-bold text-primary dark:text-secondary-200 uppercase tracking-wider mt-1">
                                {{ member.role }}
                            </p>
                        </div>
                    </div>

                    <p v-if="member.bio" class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed pt-3 border-t border-gray-100 dark:border-gray-700/60">
                        {{ member.bio }}
                    </p>
                </div>
            </div>

        </div>
    </section>
</template>
