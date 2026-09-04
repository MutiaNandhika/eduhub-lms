<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import { Quiz, QuizAttempt } from '@/Types';
import {
    CheckCircle2,
    XCircle,
    RotateCcw,
    ArrowRight,
    Award,
    HelpCircle,
} from 'lucide-vue-next';

interface Props {
    quiz: Quiz;
    attempt: QuizAttempt;
}

const props = defineProps<Props>();

const totalQuestions = computed(() => props.attempt.answers?.length || 0);
const correctCount = computed(() => props.attempt.answers?.filter((a) => a.is_correct).length || 0);
</script>

<template>
    <AppLayout>
        <Head :title="`Quiz Results: ${quiz.title} — EduHub`" />

        <div class="py-12 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Result Summary Card -->
            <div
                :class="[
                    'p-8 sm:p-10 rounded-3xl border text-center space-y-6 shadow-2xl relative overflow-hidden',
                    attempt.passed
                        ? 'bg-emerald-50/60 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800'
                        : 'bg-rose-50/60 dark:bg-rose-950/20 border-rose-300 dark:border-rose-800',
                ]"
            >
                <div
                    :class="[
                        'w-16 h-16 rounded-3xl flex items-center justify-center mx-auto',
                        attempt.passed ? 'bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-400',
                    ]"
                >
                    <Award v-if="attempt.passed" class="w-9 h-9" />
                    <XCircle v-else class="w-9 h-9" />
                </div>

                <div class="space-y-2">
                    <Badge :variant="attempt.passed ? 'success' : 'danger'" size="lg">
                        {{ attempt.passed ? 'PASSED' : 'DID NOT PASS' }}
                    </Badge>

                    <h1 class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white">
                        {{ attempt.score }}%
                    </h1>

                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">
                        {{ correctCount }} of {{ totalQuestions }} questions answered correctly
                        (Passing grade is {{ quiz.passing_score }}%)
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    <Link
                        :href="`/quiz/${quiz.id}`"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs transition-colors"
                    >
                        <RotateCcw class="w-4 h-4" />
                        <span>Retry Assessment</span>
                    </Link>

                    <Link
                        v-if="quiz.lesson?.course"
                        :href="`/learning/${quiz.lesson.course.slug}/${quiz.lesson.slug}`"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-bold bg-brand-600 hover:bg-brand-700 text-white text-xs shadow-md transition-colors"
                    >
                        <span>Continue Course</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>
            </div>

            <!-- Detailed Question-by-Question Review -->
            <div class="space-y-6">
                <div class="border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">
                        Question Breakdown & Explanations
                    </h2>
                </div>

                <div class="space-y-6">
                    <div
                        v-for="(ans, index) in attempt.answers"
                        :key="ans.id"
                        class="p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm space-y-4"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Question {{ index + 1 }}
                                </span>
                                <h3 class="font-bold text-base text-slate-900 dark:text-white">
                                    {{ ans.question?.question }}
                                </h3>
                            </div>

                            <Badge :variant="ans.is_correct ? 'success' : 'danger'" size="sm">
                                <CheckCircle2 v-if="ans.is_correct" class="w-3.5 h-3.5 mr-1" />
                                <XCircle v-else class="w-3.5 h-3.5 mr-1" />
                                {{ ans.is_correct ? 'Correct' : 'Incorrect' }}
                            </Badge>
                        </div>

                        <!-- Options breakdown -->
                        <div class="space-y-2 pt-2">
                            <div
                                v-for="opt in ans.question?.options"
                                :key="opt.id"
                                :class="[
                                    'p-3 rounded-xl border text-xs flex items-center justify-between',
                                    opt.is_correct
                                        ? 'border-emerald-300 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-900 dark:text-emerald-200 font-semibold'
                                        : ans.option_id === opt.id
                                            ? 'border-rose-300 dark:border-rose-800 bg-rose-50/50 dark:bg-rose-950/30 text-rose-900 dark:text-rose-200 font-semibold'
                                            : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400',
                                ]"
                            >
                                <span>{{ opt.option_text }}</span>

                                <span v-if="opt.is_correct" class="text-emerald-600 dark:text-emerald-400 font-bold ml-2">
                                    ✓ Correct Answer
                                </span>
                                <span v-else-if="ans.option_id === opt.id" class="text-rose-500 font-bold ml-2">
                                    ✗ Your Choice
                                </span>
                            </div>
                        </div>

                        <!-- Explanation Note -->
                        <div v-if="ans.question?.explanation" class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-300 space-y-1">
                            <strong class="text-slate-800 dark:text-slate-200 block font-bold">Explanation:</strong>
                            <p class="leading-relaxed">{{ ans.question.explanation }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
