<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Button from '@/Components/UI/Button.vue';
import { Course, User, Enrollment } from '@/Types';
import {
    Users,
    BookOpen,
    DollarSign,
    Award,
    CheckCircle2,
    XCircle,
    Eye,
    Clock,
    Shield,
    AlertCircle,
} from 'lucide-vue-next';

interface Props {
    stats: {
        total_users: number;
        total_students: number;
        total_instructors: number;
        total_courses: number;
        published_courses: number;
        pending_courses: number;
        total_certificates: number;
        total_enrollments: number;
        estimated_revenue: number;
    };
    pendingCourses: Course[];
    recentUsers: User[];
    recentEnrollments: Enrollment[];
}

defineProps<Props>();

const approveCourse = (courseId: number) => {
    router.post(`/admin/courses/${courseId}/approve`);
};

const rejectCourse = (courseId: number) => {
    const reason = prompt('Please enter rejection feedback for the instructor:');
    if (reason !== null) {
        router.post(`/admin/courses/${courseId}/reject`, { reason });
    }
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};
</script>

<template>
    <AdminLayout title="Admin Overview">
        <Head title="Super Admin Dashboard — EduHub" />

        <div class="space-y-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                    Platform Management & Overview
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Monitor system metrics, moderate instructor submissions, and oversee learner accounts.
                </p>
            </div>

            <!-- KPI Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard
                    title="Total Registered Users"
                    :value="stats.total_users"
                    :description="`${stats.total_students} Students • ${stats.total_instructors} Instructors`"
                    variant="brand"
                >
                    <template #icon>
                        <Users class="w-5 h-5 text-brand-600 dark:text-brand-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Platform Courses"
                    :value="stats.total_courses"
                    :description="`${stats.published_courses} Published • ${stats.pending_courses} Pending`"
                    variant="info"
                >
                    <template #icon>
                        <BookOpen class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Gross Platform Sales"
                    :value="`$${stats.estimated_revenue}`"
                    :description="`${stats.total_enrollments} total enrollments`"
                    variant="success"
                >
                    <template #icon>
                        <DollarSign class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Issued Certificates"
                    :value="stats.total_certificates"
                    description="Verified credentials"
                    variant="warning"
                >
                    <template #icon>
                        <Award class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </template>
                </StatCard>
            </div>

            <!-- Pending Review Queue Card -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <AlertCircle class="w-4 h-4 text-amber-500" />
                        <span>Courses Awaiting Moderation ({{ pendingCourses.length }})</span>
                    </h3>

                    <Link
                        href="/admin/courses?status=pending"
                        class="text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline"
                    >
                        View Full Moderation Queue
                    </Link>
                </div>

                <Card :padded="false" v-if="pendingCourses.length > 0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-3.5">Course</th>
                                    <th class="px-4 py-3.5">Instructor</th>
                                    <th class="px-4 py-3.5">Category</th>
                                    <th class="px-4 py-3.5">Lessons</th>
                                    <th class="px-6 py-3.5 text-right">Moderation Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="course in pendingCourses" :key="course.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                        {{ course.title }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <Avatar :src="course.instructor?.avatar" :name="course.instructor?.name" size="xs" />
                                            <span>{{ course.instructor?.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <Badge variant="brand" size="sm">
                                            {{ course.category?.name }}
                                        </Badge>
                                    </td>
                                    <td class="px-4 py-4">
                                        {{ course.lessons_count || 0 }} lessons
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <Link
                                                :href="`/courses/${course.slug}`"
                                                target="_blank"
                                                class="px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 text-xs font-semibold hover:bg-slate-50"
                                            >
                                                Preview
                                            </Link>
                                            <button
                                                type="button"
                                                @click="approveCourse(course.id)"
                                                class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors cursor-pointer"
                                            >
                                                Approve
                                            </button>
                                            <button
                                                type="button"
                                                @click="rejectCourse(course.id)"
                                                class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-colors cursor-pointer"
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Card>

                <div v-else class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 text-center text-xs text-slate-400">
                    No pending course submissions requiring moderation.
                </div>
            </div>

            <!-- Split Section: Recent Registrations & Recent Enrollments -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Registrations -->
                <Card>
                    <template #header>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-base text-slate-900 dark:text-white">Recent Registrations</h3>
                            <Link href="/admin/users" class="text-xs font-bold text-brand-600 hover:underline">All Users</Link>
                        </div>
                    </template>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div v-for="user in recentUsers" :key="user.id" class="py-3 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-3">
                                <Avatar :src="user.avatar" :name="user.name" size="sm" />
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ user.name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ user.email }}</div>
                                </div>
                            </div>

                            <Badge
                                :variant="user.role === 'admin' ? 'brand' : user.role === 'instructor' ? 'warning' : 'neutral'"
                                size="sm"
                                class="capitalize"
                            >
                                {{ user.role }}
                            </Badge>
                        </div>
                    </div>
                </Card>

                <!-- Recent Enrollments -->
                <Card>
                    <template #header>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">Recent Platform Enrollments</h3>
                    </template>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div v-for="enr in recentEnrollments" :key="enr.id" class="py-3 flex items-center justify-between text-xs">
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ enr.user?.name }}</div>
                                <div class="text-[11px] text-brand-600 dark:text-brand-400 truncate max-w-xs">{{ enr.course?.title }}</div>
                            </div>

                            <span class="text-[11px] text-slate-400">
                                {{ formatDate(enr.enrolled_at) }}
                            </span>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>
