<script setup>
import { ref } from 'vue';
import Logo from '../../../images/logo.png';
import NavLink from '@/Components/Navigations/NavLink.vue';
import DesktopNavLinks from '@/Components/Navigations/Staff/DesktopNavLinks.vue';
import MobileNavLinks from '@/Components/Navigations/Staff/MobileNavLinks.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue';

const isOpen = ref(false);
const toggle = () => (isOpen.value = !isOpen.value);
</script>

<template>
  <nav class="fixed top-0 left-0 w-full z-50 shadow bg-light text-black dark:bg-dark dark:text-white">
    <div class="container flex items-center justify-between p-2">
      <Link href="/" class="flex items-center space-x-2">
        <img :src="Logo" width="50" height="35" alt="logo" />
        <div>
          <h1 class="text-primary dark:text-secondary-200 font-bold text-lg">
            SKYNET DIGITAL LTD
            <span class=" hidden text-xs text-gray-600 dark:text-gray-400">Limited</span>
          </h1>
        </div>
      </Link>

      <ul class="hidden md:flex space-x-4 items-center">
        <NavLink :href="route('home')" :active="$page.component === 'Home'">Home</NavLink>
        <NavLink :href="route('about')" :active="$page.component === 'About'">About</NavLink>
        <NavLink :href="route('services')" :active="$page.component === 'Services'">Services</NavLink>
        <NavLink :href="route('portfolio')" :active="$page.component === 'Portfolio'">Portfolio</NavLink>
        <NavLink :href="route('contact')" :active="$page.component === 'Contact'">Contact</NavLink>
        
        <template v-if="$page.props.auth.user">
           <DesktopNavLinks />
        </template>
        <template v-else>
           <Link :href="route('login')" class="ml-4 px-4 py-2 text-primary border border-primary rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 text-sm font-medium transition cursor-pointer">Login</Link>
        </template>

        <li class="list-none ml-2">
          <ThemeToggle />
        </li>
      </ul>

      <div class="flex items-center space-x-2 md:hidden">
        <ThemeToggle />
        <button @click="toggle" class="focus:outline-none">
          <svg
            v-if="!isOpen"
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg
            v-else
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <transition name="slide">
      <div v-if="isOpen" class="md:hidden shadow-md p-4">
        <ul class="space-y-3 text-center">
          <li><Link :href="route('home')" @click="toggle">Home</Link></li>
          <li><Link :href="route('about')" @click="toggle">About Us</Link></li>
          <li><Link :href="route('services')" @click="toggle">Services</Link></li>
          <li><Link :href="route('portfolio')" @click="toggle">Portfolio</Link></li>
          <li><Link :href="route('contact')" @click="toggle">Contact</Link></li>
          
          <template v-if="$page.props.auth.user">
             <MobileNavLinks />
          </template>
          <template v-else>
             <li><Link :href="route('login')" @click="toggle" class="block w-full py-2 text-primary border border-primary rounded-md mt-2">Login</Link></li>
          </template>
        </ul>
      </div>
    </transition>
  </nav>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>
