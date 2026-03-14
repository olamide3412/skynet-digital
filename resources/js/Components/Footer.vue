<script setup>
import Logo from '../../images/logo.png' // Replace with proper logo
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <section class="relative pb-16 bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100">
    <!-- Optional Background Overlay -->
    <div class="absolute inset-0 bg-black opacity-5 pointer-events-none"></div>

    <div class="relative container mx-auto px-4 py-12">
      <div class="flex flex-col md:flex-row gap-10">

        <!-- Left Column: Logo + Company Description -->
        <div class="md:w-1/3" data-aos="fade-up" data-aos-delay="100">
          <img :src="Logo" alt="Texas Trust Retail & Credit Marketplace Logo" class="h-16 mb-4" />
          <p class="text-sm leading-relaxed">
            We design, build, secure, and maintain modern digital systems that help organizations grow, operate faster, and stay competitive.
          </p>
        </div>

        <!-- Middle Column: Navigation -->
        <nav class="md:w-1/3" data-aos="fade-up" data-aos-delay="200" aria-label="Site Links">
          <h4 class="font-semibold text-lg mb-4">Quick Links</h4>
          <ul class="space-y-2 text-sm">
            <li><Link :href="route('home')" class="hover:underline">Home</Link></li>
            <li><Link :href="route('about')" class="hover:underline">About Us</Link></li>
            <li><Link :href="route('services')" class="hover:underline">Services</Link></li>
            <li><Link :href="route('portfolio')" class="hover:underline">Portfolio</Link></li>
            <li><Link :href="route('contact')" class="hover:underline">Contact</Link></li>
          </ul>
        </nav>

        <!-- Right Column: Contact Info -->
        <address class="md:w-1/3 not-italic text-sm space-y-2" data-aos="fade-up" data-aos-delay="300">
          <h4 class="font-semibold text-lg mb-4">Contact</h4>
          <div class="flex items-start">
            <font-awesome-icon :icon="['fas', 'map-marker-alt']" class="mr-2 text-primary" />
            <span>Delta State, Nigeria</span>
          </div>
          <div class="flex items-center">
             <font-awesome-icon :icon="['fas', 'phone']" class="mr-2 text-primary" />
             <span>+234 803 2072 831</span>
          </div>
          <div class="flex items-center">
            <font-awesome-icon :icon="['fas', 'envelope']" class="mr-2 text-primary" />
            <a href="mailto:support@skynet.com" class="hover:underline">support@skynetdigitalhubs.com</a>
          </div>
        </address>
      </div>

      <!-- Divider -->
      <hr class="my-8 border-t border-gray-300 dark:border-gray-700" />

      <!-- Footer Note -->
      <div class="flex flex-col md:flex-row justify-between items-center text-xs text-gray-600 dark:text-gray-400">
        <div>
          © {{ new Date().getFullYear() }} Skynet Digital Limited. All rights reserved.
        </div>
        <div class="flex space-x-4 mt-4 md:mt-0">
          <a href="#" class="hover:underline">Privacy Policy</a>
          <a href="#" class="hover:underline">Terms of Use</a>
        </div>
      </div>
    </div>
  </section>
</template>
