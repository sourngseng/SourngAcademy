<template>
    <section class="relative h-screen overflow-hidden">
        <!-- Loading State -->
        <div v-if="isLoading || slides.length === 0" class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex items-center justify-center">
            <div class="text-center">
                <div class="w-12 h-12 border-3 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                <p class="text-white/70">Loading University...</p>
            </div>
        </div>

        <!-- Hero Slider -->
        <Swiper
            v-else
            :modules="modules"
            :slides-per-view="1"
            :loop="true"
            :autoplay="{ delay: 6000, disableOnInteraction: false }"
            :effect="'fade'"
            :speed="1200"
            :pagination="{ clickable: true }"
            class="h-full hero-swiper"
        >
            <SwiperSlide v-for="(slide, index) in slides" :key="index">
                <div class="relative h-full">
                    <!-- Background Image -->
                    <img 
                        :src="slide.image" 
                        :alt="slide.title"
                        class="absolute inset-0 w-full h-full object-cover"
                    >
                    
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
                    
                    <!-- Slide Content -->
                    <div class="relative h-full flex items-center justify-center text-center">
                        <div class="max-w-4xl mx-auto px-4">
                            <span v-if="slide.show_subtitle !== false" class="inline-block px-5 py-2 bg-white/10 backdrop-blur-sm text-amber-400 text-sm font-medium rounded-full mb-5 border border-white/20">
                                {{ slide.subtitle || 'Welcome to Sourng Academy' }}
                            </span>
                            <h1 v-if="slide.show_title !== false" class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-tight mb-5">
                                {{ slide.title }}
                            </h1>
                            <p v-if="slide.show_description !== false" class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-8">
                                {{ slide.description }}
                            </p>
                            <div v-if="slide.show_button !== false" class="flex flex-wrap justify-center gap-4">
                                <template v-if="slide.button_link">
                                    <component 
                                        :is="slide.button_link.startsWith('http') ? 'a' : 'router-link'"
                                        :href="slide.button_link.startsWith('http') ? slide.button_link : undefined"
                                        :to="!slide.button_link.startsWith('http') ? slide.button_link : undefined"
                                        class="px-8 py-4 bg-amber-500 text-white font-semibold rounded-full hover:bg-amber-600 transition-all duration-300 shadow-lg hover:shadow-amber-500/30"
                                        :target="slide.button_link.startsWith('http') ? '_blank' : undefined"
                                    >
                                        {{ slide.button_text || t('Explore Programs') }}
                                    </component>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </SwiperSlide>
        </Swiper>


    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, EffectFade } from 'swiper/modules';
import { t } from '../composables/useTranslation';

const modules = [Autoplay, Pagination, EffectFade];
const slides = ref([]);
const isLoading = ref(true);

const mockSlides = [
    {
        image: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
        title: 'Shaping the Future',
        subtitle: 'World-Class Education',
        description: 'Join a diverse community of scholars and innovators dedicated to excellence.'
    },
    {
        image: 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
        title: 'Inspiring Innovation',
        subtitle: 'Research & Discovery',
        description: 'State-of-the-art facilities and expert faculty to help you achieve your goals.'
    },
    {
        image: 'https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80',
        title: 'Campus Life',
        subtitle: 'Vibrant Community',
        description: 'Experience a rich and engaging student life with endless opportunities.'
    }
];

// Fetch data from API or use mock
onMounted(async () => {
    try {
        const response = await fetch('/api/frontend/slides');
        const data = await response.json();
        
        if (data.success && data.data?.length > 0) {
            slides.value = data.data;
        } else {
            slides.value = mockSlides;
        }
    } catch (error) {
        console.error('Failed to fetch slides:', error);
        slides.value = mockSlides;
    } finally {
        isLoading.value = false;
    }
});
</script>

<style scoped>
.hero-swiper {
    --swiper-pagination-color: #f59e0b;
    --swiper-pagination-bullet-inactive-color: rgba(255, 255, 255, 0.5);
    --swiper-pagination-bullet-inactive-opacity: 1;
}

:deep(.swiper-pagination) {
    bottom: 40px !important;
}

:deep(.swiper-pagination-bullet) {
    width: 10px;
    height: 10px;
    transition: all 0.3s ease;
}

:deep(.swiper-pagination-bullet-active) {
    width: 30px;
    border-radius: 5px;
}
</style>
