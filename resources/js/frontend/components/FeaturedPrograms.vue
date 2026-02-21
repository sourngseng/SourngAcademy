<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Popular Programs</h2>
        <p class="mt-4 text-xl text-gray-500">Discover our most sought-after degrees</p>
      </div>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="i in 3" :key="i" class="bg-gray-100 animate-pulse rounded-lg h-80"></div>
      </div>

      <div v-else-if="programs.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div v-for="program in programs" :key="program.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col h-full hover:shadow-xl transition-all duration-300">
          <div class="relative h-56 overflow-hidden">
             <img :src="program.image" :alt="program.name" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
             <div class="absolute top-4 left-4">
                 <span class="px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-lg">{{ program.degree_level }}</span>
             </div>
          </div>
          <div class="p-6 flex-1 flex flex-col">
            <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-3">
                <i class="far fa-clock mr-2 text-amber-500"></i>
                <span>{{ program.duration_years }} Years</span>
                <span class="mx-2">•</span>
                <i class="fas fa-graduation-cap mr-2 text-amber-500"></i>
                <span>{{ program.total_credits }} Credits</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">{{ program.name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 flex-1 line-clamp-1 text-sm">{{ program.description }}</p>
            
            <div class="mt-auto pt-4">
                <router-link to="/programs" class="w-full bg-indigo-600 dark:bg-indigo-500 text-white py-3 px-4 rounded-xl hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-300 flex items-center justify-center font-semibold shadow-md">
                    Explore Program
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </router-link>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-12">
          <p class="text-gray-500">No popular programs found at the moment.</p>
      </div>
      
      <div class="mt-12 text-center">
        <router-link to="/programs" class="inline-block bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl py-4 px-10 text-base font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300">
            View All Programs
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const programs = ref([]);
const loading = ref(true);

const fetchPrograms = async () => {
    try {
        const response = await fetch('/api/frontend/programs?is_popular=1&limit=3');
        const data = await response.json();
        if (data.success) {
            programs.value = data.data;
        }
    } catch (error) {
        console.error('Error fetching programs:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchPrograms();
});
</script>
