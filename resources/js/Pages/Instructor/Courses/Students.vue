<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Badge from '@/Components/UI/Badge.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { Course, Enrollment, PaginatedData } from '@/Types';
import { ArrowLeft, Users } from 'lucide-vue-next';

interface Props {
    course: Course;
    enrollments: PaginatedData<Enrollment>;
}

defineProps<Props>();

const formatDate = (dateStr?: string | null) => {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <InstructorLayout :title="`Students: ${course.title}`">
        <Head :title="`Enrolled Students: ${course.title} — EduHub`" />

        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <Link
                    href="/instructor/courses"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span>Back to Courses</span>
                </Link>
            </div>

            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                    Enrolled Students
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Course: <strong class="text-slate-800 dark:text-slate-200">{{ course.title }}</strong> ({{ enrollments.total }} students)
                </p>
            </div>

            <Card :padded="false" v-if="enrollments.data.length > 0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Student</th>
                                <th class="px-4 py-3.5">Enrolled Date</th>
                                <th class="px-4 py-3.5">Learning Progress</th>
                                <th class="px-4 py-3.5">Completed Date</th>
                                <th class="px-6 py-3.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="enr in enrollments.data" :key="enr.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <Avatar :src="enr.user?.avatar" :name="enr.user?.name" size="sm" />
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ enr.user?.name }}</div>
                                            <div class="text-[11px] text-slate-400">{{ enr.user?.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ formatDate(enr.enrolled_at) }}
                                </td>
                                <td class="px-4 py-4 w-48">
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-[11px] font-semibold">
                                            <span>{{ enr.progress?.completed_lessons || 0 }} / {{ enr.progress?.total_lessons || 0 }} lessons</span>
                                            <span class="text-brand-600 dark:text-brand-400 font-bold">{{ enr.progress?.progress_percentage || 0 }}%</span>
                                        </div>
                                        <ProgressBar :value="enr.progress?.progress_percentage || 0" variant="brand" size="xs" />
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ formatDate(enr.completed_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <Badge :variant="enr.status === 'completed' ? 'success' : 'brand'" size="sm" class="capitalize">
                                        {{ enr.status }}
                                    </Badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                    <Pagination
                        :links="enrollments.links"
                        :from="enrollments.from"
                        :to="enrollments.to"
                        :total="enrollments.total"
                    />
                </div>
            </Card>

            <div v-else>
                <EmptyState
                    title="No students enrolled yet"
                    description="When students enroll in this course, their progress and completed assessments will appear here."
                >
                    <template #icon>
                        <Users class="w-6 h-6" />
                    </template>
                </EmptyState>
            </div>
        </div>
    </InstructorLayout>
</template>
