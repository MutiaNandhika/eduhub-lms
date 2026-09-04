<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Badge from '@/Components/UI/Badge.vue';
import Rating from '@/Components/UI/Rating.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Modal from '@/Components/UI/Modal.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import { Course, PaginatedData } from '@/Types';
import { Search, CheckCircle2, XCircle, Archive, Eye, BookOpen } from 'lucide-vue-next';

interface Props {
    courses: PaginatedData<Course>;
    filters: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const filterState = reactive({
    search: props.filters.search || '',
    status: props.filters.status || 'all',
});

const applyFilters = () => {
    router.get(
        '/admin/courses',
        {
            search: filterState.search || undefined,
            status: filterState.status !== 'all' ? filterState.status : undefined,
        },
        { preserveState: true, replace: true }
    );
};

const approveCourse = (course: Course) => {
    router.post(`/admin/courses/${course.id}/approve`);
};

const archiveCourse = (course: Course) => {
    if (confirm(`Archive course '${course.title}'?`)) {
        router.post(`/admin/courses/${course.id}/archive`);
    }
};

// Rejection Modal
const rejectModalOpen = ref(false);
const rejectingCourse = ref<Course | null>(null);
const rejectionReason = ref('');

const openRejectModal = (course: Course) => {
    rejectingCourse.value = course;
    rejectionReason.value = '';
    rejectModalOpen.value = true;
};

const submitRejection = () => {
    if (rejectingCourse.value) {
        router.post(`/admin/courses/${rejectingCourse.value.id}/reject`, {
            reason: rejectionReason.value,
        }, {
            onSuccess: () => (rejectModalOpen.value = false),
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
    <AdminLayout title="Course Moderation">
        <Head title="Moderate Courses — Admin Portal" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                    Course Moderation & Catalog Management
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Review submissions from instructors, approve quality curriculum, or request revisions.
                </p>
            </div>

            <!-- Filters Bar -->
            <Card>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <Input
                            v-model="filterState.search"
                            placeholder="Search by course title or instructor..."
                            @keydown.enter="applyFilters"
                        >
                            <template #prefix>
                                <Search class="w-4 h-4" />
                            </template>
                        </Input>
                    </div>

                    <div class="w-full sm:w-48">
                        <Select
                            v-model="filterState.status"
                            :options="[
                                { value: 'all', label: 'All Statuses' },
                                { value: 'pending', label: 'Pending Review' },
                                { value: 'published', label: 'Published' },
                                { value: 'draft', label: 'Draft' },
                                { value: 'archived', label: 'Archived' },
                            ]"
                            @change="applyFilters"
                        />
                    </div>

                    <Button variant="primary" size="md" @click="applyFilters">
                        Filter
                    </Button>
                </div>
            </Card>

            <!-- Table -->
            <Card :padded="false" v-if="courses.data.length > 0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Course</th>
                                <th class="px-4 py-3.5">Instructor</th>
                                <th class="px-4 py-3.5">Category</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Students</th>
                                <th class="px-4 py-3.5">Rating</th>
                                <th class="px-6 py-3.5 text-right">Moderation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="course in courses.data" :key="course.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white line-clamp-1 max-w-xs">
                                        {{ course.title }}
                                    </div>
                                    <span class="text-[11px] text-slate-400">{{ course.lessons_count || 0 }} lessons</span>
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
                                    <Badge :variant="statusVariant(course.status)" size="sm" class="capitalize">
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
                                    <div class="inline-flex items-center gap-1.5">
                                        <Link
                                            :href="`/courses/${course.slug}`"
                                            target="_blank"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200"
                                            title="View Public Details"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>

                                        <button
                                            v-if="course.status !== 'published'"
                                            type="button"
                                            @click="approveCourse(course)"
                                            class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs cursor-pointer shadow-sm"
                                        >
                                            Approve
                                        </button>

                                        <button
                                            v-if="course.status === 'pending'"
                                            type="button"
                                            @click="openRejectModal(course)"
                                            class="px-2.5 py-1 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs cursor-pointer shadow-sm"
                                        >
                                            Reject
                                        </button>

                                        <button
                                            v-if="course.status === 'published'"
                                            type="button"
                                            @click="archiveCourse(course)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600"
                                            title="Archive Course"
                                        >
                                            <Archive class="w-4 h-4" />
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
        </div>

        <!-- Reject Course Modal -->
        <Modal :show="rejectModalOpen" max-width="md" @close="rejectModalOpen = false">
            <template #title>
                Request Course Revisions
            </template>
            <div class="space-y-4">
                <p class="text-xs text-slate-500">
                    Provide feedback for {{ rejectingCourse?.instructor?.name }} regarding what needs improvement in '{{ rejectingCourse?.title }}'.
                </p>
                <Textarea
                    v-model="rejectionReason"
                    label="Feedback / Revision Notes"
                    placeholder="e.g. Please add complete video lectures for Module 2 before publication."
                    rows="4"
                    required
                />
            </div>
            <template #footer>
                <Button variant="outline" size="sm" @click="rejectModalOpen = false">Cancel</Button>
                <Button variant="danger" size="sm" @click="submitRejection">Send Feedback & Return to Draft</Button>
            </template>
        </Modal>
    </AdminLayout>
</template>
