<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import CourseCard from '@/Components/Domain/CourseCard.vue';
import CertificateCard from '@/Components/Domain/CertificateCard.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import { Course, Certificate, QuizAttempt } from '@/Types';
import {
    BookOpen,
    CheckCircle2,
    Award,
    Clock,
    PlayCircle,
    ArrowRight,
    HelpCircle,
    GraduationCap,
} from 'lucide-vue-next';

interface Props {
    stats: {
        total_enrolled: number;
        completed_courses: number;
        total_certificates: number;
        learning_hours: number;
    };
    continueCourse?: Course | null;
    enrolledCourses: Course[];
    recommendedCourses: Course[];
    recentQuizzes: QuizAttempt[];
    recentCertificates: Certificate[];
}

defineProps<Props>();

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};
</script>

<template>
    <AppLayout>
        <Head title="Student Learning Dashboard — EduHub" />

        <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Welcome Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                        Welcome back, {{ $page.props.auth.user?.name }}!
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Here is your learning summary and ongoing progress.
                    </p>
                </div>

                <Link
                    href="/courses"
                    class="pill-btn-coral"
                >
                    <BookOpen class="w-4 h-4 mr-1.5" />
                    <span>Browse More Courses</span>
                </Link>
            </div>

            <!-- 1. KPI Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard
                    title="Enrolled Courses"
                    :value="stats.total_enrolled"
                    variant="brand"
                >
                    <template #icon>
                        <BookOpen class="w-5 h-5 text-brand-600 dark:text-brand-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Completed Courses"
                    :value="stats.completed_courses"
                    variant="success"
                >
                    <template #icon>
                        <CheckCircle2 class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Certificates"
                    :value="stats.total_certificates"
                    variant="warning"
                >
                    <template #icon>
                        <Award class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Learning Hours"
                    :value="stats.learning_hours + 'h'"
                    variant="info"
                >
                    <template #icon>
                        <Clock class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                    </template>
                </StatCard>
            </div>

            <!-- 2. Continue Learning Hero Card (Midnight Slate aesthetic) -->
            <div
                v-if="continueCourse"
                class="rounded-3xl bg-gradient-to-br from-midnight-950 via-midnight-900 to-brand-950 text-white p-6 sm:p-8 shadow-xl border border-white/10 relative overflow-hidden"
            >
                <!-- Glowing Orb -->
                <div class="absolute -top-12 -right-12 w-64 h-64 rounded-full bg-brand-500/20 blur-3xl pointer-events-none" />

                <div class="max-w-2xl space-y-4 relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 backdrop-blur-md">
                        Continue Learning
                    </span>

                    <h2 class="text-2xl sm:text-3xl font-extrabold leading-snug text-white">
                        {{ continueCourse.title }}
                    </h2>

                    <div class="space-y-2 max-w-md">
                        <div class="flex justify-between text-xs font-semibold text-slate-300">
                            <span>Course Progress</span>
                            <span class="text-coral-400 font-bold">{{ continueCourse.progress?.progress_percentage || 0 }}% completed</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-2.5 overflow-hidden">
                            <div
                                class="bg-gradient-to-r from-brand-500 to-coral-500 h-2.5 rounded-full transition-all duration-500"
                                :style="{ width: `${continueCourse.progress?.progress_percentage || 0}%` }"
                            />
                        </div>
                    </div>

                    <div class="pt-2 flex items-center gap-4">
                        <Link
                            :href="`/learning/${continueCourse.slug}/${continueCourse.modules?.[0]?.lessons?.[0]?.slug || ''}`"
                            class="pill-btn-coral px-6 py-2.5"
                        >
                            <PlayCircle class="w-4 h-4 mr-1.5" />
                            <span>Resume Course</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- 3. My Courses Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                            My Active Courses
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Track your enrolled tracks and continue studying.
                        </p>
                    </div>

                    <Link
                        href="/my-courses"
                        class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1"
                    >
                        <span>View all</span>
                        <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>

                <div v-if="enrolledCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <CourseCard
                        v-for="course in enrolledCourses"
                        :key="course.id"
                        :course="course"
                        show-progress
                    />
                </div>

                <div
                    v-else
                    class="p-8 rounded-2xl border border-slate-200 dark:border-slate-800 text-center space-y-3"
                >
                    <p class="text-sm text-slate-500">You have not enrolled in any courses yet.</p>
                    <Link
                        href="/courses"
                        class="inline-flex items-center gap-1 text-xs font-bold text-brand-600 hover:underline"
                    >
                        Explore the Course Catalog →
                    </Link>
                </div>
            </div>

            <!-- 4. Split Section: Recent Quiz Attempts & Recent Certificates -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Quizzes -->
                <div class="p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <HelpCircle class="w-4 h-4 text-brand-600" />
                            <span>Recent Quiz Results</span>
                        </h3>
                    </div>

                    <div v-if="recentQuizzes.length > 0" class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div
                            v-for="attempt in recentQuizzes"
                            :key="attempt.id"
                            class="py-3 flex items-center justify-between text-xs"
                        >
                            <div>
                                <div class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ attempt.quiz?.title || 'Course Quiz' }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    {{ formatDate(attempt.created_at || attempt.started_at) }}
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm" :class="attempt.passed ? 'text-emerald-600' : 'text-rose-500'">
                                    {{ attempt.score }}%
                                </span>
                                <Badge :variant="attempt.passed ? 'success' : 'danger'" size="sm">
                                    {{ attempt.passed ? 'Passed' : 'Failed' }}
                                </Badge>
                            </div>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-400 py-4 text-center">
                        No quiz attempts recorded yet.
                    </p>
                </div>

                <!-- Recent Certificates -->
                <div class="p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                            <Award class="w-4 h-4 text-amber-500" />
                            <span>Recent Certificates</span>
                        </h3>

                        <Link
                            href="/certificates"
                            class="text-xs text-brand-600 hover:underline font-bold"
                        >
                            All Certificates
                        </Link>
                    </div>

                    <div v-if="recentCertificates.length > 0" class="space-y-3">
                        <div
                            v-for="cert in recentCertificates"
                            :key="cert.id"
                            class="p-3.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 flex items-center justify-between text-xs"
                        >
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white line-clamp-1">
                                    {{ cert.course?.title }}
                                </div>
                                <div class="font-mono text-[10px] text-slate-400 mt-0.5">
                                    {{ cert.certificate_number }}
                                </div>
                            </div>

                            <Link
                                :href="`/certificates/${cert.id}`"
                                class="px-3 py-1.5 rounded-lg bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-300 font-bold hover:bg-brand-100 transition-colors"
                            >
                                View
                            </Link>
                        </div>
                    </div>

                    <p v-else class="text-xs text-slate-400 py-4 text-center">
                        Complete 100% of a course to unlock your first certificate.
                    </p>
                </div>
            </div>

            <!-- 5. Recommended Courses -->
            <div v-if="recommendedCourses.length > 0" class="space-y-6 pt-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Recommended For You
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Popular courses matching your learning interests.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <CourseCard
                        v-for="course in recommendedCourses"
                        :key="course.id"
                        :course="course"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
