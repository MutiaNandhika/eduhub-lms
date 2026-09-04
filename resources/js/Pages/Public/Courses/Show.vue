<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CurriculumAccordion from '@/Components/Domain/CurriculumAccordion.vue';
import ReviewList from '@/Components/Domain/ReviewList.vue';
import ReviewModal from '@/Components/Domain/ReviewModal.vue';
import CourseCard from '@/Components/Domain/CourseCard.vue';
import Badge from '@/Components/UI/Badge.vue';
import Rating from '@/Components/UI/Rating.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Button from '@/Components/UI/Button.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Modal from '@/Components/UI/Modal.vue';
import { Course, CourseProgress, Review, Lesson } from '@/Types';
import {
    Clock,
    Users,
    BookOpen,
    Globe,
    CheckCircle2,
    PlayCircle,
    Award,
    ShieldCheck,
    Share2,
    Lock,
    Eye,
} from 'lucide-vue-next';

interface Props {
    course: Course;
    isEnrolled: boolean;
    progress?: CourseProgress | null;
    userReview?: Review | null;
    canReview: boolean;
    relatedCourses: Course[];
}

const props = defineProps<Props>();

const reviewModalOpen = ref(false);
const previewModalOpen = ref(false);
const previewLesson = ref<Lesson | null>(null);

const enrollForm = useForm({});

const enroll = () => {
    enrollForm.post(`/courses/${props.course.id}/enroll`);
};

const openPreview = (lesson: Lesson) => {
    previewLesson.value = lesson;
    previewModalOpen.value = true;
};

const formattedPrice = computed(() => {
    const priceNum = Number(props.course.price);
    const discountNum = props.course.discount_price ? Number(props.course.discount_price) : null;

    if (priceNum === 0 || discountNum === 0) {
        return { isFree: true, current: 'Free', original: null };
    }

    if (discountNum !== null && discountNum < priceNum) {
        return {
            isFree: false,
            current: `$${discountNum.toFixed(2)}`,
            original: `$${priceNum.toFixed(2)}`,
        };
    }

    return {
        isFree: false,
        current: `$${priceNum.toFixed(2)}`,
        original: null,
    };
});

const formattedDuration = computed(() => {
    const mins = props.course.duration_minutes || 0;
    if (mins < 60) return `${mins} mins`;
    const hours = Math.floor(mins / 60);
    const remMins = mins % 60;
    return remMins > 0 ? `${hours}h ${remMins}m` : `${hours} hours`;
});

const firstLessonSlug = computed(() => {
    return props.course.modules?.[0]?.lessons?.[0]?.slug || '';
});
</script>

<template>
    <AppLayout>
        <Head :title="`${course.title} — EduHub Course`" />

        <!-- Top Hero Section -->
        <div class="bg-slate-900 text-white py-12 lg:py-16 border-b border-slate-800 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                    <!-- Left Column: Course Meta Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span v-if="course.category" class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-300 border border-brand-500/30 backdrop-blur-md">
                                {{ course.category.name }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold capitalize bg-white/10 text-slate-200 border border-white/15 backdrop-blur-md">
                                {{ course.level }} Level
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                            {{ course.title }}
                        </h1>

                        <!-- Short description -->
                        <p class="text-base sm:text-lg text-slate-300 leading-relaxed max-w-3xl">
                            {{ course.short_description }}
                        </p>

                        <!-- Instructor & Rating Bar -->
                        <div class="flex flex-wrap items-center gap-6 pt-2 text-sm text-slate-300">
                            <!-- Instructor -->
                            <div v-if="course.instructor" class="flex items-center gap-2.5">
                                <Avatar :src="course.instructor.avatar" :name="course.instructor.name" size="sm" />
                                <span class="font-medium text-slate-200">Created by <strong class="text-white">{{ course.instructor.name }}</strong></span>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center gap-2">
                                <Rating :model-value="course.reviews_avg_rating ?? 5.0" size="sm" />
                                <span class="font-bold text-white">{{ Number(course.reviews_avg_rating ?? 5.0).toFixed(1) }}</span>
                                <span class="text-slate-400 text-xs">({{ course.reviews_count || 0 }} reviews)</span>
                            </div>

                            <!-- Students Count -->
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-300">
                                <Users class="w-4 h-4 text-brand-400" />
                                <span>{{ course.enrollments_count || 0 }} students enrolled</span>
                            </div>
                        </div>

                        <!-- Meta highlights -->
                        <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-slate-800/80 text-xs text-slate-400">
                            <div class="flex items-center gap-1.5">
                                <Clock class="w-4 h-4 text-slate-400" />
                                <span>{{ formattedDuration }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <BookOpen class="w-4 h-4 text-slate-400" />
                                <span>{{ course.lessons_count || 0 }} lessons</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <Globe class="w-4 h-4 text-slate-400" />
                                <span>{{ course.language }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <Award class="w-4 h-4 text-slate-400" />
                                <span>Certificate of completion</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sticky Enrollment Box -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-2xl text-slate-900 dark:text-white space-y-6">
                            <!-- Thumbnail / Preview Video Image -->
                            <div class="relative aspect-video rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800">
                                <img
                                    v-if="course.thumbnail"
                                    :src="course.thumbnail"
                                    :alt="course.title"
                                    class="w-full h-full object-cover"
                                />
                                <div v-else class="w-full h-full flex items-center justify-center bg-brand-600 text-white font-bold">
                                    EduHub
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="flex items-baseline justify-between">
                                <div>
                                    <span class="text-3xl font-black text-slate-900 dark:text-white">
                                        {{ formattedPrice.current }}
                                    </span>
                                    <span v-if="formattedPrice.original" class="text-sm text-slate-400 line-through ml-2">
                                        {{ formattedPrice.original }}
                                    </span>
                                </div>

                                <Badge v-if="formattedPrice.isFree" variant="success" size="md">
                                    Free Enrollment
                                </Badge>
                            </div>

                            <!-- Primary Action Button -->
                            <div class="space-y-3">
                                <!-- If already enrolled -->
                                <template v-if="isEnrolled">
                                    <div v-if="progress" class="space-y-2 mb-2 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-800">
                                        <div class="flex justify-between text-xs font-bold">
                                            <span>Your Progress</span>
                                            <span class="text-brand-600 dark:text-brand-400">{{ progress.progress_percentage }}%</span>
                                        </div>
                                        <ProgressBar :value="progress.progress_percentage" variant="brand" />
                                    </div>

                                    <Link
                                        :href="`/learning/${course.slug}/${firstLessonSlug}`"
                                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-lg shadow-brand-600/20 transition-all text-center"
                                    >
                                        <PlayCircle class="w-5 h-5" />
                                        <span>Continue Learning</span>
                                    </Link>
                                </template>

                                <!-- If not enrolled -->
                                <template v-else>
                                    <Button
                                        variant="primary"
                                        size="lg"
                                        full-width
                                        :loading="enrollForm.processing"
                                        @click="enroll"
                                    >
                                        Enroll Now
                                    </Button>
                                    <p class="text-[11px] text-center text-slate-400">
                                        Instant full lifetime access to all lessons & quiz assessments
                                    </p>
                                </template>
                            </div>

                            <!-- What's included checklist -->
                            <div class="space-y-2.5 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
                                <div class="flex items-center gap-2.5">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
                                    <span>{{ course.lessons_count || 0 }} structured lessons</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
                                    <span>Interactive quizzes & scoring engine</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
                                    <span>Verifiable completion certificate</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
                                    <span>Access on mobile, tablet, and desktop</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Body Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left 2 Columns: Description, Curriculum, Instructor, Reviews -->
                <div class="lg:col-span-2 space-y-12">
                    <!-- 1. Description -->
                    <section class="space-y-4">
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                            Course Overview
                        </h2>
                        <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                            {{ course.description || course.short_description }}
                        </div>
                    </section>

                    <!-- 2. Curriculum Section -->
                    <section class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                                    Course Syllabus
                                </h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ course.modules?.length || 0 }} modules • {{ course.lessons_count || 0 }} total lessons
                                </p>
                            </div>
                        </div>

                        <CurriculumAccordion
                            :modules="course.modules || []"
                            :completed-lesson-ids="progress?.completed_lesson_ids || []"
                            :is-enrolled="isEnrolled"
                            :allow-navigation="false"
                        />
                    </section>

                    <!-- 3. Instructor Bio -->
                    <section v-if="course.instructor" class="p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            About The Instructor
                        </h3>
                        <div class="flex items-start gap-4">
                            <Avatar :src="course.instructor.avatar" :name="course.instructor.name" size="lg" />
                            <div>
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white">
                                    {{ course.instructor.name }}
                                </h4>
                                <p class="text-xs text-brand-600 dark:text-brand-400 font-semibold mt-0.5">
                                    Verified EduHub Instructor
                                </p>
                                <p v-if="course.instructor.bio" class="mt-3 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                    {{ course.instructor.bio }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <!-- 4. Student Reviews -->
                    <section>
                        <ReviewList
                            :reviews="course.reviews || []"
                            :average-rating="course.reviews_avg_rating"
                            :can-review="canReview"
                            :user-review="userReview"
                            @write-review="reviewModalOpen = true"
                            @edit-review="reviewModalOpen = true"
                        />
                    </section>
                </div>

                <!-- Right Column: Related Courses -->
                <div class="lg:col-span-1 space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                        Related Courses
                    </h3>

                    <div class="space-y-6">
                        <CourseCard
                            v-for="rel in relatedCourses"
                            :key="rel.id"
                            :course="rel"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Modal -->
        <ReviewModal
            :show="reviewModalOpen"
            :course="course"
            :existing-review="userReview"
            @close="reviewModalOpen = false"
        />
    </AppLayout>
</template>
