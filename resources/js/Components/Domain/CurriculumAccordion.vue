<script setup lang="ts">
import { ref } from 'vue';
import { CourseModule, Lesson } from '@/Types';
import { ChevronDown, PlayCircle, FileText, HelpCircle, Lock, CheckCircle2, Eye } from 'lucide-vue-next';
import Badge from '@/Components/UI/Badge.vue';

interface Props {
    modules: CourseModule[];
    completedLessonIds?: number[];
    isEnrolled?: boolean;
    currentLessonId?: number;
    allowNavigation?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    completedLessonIds: () => [],
    isEnrolled: false,
    allowNavigation: false,
});

const emit = defineEmits<{
    (e: 'select-lesson', lesson: Lesson): void;
}>();

// Open all modules by default
const openModules = ref<Record<number, boolean>>(
    props.modules.reduce((acc, mod) => ({ ...acc, [mod.id]: true }), {})
);

const toggleModule = (moduleId: number) => {
    openModules.value[moduleId] = !openModules.value[moduleId];
};

const formatDuration = (mins: number) => {
    if (!mins) return '';
    if (mins < 60) return `${mins}m`;
    const h = Math.floor(mins / 60);
    const m = mins % 60;
    return m > 0 ? `${h}h ${m}m` : `${h}h`;
};
</script>

<template>
    <div class="space-y-3">
        <div
            v-for="(mod, index) in modules"
            :key="mod.id"
            class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden transition-all shadow-sm"
        >
            <!-- Module Header -->
            <button
                type="button"
                @click="toggleModule(mod.id)"
                class="w-full px-5 py-4 flex items-center justify-between bg-slate-50/70 dark:bg-slate-800/40 hover:bg-slate-100/70 dark:hover:bg-slate-800/80 transition-colors text-left select-none"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-6 h-6 rounded-lg bg-brand-100 dark:bg-brand-950 text-brand-700 dark:text-brand-300 text-xs font-bold flex items-center justify-center shrink-0">
                        {{ index + 1 }}
                    </span>
                    <div class="min-w-0">
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate">
                            {{ mod.title }}
                        </h4>
                        <p v-if="mod.description" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">
                            {{ mod.description }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 ml-4">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ mod.lessons?.length || 0 }} lessons
                    </span>
                    <ChevronDown
                        :class="[
                            'w-4 h-4 text-slate-400 transition-transform duration-200',
                            openModules[mod.id] ? 'rotate-180' : '',
                        ]"
                    />
                </div>
            </button>

            <!-- Lessons List -->
            <div v-show="openModules[mod.id]" class="divide-y divide-slate-100 dark:divide-slate-800/60 border-t border-slate-100 dark:border-slate-800">
                <div
                    v-for="lesson in mod.lessons"
                    :key="lesson.id"
                    :class="[
                        'px-5 py-3.5 flex items-center justify-between text-sm transition-colors',
                        currentLessonId === lesson.id
                            ? 'bg-brand-50/70 dark:bg-brand-950/40 border-l-4 border-l-brand-600'
                            : 'hover:bg-slate-50 dark:hover:bg-slate-800/30',
                    ]"
                >
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Type Icon / Complete status -->
                        <div class="shrink-0">
                            <CheckCircle2
                                v-if="completedLessonIds.includes(lesson.id)"
                                class="w-4 h-4 text-emerald-500"
                            />
                            <PlayCircle
                                v-else-if="lesson.type === 'video'"
                                :class="[
                                    'w-4 h-4',
                                    currentLessonId === lesson.id ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500'
                                ]"
                            />
                            <FileText
                                v-else-if="lesson.type === 'article'"
                                :class="[
                                    'w-4 h-4',
                                    currentLessonId === lesson.id ? 'text-brand-600 dark:text-brand-400' : 'text-slate-400 dark:text-slate-500'
                                ]"
                            />
                            <HelpCircle
                                v-else-if="lesson.type === 'quiz'"
                                :class="[
                                    'w-4 h-4',
                                    currentLessonId === lesson.id ? 'text-brand-600 dark:text-brand-400' : 'text-amber-500'
                                ]"
                            />
                        </div>

                        <!-- Title -->
                        <button
                            v-if="allowNavigation && (isEnrolled || lesson.is_preview)"
                            type="button"
                            @click="emit('select-lesson', lesson)"
                            :class="[
                                'text-left truncate font-medium hover:text-brand-600 dark:hover:text-brand-400 transition-colors',
                                currentLessonId === lesson.id ? 'text-brand-700 dark:text-brand-300 font-bold' : 'text-slate-700 dark:text-slate-300',
                            ]"
                        >
                            {{ lesson.title }}
                        </button>
                        <span
                            v-else
                            :class="[
                                'truncate font-medium',
                                currentLessonId === lesson.id ? 'text-brand-700 dark:text-brand-300 font-bold' : 'text-slate-700 dark:text-slate-300',
                            ]"
                        >
                            {{ lesson.title }}
                        </span>
                    </div>

                    <!-- Meta / Badges -->
                    <div class="flex items-center gap-2.5 shrink-0 ml-4">
                        <Badge v-if="lesson.is_preview && !isEnrolled" variant="brand" size="sm">
                            <Eye class="w-3 h-3 mr-1" />
                            Preview
                        </Badge>

                        <span v-if="lesson.duration_minutes" class="text-xs text-slate-400 dark:text-slate-500">
                            {{ formatDuration(lesson.duration_minutes) }}
                        </span>

                        <Lock
                            v-if="!isEnrolled && !lesson.is_preview"
                            class="w-3.5 h-3.5 text-slate-400 dark:text-slate-600"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
