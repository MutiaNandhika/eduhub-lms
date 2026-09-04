<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Tabs from '@/Components/UI/Tabs.vue';
import CourseCard from '@/Components/Domain/CourseCard.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Badge from '@/Components/UI/Badge.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { Course } from '@/Types';
import { BookOpen, PlayCircle, Award, CheckCircle2 } from 'lucide-vue-next';

interface Props {
    courses: Course[];
}

const props = defineProps<Props>();

const activeTab = ref<string>('all');

const tabs = computed(() => [
    { id: 'all', label: 'All Courses', count: props.courses.length },
    {
        id: 'in_progress',
        label: 'In Progress',
        count: props.courses.filter((c) => !(c.progress?.is_completed)).length,
    },
    {
        id: 'completed',
        label: 'Completed',
        count: props.courses.filter((c) => c.progress?.is_completed).length,
    },
]);

const filteredCourses = computed(() => {
    if (activeTab.value === 'in_progress') {
        return props.courses.filter((c) => !(c.progress?.is_completed));
    }
    if (activeTab.value === 'completed') {
        return props.courses.filter((c) => c.progress?.is_completed);
    }
    return props.courses;
});
</script>

<template>
    <AppLayout>
        <Head title="My Enrolled Courses — EduHub" />

        <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    My Learning
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Manage and resume your enrolled courses, monitor your progress, and review completed tracks.
                </p>
            </div>

            <!-- Tabs -->
            <Tabs v-model="activeTab" :tabs="tabs" />

            <!-- Courses Grid -->
            <div v-if="filteredCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <CourseCard
                    v-for="course in filteredCourses"
                    :key="course.id"
                    :course="course"
                    show-progress
                />
            </div>

            <div v-else>
                <EmptyState
                    title="No courses found in this tab"
                    description="Explore our course catalog to find your next skill mastery track."
                >
                    <template #icon>
                        <BookOpen class="w-6 h-6" />
                    </template>
                    <template #action>
                        <Link
                            href="/courses"
                            class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition-colors"
                        >
                            Explore Courses
                        </Link>
                    </template>
                </EmptyState>
            </div>
        </div>
    </AppLayout>
</template>
