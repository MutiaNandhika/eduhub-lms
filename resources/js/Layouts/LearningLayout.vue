<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Course, Lesson, CourseProgress, SharedProps } from '@/Types';
import ThemeToggle from '@/Components/Domain/ThemeToggle.vue';
import CurriculumAccordion from '@/Components/Domain/CurriculumAccordion.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import Toast from '@/Components/UI/Toast.vue';
import { useToast } from '@/Composables/useToast';
import {
    ChevronLeft,
    ChevronRight,
    CheckCircle2,
    Menu,
    X,
    Award,
    BookOpen,
    ArrowLeft,
    PanelLeftClose,
    PanelLeftOpen,
} from 'lucide-vue-next';

interface Props {
    course: Course;
    currentLesson: Lesson;
    progress: CourseProgress;
    prevLesson?: Lesson | null;
    nextLesson?: Lesson | null;
    isCompleted?: boolean;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'toggle-complete'): void;
    (e: 'select-lesson', lesson: Lesson): void;
}>();

const page = usePage<SharedProps>();
const { success, error, warning, info } = useToast();
const sidebarOpen = ref(true);
const mobileDrawerOpen = ref(false);

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) success(flash.success);
        if (flash?.error) error(flash.error);
        if (flash?.warning) warning(flash.warning);
        if (flash?.info) info(flash.info);
    },
    { deep: true, immediate: true }
);

const handleLessonSelect = (lesson: Lesson) => {
    mobileDrawerOpen.value = false;
    emit('select-lesson', lesson);
};
</script>

<template>
    <div class="h-screen flex flex-col bg-midnight-950 text-slate-100 overflow-hidden select-none">
        <Toast />

        <!-- Top Navigation Bar -->
        <header class="h-16 bg-midnight-900 border-b border-white/5 px-4 sm:px-6 flex items-center justify-between shrink-0 z-30">
            <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                <!-- Back to Course Link -->
                <Link
                    :href="`/courses/${course.slug}`"
                    class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors shrink-0"
                    title="Back to Course Details"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>

                <div class="h-6 w-px bg-slate-800 hidden sm:block shrink-0" />

                <!-- Course Title & Lesson Title -->
                <div class="min-w-0">
                    <h1 class="text-xs text-slate-400 font-semibold truncate">
                        {{ course.title }}
                    </h1>
                    <h2 class="text-sm font-bold text-white truncate">
                        {{ currentLesson.title }}
                    </h2>
                </div>
            </div>

            <!-- Right Controls: Progress, Theme, Mobile toggle -->
            <div class="flex items-center gap-4 shrink-0">
                <!-- Course Progress Badge -->
                <div class="hidden sm:flex items-center gap-3 w-44">
                    <div class="flex-1">
                        <ProgressBar
                            :value="progress.progress_percentage"
                            variant="brand"
                            size="sm"
                        />
                    </div>
                    <span class="text-xs font-bold text-brand-400 shrink-0">
                        {{ progress.progress_percentage }}%
                    </span>
                </div>

                <ThemeToggle />

                <!-- Toggle Sidebar Button (Desktop) -->
                <button
                    type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="hidden lg:flex p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                    :title="sidebarOpen ? 'Collapse Curriculum' : 'Expand Curriculum'"
                >
                    <PanelLeftClose v-if="sidebarOpen" class="w-5 h-5" />
                    <PanelLeftOpen v-else class="w-5 h-5" />
                </button>

                <!-- Mobile Curriculum Drawer Button -->
                <button
                    type="button"
                    @click="mobileDrawerOpen = true"
                    class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800"
                >
                    <Menu class="w-5 h-5" />
                </button>
            </div>
        </header>

        <!-- Main Body: Split Sidebar and Learning Arena -->
        <div class="flex-1 flex min-h-0 relative">
            <!-- Left: Curriculum Sidebar (Desktop) -->
            <aside
                v-show="sidebarOpen"
                class="hidden lg:flex flex-col w-80 xl:w-96 bg-slate-950 border-r border-slate-800/80 overflow-y-auto shrink-0 select-text"
            >
                <div class="p-4 border-b border-slate-800 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Course Syllabus</span>
                    <span class="text-xs font-semibold text-slate-500">
                        {{ progress.completed_lessons }} / {{ progress.total_lessons }} completed
                    </span>
                </div>

                <div class="p-4 flex-1">
                    <CurriculumAccordion
                        :modules="course.modules || []"
                        :completed-lesson-ids="progress.completed_lesson_ids"
                        :current-lesson-id="currentLesson.id"
                        :is-enrolled="true"
                        :allow-navigation="true"
                        @select-lesson="handleLessonSelect"
                    />
                </div>
            </aside>

            <!-- Mobile Syllabus Drawer -->
            <div
                v-if="mobileDrawerOpen"
                @click="mobileDrawerOpen = false"
                class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm lg:hidden"
            />
            <aside
                :class="[
                    'fixed inset-y-0 right-0 z-50 w-80 sm:w-96 bg-slate-950 text-white border-l border-slate-800 flex flex-col justify-between transition-transform duration-200 lg:hidden',
                    mobileDrawerOpen ? 'translate-x-0' : 'translate-x-full',
                ]"
            >
                <div class="h-16 px-5 border-b border-slate-800 flex items-center justify-between">
                    <span class="font-bold text-sm">Course Content</span>
                    <button @click="mobileDrawerOpen = false" class="p-1 rounded-lg text-slate-400 hover:text-white">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-4 flex-1 overflow-y-auto">
                    <CurriculumAccordion
                        :modules="course.modules || []"
                        :completed-lesson-ids="progress.completed_lesson_ids"
                        :current-lesson-id="currentLesson.id"
                        :is-enrolled="true"
                        :allow-navigation="true"
                        @select-lesson="handleLessonSelect"
                    />
                </div>
            </aside>

            <!-- Right: Learning Player & Content Area -->
            <main class="flex-1 flex flex-col min-w-0 bg-slate-900 overflow-y-auto select-text">
                <div class="flex-1 p-4 sm:p-8 max-w-5xl w-full mx-auto">
                    <slot />
                </div>

                <!-- Bottom Sticky Player Navigation Bar -->
                <footer class="sticky bottom-0 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 px-4 sm:px-8 py-3.5 flex items-center justify-between gap-4 z-20 shrink-0">
                    <!-- Prev Button -->
                    <Link
                        v-if="prevLesson"
                        :href="`/learning/${course.slug}/${prevLesson.slug}`"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-800 hover:bg-slate-700 text-slate-200 transition-colors"
                    >
                        <ChevronLeft class="w-4 h-4" />
                        <span class="hidden sm:inline">Previous Lesson</span>
                        <span class="sm:hidden">Prev</span>
                    </Link>
                    <div v-else class="w-20" />

                    <!-- Mark Complete Toggle Button -->
                    <button
                        type="button"
                        @click="emit('toggle-complete')"
                        :class="[
                            'inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer',
                            isCompleted
                                ? 'bg-emerald-600 hover:bg-emerald-700 text-white'
                                : 'bg-brand-600 hover:bg-brand-700 text-white shadow-brand-600/20',
                        ]"
                    >
                        <CheckCircle2 class="w-4 h-4" />
                        <span>{{ isCompleted ? 'Completed' : 'Mark as Complete' }}</span>
                    </button>

                    <!-- Next Button -->
                    <Link
                        v-if="nextLesson"
                        :href="`/learning/${course.slug}/${nextLesson.slug}`"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-colors"
                    >
                        <span class="hidden sm:inline">Next Lesson</span>
                        <span class="sm:hidden">Next</span>
                        <ChevronRight class="w-4 h-4" />
                    </Link>

                    <Link
                        v-else-if="progress.is_completed"
                        :href="`/certificates`"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-sm transition-colors"
                    >
                        <Award class="w-4 h-4" />
                        <span>Claim Certificate</span>
                    </Link>
                    <div v-else class="w-20" />
                </footer>
            </main>
        </div>
    </div>
</template>
