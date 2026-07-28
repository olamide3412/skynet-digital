<script setup>
import { computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

defineOptions({ layout: AdminLayout });

const toast = useToast();

// Password Form
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    passwordForm.put(route('profile.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.reset();
            toast.success('Password updated successfully.');
        },
        onError: () => {
            if (passwordForm.errors.password) {
                passwordForm.reset('password', 'password_confirmation');
            }
            if (passwordForm.errors.current_password) {
                passwordForm.reset('current_password');
            }
        },
    });
};

// Compute user initials for avatar
const userInitials = computed(() => {
    if (!props.user?.name) return 'AD';
    const parts = props.user.name.split(' ');
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase();
    }
    return props.user.name.substring(0, 2).toUpperCase();
});

// Formatted Join Date
const formattedJoinedDate = computed(() => {
    if (!props.user?.created_at) return 'Recently';
    return new Date(props.user.created_at).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    });
});
</script>

<template>
    <Head title="Admin Profile Settings" />

    <div class="space-y-8">
        
        <!-- Header Banner & Avatar Overview -->
        <div class="relative rounded-3xl bg-gradient-to-r from-primary via-indigo-600 to-purple-700 text-white p-6 sm:p-10 shadow-xl overflow-hidden">
            <!-- Background Ambient Glow -->
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                <!-- Avatar Emblem -->
                <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl bg-white/20 backdrop-blur-md border-2 border-white/30 text-white text-3xl sm:text-4xl font-extrabold flex items-center justify-center shadow-2xl flex-shrink-0">
                    {{ userInitials }}
                </div>

                <!-- User Details -->
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 backdrop-blur-xs text-white">
                            🛡️ {{ user.role || 'Administrator' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/80 text-white shadow-xs">
                            ● {{ user.status || 'Active' }}
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                        {{ user.name }}
                    </h1>

                    <p class="text-xs sm:text-sm text-gray-200 font-medium flex items-center justify-center sm:justify-start gap-2">
                        <span>✉️ {{ user.email }}</span>
                        <span>•</span>
                        <span>Joined {{ formattedJoinedDate }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main 2-Column Grid (Left: Profile Summary, Right: Password Settings) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Account Details & Status (1 Col) -->
            <div class="space-y-6">
                
                <!-- Account Overview Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 shadow-xs space-y-5">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider pb-3 border-b border-gray-100 dark:border-gray-700">
                        Account Information
                    </h3>

                    <div class="space-y-4 text-xs">
                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Full Name</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white mt-0.5 block">{{ user.name }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Email Address</span>
                            <span class="text-sm font-bold text-primary dark:text-secondary-200 mt-0.5 block">{{ user.email }}</span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">System Access Level</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md text-xs font-extrabold uppercase tracking-wider bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                                {{ user.role || 'Administrator' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Account Status</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-md text-xs font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                Enabled & Active
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 font-semibold block uppercase tracking-wider text-[10px]">Account Created</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-0.5 block">{{ formattedJoinedDate }}</span>
                        </div>
                    </div>
                </div>

                <!-- Security & Privileges Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider pb-2 border-b border-gray-100 dark:border-gray-700">
                        Security & Privileges
                    </h3>

                    <div class="space-y-3 text-xs text-gray-600 dark:text-gray-300">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Password Encryption</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Bcrypt / Hashing</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-medium">Session Protection</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Active CSRF / Auth</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-medium">Admin Rights</span>
                            <span class="font-bold text-primary dark:text-secondary-200">Full System Access</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Settings & Password Update (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Card 1: Read-Only Personal Details Display -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">
                                Account Identity
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Official administrative identity details.
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            🔒 Read Only
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200/60 dark:border-gray-700 space-y-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Full Name</span>
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white block">{{ user.name }}</span>
                        </div>

                        <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-700/40 border border-gray-200/60 dark:border-gray-700 space-y-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Email Address</span>
                            <span class="text-sm font-extrabold text-primary dark:text-secondary-200 block">{{ user.email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Change Security Password -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="pb-3 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-extrabold text-gray-900 dark:text-white">
                            Update Password
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Ensure your account uses a strong, random password to maintain administrative security.
                        </p>
                    </div>

                    <form @submit.prevent="updatePassword" class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Current Password</label>
                            <input 
                                v-model="passwordForm.current_password" 
                                type="password" 
                                id="current_password" 
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                autocomplete="current-password"
                                required 
                            />
                            <span v-if="passwordForm.errors.current_password" class="text-red-500 text-xs font-semibold mt-1 block">{{ passwordForm.errors.current_password }}</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">New Password</label>
                                <input 
                                    v-model="passwordForm.password" 
                                    type="password" 
                                    id="password" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    autocomplete="new-password"
                                    required 
                                />
                                <span v-if="passwordForm.errors.password" class="text-red-500 text-xs font-semibold mt-1 block">{{ passwordForm.errors.password }}</span>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Confirm New Password</label>
                                <input 
                                    v-model="passwordForm.password_confirmation" 
                                    type="password" 
                                    id="password_confirmation" 
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    autocomplete="new-password"
                                    required 
                                />
                                <span v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-xs font-semibold mt-1 block">{{ passwordForm.errors.password_confirmation }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                :disabled="passwordForm.processing" 
                                class="px-6 py-3 bg-primary hover:bg-primary-dark text-white font-bold text-xs rounded-xl shadow transition disabled:opacity-50"
                            >
                                Update Security Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
</template>
