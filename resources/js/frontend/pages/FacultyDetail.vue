<template>
    <div v-if="loading" class="min-h-screen bg-gray-50 pt-24 pb-12 flex justify-center items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>
    
    <div v-else-if="faculty" class="bg-white min-h-screen">
        <!-- Faculty Header -->
        <header class="relative pt-32 pb-24 bg-gray-900">
            <div class="absolute inset-0 bg-cover bg-center opacity-40 transition-opacity duration-700" :style="`background-image: url('${faculty.image}')`"></div>
            <div class="absolute inset-x-0 bottom-0 top-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex mb-8 justify-center md:justify-start" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-400">
                        <li><router-link to="/" class="hover:text-white transition-colors">Home</router-link></li>
                        <li><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                        <li class="text-white font-medium">Faculties</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-8">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full uppercase tracking-widest mb-4">
                            {{ faculty.code }}
                        </div>
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 leading-tight">
                            {{ faculty.name }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-gray-300">
                            <div v-if="faculty.campus" class="flex items-center">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-400"></i>
                                {{ faculty.campus.name }}
                            </div>
                            <div v-if="faculty.dean_name" class="flex items-center border-l border-gray-700 pl-6 hidden md:flex">
                                <i class="fas fa-user-tie mr-2 text-indigo-400"></i>
                                Dean: {{ faculty.dean_name }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 bg-white/10 backdrop-blur-md p-6 rounded-2xl border border-white/20 hidden lg:block">
                        <div class="text-center">
                            <span class="block text-3xl font-bold text-white">{{ faculty.departments.length }}</span>
                            <span class="text-gray-400 text-sm uppercase tracking-wider">Departments</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <!-- Left: Description & Departments -->
                <div class="lg:col-span-2">
                    <section class="mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="w-8 h-1 bg-indigo-600 mr-4 rounded-full"></span>
                            Overview
                        </h2>
                        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed" v-html="faculty.description || 'Welcome to our faculty dedicated to excellence in education and research.'"></div>
                    </section>

                    <section v-if="faculty.departments && faculty.departments.length">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-1 bg-indigo-600 mr-4 rounded-full"></span>
                                Our Departments
                            </h2>
                            <span class="text-sm font-medium text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                                {{ faculty.departments.length }} Specialized Units
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <router-link 
                                v-for="dept in faculty.departments" 
                                :key="dept.id" 
                                :to="{ name: 'DepartmentDetail', params: { slug: dept.slug } }"
                                class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:border-indigo-600 transition-all duration-300 flex items-center p-3"
                            >
                                <!-- Compact Image -->
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                                    <img :src="dept.image" :alt="dept.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                                
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1 leading-tight">
                                        {{ dept.name }}
                                    </h3>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center mt-1">
                                        View Department <i class="fas fa-arrow-right ml-2 text-[8px] transform group-hover:translate-x-1 transition-transform"></i>
                                    </span>
                                </div>
                            </router-link>
                        </div>
                    </section>
                </div>

                <!-- Right: Dynamic Programs or Info -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-8">
                        <!-- Application Widget -->
                        <div class="bg-indigo-900 rounded-3xl p-8 text-white shadow-xl overflow-hidden relative">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-800 rounded-full opacity-50"></div>
                            <div class="relative z-10">
                                <h3 class="text-2xl font-bold mb-4">Ready to Apply?</h3>
                                <p class="text-indigo-200 mb-8 leading-relaxed">Join thousands of students and start your journey with us today. Applications for next semester are now open.</p>
                                <router-link to="/contact" class="block w-full text-center py-4 px-6 bg-white text-indigo-900 font-bold rounded-xl hover:bg-indigo-50 transition-colors shadow-lg shadow-indigo-950/20">
                                    Apply Now
                                </router-link>
                            </div>
                        </div>

                        <!-- Featured Info -->
                        <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                            <h4 class="text-lg font-bold text-gray-900 mb-6">Quick Links</h4>
                            <ul class="space-y-4">
                                <li>
                                    <a href="#" class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors group">
                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm group-hover:bg-indigo-50">
                                            <i class="fas fa-file-pdf text-xs"></i>
                                        </div>
                                        Admission Guide
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors group">
                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm group-hover:bg-indigo-50">
                                            <i class="fas fa-calendar-alt text-xs"></i>
                                        </div>
                                        Academic Calendar
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors group">
                                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center mr-3 shadow-sm group-hover:bg-indigo-50">
                                            <i class="fas fa-users text-xs"></i>
                                        </div>
                                        Student Life
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <div v-else class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 pt-24">
        <div class="text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-university text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Faculty not found</h2>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto">The faculty you are looking for does not exist or has been relocated to another department.</p>
            <router-link to="/" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Campus
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const faculty = ref(null);
const loading = ref(true);

const fetchFacultyDetail = async (slug) => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/frontend/faculties/${slug}`);
        if (response.data.success) {
            faculty.value = response.data.data;
            document.title = `${faculty.value.name} - Sourng Academy`;
        } else {
            faculty.value = null;
        }
    } catch (error) {
        console.error('Error fetching faculty detail:', error);
        faculty.value = null;
    } finally {
        setTimeout(() => {
            loading.value = false;
        }, 500); // Smooth transition
    }
};

onMounted(() => {
    fetchFacultyDetail(route.params.slug);
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchFacultyDetail(newSlug);
});
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Base typography enhancements */
:deep(.prose) {
    color: #4b5563;
}
:deep(.prose h3) {
    color: #1f2937;
    font-weight: 700;
}
</style>
