<script setup>

const liClasses = 'block px-4 py-2 text-gray-700 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700';
</script>
<template>
    <!-- Admin Dropdown Menu -->
    <li class="relative group" >
        <button class=" px-0 py-0  transition-all">
            Menu
        </button>
        <!-- Dropdown menu (hidden by default, shown on hover) -->
        <ul class="absolute bg-white dark:bg-gray-900 right-0 mt-0 w-48  rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">
            <li>
                <Link :href="route('dashboard')" :class="liClasses">
                    Dashboard
                </Link>
            </li>
            <li>
                <Link :href="route('profile.edit')" :class="liClasses">
                    Profile
                </Link>
            </li>
                <li>
                  <Link :href="route('admin.projects.index')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Manage Projects</Link>
                </li>
                <li>
                  <Link :href="route('admin.contacts.index')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Contact Messages</Link>
                </li>
            <li>
                <Link :href="route('log.index')" :class="liClasses">
                    Logs
                </Link>
            </li>
            <li>
                <Link :href="route('logout')" as="button" method="post"
                    class="block px-4 py-2 text-red-500 hover:bg-gray-100 w-full text-left dark:hover:bg-gray-700">
                    Logout
                </Link>
            </li>
        </ul>
    </li>
</template>
