<template>
    <section class="py-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-20 left-10 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-amber-400/10 rounded-full blur-3xl"></div>
        </div>
        
        <!-- Quote Pattern -->
        <div class="absolute top-20 left-20 text-amber-500/10 hidden lg:block">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 backdrop-blur-sm rounded-full mb-4">
                    <svg class="w-4 h-4 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="text-amber-400 font-semibold text-sm uppercase tracking-wider">{{ t('Guest Reviews') }}</span>
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-white mb-4">
                    {{ t('Loved by Thousands') }}
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    {{ t("Don't just take our word for it — hear what our guests have to say") }}
                </p>
            </div>

            <!-- Testimonials Swiper -->
            <Swiper
                :modules="modules"
                :slides-per-view="1"
                :space-between="32"
                :loop="true"
                :autoplay="{ delay: 5000, disableOnInteraction: false }"
                :pagination="{ clickable: true, dynamicBullets: true }"
                :breakpoints="{
                    768: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }"
                class="pb-16 testimonials-swiper"
            >
                <SwiperSlide v-for="testimonial in testimonials" :key="testimonial.id">
                    <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 h-full border border-white/10 hover:border-amber-500/30 transition-all duration-300 group">
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-1 mb-6">
                            <template v-for="i in 5" :key="i">
                                <svg 
                                    class="w-5 h-5 transition-transform group-hover:scale-110" 
                                    :class="i <= testimonial.rating ? 'text-amber-400' : 'text-gray-600'"
                                    :style="{ transitionDelay: `${i * 50}ms` }"
                                    fill="currentColor" 
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </template>
                        </div>

                        <!-- Quote -->
                        <p class="text-gray-300 mb-8 leading-relaxed text-lg">
                            "{{ testimonial.content }}"
                        </p>

                        <!-- Author -->
                        <div class="flex items-center mt-auto">
                            <div class="relative">
                                <img 
                                    :src="testimonial.avatar" 
                                    :alt="testimonial.name"
                                    class="w-14 h-14 rounded-full object-cover ring-4 ring-amber-500/30"
                                >
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-white text-lg">{{ testimonial.name }}</h4>
                                <p class="text-amber-400 text-sm">{{ testimonial.location }}</p>
                            </div>
                        </div>
                    </div>
                </SwiperSlide>
            </Swiper>

            <!-- Stats Section -->
            <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center group">
                    <div class="relative inline-block mb-4">
                        <div class="w-20 h-20 bg-amber-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1">15K+</div>
                    <div class="text-gray-400">{{ t('Happy Guests') }}</div>
                </div>
                <div class="text-center group">
                    <div class="relative inline-block mb-4">
                        <div class="w-20 h-20 bg-amber-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1">4.9</div>
                    <div class="text-gray-400">{{ t('Average Rating') }}</div>
                </div>
                <div class="text-center group">
                    <div class="relative inline-block mb-4">
                        <div class="w-20 h-20 bg-amber-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1">50+</div>
                    <div class="text-gray-400">{{ t('Luxury Rooms') }}</div>
                </div>
                <div class="text-center group">
                    <div class="relative inline-block mb-4">
                        <div class="w-20 h-20 bg-amber-500/20 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-4xl font-bold text-white mb-1">10+</div>
                    <div class="text-gray-400">{{ t('Years Experience') }}</div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination } from 'swiper/modules';
import { t, translationKey } from '../composables/useTranslation';

const modules = [Autoplay, Pagination];

const testimonials = ref([
    {
        id: 1,
        name: 'Sarah Johnson',
        location: 'New York, USA',
        rating: 5,
        content: 'An absolutely wonderful experience! The staff was incredibly attentive, and the room exceeded all expectations. Will definitely return!',
        avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80'
    },
    {
        id: 2,
        name: 'Michael Chen',
        location: 'Singapore',
        rating: 5,
        content: 'The best hotel I\'ve stayed at in Cambodia. The spa was divine, and the restaurant served the most delicious local cuisine.',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80'
    },
    {
        id: 3,
        name: 'Emma Thompson',
        location: 'London, UK',
        rating: 5,
        content: 'Perfect location, stunning views, and impeccable service. The attention to detail here is remarkable. Highly recommend!',
        avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80'
    },
    {
        id: 4,
        name: 'David Park',
        location: 'Seoul, Korea',
        rating: 4,
        content: 'Great value for money. The rooms are spacious and clean. The breakfast buffet had an excellent variety. Will come back!',
        avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80'
    },
    {
        id: 5,
        name: 'Lisa Anderson',
        location: 'Sydney, Australia',
        rating: 5,
        content: 'From check-in to check-out, everything was seamless. The pool area is beautiful and the gym is well-equipped. Fantastic stay!',
        avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80'
    }
]);

// Fetch reviews from API
onMounted(async () => {
    try {
        const response = await fetch('/api/frontend/testimonials');
        const data = await response.json();
        if (data.success && data.data && data.data.length > 0) {
            testimonials.value = data.data.map(review => ({
                id: review.id,
                name: review.guest_name,
                location: review.guest_country,
                rating: review.rating,
                content: review.comment,
                avatar: review.guest_image
            }));
        }
    } catch (error) {
    }
});
</script>

<style scoped>
.testimonials-swiper {
    --swiper-pagination-color: #f59e0b;
    --swiper-pagination-bullet-inactive-color: rgba(255, 255, 255, 0.3);
    --swiper-pagination-bullet-inactive-opacity: 1;
    --swiper-pagination-bullet-size: 10px;
    --swiper-pagination-bullet-horizontal-gap: 6px;
}
</style>
