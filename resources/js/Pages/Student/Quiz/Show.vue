<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import { Quiz } from '@/Types';
import { ChevronLeft, ChevronRight, CheckCircle2, AlertCircle, ArrowLeft } from 'lucide-vue-next';

interface Props {
    quiz: Quiz;
}

const props = defineProps<Props>();

const currentQuestionIndex = ref(0);

const form = useForm({
    answers: {} as Record<number, number>,
});

const currentQuestion = computed(() => {
    return props.quiz.questions?.[currentQuestionIndex.value];
});

const totalQuestions = computed(() => props.quiz.questions?.length || 0);

const progressPercent = computed(() => {
    if (totalQuestions.value === 0) return 0;
    const answeredCount = Object.keys(form.answers).length;
    return Math.round((answeredCount / totalQuestions.value) * 100);
});

const selectOption = (questionId: number, optionId: number) => {
    form.answers[questionId] = optionId;
};

const nextQuestion = () => {
    if (currentQuestionIndex.value < totalQuestions.value - 1) {
        currentQuestionIndex.value++;
    }
};

const prevQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const submitQuiz = () => {
    form.post(`/quiz/${props.quiz.id}/submit`);
};
</script>

<template>
    <AppLayout>
        <Head :title="`Quiz: ${quiz.title} — EduHub`" />

        <div class="py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header with Back to Course -->
            <div class="flex items-center justify-between">
                <Link
                    v-if="quiz.lesson?.course"
                    :href="`/learning/${quiz.lesson.course.slug}/${quiz.lesson.slug}`"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span>Back to Lesson</span>
                </Link>

                <Badge variant="warning" size="md">
                    Passing Score: {{ quiz.passing_score }}%
                </Badge>
            </div>

            <!-- Quiz Title & Progress Bar -->
            <div class="space-y-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                        {{ quiz.title }}
                    </h1>
                    <p v-if="quiz.description" class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ quiz.description }}
                    </p>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs font-semibold text-slate-500">
                        <span>Answered: {{ Object.keys(form.answers).length }} of {{ totalQuestions }}</span>
                        <span>Question {{ currentQuestionIndex + 1 }} of {{ totalQuestions }}</span>
                    </div>
                    <ProgressBar :value="((currentQuestionIndex + 1) / totalQuestions) * 100" variant="brand" size="sm" />
                </div>
            </div>

            <!-- Question Card -->
            <div
                v-if="currentQuestion"
                class="p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xl space-y-6"
            >
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                        Question {{ currentQuestionIndex + 1 }}
                    </span>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white leading-relaxed">
                        {{ currentQuestion.question }}
                    </h2>
                </div>

                <!-- Options List -->
                <div class="space-y-3">
                    <button
                        v-for="opt in currentQuestion.options"
                        :key="opt.id"
                        type="button"
                        @click="selectOption(currentQuestion.id, opt.id)"
                        :class="[
                            'w-full text-left p-4 rounded-2xl border transition-all flex items-center justify-between text-sm cursor-pointer select-none',
                            form.answers[currentQuestion.id] === opt.id
                                ? 'border-brand-600 bg-brand-50/70 dark:bg-brand-950/40 text-brand-900 dark:text-brand-100 ring-2 ring-brand-500/20'
                                : 'border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-700 dark:text-slate-300',
                        ]"
                    >
                        <span class="font-medium pr-4">{{ opt.option_text }}</span>
                        <div
                            :class="[
                                'w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition-colors',
                                form.answers[currentQuestion.id] === opt.id
                                    ? 'border-brand-600 bg-brand-600 text-white'
                                    : 'border-slate-300 dark:border-slate-700',
                            ]"
                        >
                            <div v-if="form.answers[currentQuestion.id] === opt.id" class="w-2 h-2 rounded-full bg-white" />
                        </div>
                    </button>
                </div>
            </div>

            <!-- Quiz Bottom Navigation -->
            <div class="flex items-center justify-between gap-4 pt-4 border-t border-slate-200/80 dark:border-slate-800/80">
                <Button
                    variant="outline"
                    size="md"
                    :disabled="currentQuestionIndex === 0"
                    @click="prevQuestion"
                >
                    <ChevronLeft class="w-4 h-4" />
                    <span>Previous</span>
                </Button>

                <Button
                    v-if="currentQuestionIndex < totalQuestions - 1"
                    variant="primary"
                    size="md"
                    @click="nextQuestion"
                >
                    <span>Next Question</span>
                    <ChevronRight class="w-4 h-4" />
                </Button>

                <Button
                    v-else
                    variant="success"
                    size="md"
                    :loading="form.processing"
                    @click="submitQuiz"
                >
                    <CheckCircle2 class="w-4 h-4" />
                    <span>Submit Assessment</span>
                </Button>
            </div>
        </div>
    </AppLayout>
</template>
