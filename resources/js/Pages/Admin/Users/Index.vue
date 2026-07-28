<script setup>
import { ref, watch } from 'vue';
import { useForm, Head, router, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', role: '', status: '' }),
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, active: 0, disabled: 0, admins: 0 }),
    },
    roles: {
        type: Array,
        default: () => [],
    },
    statuses: {
        type: Array,
        default: () => [],
    },
});

defineOptions({ layout: AdminLayout });

const toast = useToast();

// Search & Filtering State
const search = ref(props.filters.search || '');
const selectedRole = ref(props.filters.role || '');
const selectedStatus = ref(props.filters.status || '');

const applyFilters = () => {
    router.get(
        route('admin.users.index'),
        {
            search: search.value,
            role: selectedRole.value,
            status: selectedStatus.value,
        },
        { preserveState: true, replace: true }
    );
};

let searchTimeout;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 400);
});

// Modal State
const isUserModalOpen = ref(false);
const isEditMode = ref(false);
const editingUserId = ref(null);

const isPasswordModalOpen = ref(false);
const targetUserForPassword = ref(null);

const isDeleteModalOpen = ref(false);
const targetUserForDelete = ref(null);

// Forms
const userForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'staff',
    status: 'enable',
});

const passwordForm = useForm({
    password: '',
    password_confirmation: '',
});

// Open Create User Modal
const openCreateModal = () => {
    isEditMode.value = false;
    editingUserId.value = null;
    userForm.reset();
    userForm.role = 'staff';
    userForm.status = 'enable';
    userForm.clearErrors();
    isUserModalOpen.value = true;
};

// Open Edit User Modal
const openEditModal = (user) => {
    isEditMode.value = true;
    editingUserId.value = user.id;
    userForm.name = user.name;
    userForm.email = user.email;
    userForm.role = user.role;
    userForm.status = user.status;
    userForm.password = '';
    userForm.password_confirmation = '';
    userForm.clearErrors();
    isUserModalOpen.value = true;
};

// Save User (Create or Update)
const submitUserForm = () => {
    if (isEditMode.value) {
        userForm.put(route('admin.users.update', editingUserId.value), {
            preserveScroll: true,
            onSuccess: () => {
                isUserModalOpen.value = false;
                userForm.reset();
                toast.success('User account updated successfully.');
            },
            onError: () => {
                toast.error('Please fix errors in the form.');
            },
        });
    } else {
        userForm.post(route('admin.users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                isUserModalOpen.value = false;
                userForm.reset();
                toast.success('User account created successfully.');
            },
            onError: () => {
                toast.error('Please fix errors in the form.');
            },
        });
    }
};

// Open Reset Password Modal
const openPasswordModal = (user) => {
    targetUserForPassword.value = user;
    passwordForm.reset();
    passwordForm.clearErrors();
    isPasswordModalOpen.value = true;
};

// Submit Password Reset
const submitPasswordReset = () => {
    if (!targetUserForPassword.value) return;

    passwordForm.put(route('admin.users.reset-password', targetUserForPassword.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isPasswordModalOpen.value = false;
            passwordForm.reset();
            toast.success(`Password for ${targetUserForPassword.value.name} reset successfully.`);
        },
        onError: () => {
            toast.error('Failed to reset password. Check requirements.');
        },
    });
};

// Toggle User Status (Enable ↔ Disable)
const toggleUserStatus = (user) => {
    router.patch(route('admin.users.toggle-status', user.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(`User status updated.`);
        },
        onError: (err) => {
            toast.error(err.error || 'Failed to update status.');
        },
    });
};

// Delete User Modal Trigger
const openDeleteModal = (user) => {
    targetUserForDelete.value = user;
    isDeleteModalOpen.value = true;
};

const confirmDeleteUser = () => {
    if (!targetUserForDelete.value) return;

    router.delete(route('admin.users.destroy', targetUserForDelete.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            targetUserForDelete.value = null;
            toast.success('User account deleted.');
        },
        onError: (err) => {
            toast.error(err.error || 'Failed to delete user.');
        },
    });
};

// Helper: Initials
const getUserInitials = (name) => {
    if (!name) return 'U';
    const parts = name.split(' ');
    if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
    return name.substring(0, 2).toUpperCase();
};

// Helper: Joined Date
const formatDate = (dateStr) => {
    if (!dateStr) return 'N/A';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<template>
    <Head title="User Management - Admin Panel" />

    <div class="space-y-8">
        
        <!-- Header Banner & Action Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xs">
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary dark:bg-secondary/20 dark:text-secondary-200">
                    👥 Access Control
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mt-2 tracking-tight">
                    User Management
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Manage system administrators, staff members, status permissions, and password resets.
                </p>
            </div>

            <button 
                @click="openCreateModal"
                class="px-6 py-3.5 bg-primary hover:bg-primary-dark text-white font-bold text-xs sm:text-sm rounded-2xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 flex-shrink-0"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>Create New User</span>
            </button>
        </div>

        <!-- KPI Stats Overview Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Total Accounts</span>
                <div class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white">{{ stats.total }}</div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Registered Users</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest block">Active Users</span>
                <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ stats.active }}</div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Enabled Status</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest block">Disabled Accounts</span>
                <div class="text-2xl sm:text-3xl font-black text-red-600 dark:text-red-400">{{ stats.disabled }}</div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Restricted Access</span>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-xs space-y-1">
                <span class="text-[10px] font-bold text-primary dark:text-secondary-200 uppercase tracking-widest block">Administrators</span>
                <div class="text-2xl sm:text-3xl font-black text-primary dark:text-secondary-200">{{ stats.admins }}</div>
                <span class="text-xs text-gray-500 dark:text-gray-400">Full Privileges</span>
            </div>

        </div>

        <!-- Filter Controls Bar -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <!-- Search Bar -->
            <div class="relative w-full sm:w-80">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="Search by name or email..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent"
                />
            </div>

            <!-- Role & Status Filter Pills -->
            <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <select 
                    v-model="selectedRole" 
                    @change="applyFilters"
                    class="px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-900 dark:text-white shadow-xs cursor-pointer"
                >
                    <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">All Roles</option>
                    <option v-for="r in roles" :key="r" :value="r" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ r.toUpperCase() }}</option>
                </select>

                <select 
                    v-model="selectedStatus" 
                    @change="applyFilters"
                    class="px-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-900 dark:text-white shadow-xs cursor-pointer"
                >
                    <option value="" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">All Statuses</option>
                    <option v-for="s in statuses" :key="s" :value="s" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ s.toUpperCase() }}</option>
                </select>
            </div>

        </div>

        <!-- Users Table Card -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/40 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200 dark:border-gray-700">
                            <th class="py-4 px-6">User Identity</th>
                            <th class="py-4 px-6">Role / Privilege</th>
                            <th class="py-4 px-6">Account Status</th>
                            <th class="py-4 px-6">Joined Date</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 text-xs">
                        <tr 
                            v-for="u in users.data" 
                            :key="u.id"
                            class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition"
                        >
                            <!-- Identity -->
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-primary to-indigo-600 text-white font-extrabold text-xs flex items-center justify-center shadow-xs flex-shrink-0">
                                        {{ getUserInitials(u.name) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white text-sm">
                                            {{ u.name }}
                                            <span v-if="u.id === $page.props.auth.user?.id" class="ml-1 text-[10px] px-2 py-0.5 rounded-md bg-primary/10 text-primary font-bold">You</span>
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">{{ u.email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="py-4 px-6">
                                <span 
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider"
                                    :class="[
                                        u.role === 'administrator' || u.role === 'super administrator' 
                                            ? 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20' 
                                            : 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20'
                                    ]"
                                >
                                    🛡️ {{ u.role }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span 
                                    class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider inline-flex items-center gap-1"
                                    :class="[
                                        u.status === 'enable' 
                                            ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' 
                                            : 'bg-red-500/10 text-red-600 dark:text-red-400'
                                    ]"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="u.status === 'enable' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                    {{ u.status === 'enable' ? 'Active' : u.status }}
                                </span>
                            </td>

                            <!-- Joined Date -->
                            <td class="py-4 px-6 text-gray-500 dark:text-gray-400 font-semibold">
                                {{ formatDate(u.created_at) }}
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    
                                    <!-- Edit Details -->
                                    <button 
                                        @click="openEditModal(u)" 
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                        title="Edit User Details"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <!-- Reset Password -->
                                    <button 
                                        @click="openPasswordModal(u)" 
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                        title="Reset User Password"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                    </button>

                                    <!-- Toggle Status (Disable / Enable) -->
                                    <button 
                                        @click="toggleUserStatus(u)"
                                        :disabled="u.id === $page.props.auth.user?.id"
                                        class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition disabled:opacity-30 disabled:cursor-not-allowed"
                                        :class="[
                                            u.status === 'enable' 
                                                ? 'border-red-300 text-red-600 hover:bg-red-50 dark:border-red-800 dark:hover:bg-red-950/40' 
                                                : 'border-emerald-300 text-emerald-600 hover:bg-emerald-50 dark:border-emerald-800 dark:hover:bg-emerald-950/40'
                                        ]"
                                        :title="u.status === 'enable' ? 'Disable Account' : 'Enable Account'"
                                    >
                                        {{ u.status === 'enable' ? 'Disable' : 'Enable' }}
                                    </button>

                                    <!-- Delete -->
                                    <button 
                                        @click="openDeleteModal(u)"
                                        :disabled="u.id === $page.props.auth.user?.id"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition disabled:opacity-30 disabled:cursor-not-allowed"
                                        title="Delete User Account"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-if="users.data.length === 0">
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                No user accounts found matching your filters.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Row -->
            <div v-if="users.links && users.links.length > 3" class="p-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-bold">{{ users.from || 0 }}</span> to <span class="font-bold">{{ users.to || 0 }}</span> of <span class="font-bold">{{ users.total }}</span> users
                </div>

                <div class="flex items-center space-x-1">
                    <Component 
                        v-for="(link, i) in users.links" 
                        :key="i"
                        :is="link.url ? Link : 'span'"
                        :href="link.url"
                        v-html="link.label"
                        class="px-3 py-1 rounded-lg text-xs font-semibold transition"
                        :class="[
                            link.active ? 'bg-primary text-white dark:bg-secondary-200 dark:text-gray-900' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                            !link.url ? 'opacity-40 cursor-not-allowed' : ''
                        ]"
                    />
                </div>
            </div>
        </div>

        <!-- CREATE / EDIT USER MODAL -->
        <div 
            v-if="isUserModalOpen"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isUserModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-6">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">
                        {{ isEditMode ? 'Edit User Account' : 'Create New User Account' }}
                    </h3>
                    <button @click="isUserModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitUserForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Full Name *</label>
                        <input v-model="userForm.name" type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required placeholder="John Doe" />
                        <span v-if="userForm.errors.name" class="text-red-500 text-xs font-bold mt-1 block">{{ userForm.errors.name }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Email Address *</label>
                        <input v-model="userForm.email" type="email" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required placeholder="john@example.com" />
                        <span v-if="userForm.errors.email" class="text-red-500 text-xs font-bold mt-1 block">{{ userForm.errors.email }}</span>
                    </div>

                    <!-- Role & Status -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Role *</label>
                            <select v-model="userForm.role" class="w-full px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-900 dark:text-white cursor-pointer">
                                <option v-for="r in roles" :key="r" :value="r" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ r.toUpperCase() }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Status *</label>
                            <select v-model="userForm.status" class="w-full px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-900 dark:text-white cursor-pointer">
                                <option v-for="s in statuses" :key="s" :value="s" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-bold">{{ s.toUpperCase() }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password fields (required for create only) -->
                    <div v-if="!isEditMode" class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-700">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Initial Password *</label>
                            <input v-model="userForm.password" type="password" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required autocomplete="new-password" />
                            <span v-if="userForm.errors.password" class="text-red-500 text-xs font-bold mt-1 block">{{ userForm.errors.password }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Confirm Password *</label>
                            <input v-model="userForm.password_confirmation" type="password" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required autocomplete="new-password" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isUserModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="userForm.processing" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold shadow transition disabled:opacity-50">
                            {{ isEditMode ? 'Save Changes' : 'Create User' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- RESET PASSWORD MODAL -->
        <div 
            v-if="isPasswordModalOpen && targetUserForPassword"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isPasswordModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-6">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Reset User Password</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Target User: {{ targetUserForPassword.name }}</p>
                    </div>
                    <button @click="isPasswordModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitPasswordReset" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">New Password *</label>
                        <input v-model="passwordForm.password" type="password" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required />
                        <span v-if="passwordForm.errors.password" class="text-red-500 text-xs font-bold mt-1 block">{{ passwordForm.errors.password }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Confirm New Password *</label>
                        <input v-model="passwordForm.password_confirmation" type="password" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-900 dark:text-white" required />
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="isPasswordModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit" :disabled="passwordForm.processing" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow transition disabled:opacity-50">
                            Reset Password
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- DELETE USER CONFIRMATION MODAL -->
        <div 
            v-if="isDeleteModalOpen && targetUserForDelete"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click.self="isDeleteModalOpen = false"
        >
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-200 dark:border-gray-700 space-y-4 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 dark:bg-red-950/60 dark:text-red-400 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Delete User Account?</h3>
                <p class="text-xs text-gray-500 dark:text-gray-300 leading-relaxed">
                    Are you sure you want to permanently delete account for <strong class="text-gray-900 dark:text-white">{{ targetUserForDelete.name }}</strong> ({{ targetUserForDelete.email }})?
                </p>

                <div class="flex items-center justify-center space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="isDeleteModalOpen = false" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button @click="confirmDeleteUser" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow transition">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
