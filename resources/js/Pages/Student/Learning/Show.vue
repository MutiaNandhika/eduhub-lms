<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import LearningLayout from '@/Layouts/LearningLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import { Course, Lesson, CourseProgress, Quiz } from '@/Types';
import confetti from 'canvas-confetti';
import {
    PlayCircle,
    FileText,
    HelpCircle,
    Download,
    CheckCircle2,
    Award,
    ExternalLink,
    ChevronRight,
} from 'lucide-vue-next';

interface Props {
    course: Course;
    currentLesson: Lesson;
    quiz?: Quiz | null;
    progress: CourseProgress;
    prevLesson?: Lesson | null;
    nextLesson?: Lesson | null;
    isCompleted?: boolean;
}

const props = defineProps<Props>();

const completionModalOpen = ref(false);

const isVideoLesson = computed(() => props.currentLesson.type === 'video');
const isArticleLesson = computed(() => props.currentLesson.type === 'article');
const isQuizLesson = computed(() => props.currentLesson.type === 'quiz');

// Convert standard YouTube / Vimeo URLs to embed URLs if needed
const embedVideoUrl = computed(() => {
    const url = props.currentLesson.video_url;
    if (!url) return null;

    if (url.includes('youtube.com/watch?v=')) {
        const videoId = url.split('v=')[1]?.split('&')[0];
        return `https://www.youtube.com/embed/${videoId}?autoplay=0&rel=0`;
    }
    if (url.includes('youtu.be/')) {
        const videoId = url.split('youtu.be/')[1]?.split('?')[0];
        return `https://www.youtube.com/embed/${videoId}?autoplay=0&rel=0`;
    }
    if (url.includes('vimeo.com/')) {
        const videoId = url.split('vimeo.com/')[1]?.split('?')[0];
        return `https://player.vimeo.com/video/${videoId}`;
    }

    return url;
});

const toggleComplete = () => {
    router.post(
        `/learning/${props.currentLesson.id}/toggle-complete`,
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (page: any) => {
                const newProgress = page.props.progress as CourseProgress;
                if (newProgress?.is_completed && !props.progress.is_completed) {
                    triggerCelebration();
                }
            },
        }
    );
};

const triggerCelebration = () => {
    completionModalOpen.value = true;
    try {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 },
        });
    } catch (_) {}
};

const handleSelectLesson = (lesson: Lesson) => {
    router.get(`/learning/${props.course.slug}/${lesson.slug}`);
};
</script>

<template>
    <LearningLayout
        :course="course"
        :current-lesson="currentLesson"
        :progress="progress"
        :prev-lesson="prevLesson"
        :next-lesson="nextLesson"
        :is-completed="isCompleted"
        @toggle-complete="toggleComplete"
        @select-lesson="handleSelectLesson"
    >
        <Head :title="`${currentLesson.title} — ${course.title}`" />

        <div class="space-y-8 pb-12">
            <!-- 1. Video Lesson Type -->
            <div v-if="isVideoLesson" class="space-y-6">
                <!-- Video Container -->
                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-2xl border border-slate-800">
                    <iframe
                        v-if="embedVideoUrl"
                        :src="embedVideoUrl"
                        class="w-full h-full"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                    />
                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-500 gap-3">
                        <PlayCircle class="w-16 h-16 opacity-40 text-brand-500" />
                        <span class="text-sm font-medium">Video Player (Sample Lecture Stream)</span>
                    </div>
                </div>

                <!-- Lesson Info & Description -->
                <div class="space-y-4 text-slate-200">
                    <div class="flex items-center gap-3">
                        <Badge variant="brand" size="sm">
                            <PlayCircle class="w-3.5 h-3.5 mr-1" />
                            Video Lecture
                        </Badge>
                        <span v-if="currentLesson.duration_minutes" class="text-xs text-slate-400">
                            {{ currentLesson.duration_minutes }} minutes
                        </span>
                    </div>

                    <h2 class="text-2xl font-black text-white">
                        {{ currentLesson.title }}
                    </h2>

                    <div v-if="currentLesson.content" class="prose dark:prose-invert max-w-none text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                        {{ currentLesson.content }}
                    </div>
                </div>
            </div>

            <!-- 2. Article Lesson Type -->
            <div v-else-if="isArticleLesson" class="space-y-6 bg-slate-950/60 p-6 sm:p-10 rounded-3xl border border-slate-800">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-800">
                    <Badge variant="brand" size="sm">
                        <FileText class="w-3.5 h-3.5 mr-1" />
                        Article Reading
                    </Badge>
                    <span v-if="currentLesson.duration_minutes" class="text-xs text-slate-400">
                        {{ currentLesson.duration_minutes }} min read
                    </span>
                </div>

                <h2 class="text-3xl font-black text-white">
                    {{ currentLesson.title }}
                </h2>

                <div class="prose dark:prose-invert max-w-none text-slate-300 leading-relaxed whitespace-pre-line text-sm sm:text-base">
                    {{ currentLesson.content || 'Article content details for this lesson.' }}
                </div>
            </div>

            <!-- 3. Quiz Lesson Type -->
            <div v-else-if="isQuizLesson" class="space-y-6">
                <div class="p-8 sm:p-12 rounded-3xl border border-slate-800 bg-slate-950 text-center space-y-6 shadow-2xl">
                    <div class="w-16 h-16 rounded-3xl bg-brand-950 border border-brand-800 text-brand-400 flex items-center justify-center mx-auto">
                        <HelpCircle class="w-9 h-9" />
                    </div>

                    <div class="max-w-md mx-auto space-y-2">
                        <Badge variant="warning" size="md">
                            Knowledge Assessment
                        </Badge>
                        <h2 class="text-2xl sm:text-3xl font-black text-white">
                            {{ quiz?.title || currentLesson.title }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400">
                            {{ quiz?.description || 'Test your understanding of the concepts covered in this module.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-8 py-4 border-y border-slate-800 text-xs text-slate-300 max-w-sm mx-auto">
                        <div>
                            <span class="text-slate-500 block">Passing Score</span>
                            <span class="font-bold text-base text-emerald-400">{{ quiz?.passing_score || 70 }}%</span>
                        </div>
                        <div class="w-px h-8 bg-slate-800" />
                        <div>
                            <span class="text-slate-500 block">Questions</span>
                            <span class="font-bold text-base text-white">{{ quiz?.questions?.length || 0 }}</span>
                        </div>
                    </div>

                    <div>
                        <Link
                            v-if="quiz"
                            :href="`/quiz/${quiz.id}`"
                            class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white shadow-lg shadow-brand-600/25 transition-all text-sm"
                        >
                            <span>Start Assessment</span>
                            <ChevronRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Downloadable Lesson Resources -->
            <div v-if="currentLesson.resources && currentLesson.resources.length > 0" class="p-6 rounded-2xl border border-slate-800 bg-slate-950/40 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <Download class="w-4 h-4 text-brand-400" />
                    <span>Downloadable Lesson Materials & Resources</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a
                        v-for="res in currentLesson.resources"
                        :key="res.id"
                        :href="res.file_path"
                        target="_blank"
                        class="p-3 rounded-xl border border-slate-800 bg-slate-900 hover:bg-slate-800 flex items-center justify-between text-xs transition-colors"
                    >
                        <span class="font-semibold text-slate-200 truncate">{{ res.name }}</span>
                        <Download class="w-4 h-4 text-slate-400 shrink-0 ml-2" />
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Completion Celebratory Modal -->
        <Modal :show="completionModalOpen" max-width="md" @close="completionModalOpen = false">
            <div class="text-center p-4 space-y-5">
                <div class="w-16 h-16 rounded-3xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 border border-amber-200 dark:border-amber-800 flex items-center justify-center mx-auto animate-bounce">
                    <Award class="w-9 h-9" />
                </div>

                <div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        Course Completed! 🎉
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                        Outstanding achievement! You have completed 100% of <strong class="text-slate-800 dark:text-slate-200">{{ course.title }}</strong>. Your certificate has been issued.
                    </p>
                </div>

                <div class="pt-2 flex flex-col gap-2.5">
                    <Link
                        href="/certificates"
                        class="w-full py-3 rounded-xl text-xs font-bold bg-brand-600 hover:bg-brand-700 text-white transition-colors"
                    >
                        View & Claim Certificate
                    </Link>
                    <button
                        type="button"
                        @click="completionModalOpen = false"
                        class="w-full py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    >
                        Close
                    </button>
                </div>
            </div>
        </Modal>
    </LearningLayout>
</template>
