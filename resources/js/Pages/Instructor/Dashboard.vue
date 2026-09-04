<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Rating from '@/Components/UI/Rating.vue';
import { Course, Enrollment } from '@/Types';
import {
    BookOpen,
    Users,
    DollarSign,
    Star,
    PlusCircle,
    ArrowRight,
    Clock,
    CheckCircle2,
    BarChart3,
} from 'lucide-vue-next';

interface Props {
    stats: {
        total_courses: number;
        published_courses: number;
        pending_courses: number;
        draft_courses: number;
        total_students: number;
        total_enrollments: number;
        estimated_revenue: number;
        average_rating: number;
        total_reviews: number;
    };
    recentEnrollments: Enrollment[];
    topCourses: Course[];
}

defineProps<Props>();

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <InstructorLayout title="Instructor Dashboard">
        <Head title="Instructor Dashboard — EduHub" />

        <div class="space-y-8">
            <!-- Header with Create Course CTA -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                        Teaching Overview
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Track your courses, student enrollments, and estimated performance revenue.
                    </p>
                </div>

                <Link
                    href="/instructor/courses/create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-sm text-xs transition-colors"
                >
                    <PlusCircle class="w-4 h-4" />
                    <span>Create New Course</span>
                </Link>
            </div>

            <!-- 1. KPI Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard
                    title="Total Courses"
                    :value="stats.total_courses"
                    :description="`${stats.published_courses} Published • ${stats.pending_courses} In Review`"
                    variant="brand"
                >
                    <template #icon>
                        <BookOpen class="w-5 h-5 text-brand-600 dark:text-brand-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Total Students"
                    :value="stats.total_students"
                    :description="`${stats.total_enrollments} total enrollments`"
                    variant="info"
                >
                    <template #icon>
                        <Users class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Estimated Revenue"
                    :value="`$${stats.estimated_revenue}`"
                    description="Gross course sales"
                    variant="success"
                >
                    <template #icon>
                        <DollarSign class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Average Rating"
                    :value="stats.average_rating"
                    :description="`From ${stats.total_reviews} reviews`"
                    variant="warning"
                >
                    <template #icon>
                        <Star class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </template>
                </StatCard>
            </div>

            <!-- 2. Split Content: Top Courses & Recent Student Enrollments -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Top Courses Table (2 Cols) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">
                            Top Performing Courses
                        </h3>
                        <Link
                            href="/instructor/courses"
                            class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline"
                        >
                            View All Courses
                        </Link>
                    </div>

                    <Card :padded="false">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                                <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                    <tr>
                                        <th class="px-6 py-3.5">Course</th>
                                        <th class="px-4 py-3.5">Status</th>
                                        <th class="px-4 py-3.5">Students</th>
                                        <th class="px-4 py-3.5">Rating</th>
                                        <th class="px-6 py-3.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr v-for="course in topCourses" :key="course.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900 dark:text-white line-clamp-1 max-w-xs">
                                                {{ course.title }}
                                            </div>
                                            <span class="text-[11px] text-slate-400">{{ course.lessons_count || 0 }} lessons</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <Badge
                                                :variant="course.status === 'published' ? 'success' : course.status === 'pending' ? 'warning' : 'neutral'"
                                                size="sm"
                                                class="capitalize"
                                            >
                                                {{ course.status }}
                                            </Badge>
                                        </td>
                                        <td class="px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                            {{ course.enrollments_count || 0 }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <Rating :model-value="course.reviews_avg_rating ?? 5.0" size="sm" show-score />
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <Link
                                                :href="`/instructor/courses/${course.id}/edit`"
                                                class="text-brand-600 dark:text-brand-400 hover:underline font-bold"
                                            >
                                                Edit
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </Card>
                </div>

                <!-- Recent Enrollments (1 Col) -->
                <div class="lg:col-span-1 space-y-4">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white">
                        Recent Enrollments
                    </h3>

                    <Card>
                        <div v-if="recentEnrollments.length > 0" class="divide-y divide-slate-100 dark:divide-slate-800">
                            <div
                                v-for="enrollment in recentEnrollments"
                                :key="enrollment.id"
                                class="py-3 flex items-start gap-3 text-xs"
                            >
                                <Avatar :src="enrollment.user?.avatar" :name="enrollment.user?.name" size="sm" />
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-slate-900 dark:text-white truncate">
                                        {{ enrollment.user?.name }}
                                    </div>
                                    <div class="text-[11px] text-brand-600 dark:text-brand-400 font-medium truncate">
                                        {{ enrollment.course?.title }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">
                                        {{ formatDate(enrollment.enrolled_at) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p v-else class="text-xs text-slate-400 text-center py-6">
                            No student enrollments yet.
                        </p>
                    </Card>
                </div>
            </div>
        </div>
    </InstructorLayout>
</template>
