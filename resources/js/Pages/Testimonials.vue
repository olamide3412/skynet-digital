<script setup>
import { ref, onMounted } from 'vue';
import { useForm, usePage, Head } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Layout from '@/Layouts/Layout.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    testimonials: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({ avgRating: 5.0, totalApproved: 0 }),
    },
});

defineOptions({ layout: Layout });

const toast = useToast();

const turnstileWidgetId = ref(null);
const turnstileError = ref('');
const submittedSuccess = ref(false);
const hoverRating = ref(0);

const form = useForm({
    name: '',
    email: '',
    company: '',
    rating: 5,
    message: '',
    avatar: null,
    cf_turnstile_response: '',
});

const ratingLabels = {
    1: '★ 1/5 - Poor',
    2: '★ 2/5 - Fair',
    3: '★ 3/5 - Good',
    4: '★ 4/5 - Very Good',
    5: '★ 5/5 - Exceptional!',
};

// Load Cloudflare Turnstile script
const loadTurnstile = () => {
    if (window.turnstile) {
        renderTurnstile();
        return;
    }

    const script = document.createElement('script');
    script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
    script.async = true;
    script.defer = true;

    script.onload = () => {
        renderTurnstile();
    };

    script.onerror = () => {
        turnstileError.value = 'Failed to load CAPTCHA verification. Please refresh the page.';
    };

    document.head.appendChild(script);
};

const renderTurnstile = () => {
    const el = document.getElementById('cf-turnstile-testimonial');
    if (window.turnstile && el) {
        turnstileWidgetId.value = window.turnstile.render('#cf-turnstile-testimonial', {
            sitekey: usePage().props.turnstileSiteKey,
            callback: (token) => {
                form.cf_turnstile_response = token;
                turnstileError.value = '';
            },
            'expired-callback': () => {
                form.cf_turnstile_response = '';
                turnstileError.value = 'CAPTCHA expired. Please verify again.';
                resetTurnstile();
            },
            'error-callback': () => {
                form.cf_turnstile_response = '';
                turnstileError.value = 'CAPTCHA error. Please try again.';
                resetTurnstile();
            }
        });
    }
};

const resetTurnstile = () => {
    if (window.turnstile && turnstileWidgetId.value) {
        window.turnstile.reset(turnstileWidgetId.value);
    }
};

onMounted(() => {
    loadTurnstile();
});

const handleAvatarUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.avatar = file;
    }
};

const submitTestimonial = () => {
    if (!form.cf_turnstile_response) {
        turnstileError.value = 'Please complete the CAPTCHA verification.';
        return;
    }

    form.post(route('testimonials.store'), {
        preserveScroll: true,
        onSuccess: () => {
            submittedSuccess.value = true;
            form.reset();
            form.rating = 5;
            resetTurnstile();
            toast.success('Thank you! Your testimonial is submitted for admin review.');
        },
        onError: () => {
            toast.error('Please check the form and complete CAPTCHA verification.');
            if (form.errors.cf_turnstile_response) {
                resetTurnstile();
                form.cf_turnstile_response = '';
            }
        },
    });
};

const getInitials = (name) => {
    if (!name) return 'CL';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <Head>
        <title>Client Testimonials & Reviews - Skynet Digital Limited</title>
        <meta name="description" content="Read verified client reviews and testimonials for Skynet Digital Limited. Submit your feedback and rate our technical services." />
    </Head>

    <div class="relative bg-gray-50 dark:bg-gray-900 min-h-screen pt-20 pb-24 px-4 sm:px-6 lg:px-8 transition-colors">
        
        <!-- Ambient Header Glow -->
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-16">
            
            <!-- Page Hero Header -->
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-widest border border-primary/20 dark:border-secondary/30">
                    <span>💬 Client Feedback</span>
                    <span>•</span>
                    <span class="text-amber-500 font-extrabold">★ {{ stats.avgRating }} / 5.0</span>
                </span>

                <h1 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">
                    What Our Clients Say About Us
                </h1>

                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Real testimonials and verified ratings from organizations, businesses, and leaders who trust Skynet Digital Limited.
                </p>
            </div>

            <!-- Approved Testimonials Grid -->
            <div class="space-y-8">
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        Verified Client Reviews ({{ stats.totalApproved }})
                    </h2>
                    <a href="#submit-testimonial-form" class="text-xs font-bold text-primary dark:text-secondary-200 hover:underline flex items-center gap-1">
                        <span>✍️ Leave Your Feedback</span>
                        <span>↓</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <div 
                        v-for="item in testimonials" 
                        :key="item.id"
                        class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between space-y-6 group"
                    >
                        <div class="space-y-4">
                            <!-- Star Rating Row -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-amber-400">
                                    <span v-for="star in 5" :key="star" class="text-lg">
                                        {{ star <= item.rating ? '★' : '☆' }}
                                    </span>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ formatDate(item.created_at) }}
                                </span>
                            </div>

                            <!-- Message Content -->
                            <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 leading-relaxed italic relative">
                                "{{ item.message }}"
                            </p>
                        </div>

                        <!-- Client Identity Footer -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/80 flex items-center space-x-3">
                            <div v-if="item.avatar_path" class="w-11 h-11 rounded-full overflow-hidden border-2 border-primary/20 flex-shrink-0">
                                <img :src="'/storage/' + item.avatar_path" :alt="item.name" class="w-full h-full object-cover" />
                            </div>
                            <div v-else class="w-11 h-11 rounded-full bg-gradient-to-tr from-primary to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                                {{ getInitials(item.name) }}
                            </div>

                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary dark:group-hover:text-secondary-200 transition-colors">
                                    {{ item.name }}
                                </h4>
                                <p class="text-[11px] font-semibold text-gray-500 dark:text-gray-400">
                                    {{ item.company || 'Verified Client' }}
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- Empty Approved State -->
                    <div v-if="testimonials.length === 0" class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 text-gray-400">
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">No client reviews published yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Be the first to share your experience below!</p>
                    </div>

                </div>
            </div>

            <!-- Visitor Submission Form Section -->
            <div id="submit-testimonial-form" class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 sm:p-12 shadow-xl space-y-8">
                
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 uppercase tracking-wider">
                        ✍️ Share Your Feedback
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                        Submit Your Testimonial & Rating
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        Your feedback helps us continually improve. Submissions are reviewed by our team before publishing.
                    </p>
                </div>

                <!-- Success Notice Alert -->
                <div v-if="submittedSuccess" class="p-6 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl text-emerald-700 dark:text-emerald-300 text-center space-y-2">
                    <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto text-xl font-bold">✓</div>
                    <h3 class="text-lg font-extrabold">Thank You for Your Feedback!</h3>
                    <p class="text-xs max-w-md mx-auto leading-relaxed">
                        Your testimonial has been submitted successfully and is pending admin approval before appearing on the website.
                    </p>
                </div>

                <form @submit.prevent="submitTestimonial" class="max-w-3xl mx-auto space-y-6">
                    
                    <!-- Overall Service Rating Dropdown Select -->
                    <div>
                        <label for="rating" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Overall Service Rating *</label>
                        <div class="relative">
                            <select 
                                v-model="form.rating" 
                                id="rating" 
                                name="rating" 
                                class="w-full px-4 py-3.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-extrabold text-amber-600 dark:text-amber-400 focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-xs cursor-pointer"
                                required
                            >
                                <option :value="5" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-amber-400 font-bold">★★★★★ 5 Stars — Exceptional!</option>
                                <option :value="4" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-amber-400 font-bold">★★★★☆ 4 Stars — Very Good</option>
                                <option :value="3" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-amber-400 font-bold">★★★☆☆ 3 Stars — Good</option>
                                <option :value="2" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-amber-400 font-bold">★★☆☆☆ 2 Stars — Fair</option>
                                <option :value="1" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-amber-400 font-bold">★☆☆☆☆ 1 Star — Poor</option>
                            </select>
                        </div>
                        <span v-if="form.errors.rating" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.rating }}</span>
                    </div>

                    <!-- Name & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Full Name *</label>
                            <input v-model="form.name" type="text" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" required placeholder="John Doe" />
                            <span v-if="form.errors.name" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.name }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Email Address *</label>
                            <input v-model="form.email" type="email" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" required placeholder="john@example.com" />
                            <span v-if="form.errors.email" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.email }}</span>
                        </div>
                    </div>

                    <!-- Company / Designation -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Company / Position Title (Optional)</label>
                        <input v-model="form.company" type="text" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="CEO, Apex Tech or Managing Director" />
                    </div>

                    <!-- Profile Avatar Photo (Optional) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Your Photo / Avatar (Optional)</label>
                        <input @change="handleAvatarUpload" type="file" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary dark:file:bg-secondary/20 dark:file:text-secondary-200 hover:file:bg-primary/20" />
                    </div>

                    <!-- Review Message -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Testimonial Review *</label>
                        <textarea v-model="form.message" rows="5" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" required placeholder="Describe your experience working with Skynet Digital Limited..."></textarea>
                        <span v-if="form.errors.message" class="text-red-500 text-xs font-bold mt-1 block">{{ form.errors.message }}</span>
                    </div>

                    <!-- Cloudflare Turnstile CAPTCHA Widget -->
                    <div class="space-y-2 pt-2">
                        <div id="cf-turnstile-testimonial" class="flex justify-center"></div>
                        <div v-if="turnstileError" class="text-red-600 text-xs font-bold text-center">{{ turnstileError }}</div>
                        <div v-if="form.errors.cf_turnstile_response" class="text-red-600 text-xs font-bold text-center">{{ form.errors.cf_turnstile_response }}</div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-4 px-6 bg-gradient-to-r from-primary to-indigo-600 hover:from-primary-dark hover:to-indigo-700 text-white font-extrabold text-sm rounded-2xl shadow-md hover:shadow-lg transition transform active:scale-95 flex items-center justify-center disabled:opacity-50"
                        >
                            <span>Submit Testimonial for Review</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

    <Footer />
</template>
