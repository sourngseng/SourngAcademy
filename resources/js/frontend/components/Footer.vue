<template>
    <footer class="bg-gray-900 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5 footer-bg-pattern"></div>

        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12">
                <!-- Brand Section -->
                <div class="lg:col-span-4">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold">{{ hotelName }}</span>
                    </div>
                    <p class="text-gray-400 mb-8 leading-relaxed">
                        {{ t('Experience luxury and comfort at its finest. Our hotel offers world-class amenities and exceptional service for an unforgettable stay.') }}
                    </p>
                    
                    <!-- Social Links -->
                    <div class="flex space-x-3">
                        <a :href="hotelSettings.facebook" class="w-12 h-12 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-amber-500 transition-all duration-300 group border border-white/10">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a :href="hotelSettings.instagram" class="w-12 h-12 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-gradient-to-br hover:from-purple-500 hover:to-pink-500 transition-all duration-300 group border border-white/10">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a :href="hotelSettings.twitter" class="w-12 h-12 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-sky-500 transition-all duration-300 group border border-white/10">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a :href="hotelSettings.youtube" class="w-12 h-12 bg-white/5 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-red-500 transition-all duration-300 group border border-white/10">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-amber-500 rounded mr-3"></span>
                        {{ t('Quick Links') }}
                    </h3>
                    <ul class="space-y-4">
                        <li>
                            <router-link to="/" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                {{ t('Home') }}
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/programs" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-3 opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-0 group-hover:scale-100"></span>
                                Programs
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/about" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                {{ t('About Us') }}
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/blog" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                {{ t('Blog & News') }}
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/contact" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                                <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                {{ t('Contact') }}
                            </router-link>
                        </li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-amber-500 rounded mr-3"></span>
                        {{ t('Services') }}
                    </h3>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            {{ t('Room Service') }}
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            {{ t('Restaurant') }}
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            {{ t('Spa & Wellness') }}
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            {{ t('Swimming Pool') }}
                        </a></li>
                        <li><a href="#" class="text-gray-400 hover:text-amber-400 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            {{ t('Fitness Center') }}
                        </a></li>
                    </ul>
                </div>

                <!-- Contact & Newsletter -->
                <div class="lg:col-span-4">
                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-8 h-1 bg-amber-500 rounded mr-3"></span>
                        {{ t('Contact Us') }}
                    </h3>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-sm">{{ hotelSettings.address }}</span>
                        </li>
                        <li class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-sm">{{ hotelSettings.phone }}</span>
                        </li>
                        <li class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-400 text-sm">{{ hotelSettings.email }}</span>
                        </li>
                    </ul>

                    <!-- Newsletter -->
                    <h3 class="text-lg font-bold mb-4">{{ t('Subscribe Newsletter') }}</h3>
                    <form @submit.prevent="subscribeNewsletter" class="relative">
                        <input 
                            v-model="newsletterEmail"
                            type="email" 
                            :placeholder="t('Enter your email')" 
                            class="w-full px-5 py-4 bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl focus:outline-none focus:border-amber-500 text-white placeholder-gray-500 pr-14"
                        >
                        <button 
                            type="submit"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all flex items-center justify-center"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <p class="text-gray-500 text-sm">
                        © {{ new Date().getFullYear() }} {{ hotelName }}. {{ t('All rights reserved') }}
                    </p>
                    <div class="flex flex-wrap justify-center gap-6">
                        <template v-if="footerPages.length > 0">
                            <router-link 
                                v-for="page in footerPages" 
                                :key="page.id"
                                :to="'/page/' + page.slug" 
                                class="text-gray-500 hover:text-amber-400 text-sm transition-colors"
                            >
                                {{ page.title }}
                            </router-link>
                        </template>
                        <template v-else>
                            <router-link to="/page/privacy-policy" class="text-gray-500 hover:text-amber-400 text-sm transition-colors">{{ t('Privacy Policy') }}</router-link>
                            <router-link to="/page/terms-conditions" class="text-gray-500 hover:text-amber-400 text-sm transition-colors">{{ t('Terms of Service') }}</router-link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { t, translationKey } from '../composables/useTranslation';

const hotelName = ref(window.hotelSettings?.name || 'Luxury Hotel');
const newsletterEmail = ref('');
const footerPages = ref([]);
const hotelSettings = ref({
    phone: '+855 12 345 678',
    email: 'info@luxuryhotel.com',
    address: 'Phnom Penh, Cambodia',
    facebook: '#',
    instagram: '#',
    twitter: '#',
    youtube: '#'
});

// Fetch hotel settings from API
const fetchSettings = async () => {
    try {
        const response = await fetch('/api/frontend/settings');
        const data = await response.json();
        if (data.success && data.data) {
            hotelName.value = data.data.hotel_name || 'Luxury Hotel';
            hotelSettings.value = {
                phone: data.data.phone || '+855 12 345 678',
                email: data.data.email || 'info@luxuryhotel.com',
                address: data.data.address || 'Phnom Penh, Cambodia',
                facebook: data.data.facebook || '#',
                instagram: data.data.instagram || '#',
                twitter: data.data.twitter || '#',
                youtube: data.data.youtube || '#'
            };
        }
    } catch (error) {
        console.log('Using default hotel settings');
    }
};

// Fetch footer pages from API
const fetchFooterPages = async () => {
    try {
        const response = await fetch('/api/frontend/pages/footer');
        const data = await response.json();
        if (data.success && data.data) {
            footerPages.value = data.data;
        }
    } catch (error) {
        console.log('Could not load footer pages');
    }
};

const subscribeNewsletter = () => {
    // TODO: Implement newsletter subscription
    alert(t('footer.thank_you_subscribe', 'Thank you for subscribing!'));
    newsletterEmail.value = '';
};

onMounted(() => {
    fetchSettings();
    fetchFooterPages();
});
</script>

<style scoped>
.footer-bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
