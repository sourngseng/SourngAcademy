<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header section -->
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">Explore Our Programs</h1>
                <p class="mt-4 text-xl text-gray-600 dark:text-gray-400">Find the perfect academic path for your future career.</p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Filters (Desktop) -->
                <aside class="hidden lg:block w-72 shrink-0 space-y-8 sticky top-28 h-fit self-start overflow-y-auto max-h-[80vh] pr-2">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-filter mr-2 text-indigo-500"></i>
                                Filters
                            </h3>
                            <button @click="clearAllFilters" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Clear All</button>
                        </div>

                        <!-- Search -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <div class="relative">
                                <input 
                                    v-model="filters.search" 
                                    @input="handleFilterChange"
                                    type="text" 
                                    placeholder="Program name..." 
                                    class="w-full bg-gray-50 dark:bg-gray-700 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white"
                                >
                                <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Degree Level -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <i class="fas fa-user-graduate mr-2 text-xs text-indigo-500"></i>
                                Degree Level
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                <label v-for="level in availableFilters.degree_levels" :key="level" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" :value="level" v-model="filters.degree_level" @change="handleFilterChange" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ level }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <i class="far fa-clock mr-2 text-xs text-indigo-500"></i>
                                Duration (Years)
                            </label>
                            <div class="space-y-2">
                                <label v-for="year in availableFilters.durations" :key="year" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" :value="year" v-model="filters.duration" @change="handleFilterChange" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ year }} Year(s)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Departments -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <i class="fas fa-university mr-2 text-xs text-indigo-500"></i>
                                Departments
                            </label>
                            <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                                <label v-for="dept in availableFilters.departments" :key="dept.id" class="flex items-center cursor-pointer group">
                                    <input type="checkbox" :value="dept.id" v-model="filters.department_id" @change="handleFilterChange" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ dept.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Mobile Filter Trigger -->
                <div class="lg:hidden mb-6">
                    <button @click="isMobileFiltersOpen = true" class="w-full bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm flex items-center justify-between border border-gray-100 dark:border-gray-700">
                        <span class="flex items-center font-bold text-gray-900 dark:text-white">
                            <i class="fas fa-sliders-h mr-3 text-indigo-500"></i>
                            Filter Programs
                        </span>
                        <span class="text-xs bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 px-2 py-1 rounded-full font-bold">
                            {{ activeFilterCount }} Active
                        </span>
                    </button>
                </div>

                <!-- Main Content -->
                <main class="flex-1">
                    <!-- Loading state: Pulse Skeletons -->
                    <div v-if="loading && programs.length === 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <div v-for="i in 6" :key="i" class="bg-gray-100 dark:bg-gray-800 animate-pulse rounded-2xl h-96"></div>
                    </div>

                    <!-- Programs Grid -->
                    <div v-else-if="programs.length > 0">
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div v-for="program in programs" :key="program.id" 
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                
                                <div class="relative h-52 overflow-hidden">
                                    <img :src="program.image" :alt="program.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent"></div>
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1.5 bg-white/90 dark:bg-black/60 backdrop-blur-md text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-wider rounded-lg shadow-sm">{{ program.degree_level }}</span>
                                    </div>
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <div class="flex items-center justify-between text-white text-[10px] font-bold">
                                            <span class="flex items-center px-2 py-1 bg-black/40 backdrop-blur-sm rounded-md">
                                                <i class="far fa-clock mr-1.5 text-indigo-400"></i>
                                                {{ program.duration_years }} Year(s)
                                            </span>
                                            <span class="flex items-center px-2 py-1 bg-black/40 backdrop-blur-sm rounded-md">
                                                <i class="fas fa-th-list mr-1.5 text-indigo-400"></i>
                                                {{ program.total_credits }} Credits
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="mb-2">
                                        <span v-if="program.department" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ program.department.name }}</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ program.name }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 flex-1 line-clamp-1 h-4">{{ program.description }}</p>
                                    
                                    <div class="pt-4 mt-auto border-t border-gray-50 dark:border-gray-700/50">
                                        <router-link :to="'/programs/' + program.id" class="w-full bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white py-3 px-4 rounded-xl hover:bg-indigo-600 dark:hover:bg-indigo-500 hover:text-white transition duration-300 flex items-center justify-between font-bold text-sm shadow-sm group/btn">
                                            <span>Explore Details</span>
                                            <i class="fas fa-arrow-right transition-transform group-hover/btn:translate-x-1"></i>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Load More Button -->
                        <div class="mt-12 text-center" v-if="hasMore">
                            <button 
                                @click="loadMore" 
                                :disabled="loading"
                                class="inline-flex items-center px-10 py-4 bg-indigo-600 dark:bg-indigo-500 text-white font-bold rounded-2xl hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-300 shadow-xl shadow-indigo-200 dark:shadow-indigo-900/20 disabled:opacity-50"
                            >
                                <span v-if="loading" class="mr-3">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                                <i v-else class="fas fa-plus-circle mr-3"></i>
                                Load More Programs
                            </button>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="w-24 h-24 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-3xl text-indigo-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No programs match your search</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto mb-8">We couldn't find any programs that match your current filters. Try adjusting your selection.</p>
                        <button @click="clearAllFilters" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">Clear All Filters</button>
                    </div>
                </main>
            </div>
        </div>

        <!-- Mobile Filters Modal/Drawer -->
        <Transition name="fade">
            <div v-if="isMobileFiltersOpen" class="fixed inset-0 z-[60] lg:hidden">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="isMobileFiltersOpen = false"></div>
                <Transition name="slide-up">
                    <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-gray-800 rounded-t-3xl p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between mb-8 sticky top-0 bg-white dark:bg-gray-800 z-10 py-2">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filter Programs</h3>
                            <button @click="isMobileFiltersOpen = false" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-full">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="space-y-8 pb-10">
                            <!-- Mobile Search -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Keyword</label>
                                <input v-model="filters.search" @input="handleFilterChange" type="text" placeholder="Search programs..." class="w-full bg-gray-50 dark:bg-gray-700 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-indigo-500">
                            </div>

                            <!-- Mobile Degree Level -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Degree Level</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button 
                                        v-for="level in availableFilters.degree_levels" 
                                        :key="level"
                                        @click="toggleFilter('degree_level', level)"
                                        :class="[filters.degree_level.includes(level) ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300']"
                                        class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                                    >
                                        {{ level }}
                                    </button>
                                </div>
                            </div>

                            <!-- Mobile Duration -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Duration</label>
                                <div class="flex flex-wrap gap-2">
                                    <button 
                                        v-for="year in availableFilters.durations" 
                                        :key="year"
                                        @click="toggleFilter('duration', year)"
                                        :class="[filters.duration.includes(year) ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300']"
                                        class="px-4 py-2 rounded-lg text-sm font-semibold transition"
                                    >
                                        {{ year }} Year(s)
                                    </button>
                                </div>
                            </div>

                            <!-- Mobile Departments -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Departments</label>
                                <div class="space-y-2">
                                    <button 
                                        v-for="dept in availableFilters.departments" 
                                        :key="dept.id"
                                        @click="toggleFilter('department_id', dept.id)"
                                        :class="[filters.department_id.includes(dept.id) ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300']"
                                        class="w-full px-4 py-3 rounded-xl text-left text-sm font-semibold transition flex items-center justify-between"
                                    >
                                        {{ dept.name }}
                                        <i v-if="filters.department_id.includes(dept.id)" class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="sticky bottom-0 bg-white dark:bg-gray-800 pt-4 pb-4 border-t border-gray-100 dark:border-gray-700">
                            <button @click="isMobileFiltersOpen = false" class="w-full py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 dark:shadow-none">
                                Apply & See {{ programs.length }} Programs
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue';

const programs = ref([]);
const loading = ref(true);
const availableFilters = reactive({
    degree_levels: [],
    durations: [],
    departments: []
});

const filters = reactive({
    search: '',
    degree_level: [],
    duration: [],
    department_id: []
});

const offset = ref(0);
const limit = ref(9);
const hasMore = ref(false);
const isMobileFiltersOpen = ref(false);

const activeFilterCount = computed(() => {
    return filters.degree_level.length + filters.duration.length + filters.department_id.length;
});

const fetchPrograms = async (isLoadMore = false) => {
    loading.value = true;
    try {
        const queryParams = new URLSearchParams({
            limit: limit.value,
            offset: isLoadMore ? offset.value : 0,
            search: filters.search
        });

        if (filters.degree_level.length) queryParams.append('degree_level', filters.degree_level.join(','));
        if (filters.duration.length) queryParams.append('duration', filters.duration.join(','));
        if (filters.department_id.length) queryParams.append('department_id', filters.department_id.join(','));

        const response = await fetch(`/api/frontend/programs?${queryParams.toString()}`);
        const result = await response.json();

        if (result.success) {
            if (isLoadMore) {
                programs.value = [...programs.value, ...result.data];
            } else {
                programs.value = result.data;
            }
            hasMore.value = result.meta.has_more;
            offset.value = result.meta.next_offset;
        }
    } catch (error) {
        console.error('Error fetching programs:', error);
    } finally {
        loading.value = false;
    }
};

const fetchFilters = async () => {
    try {
        const response = await fetch('/api/frontend/programs-filters');
        const result = await response.json();
        if (result.success) {
            availableFilters.degree_levels = result.data.degree_levels;
            availableFilters.durations = result.data.durations;
            availableFilters.departments = result.data.departments;
        }
    } catch (error) {
        console.error('Error fetching filters:', error);
    }
};

const handleFilterChange = () => {
    offset.value = 0;
    fetchPrograms();
};

const toggleFilter = (key, value) => {
    const index = filters[key].indexOf(value);
    if (index === -1) {
        filters[key].push(value);
    } else {
        filters[key].splice(index, 1);
    }
    handleFilterChange();
};

const loadMore = () => {
    fetchPrograms(true);
};

const clearAllFilters = () => {
    filters.search = '';
    filters.degree_level = [];
    filters.duration = [];
    filters.department_id = [];
    handleFilterChange();
};

onMounted(() => {
    fetchPrograms();
    fetchFilters();
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.slide-up-enter-active, .slide-up-leave-active {
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-up-enter-from, .slide-up-leave-to {
    transform: translateY(100%);
}
</style>
