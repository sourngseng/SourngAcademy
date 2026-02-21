<template>
    <div class="bg-white min-h-screen">
        <!-- Premium Hero Header -->
        <header class="relative pt-40 pb-24 bg-gray-900 overflow-hidden">
            <div class="absolute inset-0 bg-[#0a0a0a]">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fbbf24 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-gray-900/50 to-gray-900"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="inline-block px-4 py-1.5 bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-8 border border-amber-500/20">
                    Our Network
                </span>
                <h1 class="text-4xl md:text-7xl font-black text-white mb-8 uppercase tracking-tighter leading-none">
                    Our <span class="text-amber-500">Campuses</span>
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto font-medium text-lg leading-relaxed mb-4">
                    Strategically located campuses across the region, each serving as a hub for academic excellence and student success.
                </p>
                <div v-if="!loading && campuses.length > 0" class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-500 text-sm font-bold">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ campuses.length }} Campuses Available
                </div>
            </div>
        </header>

        <section class="relative -mt-12 pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Skeleton Loading -->
                <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="i in 3" :key="i" class="bg-white rounded-[40px] p-4 shadow-xl border border-gray-100">
                        <div class="w-full h-64 bg-gray-100 rounded-[30px] animate-pulse mb-6"></div>
                        <div class="h-8 bg-gray-100 rounded-lg animate-pulse mb-4 w-3/4"></div>
                        <div class="h-4 bg-gray-100 rounded-lg animate-pulse w-1/2"></div>
                    </div>
                </div>

                <!-- Actual Campus Grid -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <router-link 
                        v-for="campus in campuses" 
                        :key="campus.id"
                        :to="{ name: 'CampusDetail', params: { slug: campus.slug || 'campus-' + campus.id } }"
                        class="group bg-white rounded-[40px] p-4 shadow-[0_20px_50px_rgba(0,0,0,0.05)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.1)] transition-all duration-700 hover:-translate-y-4 border border-gray-50 flex flex-col"
                    >
                        <div class="relative h-72 rounded-[30px] overflow-hidden mb-8">
                            <img :src="campus.image" :alt="campus.name" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-black text-amber-600 uppercase tracking-widest shadow-lg">
                                    {{ campus.code }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-4 pb-4 flex-grow flex flex-col">
                            <h2 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-amber-600 transition-colors uppercase tracking-tight leading-tight">
                                {{ campus.name }}
                            </h2>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex items-start text-sm text-gray-500 font-medium">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 mr-4 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                        <i class="fas fa-map-marker-alt text-xs"></i>
                                    </div>
                                    <span class="line-clamp-2 pt-1">{{ campus.address }}</span>
                                </div>
                                <div v-if="campus.phone" class="flex items-center text-sm text-gray-500 font-medium">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 mr-4 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300">
                                        <i class="fas fa-phone text-xs"></i>
                                    </div>
                                    <span>{{ campus.phone }}</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-6 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-[10px] font-black text-amber-600 uppercase tracking-[0.3em]">Explore Campus</span>
                                <div class="w-12 h-12 rounded-full bg-gray-900 text-white flex items-center justify-center group-hover:bg-amber-500 transition-all duration-300 transform group-hover:rotate-[360deg]">
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>

                <div v-if="!loading && campuses.length === 0" class="text-center py-32 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-8">
                        <i class="fas fa-satellite text-4xl text-amber-500 animate-bounce"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-3 uppercase">Establishing Connectivity</h3>
                    <p class="text-gray-500 font-medium max-w-md mx-auto italic">Our global campus synchronization is in progress. Please check back shortly.</p>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const campuses = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        const response = await axios.get('/api/frontend/campuses');
        if (response.data.success) {
            campuses.value = response.data.data;
        }
    } catch (error) {
        console.error('Error fetching campuses:', error);
    } finally {
        setTimeout(() => {
            loading.value = false;
        }, 600);
    }
});
</script>
