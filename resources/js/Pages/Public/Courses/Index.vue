<script setup lang="ts">
import { ref, reactive, watch } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CourseCard from '@/Components/Domain/CourseCard.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Button from '@/Components/UI/Button.vue';
import { Course, Category, PaginatedData } from '@/Types';
import { Search, Filter, X, SlidersHorizontal, BookOpen } from 'lucide-vue-next';

interface Props {
    courses: PaginatedData<Course>;
    categories: Category[];
    filters: {
        search?: string;
        category?: string;
        level?: string;
        price?: string;
        rating?: string;
        sort?: string;
    };
}

const props = defineProps<Props>();

const mobileFiltersOpen = ref(false);

const filterState = reactive({
    search: props.filters.search || '',
    category: props.filters.category || 'all',
    level: props.filters.level || 'all',
    price: props.filters.price || 'all',
    sort: props.filters.sort || 'newest',
});

const applyFilters = () => {
    router.get(
        '/courses',
        {
            search: filterState.search || undefined,
            category: filterState.category !== 'all' ? filterState.category : undefined,
            level: filterState.level !== 'all' ? filterState.level : undefined,
            price: filterState.price !== 'all' ? filterState.price : undefined,
            sort: filterState.sort !== 'newest' ? filterState.sort : undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const resetFilters = () => {
    filterState.search = '';
    filterState.category = 'all';
    filterState.level = 'all';
    filterState.price = 'all';
    filterState.sort = 'newest';
    applyFilters();
};

const sortOptions = [
    { value: 'newest', label: 'Newest First' },
    { value: 'popular', label: 'Most Popular' },
    { value: 'highest_rated', label: 'Highest Rated' },
    { value: 'price_low', label: 'Price: Low to High' },
    { value: 'price_high', label: 'Price: High to Low' },
];

const levelOptions = [
    { value: 'all', label: 'All Levels' },
    { value: 'beginner', label: 'Beginner' },
    { value: 'intermediate', label: 'Intermediate' },
    { value: 'advanced', label: 'Advanced' },
];

const priceOptions = [
    { value: 'all', label: 'All Prices' },
    { value: 'free', label: 'Free Only' },
    { value: 'paid', label: 'Paid' },
];
</script>

<template>
    <AppLayout>
        <Head title="Browse Courses — EduHub LMS" />

        <!-- Header Banner -->
        <div class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800/80 py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    Explore Course Catalog
                </h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-2xl">
                    Discover comprehensive courses crafted by industry practitioners. Learn cutting-edge full-stack web, database, cloud, and software engineering skills.
                </p>

                <!-- Search and Mobile Trigger Bar -->
                <div class="mt-6 flex flex-col sm:flex-row items-center gap-3 max-w-3xl">
                    <div class="relative flex-1 w-full">
                        <Input
                            v-model="filterState.search"
                            placeholder="Search by course title, skill, or instructor name..."
                            @keydown.enter="applyFilters"
                        >
                            <template #prefix>
                                <Search class="w-4 h-4" />
                            </template>
                        </Input>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <Button
                            variant="primary"
                            size="md"
                            @click="applyFilters"
                            class="w-full sm:w-auto"
                        >
                            Search
                        </Button>

                        <button
                            type="button"
                            @click="mobileFiltersOpen = true"
                            class="lg:hidden p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center gap-2 text-sm font-semibold"
                        >
                            <SlidersHorizontal class="w-4 h-4" />
                            <span>Filters</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex flex-col lg:flex-row items-start gap-8">
                <!-- Left Sidebar Filters (Desktop) -->
                <aside class="hidden lg:block w-64 shrink-0 space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <Filter class="w-4 h-4 text-brand-600" />
                            <span>Filters</span>
                        </h3>
                        <button
                            type="button"
                            @click="resetFilters"
                            class="text-xs text-brand-600 dark:text-brand-400 hover:underline font-semibold"
                        >
                            Reset
                        </button>
                    </div>

                    <!-- Category Filter -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Category
                        </label>
                        <div class="space-y-1 max-h-60 overflow-y-auto pr-1">
                            <button
                                type="button"
                                @click="filterState.category = 'all'; applyFilters()"
                                :class="[
                                    'w-full text-left px-3 py-2 rounded-xl text-xs transition-colors flex items-center justify-between',
                                    filterState.category === 'all'
                                        ? 'bg-brand-100 text-brand-900 dark:bg-brand-950/80 dark:text-brand-300 font-bold border border-brand-300/60 dark:border-brand-800/60 shadow-sm'
                                        : 'text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-midnight-800/60',
                                ]"
                            >
                                <span>All Categories</span>
                            </button>

                            <button
                                v-for="cat in categories"
                                :key="cat.id"
                                type="button"
                                @click="filterState.category = cat.slug; applyFilters()"
                                :class="[
                                    'w-full text-left px-3 py-2 rounded-xl text-xs transition-colors flex items-center justify-between',
                                    filterState.category === cat.slug
                                        ? 'bg-brand-100 text-brand-900 dark:bg-brand-950/80 dark:text-brand-300 font-bold border border-brand-300/60 dark:border-brand-800/60 shadow-sm'
                                        : 'text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-midnight-800/60',
                                ]"
                            >
                                <span class="truncate">{{ cat.name }}</span>
                                <span class="text-[10px] font-bold opacity-75 bg-slate-200/70 dark:bg-midnight-800 px-1.5 py-0.5 rounded-full">{{ cat.courses_count || 0 }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Level Filter -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Difficulty Level
                        </label>
                        <Select
                            v-model="filterState.level"
                            :options="levelOptions"
                            @change="applyFilters"
                        />
                    </div>

                    <!-- Price Filter -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Pricing
                        </label>
                        <Select
                            v-model="filterState.price"
                            :options="priceOptions"
                            @change="applyFilters"
                        />
                    </div>
                </aside>

                <!-- Right Main Content -->
                <main class="flex-1 min-w-0 w-full space-y-6">
                    <!-- Top Bar: Result count & Sort Dropdown -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200/80 dark:border-slate-800/80">
                        <div class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                            Showing <span class="font-bold text-slate-900 dark:text-white">{{ courses.total }}</span> courses
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 shrink-0">Sort by:</span>
                            <div class="w-48">
                                <Select
                                    v-model="filterState.sort"
                                    :options="sortOptions"
                                    @change="applyFilters"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Courses Grid -->
                    <div v-if="courses.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <CourseCard
                            v-for="course in courses.data"
                            :key="course.id"
                            :course="course"
                        />
                    </div>

                    <!-- Empty State -->
                    <div v-else>
                        <EmptyState
                            title="No matching courses found"
                            description="Try adjusting your search criteria or clearing applied filters to see more results."
                            action-label="Clear All Filters"
                            @action="resetFilters"
                        >
                            <template #icon>
                                <BookOpen class="w-6 h-6" />
                            </template>
                        </EmptyState>
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        <Pagination
                            :links="courses.links"
                            :from="courses.from"
                            :to="courses.to"
                            :total="courses.total"
                        />
                    </div>
                </main>
            </div>
        </div>

        <!-- Mobile Filter Modal / Drawer -->
        <div v-if="mobileFiltersOpen" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm lg:hidden flex justify-end">
            <div class="w-80 bg-white dark:bg-slate-900 h-full p-6 flex flex-col justify-between overflow-y-auto">
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">Filter Courses</h3>
                        <button @click="mobileFiltersOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Category</label>
                            <select
                                v-model="filterState.category"
                                class="w-full rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-sm"
                            >
                                <option value="all">All Categories</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.slug">{{ cat.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Level</label>
                            <Select v-model="filterState.level" :options="levelOptions" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Price</label>
                            <Select v-model="filterState.price" :options="priceOptions" />
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-slate-800 flex gap-3">
                    <Button variant="outline" size="sm" full-width @click="resetFilters(); mobileFiltersOpen = false">
                        Reset
                    </Button>
                    <Button variant="primary" size="sm" full-width @click="applyFilters(); mobileFiltersOpen = false">
                        Apply
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
