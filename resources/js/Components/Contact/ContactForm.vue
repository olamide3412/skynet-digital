<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const turnstileWidgetId = ref(null);
const isTurnstileLoaded = ref(false);
const turnstileError = ref('');

const form = useForm({
    name: '',
    email: '',
    phone: '',
    service: '',
    message: '',
    cf_turnstile_response: '',
});

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
        isTurnstileLoaded.value = true;
        renderTurnstile();
    };

    script.onerror = () => {
        turnstileError.value = 'Failed to load CAPTCHA. Please refresh the page.';
    };

    document.head.appendChild(script);
};

// Render Turnstile widget
const renderTurnstile = () => {
    if (window.turnstile && document.getElementById('cf-turnstile-contact')) {
        turnstileWidgetId.value = window.turnstile.render('#cf-turnstile-contact', {
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

const submit = () => {
    if (!form.cf_turnstile_response) {
         turnstileError.value = 'Please complete the CAPTCHA verification';
         return;
    }

    form.post(route('contact.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            resetTurnstile();
        },
        onError: () => {
             toast.error('Something went wrong. Please check the form and try again.');
             if (form.errors.cf_turnstile_response) {
                 resetTurnstile();
                 form.cf_turnstile_response = '';
             }
        }
    });
};
</script>

<template>
    <div class="relative bg-gray-50 dark:bg-gray-900 min-h-screen pt-20 pb-24 px-4 sm:px-6 lg:px-8 transition-colors">
        
        <!-- Background Ambient Glow -->
        <div class="absolute inset-x-0 top-0 h-96 bg-gradient-to-b from-primary/10 via-purple-500/5 to-transparent pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto space-y-12">
            
            <!-- Page Header -->
            <div class="text-center max-w-3xl mx-auto space-y-3">
                <span class="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 text-xs font-bold uppercase tracking-widest border border-primary/20 dark:border-secondary/30">
                    ⚡ Get In Touch
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                    Let’s Build Something Great Together
                </h1>
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                    Have a software project, enterprise networking inquiry, or IT support request? Our technical team is ready to assist you.
                </p>
            </div>

            <!-- 2-Column Main Layout (Left: Direct Info Cards, Right: Form) -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">
                
                <!-- Left Sidebar Cards (2 Cols) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Direct Contact Card -->
                    <div class="bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 shadow-sm space-y-6">
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white pb-3 border-b border-gray-100 dark:border-gray-700">
                            Contact Information
                        </h3>

                        <div class="space-y-5">
                            
                            <!-- Email -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Email Us</span>
                                    <a href="mailto:info@skynetdigital.com.ng" class="text-sm font-bold text-gray-900 dark:text-white hover:text-primary dark:hover:text-secondary-200 transition">
                                        info@skynetdigital.com.ng
                                    </a>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Call / WhatsApp</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white block">+234 803 207 2831</span>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Office Headquarters</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white block">Delta State, Nigeria</span>
                                </div>
                            </div>

                            <!-- Hours -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Business Hours</span>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white block">Monday – Friday: 8:00 AM – 6:00 PM</span>
                                </div>
                            </div>

                            <!-- CAC Registration -->
                            <div class="flex items-start space-x-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">CAC Registered Entity</span>
                                    <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400 block">RC No. 9168845</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- SLA Guarantee Banner -->
                    <div class="bg-gradient-to-br from-primary via-indigo-700 to-purple-800 dark:from-gray-800 dark:to-gray-900 rounded-3xl p-6 text-white shadow-lg space-y-2 border border-primary/20">
                        <span class="text-[10px] uppercase font-bold tracking-widest px-2.5 py-0.5 bg-white/20 rounded-full">Fast SLA Response</span>
                        <h4 class="text-base font-extrabold">24-Hour Technical Response Guarantee</h4>
                        <p class="text-xs text-gray-100 dark:text-gray-300 leading-relaxed">
                            Our team reviews and responds to all technical project inquiries within 24 business hours.
                        </p>
                    </div>

                </div>

                <!-- Right Form Card (3 Cols) -->
                <div class="lg:col-span-3 bg-white dark:bg-gray-800/90 border border-gray-200/80 dark:border-gray-700/80 rounded-3xl p-8 sm:p-12 shadow-md">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Full Name *</label>
                                <input v-model="form.name" type="text" name="name" id="name" autocomplete="name" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="John Doe" required />
                            </div>

                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Email Address *</label>
                                <input v-model="form.email" type="email" name="email" id="email" autocomplete="email" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="john@example.com" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Phone Number</label>
                                <input v-model="form.phone" type="text" name="phone" id="phone" autocomplete="tel" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="+234..." />
                            </div>

                            <div>
                                <label for="service" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Service Needed</label>
                                <select v-model="form.service" id="service" name="service" class="w-full px-4 py-3.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition shadow-xs cursor-pointer">
                                    <option disabled value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Select a service</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Computer & Enterprise Networking</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Product & Software Development</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Graphic Design & UI/UX Systems</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Corporate Tech Training</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">IT Infrastructure Maintenance</option>
                                    <option class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold">Other Consultation</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Project Details / Message *</label>
                            <textarea v-model="form.message" id="message" name="message" rows="5" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition" placeholder="Tell us about your technical goals, timeline, or requirements..." required></textarea>
                        </div>

                        <!-- Turnstile CAPTCHA Widget -->
                        <div class="space-y-2">
                            <div id="cf-turnstile-contact" class="flex justify-center"></div>
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
                                <span>Send Message / Consultation Request</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</template>
