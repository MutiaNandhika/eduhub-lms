<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Tabs from '@/Components/UI/Tabs.vue';
import ConfirmDialog from '@/Components/UI/ConfirmDialog.vue';
import { Course, Category, CourseModule, Lesson, Quiz, QuizQuestion } from '@/Types';
import {
    ArrowLeft,
    Sparkles,
    PlusCircle,
    Layers,
    PlayCircle,
    FileText,
    HelpCircle,
    Edit3,
    Trash2,
    CheckCircle2,
    Send,
    Plus,
    Eye,
} from 'lucide-vue-next';

interface Props {
    course: Course;
    categories: Category[];
}

const props = defineProps<Props>();

const activeTab = ref('curriculum');

const builderTabs = [
    { id: 'curriculum', label: '1. Curriculum & Lessons', icon: Layers },
    { id: 'quizzes', label: '2. Quiz Builder', icon: HelpCircle },
    { id: 'info', label: '3. Info & Media', icon: Edit3 },
    { id: 'pricing', label: '4. Pricing', icon: Sparkles },
    { id: 'publish', label: '5. Publish', icon: Send },
];

const categoryOptions = props.categories.map((c) => ({
    value: c.id,
    label: c.name,
}));

const levelOptions = [
    { value: 'beginner', label: 'Beginner' },
    { value: 'intermediate', label: 'Intermediate' },
    { value: 'advanced', label: 'Advanced' },
];

// Course Info / Pricing Form
const infoForm = useForm({
    title: props.course.title,
    category_id: props.course.category_id,
    level: props.course.level,
    language: props.course.language,
    short_description: props.course.short_description || '',
    description: props.course.description || '',
    thumbnail: props.course.thumbnail || '',
    preview_video: props.course.preview_video || '',
    price: props.course.price,
    discount_price: props.course.discount_price,
    duration_minutes: props.course.duration_minutes,
});

const saveInfo = () => {
    infoForm.put(`/instructor/courses/${props.course.id}`);
};

// Module Modal
const moduleModalOpen = ref(false);
const editingModule = ref<CourseModule | null>(null);
const moduleForm = useForm({
    title: '',
    description: '',
});

const openAddModule = () => {
    editingModule.value = null;
    moduleForm.title = '';
    moduleForm.description = '';
    moduleModalOpen.value = true;
};

const openEditModule = (mod: CourseModule) => {
    editingModule.value = mod;
    moduleForm.title = mod.title;
    moduleForm.description = mod.description || '';
    moduleModalOpen.value = true;
};

const submitModule = () => {
    if (editingModule.value) {
        moduleForm.put(`/instructor/modules/${editingModule.value.id}`, {
            onSuccess: () => (moduleModalOpen.value = false),
        });
    } else {
        moduleForm.post(`/instructor/courses/${props.course.id}/modules`, {
            onSuccess: () => (moduleModalOpen.value = false),
        });
    }
};

const deleteModule = (mod: CourseModule) => {
    if (confirm(`Remove module '${mod.title}' and all its lessons?`)) {
        router.delete(`/instructor/modules/${mod.id}`);
    }
};

// Lesson Modal
const lessonModalOpen = ref(false);
const currentModuleForLesson = ref<CourseModule | null>(null);
const editingLesson = ref<Lesson | null>(null);
const lessonForm = useForm({
    title: '',
    type: 'video' as 'video' | 'article' | 'quiz',
    video_url: '',
    content: '',
    duration_minutes: 10,
    is_preview: false,
});

const openAddLesson = (mod: CourseModule) => {
    currentModuleForLesson.value = mod;
    editingLesson.value = null;
    lessonForm.title = '';
    lessonForm.type = 'video';
    lessonForm.video_url = '';
    lessonForm.content = '';
    lessonForm.duration_minutes = 10;
    lessonForm.is_preview = false;
    lessonModalOpen.value = true;
};

const openEditLesson = (lesson: Lesson) => {
    editingLesson.value = lesson;
    lessonForm.title = lesson.title;
    lessonForm.type = lesson.type;
    lessonForm.video_url = lesson.video_url || '';
    lessonForm.content = lesson.content || '';
    lessonForm.duration_minutes = lesson.duration_minutes || 0;
    lessonForm.is_preview = !!lesson.is_preview;
    lessonModalOpen.value = true;
};

const submitLesson = () => {
    if (editingLesson.value) {
        lessonForm.put(`/instructor/lessons/${editingLesson.value.id}`, {
            onSuccess: () => (lessonModalOpen.value = false),
        });
    } else if (currentModuleForLesson.value) {
        lessonForm.post(`/instructor/modules/${currentModuleForLesson.value.id}/lessons`, {
            onSuccess: () => (lessonModalOpen.value = false),
        });
    }
};

const deleteLesson = (lesson: Lesson) => {
    if (confirm(`Delete lesson '${lesson.title}'?`)) {
        router.delete(`/instructor/lessons/${lesson.id}`);
    }
};

// Quiz Editor State
const quizLessons = computed(() => {
    return props.course.modules?.flatMap((m) => m.lessons || []).filter((l) => l.type === 'quiz') || [];
});

const selectedQuizLessonId = ref<number | null>(quizLessons.value[0]?.id || null);

const currentQuiz = computed(() => {
    const lesson = quizLessons.value.find((l) => l.id === selectedQuizLessonId.value);
    return lesson?.quiz || null;
});

const quizForm = reactive({
    title: '',
    description: '',
    passing_score: 70,
    time_limit_minutes: 15,
    questions: [] as Array<{
        question: string;
        explanation: string;
        options: Array<{ option_text: string; is_correct: boolean }>;
    }>,
});

// Sync quizForm when currentQuiz changes
const syncQuizForm = () => {
    if (currentQuiz.value) {
        quizForm.title = currentQuiz.value.title;
        quizForm.description = currentQuiz.value.description || '';
        quizForm.passing_score = currentQuiz.value.passing_score;
        quizForm.time_limit_minutes = currentQuiz.value.time_limit_minutes || 15;
        quizForm.questions = (currentQuiz.value.questions || []).map((q) => ({
            question: q.question,
            explanation: q.explanation || '',
            options: (q.options || []).map((opt) => ({
                option_text: opt.option_text,
                is_correct: !!opt.is_correct,
            })),
        }));

        if (quizForm.questions.length === 0) {
            addQuestion();
        }
    }
};

const addQuestion = () => {
    quizForm.questions.push({
        question: 'What is the primary concept explained in this lesson?',
        explanation: 'Detailed explanation for why this answer is correct.',
        options: [
            { option_text: 'Correct option answer', is_correct: true },
            { option_text: 'Distractor incorrect option A', is_correct: false },
            { option_text: 'Distractor incorrect option B', is_correct: false },
            { option_text: 'Distractor incorrect option C', is_correct: false },
        ],
    });
};

const removeQuestion = (qIndex: number) => {
    quizForm.questions.splice(qIndex, 1);
};

const setCorrectOption = (qIndex: number, optIndex: number) => {
    quizForm.questions[qIndex].options.forEach((opt, idx) => {
        opt.is_correct = idx === optIndex;
    });
};

const saveQuiz = () => {
    if (!currentQuiz.value) return;
    router.post(`/instructor/quizzes/${currentQuiz.value.id}`, quizForm, {
        preserveScroll: true,
    });
};

const submitCourseForReview = () => {
    router.post(`/instructor/courses/${props.course.id}/submit-review`);
};
</script>

<template>
    <InstructorLayout :title="`Builder: ${course.title}`">
        <Head :title="`Edit Course: ${course.title} — EduHub`" />

        <div class="space-y-6">
            <!-- Header with Status and Preview Link -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <Link
                            href="/instructor/courses"
                            class="p-1 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200"
                        >
                            <ArrowLeft class="w-4 h-4" />
                        </Link>
                        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                            {{ course.title }}
                        </h1>
                        <Badge
                            :variant="course.status === 'published' ? 'success' : course.status === 'pending' ? 'warning' : 'neutral'"
                            size="sm"
                            class="capitalize"
                        >
                            {{ course.status }}
                        </Badge>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <Link
                        :href="`/courses/${course.slug}`"
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 transition-colors"
                    >
                        <Eye class="w-3.5 h-3.5" />
                        <span>Public Preview</span>
                    </Link>

                    <Button
                        v-if="course.status === 'draft'"
                        variant="primary"
                        size="sm"
                        @click="submitCourseForReview"
                    >
                        <Send class="w-3.5 h-3.5" />
                        <span>Submit for Admin Review</span>
                    </Button>
                </div>
            </div>

            <!-- Tabs -->
            <Tabs v-model="activeTab" :tabs="builderTabs" />

            <!-- Tab 1: Curriculum & Lessons Builder -->
            <div v-if="activeTab === 'curriculum'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Course Curriculum</h3>
                        <p class="text-xs text-slate-500">Organize your course into structured modules and lessons.</p>
                    </div>

                    <Button variant="primary" size="sm" @click="openAddModule">
                        <PlusCircle class="w-4 h-4" />
                        <span>Add New Module</span>
                    </Button>
                </div>

                <!-- Modules & Lessons List -->
                <div class="space-y-4">
                    <div
                        v-for="(mod, mIndex) in course.modules"
                        :key="mod.id"
                        class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm space-y-4"
                    >
                        <!-- Module Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 text-xs font-bold flex items-center justify-center">
                                    {{ mIndex + 1 }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 dark:text-white">
                                        {{ mod.title }}
                                    </h4>
                                    <p v-if="mod.description" class="text-xs text-slate-500 line-clamp-1">
                                        {{ mod.description }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="openAddLesson(mod)"
                                    class="px-2.5 py-1.5 rounded-lg bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-300 text-xs font-bold hover:bg-brand-100 flex items-center gap-1 cursor-pointer"
                                >
                                    <Plus class="w-3.5 h-3.5" />
                                    <span>Add Lesson</span>
                                </button>
                                <button
                                    type="button"
                                    @click="openEditModule(mod)"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800"
                                >
                                    <Edit3 class="w-4 h-4" />
                                </button>
                                <button
                                    type="button"
                                    @click="deleteModule(mod)"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <!-- Lessons List within Module -->
                        <div v-if="mod.lessons && mod.lessons.length > 0" class="space-y-2 pl-4">
                            <div
                                v-for="lesson in mod.lessons"
                                :key="lesson.id"
                                class="p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/30 flex items-center justify-between text-xs"
                            >
                                <div class="flex items-center gap-3">
                                    <PlayCircle v-if="lesson.type === 'video'" class="w-4 h-4 text-brand-500" />
                                    <FileText v-else-if="lesson.type === 'article'" class="w-4 h-4 text-emerald-500" />
                                    <HelpCircle v-else class="w-4 h-4 text-amber-500" />

                                    <span class="font-semibold text-slate-800 dark:text-slate-200">
                                        {{ lesson.title }}
                                    </span>

                                    <Badge v-if="lesson.is_preview" variant="brand" size="sm">
                                        Preview
                                    </Badge>

                                    <span class="text-[10px] text-slate-400 capitalize">
                                        {{ lesson.type }} • {{ lesson.duration_minutes }}m
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        @click="openEditLesson(lesson)"
                                        class="text-brand-600 dark:text-brand-400 font-bold hover:underline"
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        @click="deleteLesson(lesson)"
                                        class="text-rose-500 font-bold hover:underline ml-2"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p v-else class="text-xs text-slate-400 py-2 pl-4">
                            No lessons added to this module yet. Click "+ Add Lesson".
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Quiz Builder -->
            <div v-else-if="activeTab === 'quizzes'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Quiz Question Engine</h3>
                        <p class="text-xs text-slate-500">Design multiple-choice assessments and define explanations.</p>
                    </div>

                    <div v-if="quizLessons.length > 0" class="flex items-center gap-3">
                        <select
                            v-model="selectedQuizLessonId"
                            @change="syncQuizForm"
                            class="rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-xs py-2"
                        >
                            <option v-for="ql in quizLessons" :key="ql.id" :value="ql.id">
                                {{ ql.title }}
                            </option>
                        </select>

                        <Button variant="primary" size="sm" @click="saveQuiz">
                            Save Quiz Questions
                        </Button>
                    </div>
                </div>

                <div v-if="quizLessons.length === 0" class="p-8 rounded-2xl border border-slate-200 dark:border-slate-800 text-center text-slate-500 text-xs">
                    No quiz lessons found in curriculum. Go to Step 1 (Curriculum) and add a lesson with Type: Quiz.
                </div>

                <div v-else-if="currentQuiz" class="space-y-6">
                    <!-- Quiz Parameters Card -->
                    <Card>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <Input v-model="quizForm.title" label="Quiz Title" required />
                            <Input v-model="quizForm.passing_score" type="number" label="Passing Score (%)" required />
                            <Input v-model="quizForm.time_limit_minutes" type="number" label="Time Limit (Minutes)" />
                        </div>
                    </Card>

                    <!-- Questions List -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                                Questions ({{ quizForm.questions.length }})
                            </h4>
                            <button
                                type="button"
                                @click="addQuestion"
                                class="px-3 py-1.5 rounded-lg bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 font-bold text-xs flex items-center gap-1 cursor-pointer"
                            >
                                <Plus class="w-3.5 h-3.5" />
                                <span>Add Question</span>
                            </button>
                        </div>

                        <div
                            v-for="(q, qIdx) in quizForm.questions"
                            :key="qIdx"
                            class="p-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4 shadow-sm"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <span class="w-6 h-6 rounded-full bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ qIdx + 1 }}
                                </span>
                                <div class="flex-1">
                                    <Input
                                        v-model="q.question"
                                        label="Question Prompt"
                                        placeholder="e.g. What lifecycle hook runs before DOM mounting?"
                                        required
                                    />
                                </div>
                                <button
                                    type="button"
                                    @click="removeQuestion(qIdx)"
                                    class="text-rose-500 hover:text-rose-700 p-2"
                                    title="Delete Question"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>

                            <!-- Options -->
                            <div class="space-y-2 pl-9">
                                <label class="block text-xs font-bold uppercase text-slate-400">
                                    Answer Options (Select the radio of the correct answer)
                                </label>
                                <div
                                    v-for="(opt, optIdx) in q.options"
                                    :key="optIdx"
                                    class="flex items-center gap-3"
                                >
                                    <input
                                        type="radio"
                                        :name="`correct-${qIdx}`"
                                        :checked="opt.is_correct"
                                        @change="setCorrectOption(qIdx, optIdx)"
                                        class="w-4 h-4 text-brand-600 focus:ring-brand-500"
                                    />
                                    <input
                                        type="text"
                                        v-model="opt.option_text"
                                        class="flex-1 rounded-lg border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-xs py-2 px-3"
                                        placeholder="Option description..."
                                    />
                                    <span v-if="opt.is_correct" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold shrink-0">
                                        Correct Answer
                                    </span>
                                </div>
                            </div>

                            <!-- Explanation -->
                            <div class="pl-9">
                                <Textarea
                                    v-model="q.explanation"
                                    label="Explanation / Rationale (Shown after quiz completion)"
                                    rows="2"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Info & Media -->
            <div v-else-if="activeTab === 'info'" class="space-y-6">
                <Card>
                    <form @submit.prevent="saveInfo" class="space-y-4">
                        <Input v-model="infoForm.title" label="Course Title" required />

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <Select v-model="infoForm.category_id" label="Category" :options="categoryOptions" required />
                            <Select v-model="infoForm.level" label="Difficulty Level" :options="levelOptions" required />
                            <Input v-model="infoForm.language" label="Language" required />
                        </div>

                        <Textarea v-model="infoForm.short_description" label="Short Description" rows="2" required />
                        <Textarea v-model="infoForm.description" label="Full Course Overview" rows="5" />

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <Input v-model="infoForm.thumbnail" label="Thumbnail Image URL" />
                            <Input v-model="infoForm.preview_video" label="Intro Video URL" />
                        </div>

                        <div class="flex justify-end pt-2">
                            <Button type="submit" variant="primary" :loading="infoForm.processing">
                                Save Information
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>

            <!-- Tab 4: Pricing -->
            <div v-else-if="activeTab === 'pricing'" class="space-y-6">
                <Card>
                    <form @submit.prevent="saveInfo" class="space-y-4 max-w-lg">
                        <Input
                            v-model="infoForm.price"
                            type="number"
                            label="Regular Price ($)"
                            placeholder="0 for Free"
                            required
                        />

                        <Input
                            v-model="infoForm.discount_price"
                            type="number"
                            label="Discount Price ($)"
                            placeholder="Optional sale price"
                        />

                        <Input
                            v-model="infoForm.duration_minutes"
                            type="number"
                            label="Estimated Total Minutes"
                        />

                        <div class="flex justify-end pt-2">
                            <Button type="submit" variant="primary" :loading="infoForm.processing">
                                Save Pricing
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>

            <!-- Tab 5: Publish Checklist -->
            <div v-else-if="activeTab === 'publish'" class="space-y-6">
                <Card>
                    <template #header>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Publishing Readiness</h3>
                    </template>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <CheckCircle2 class="w-5 h-5 text-emerald-500" />
                            <span>Course Title and Descriptions provided</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <CheckCircle2 v-if="course.modules?.length" class="w-5 h-5 text-emerald-500" />
                            <span v-else class="text-rose-500">At least one module required</span>
                            <span>{{ course.modules?.length || 0 }} modules configured</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <CheckCircle2 v-if="course.lessons_count" class="w-5 h-5 text-emerald-500" />
                            <span v-else class="text-rose-500">At least one lesson required</span>
                            <span>{{ course.lessons_count || 0 }} lessons created</span>
                        </div>
                    </div>

                    <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                        <Button
                            variant="primary"
                            size="lg"
                            :disabled="!course.lessons_count || course.status !== 'draft'"
                            @click="submitCourseForReview"
                        >
                            <Send class="w-4 h-4" />
                            <span>{{ course.status === 'pending' ? 'Currently In Review' : course.status === 'published' ? 'Course is Live' : 'Submit for Admin Review' }}</span>
                        </Button>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Module Modal -->
        <Modal :show="moduleModalOpen" max-width="md" @close="moduleModalOpen = false">
            <template #title>
                {{ editingModule ? 'Edit Module' : 'Add New Module' }}
            </template>
            <form @submit.prevent="submitModule" class="space-y-4">
                <Input v-model="moduleForm.title" label="Module Title" placeholder="e.g. Module 2: Component Architecture" required />
                <Textarea v-model="moduleForm.description" label="Module Description (Optional)" rows="2" />
            </form>
            <template #footer>
                <Button variant="outline" size="sm" @click="moduleModalOpen = false">Cancel</Button>
                <Button variant="primary" size="sm" :loading="moduleForm.processing" @click="submitModule">Save Module</Button>
            </template>
        </Modal>

        <!-- Lesson Modal -->
        <Modal :show="lessonModalOpen" max-width="xl" @close="lessonModalOpen = false">
            <template #title>
                {{ editingLesson ? 'Edit Lesson' : 'Add New Lesson' }}
            </template>
            <form @submit.prevent="submitLesson" class="space-y-4">
                <Input v-model="lessonForm.title" label="Lesson Title" placeholder="e.g. Understanding Props and Emits" required />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <Select
                        v-model="lessonForm.type"
                        label="Lesson Format"
                        :options="[
                            { value: 'video', label: 'Video Lecture' },
                            { value: 'article', label: 'Article / Reading' },
                            { value: 'quiz', label: 'Quiz Assessment' },
                        ]"
                        required
                    />

                    <Input
                        v-model="lessonForm.duration_minutes"
                        type="number"
                        label="Duration (Minutes)"
                        required
                    />
                </div>

                <Input
                    v-if="lessonForm.type === 'video'"
                    v-model="lessonForm.video_url"
                    label="Video Stream URL"
                    placeholder="https://www.youtube.com/watch?v=..."
                    hint="Accepts YouTube, Vimeo or MP4 stream URLs."
                />

                <Textarea
                    v-model="lessonForm.content"
                    label="Lesson Notes / Article Content (Markdown supported)"
                    placeholder="Write detailed explanations, code blocks, or video lecture notes..."
                    rows="6"
                />

                <Checkbox
                    v-model="lessonForm.is_preview"
                    label="Free Preview Lesson"
                    description="Allow non-enrolled students to watch or read this lesson as a sample."
                />
            </form>
            <template #footer>
                <Button variant="outline" size="sm" @click="lessonModalOpen = false">Cancel</Button>
                <Button variant="primary" size="sm" :loading="lessonForm.processing" @click="submitLesson">Save Lesson</Button>
            </template>
        </Modal>
    </InstructorLayout>
</template>
