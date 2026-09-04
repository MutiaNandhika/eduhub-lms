<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Rating from '@/Components/UI/Rating.vue';
import Button from '@/Components/UI/Button.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import ConfirmDialog from '@/Components/UI/ConfirmDialog.vue';
import { Course, PaginatedData } from '@/Types';
import { PlusCircle, Edit3, Users, Trash2, Eye, BookOpen } from 'lucide-vue-next';

interface Props {
    courses: PaginatedData<Course>;
}

defineProps<Props>();

const courseToDelete = ref<Course | null>(null);
const deleteDialogOpen = ref(false);

const confirmDelete = (course: Course) => {
    courseToDelete.value = course;
    deleteDialogOpen.value = true;
};

const executeDelete = () => {
    if (courseToDelete.value) {
        router.delete(`/instructor/courses/${courseToDelete.value.id}`, {
            onSuccess: () => {
                deleteDialogOpen.value = false;
                courseToDelete.value = null;
            },
        });
    }
};

const statusVariant = (status: string) => {
    switch (status) {
        case 'published': return 'success';
        case 'pending': return 'warning';
        case 'draft': return 'neutral';
        case 'archived': return 'danger';
        default: return 'neutral';
    }
};
</script>

<template>
    <InstructorLayout title="My Courses">
        <Head title="Manage Courses — Instructor Studio" />

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                        Courses Management
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Create, update, manage curriculum, and monitor enrolled learners.
                    </p>
                </div>

                <Link
                    href="/instructor/courses/create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow-sm transition-colors"
                >
                    <PlusCircle class="w-4 h-4" />
                    <span>Create New Course</span>
                </Link>
            </div>

            <!-- Table of Courses -->
            <Card :padded="false" v-if="courses.data.length > 0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Course Title</th>
                                <th class="px-4 py-3.5">Category</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Price</th>
                                <th class="px-4 py-3.5">Enrolled</th>
                                <th class="px-4 py-3.5">Rating</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="course in courses.data" :key="course.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white line-clamp-1 max-w-sm">
                                        {{ course.title }}
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ course.lessons_count || 0 }} lessons</span>
                                </td>
                                <td class="px-4 py-4">
                                    <Badge variant="brand" size="sm">
                                        {{ course.category?.name || 'Uncategorized' }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-4">
                                    <Badge :variant="statusVariant(course.status)" size="sm" class="capitalize">
                                        {{ course.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-4 font-bold text-slate-800 dark:text-slate-200">
                                    {{ Number(course.price) === 0 ? 'Free' : `$${Number(course.price).toFixed(2)}` }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ course.enrollments_count || 0 }}
                                </td>
                                <td class="px-4 py-4">
                                    <Rating :model-value="course.reviews_avg_rating ?? 5.0" size="sm" show-score />
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <Link
                                            :href="`/courses/${course.slug}`"
                                            target="_blank"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                                            title="View Public Page"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>

                                        <Link
                                            :href="`/instructor/courses/${course.id}/students`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-950 transition-colors"
                                            title="View Enrolled Students"
                                        >
                                            <Users class="w-4 h-4" />
                                        </Link>

                                        <Link
                                            :href="`/instructor/courses/${course.id}/edit`"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-950 transition-colors"
                                            title="Course Builder & Edit"
                                        >
                                            <Edit3 class="w-4 h-4" />
                                        </Link>

                                        <button
                                            type="button"
                                            @click="confirmDelete(course)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition-colors cursor-pointer"
                                            title="Delete Course"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                    <Pagination
                        :links="courses.links"
                        :from="courses.from"
                        :to="courses.to"
                        :total="courses.total"
                    />
                </div>
            </Card>

            <div v-else>
                <EmptyState
                    title="No courses created yet"
                    description="Create your first structured course and share your expertise with learners worldwide."
                    action-label="Create Your First Course"
                    @action="router.visit('/instructor/courses/create')"
                >
                    <template #icon>
                        <BookOpen class="w-6 h-6" />
                    </template>
                </EmptyState>
            </div>
        </div>

        <!-- Confirm Delete Dialog -->
        <ConfirmDialog
            :show="deleteDialogOpen"
            title="Delete Course"
            :message="`Are you sure you want to permanently delete '${courseToDelete?.title}'? This will remove all modules and lessons.`"
            confirm-text="Delete Course"
            variant="danger"
            @close="deleteDialogOpen = false"
            @confirm="executeDelete"
        />
    </InstructorLayout>
</template>
