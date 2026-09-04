<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import { DollarSign, Users, BookOpen, Award, TrendingUp, FolderTree } from 'lucide-vue-next';

interface Props {
    stats: {
        total_revenue: number;
        total_users: number;
        total_courses: number;
        total_certificates: number;
    };
    categories: Array<{
        id: number;
        name: string;
        courses_count: number;
        enrollments_count: number;
    }>;
    topInstructors: Array<{
        id: number;
        name: string;
        email: string;
        avatar?: string | null;
        courses_count: number;
        students_count: number;
    }>;
    monthlyData: Array<{
        month: string;
        users: number;
        enrollments: number;
        certificates: number;
    }>;
}

defineProps<Props>();
</script>

<template>
    <AdminLayout title="Platform Analytics">
        <Head title="Platform Analytics — Admin Portal" />

        <div class="space-y-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                    System-Wide Intelligence & Growth
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Platform metrics covering user adoption, revenue performance, and category engagement.
                </p>
            </div>

            <!-- KPI Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard
                    title="Gross Platform Revenue"
                    :value="`$${stats.total_revenue}`"
                    variant="success"
                >
                    <template #icon>
                        <DollarSign class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Total Registered Accounts"
                    :value="stats.total_users"
                    variant="brand"
                >
                    <template #icon>
                        <Users class="w-5 h-5 text-brand-600 dark:text-brand-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Curriculum Courses"
                    :value="stats.total_courses"
                    variant="info"
                >
                    <template #icon>
                        <BookOpen class="w-5 h-5 text-sky-600 dark:text-sky-400" />
                    </template>
                </StatCard>

                <StatCard
                    title="Issued Credentials"
                    :value="stats.total_certificates"
                    variant="warning"
                >
                    <template #icon>
                        <Award class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </template>
                </StatCard>
            </div>

            <!-- Monthly Platform Activity -->
            <Card>
                <template #header>
                    <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                        <TrendingUp class="w-4 h-4 text-brand-600" />
                        <span>Monthly Platform Activity Trends (Last 6 Months)</span>
                    </h3>
                </template>

                <div class="space-y-4 pt-2">
                    <div
                        v-for="item in monthlyData"
                        :key="item.month"
                        class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 space-y-2"
                    >
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                            <span>{{ item.month }}</span>
                            <div class="flex items-center gap-4 text-[11px]">
                                <span class="text-brand-600 dark:text-brand-400 font-semibold">{{ item.users }} new users</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ item.enrollments }} enrollments</span>
                                <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ item.certificates }} certificates</span>
                            </div>
                        </div>

                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden flex">
                            <div class="bg-brand-600 h-2" :style="{ width: `${Math.min(50, item.users * 10)}%` }" />
                            <div class="bg-emerald-500 h-2" :style="{ width: `${Math.min(50, item.enrollments * 8)}%` }" />
                        </div>
                    </div>
                </div>
            </Card>

            <!-- Split: Top Categories & Top Instructors -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Categories -->
                <Card>
                    <template #header>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                            <FolderTree class="w-4 h-4 text-brand-600" />
                            <span>Top Categories by Engagement</span>
                        </h3>
                    </template>

                    <div class="space-y-4 pt-2">
                        <div
                            v-for="cat in categories"
                            :key="cat.id"
                            class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-800 text-xs"
                        >
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white">{{ cat.name }}</div>
                                <span class="text-[11px] text-slate-400">{{ cat.courses_count }} courses</span>
                            </div>
                            <div class="font-bold text-brand-600 dark:text-brand-400">
                                {{ cat.enrollments_count }} enrollments
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Top Instructors -->
                <Card>
                    <template #header>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                            <Users class="w-4 h-4 text-sky-600" />
                            <span>Top Instructors by Student Reach</span>
                        </h3>
                    </template>

                    <div class="space-y-3 pt-2">
                        <div
                            v-for="inst in topInstructors"
                            :key="inst.id"
                            class="flex items-center justify-between p-3 rounded-xl border border-slate-100 dark:border-slate-800 text-xs"
                        >
                            <div class="flex items-center gap-3">
                                <Avatar :src="inst.avatar" :name="inst.name" size="sm" />
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ inst.name }}</div>
                                    <span class="text-[11px] text-slate-400">{{ inst.courses_count }} courses</span>
                                </div>
                            </div>

                            <div class="font-bold text-slate-800 dark:text-slate-200">
                                {{ inst.students_count }} students
                            </div>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>
