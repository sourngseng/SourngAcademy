<template>
    <div v-if="loading" class="min-h-screen bg-gray-50 pt-24 pb-12 flex justify-center items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>
    
    <div v-else-if="department" class="bg-white min-h-screen">
        <!-- Department Header -->
        <header class="relative pt-32 pb-24 bg-gray-900 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-50 transition-transform duration-1000 transform hover:scale-105" :style="`background-image: url('${department.image}')`"></div>
            <div class="absolute inset-x-0 bottom-0 top-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
            
            <!-- Animated decorative elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] -mr-48 -mt-48 animate-pulse"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex mb-8 justify-center md:justify-start" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-400">
                        <li><router-link to="/" class="hover:text-white transition-colors">Home</router-link></li>
                        <li><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                        <li v-if="department.faculty">
                            <router-link :to="{ name: 'FacultyDetail', params: { slug: department.faculty.slug } }" class="hover:text-white transition-colors">
                                {{ department.faculty.name }}
                            </router-link>
                        </li>
                        <li v-if="department.faculty"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></li>
                        <li class="text-white font-medium">{{ department.name }}</li>
                    </ol>
                </nav>

                <div class="flex flex-col md:flex-row items-center md:items-end justify-between gap-8">
                    <div class="text-center md:text-left flex-1">
                        <div class="inline-flex items-center px-4 py-1.5 bg-indigo-600/90 backdrop-blur-sm text-white text-xs font-black rounded-xl uppercase tracking-[0.2em] mb-6 shadow-xl shadow-indigo-900/40 border border-white/20">
                            {{ department.code || 'Academic Unit' }}
                        </div>
                        <h1 class="text-4xl md:text-7xl font-black text-white mb-6 leading-[1.1] tracking-tight">
                            {{ department.name }}
                        </h1>
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-8 text-gray-300">
                            <div v-if="department.head_of_department" class="flex items-center bg-white/5 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10">
                                <div class="w-10 h-10 rounded-xl overflow-hidden mr-3 bg-gray-800 border border-white/20">
                                    <img v-if="department.head_of_department.avatar" :src="department.head_of_department.avatar" class="w-full h-full object-cover">
                                    <i v-else class="fas fa-user-tie text-indigo-400 m-auto mt-2.5 ml-2.5"></i>
                                </div>
                                <div class="text-left">
                                    <span class="block text-[10px] text-indigo-400 font-bold uppercase tracking-wider">Head of Department</span>
                                    <span class="font-bold text-white">{{ department.head_of_department.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <!-- Main Body -->
                <div class="lg:col-span-8 space-y-20">
                    <!-- Overview Section -->
                    <section>
                        <div class="flex items-center mb-8 group">
                            <div class="w-1.5 h-10 bg-indigo-600 mr-6 rounded-full group-hover:scale-y-110 transition-transform"></div>
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Overview</h2>
                        </div>
                        <div class="prose prose-xl max-w-none text-gray-600 leading-relaxed font-medium" v-html="department.description || 'Welcome to our department.'"></div>
                    </section>

                    <!-- Strategic Sections (Mission, Vision, Goals) -->
                    <div class="grid grid-cols-1 gap-12 pt-8">
                        <div v-if="department.mission" class="bg-gradient-to-br from-gray-50 to-white p-10 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-xl transition-all duration-500">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mb-8 group-hover:rotate-6 transition-transform">
                                    <i class="fas fa-rocket text-indigo-600 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight">Our Mission</h3>
                                <div class="text-gray-600 prose prose-lg" v-html="department.mission"></div>
                            </div>
                        </div>

                        <div v-if="department.vision" class="bg-gradient-to-br from-indigo-900 to-indigo-800 p-10 rounded-[32px] text-white shadow-2xl relative overflow-hidden group">
                           <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 rounded-full -mb-32 -mr-32"></div>
                           <div class="relative z-10">
                                <div class="w-14 h-14 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl flex items-center justify-center mb-8 group-hover:-rotate-6 transition-transform">
                                    <i class="fas fa-eye text-white text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-6 uppercase tracking-tight">Our Vision</h3>
                                <div class="text-indigo-100 prose prose-lg prose-invert" v-html="department.vision"></div>
                           </div>
                        </div>

                        <div v-if="department.goal" class="bg-white p-10 rounded-[32px] border-2 border-dashed border-gray-100 group">
                            <div class="flex items-center mb-8">
                                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mr-6 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-bullseye text-amber-500 text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Strategic Goals</h3>
                            </div>
                            <div class="text-gray-600 prose prose-lg max-w-none" v-html="department.goal"></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4">
                    <div class="sticky top-28 space-y-8">
                        <!-- Navigation Widget -->
                        <div class="bg-gray-900 rounded-[32px] p-8 text-white shadow-2xl relative overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-600/30 rounded-full blur-3xl"></div>
                            <h3 class="text-xl font-bold mb-8 flex items-center">
                                <span class="w-1 h-6 bg-indigo-500 mr-4 rounded-full"></span>
                                Academic Journey
                            </h3>
                            <div class="space-y-4">
                                <router-link :to="{ name: 'programs', query: { department_id: department.id } }" class="flex items-center justify-between w-full p-6 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl transition-all group">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center mr-4 group-hover:bg-indigo-500/40 transition-colors">
                                            <i class="fas fa-graduation-cap text-indigo-400"></i>
                                        </div>
                                        <span class="font-bold">Browse Programs</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-gray-500 group-hover:translate-x-1 transition-transform"></i>
                                </router-link>
                                
                                <router-link to="/contact" class="flex items-center justify-between w-full p-6 bg-white rounded-2xl shadow-xl shadow-gray-950/20 transition-all hover:-translate-y-1 group">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center mr-4">
                                            <i class="fas fa-paper-plane text-indigo-600"></i>
                                        </div>
                                        <span class="font-bold text-gray-900">Inquiry Now</span>
                                    </div>
                                    <i class="fas fa-arrow-right text-indigo-600"></i>
                                </router-link>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 gap-4">
                            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mr-4">
                                    <i class="fas fa-clock text-indigo-500 text-sm"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase">Working Hours</span>
                                    <span class="font-bold text-gray-900">{{ department.working_hours || 'Mon - Fri: 8AM-5PM' }}</span>
                                </div>
                            </div>
                            <div v-if="department.email" class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope text-indigo-500 text-sm"></i>
                                </div>
                                <div class="truncate">
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase">Department Email</span>
                                    <span class="font-bold text-gray-900 truncate">{{ department.email }}</span>
                                </div>
                            </div>
                            <div v-if="department.phone" class="p-6 bg-gray-50 rounded-2xl border border-gray-100 flex items-center">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mr-4">
                                    <i class="fas fa-phone text-indigo-500 text-sm"></i>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase">Phone Number</span>
                                    <span class="font-bold text-gray-900">{{ department.phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <div v-else class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4 pt-24">
        <div class="text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-building text-4xl text-gray-300"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Department not found</h2>
            <p class="text-gray-500 mb-8 max-w-sm mx-auto font-medium">The academic unit you are looking for has either been restructured or relocated.</p>
            <router-link to="/" class="inline-flex items-center px-10 py-4 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 uppercase tracking-widest text-sm">
                <i class="fas fa-arrow-left mr-3"></i>
                Back Home
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const department = ref(null);
const loading = ref(true);

const fetchDepartmentDetail = async (slug) => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/frontend/departments/${slug}`);
        if (response.data.success) {
            department.value = response.data.data;
            document.title = `${department.value.name} - Sourng Academy`;
        } else {
            department.value = null;
        }
    } catch (error) {
        console.error('Error fetching department detail:', error);
        department.value = null;
    } finally {
        setTimeout(() => {
            loading.value = false;
        }, 600); // Smoother high-end feel
    }
};

onMounted(() => {
    fetchDepartmentDetail(route.params.slug);
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchDepartmentDetail(newSlug);
});
</script>

<style scoped>
.prose {
    max-width: none;
}
:deep(.prose p) {
    margin-bottom: 1.5rem;
}
:deep(.prose h1, .prose h2, .prose h3) {
    color: #111827;
    font-weight: 800;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
</style>
