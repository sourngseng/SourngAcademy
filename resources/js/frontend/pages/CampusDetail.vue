<template>
    <div v-if="loading" class="min-h-screen bg-white flex justify-center items-center">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
            <p class="text-gray-500 font-medium">Loading campus details...</p>
        </div>
    </div>
    
    <div v-else-if="campus" class="bg-gray-50 min-h-screen pb-20">
        <!-- Modern Hero Header -->
        <header class="relative h-[60vh] min-h-[500px] overflow-hidden">
            <div class="absolute inset-0">
                <img :src="campus.image" :alt="campus.name" class="w-full h-full object-cover transition-transform duration-1000 scale-105">
                <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-gray-50"></div>
            </div>
            
            <div class="absolute inset-0 flex items-center justify-center text-center">
                <div class="max-w-5xl px-4 sm:px-6 lg:px-8 translate-y-8">
                    <span class="inline-block px-4 py-1 bg-indigo-600/90 backdrop-blur-sm text-white text-xs font-bold uppercase tracking-widest rounded-full mb-6 border border-indigo-400/30">
                        {{ campus.code }}
                    </span>
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 tracking-tight leading-tight drop-shadow-lg">
                        {{ campus.name }}
                    </h1>
                    <div class="flex flex-wrap justify-center items-center gap-6 text-white/90">
                        <div class="flex items-center gap-2 bg-black/30 backdrop-blur-md px-5 py-2 rounded-full border border-white/10">
                            <i class="fas fa-map-marker-alt text-indigo-400"></i>
                            <span class="text-sm font-medium">{{ campus.address }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Breadcrumbs positioned absolutely at top -->
            <nav class="absolute top-32 left-0 w-full z-10" aria-label="Breadcrumb">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <ol class="flex items-center space-x-2 text-sm text-white/70">
                        <li><router-link to="/" class="hover:text-white transition-colors">Home</router-link></li>
                        <li><span class="text-white/40">/</span></li>
                        <li><router-link to="/campuses" class="hover:text-white transition-colors">Campuses</router-link></li>
                        <li><span class="text-white/40">/</span></li>
                        <li class="text-white font-medium truncate">{{ campus.name }}</li>
                    </ol>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-32 relative z-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                <!-- Left Content: Overview & Faculties (8 columns) -->
                <div class="lg:col-span-8 space-y-12">
                    
                    <!-- About Section Card -->
                    <div class="bg-white rounded-3xl p-8 md:p-10 shadow-xl shadow-gray-200/50 border border-white/50 backdrop-blur-sm">
                        <div class="flex items-center mb-8">
                            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mr-4">
                                <i class="fas fa-university text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">About this Campus</h2>
                        </div>
                        <div class="prose prose-lg text-gray-600 max-w-none">
                            <p class="leading-relaxed">
                                {{ campus.description || `Welcome to ${campus.name}, a premier institution dedicated to academic excellence.` }}
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-10 border-t border-gray-100 pt-8">
                            <div class="text-center">
                                <span class="block text-3xl font-bold text-indigo-600 mb-1">{{ campus.faculties.length }}</span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Faculties</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-3xl font-bold text-indigo-600 mb-1">{{ totalDepartments }}</span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Departments</span>
                            </div>
                             <div class="text-center">
                                <span class="block text-3xl font-bold text-indigo-600 mb-1">100+</span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Staff</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-3xl font-bold text-indigo-600 mb-1">24/7</span>
                                <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">Access</span>
                            </div>
                        </div>
                    </div>

                    <!-- Faculties Section -->
                    <div>
                        <div class="flex items-center justify-between mb-8 px-2">
                             <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-indigo-600 rounded-full"></div>
                                <h2 class="text-2xl font-bold text-gray-900">Academic Faculties</h2>
                             </div>
                            <div class="text-gray-500 text-sm font-medium">{{ campus.faculties.length }} Available</div>
                        </div>

                        <div v-if="campus.faculties.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <router-link 
                                v-for="faculty in campus.faculties" 
                                :key="faculty.id"
                                :to="{ name: 'FacultyDetail', params: { slug: faculty.slug || 'faculty-' + faculty.id } }"
                                class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100"
                            >
                                <div class="relative h-48 overflow-hidden">
                                    <img :src="faculty.image" :alt="faculty.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <span class="inline-block px-2 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold rounded mb-2 border border-white/10">
                                            {{ faculty.code }}
                                        </span>
                                        <h3 class="text-xl font-bold text-white leading-tight">
                                            {{ faculty.name }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                        <div class="flex items-center">
                                            <i class="fas fa-layer-group text-indigo-500 mr-2"></i>
                                            {{ faculty.departments_count }} Departments
                                        </div>
                                    </div>
                                    <div class="flex items-center text-indigo-600 font-semibold text-sm group-hover:gap-2 transition-all">
                                        View Departments <i class="fas fa-arrow-right ml-1 opacity-100 transition-all"></i>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                        
                        <div v-else class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-graduation-cap text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">No faculties listed for this campus yet.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Map & Contact (4 columns) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Location Map Card -->
                    <div class="bg-white rounded-3xl p-2 shadow-xl shadow-gray-200/50 border border-white/50 overflow-hidden">
                         <div class="relative h-64 w-full rounded-2xl overflow-hidden bg-gray-100">
                             <div id="campusMap" class="absolute inset-0 z-10" style="min-height: 256px; z-index: 10;"></div>
                             <!-- Fallback/Overlay if no map data -->
                             <div v-if="!hasLocation" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-100 text-gray-400 z-10">
                                 <i class="fas fa-map-marked-alt text-4xl mb-2"></i>
                                 <span>Map location not available</span>
                             </div>
                         </div>
                         <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                <i class="fas fa-location-dot text-red-500 mr-2"></i> Location
                            </h3>
                            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                {{ campus.address }}
                            </p>
                            <a :href="googleMapsLink" target="_blank" class="w-full flex items-center justify-center gap-2 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-sm font-bold transition-colors">
                                <i class="fas fa-external-link-alt"></i> Open in Google Maps
                            </a>
                         </div>
                    </div>

                    <!-- Contact Info Card -->
                    <div class="bg-indigo-900 rounded-3xl p-8 text-white shadow-xl shadow-indigo-900/20 relative overflow-hidden">
                        <!-- Decorative bg shapes -->
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 bg-purple-500 rounded-full opacity-20 blur-3xl"></div>
                        
                        <h3 class="text-xl font-bold mb-6 relative z-10">Contact Campus</h3>
                        
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 mr-4">
                                    <i class="fas fa-phone text-indigo-300"></i>
                                </div>
                                <div>
                                    <span class="block text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Call Us</span>
                                    <p class="font-medium text-lg">{{ campus.phone || 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center shrink-0 mr-4">
                                    <i class="fas fa-envelope text-indigo-300"></i>
                                </div>
                                <div>
                                    <span class="block text-xs text-indigo-300 font-bold uppercase tracking-wider mb-1">Email Us</span>
                                    <p class="font-medium text-lg breaking-all">{{ campus.email || 'info@sourng.edu.kh' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 relative z-10">
                            <p class="text-indigo-200 text-sm mb-4">Connect with us on social media</p>
                            <div class="flex gap-3">
                                <a href="#" class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white hover:scale-110 transition-transform">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-pink-600 flex items-center justify-center text-white hover:scale-110 transition-transform">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-10 h-10 rounded-full bg-sky-500 flex items-center justify-center text-white hover:scale-110 transition-transform">
                                    <i class="fab fa-telegram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div v-else class="min-h-screen flex flex-col items-center justify-center bg-gray-50 px-4">
        <div class="bg-white p-12 rounded-3xl shadow-lg text-center max-w-lg mx-auto">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-red-400 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Campus Not Found</h1>
            <p class="text-gray-500 mb-8">We couldn't find the campus you're looking for. It might have been moved or removed.</p>
            <router-link to="/" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-home mr-2"></i> Back to Home
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch, nextTick } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const campus = ref(null);
const loading = ref(true);
const mapInstance = ref(null);

const totalDepartments = computed(() => {
    if (!campus.value || !campus.value.faculties) return 0;
    return campus.value.faculties.reduce((sum, faculty) => sum + (faculty.departments_count || 0), 0);
});

const hasLocation = computed(() => {
    return campus.value && campus.value.latitude && campus.value.longitude;
});

const googleMapsLink = computed(() => {
    if (!hasLocation.value) return '#';
    return `https://www.google.com/maps/search/?api=1&query=${campus.value.latitude},${campus.value.longitude}`;
});

const initMap = () => {
    if (!hasLocation.value) return;

    // Check for Leaflet availability with retry
    if (typeof L === 'undefined') {
        setTimeout(initMap, 200);
        return;
    }
    
    // Destroy existing map if any to prevent duplicates
    if (mapInstance.value) {
        mapInstance.value.remove();
        mapInstance.value = null;
    }

    const lat = parseFloat(campus.value.latitude);
    const lng = parseFloat(campus.value.longitude);

    if (isNaN(lat) || isNaN(lng)) return;

    // Wait for DOM
    nextTick(() => {
        const mapContainer = document.getElementById('campusMap');
        if (!mapContainer) return;

        // Clear container content to be safe
        // mapContainer.innerHTML = ''; 

        try {
            // Fix icon issues
            delete L.Icon.Default.prototype._getIconUrl;
            L.Icon.Default.mergeOptions({
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            });

            const map = L.map('campusMap', {
                zoomControl: false,
                scrollWheelZoom: false
            }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map)
                .bindPopup(`<b class="text-indigo-600">${campus.value.name}</b><br>${campus.value.address}`)
                .openPopup();
                
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);

            mapInstance.value = map;
            
            // Force redraw to ensure tiles load
            setTimeout(() => {
                map.invalidateSize();
            }, 500);
        } catch (e) {
            console.error("Map init error", e);
        }
    });
};

const fetchCampusDetail = async (slug) => {
    loading.value = true;
    try {
        const response = await axios.get(`/api/frontend/campuses/${slug}`);
        if (response.data.success) {
            campus.value = response.data.data;
            document.title = `${campus.value.name} - Sourng Academy`;
        } else {
            campus.value = null;
        }
    } catch (error) {
        console.error('Error fetching campus detail:', error);
        campus.value = null;
    } finally {
        setTimeout(() => {
            loading.value = false;
            // Initialize map after loading is done and DOM is ready
            if (hasLocation.value) {
                nextTick(() => {
                    initMap();
                });
            }
        }, 500);
    }
};

onMounted(() => {
    fetchCampusDetail(route.params.slug);
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchCampusDetail(newSlug);
});
</script>

<style scoped>
/* Custom Scrollbar for map container if needed */
.prose p {
    margin-bottom: 1.5em;
}
</style>
