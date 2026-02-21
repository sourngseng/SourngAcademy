<template>
  <section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Our Faculties</h2>
        <p class="mt-4 text-xl text-gray-500">Explore our diverse range of academic disciplines</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="i in 3" :key="i" class="bg-gray-100 animate-pulse rounded-2xl h-80"></div>
      </div>

      <!-- Content State -->
      <div v-else class="relative group/swiper">
        <Swiper
          :key="faculties.length"
          :modules="modules"
          :slides-per-view="1"
          :space-between="30"
          :loop="faculties.length > 3"
          :autoplay="{ delay: 4000, disableOnInteraction: false }"
          :pagination="{ clickable: true, dynamicBullets: true }"
          :navigation="{
            nextEl: '.swiper-next',
            prevEl: '.swiper-prev',
          }"
          :breakpoints="{
            640: { slidesPerView: 1.5 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
          }"
          class="pb-20 faculties-swiper !px-4"
        >
          <SwiperSlide v-for="faculty in faculties" :key="faculty.id" class="!py-4">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 h-full border border-gray-100 group">
              <div class="relative h-56 overflow-hidden">
                <img :src="faculty.image" :alt="faculty.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur p-2.5 rounded-xl shadow-sm transform group-hover:rotate-12 transition-transform duration-500">
                  <i v-if="faculty.icon" :class="[faculty.icon, 'text-2xl text-indigo-600']"></i>
                  <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
              </div>
              <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                  <span class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] bg-indigo-50 px-2 py-1 rounded">{{ faculty.code }}</span>
                  <span class="text-xs font-medium text-gray-400 flex items-center">
                    <i class="fas fa-layer-group mr-1.5 text-indigo-300"></i>
                    {{ faculty.departments_count || 0 }} Depts
                  </span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-1">{{ faculty.name }}</h3>
                <p class="text-gray-500 mb-8 line-clamp-1 h-5 text-sm leading-relaxed">{{ faculty.description || 'Join our faculty for a transformative learning experience led by industry experts.' }}</p>
                <router-link :to="{ name: 'FacultyDetail', params: { slug: faculty.slug } }" class="inline-flex items-center justify-center w-full px-6 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-100 hover:shadow-indigo-200">
                  Explore Programs
                  <i class="fas fa-arrow-right ml-2.5 text-xs transition-transform group-hover:translate-x-1"></i>
                </router-link>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>

        <!-- Custom Navigation Arrows -->
        <button class="swiper-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 z-10 w-12 h-12 bg-white rounded-full shadow-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:scale-110 transition-all duration-300 opacity-0 group-hover/swiper:opacity-100 group-hover/swiper:-translate-x-2 hidden lg:flex">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="swiper-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 z-10 w-12 h-12 bg-white rounded-full shadow-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:scale-110 transition-all duration-300 opacity-0 group-hover/swiper:opacity-100 group-hover/swiper:translate-x-2 hidden lg:flex">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';

const faculties = ref([]);
const loading = ref(true);
const modules = [Autoplay, Pagination, Navigation];

onMounted(async () => {
    try {
        const response = await axios.get('/api/frontend/faculties');
        if (response.data.success) {
            faculties.value = response.data.data;
        }
    } catch (error) {
        console.error('Error fetching faculties:', error);
    } finally {
        loading.value = false;
    }
});
</script>
