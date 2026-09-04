<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Rating from '@/Components/UI/Rating.vue';
import { Course } from '@/Types';
import { DollarSign, Users, BookOpen, Star, TrendingUp } from 'lucide-vue-next';

interface Props {
    courses: Course[];
    stats: {
        total_revenue: number;
        total_enrollments: number;
        total_students: number;
        avg_rating: number;
    };
    monthlyEnrollments: Array<{ month: string; enrollments: number }>;
    ratingDistribution: Array<{ stars: number; count: number; percentage: number }>;
}

defineProps<Props>();
</script>

<template>
    <InstructorLayout title="Analytics & Revenue">
        <Head title="Performance Analytics — Instructor Studio" />

        <div class="space-y-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                    Performance Analytics
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Detailed insights into student enrollment growth, ratings, and course revenues.
                </p>
            </div>

            <!-- KPI Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard
                    title="Gross Revenue"
                    :value="`$${stats.total_revenue}`"
                    variant="success"
                >
                    <template #icon>
                        <DollarSign class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Total Enrollments"
                    :value="stats.total_enrollments"
                    variant="brand"
                >
                    <template #icon>
                        <Users class="w-5 h-5 text-brand-600 dark:text-brand-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Unique Students"
                    :value="stats.total_students"
                    variant="info"
                >
                    <template #icon>
                        <Users class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Average Rating"
                    :value="stats.avg_rating"
                    variant="warning"
                >
                    <template #icon>
                        <Star class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </template>
                </StatCard>
            </div>

            <!-- Split Analytics Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- 1. Monthly Enrollment Growth -->
                <Card>
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                                <TrendingUp class="w-4 h-4 text-brand-600" />
                                <span>Monthly Enrollments (Last 6 Months)</span>
                            </h3>
                        </div>
                    </template>

                    <div class="space-y-4 pt-2">
                        <div
                            v-for="item in monthlyEnrollments"
                            :key="item.month"
                            class="space-y-1.5"
                        >
                            <div class="flex justify-between text-xs font-semibold text-slate-600 dark:text-slate-300">
                                <span>{{ item.month }}</span>
                                <span class="text-brand-600 dark:text-brand-400 font-bold">{{ item.enrollments }} enrollments</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                                <div
                                    class="bg-brand-600 h-2.5 rounded-full transition-all duration-500"
                                    :style="{ width: `${Math.min(100, item.enrollments * 15 + 5)}%` }"
                                />
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- 2. Ratings Distribution -->
                <Card>
                    <template #header>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                            <Star class="w-4 h-4 text-amber-500" />
                            <span>Student Ratings Distribution</span>
                        </h3>
                    </template>

                    <div class="space-y-4 pt-2">
                        <div
                            v-for="dist in ratingDistribution"
                            :key="dist.stars"
                            class="flex items-center gap-3 text-xs"
                        >
                            <div class="w-16 flex items-center gap-1 font-bold text-slate-700 dark:text-slate-300 shrink-0">
                                <span>{{ dist.stars }}</span>
                                <Star class="w-3.5 h-3.5 fill-amber-400 text-amber-400" />
                            </div>
                            <div class="flex-1">
                                <ProgressBar :value="dist.percentage" variant="brand" size="sm" />
                            </div>
                            <div class="w-16 text-right text-slate-400 shrink-0">
                                {{ dist.count }} ({{ dist.percentage }}%)
                            </div>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Course-by-Course Performance Breakdown -->
            <div class="space-y-4">
                <h3 class="font-bold text-base text-slate-900 dark:text-white">
                    Course Performance Metrics
                </h3>

                <Card :padded="false">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-3.5">Course</th>
                                    <th class="px-4 py-3.5">Enrollments</th>
                                    <th class="px-4 py-3.5">Reviews</th>
                                    <th class="px-4 py-3.5">Avg Rating</th>
                                    <th class="px-6 py-3.5 text-right">Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="course in courses" :key="course.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        {{ course.title }}
                                    </td>
                                    <td class="px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                        {{ course.enrollments_count || 0 }}
                                    </td>
                                    <td class="px-4 py-4">
                                        {{ course.reviews_count || 0 }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <Rating :model-value="course.reviews_avg_rating ?? 5.0" size="sm" show-score />
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900 dark:text-white">
                                        {{ Number(course.price) === 0 ? 'Free' : `$${Number(course.price).toFixed(2)}` }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Card>
            </div>
        </div>
    </InstructorLayout>
</template>
