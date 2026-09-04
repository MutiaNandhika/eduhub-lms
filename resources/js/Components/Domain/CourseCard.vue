<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Course } from '@/Types';
import Badge from '@/Components/UI/Badge.vue';
import Rating from '@/Components/UI/Rating.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import ProgressBar from '@/Components/UI/ProgressBar.vue';
import { BookOpen, Clock, Users, PlayCircle } from 'lucide-vue-next';

interface Props {
    course: Course;
    showProgress?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showProgress: false,
});

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
    if (mins < 60) return `${mins}m`;
    const hours = Math.floor(mins / 60);
    const remMins = mins % 60;
    return remMins > 0 ? `${hours}h ${remMins}m` : `${hours}h`;
});

const levelVariant = computed(() => {
    switch (props.course.level) {
        case 'beginner': return 'success';
        case 'intermediate': return 'brand';
        case 'advanced': return 'warning';
        default: return 'neutral';
    }
});
</script>

<template>
    <div class="group flex flex-col rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 overflow-hidden shadow-sm hover:shadow-xl hover:border-brand-500/30 dark:hover:border-brand-500/30 transition-all duration-300">
        <!-- Thumbnail Container -->
        <Link :href="`/courses/${course.slug}`" class="relative aspect-video w-full overflow-hidden bg-slate-100 dark:bg-midnight-950 block">
            <img
                v-if="course.thumbnail"
                :src="course.thumbnail"
                :alt="course.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div
                v-else
                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-brand-700 to-midnight-900 text-white"
            >
                <BookOpen class="w-12 h-12 opacity-40 group-hover:scale-110 transition-transform" />
            </div>

            <!-- Category & Level Badges Overlay -->
            <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 pointer-events-none">
                <Badge v-if="course.category" variant="dark" size="sm">
                    {{ course.category.name }}
                </Badge>
                <Badge :variant="levelVariant" size="sm" class="capitalize shadow-sm">
                    {{ course.level }}
                </Badge>
            </div>
        </Link>

        <!-- Content Body -->
        <div class="p-6 flex-1 flex flex-col justify-between">
            <div>
                <!-- Instructor info -->
                <div v-if="course.instructor" class="flex items-center gap-2 mb-3">
                    <Avatar :src="course.instructor.avatar" :name="course.instructor.name" size="xs" />
                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 truncate">
                        {{ course.instructor.name }}
                    </span>
                </div>

                <!-- Course Title -->
                <Link :href="`/courses/${course.slug}`" class="block group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                    <h3 class="font-extrabold text-base text-slate-900 dark:text-white line-clamp-2 leading-snug">
                        {{ course.title }}
                    </h3>
                </Link>

                <p v-if="course.short_description" class="mt-2 text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                    {{ course.short_description }}
                </p>
            </div>

            <!-- Stats & Meta -->
            <div class="mt-5 pt-4 border-t border-slate-100 dark:border-white/5">
                <!-- If showProgress is true (My Courses) -->
                <div v-if="showProgress && course.progress" class="space-y-2 mb-3">
                    <ProgressBar
                        :value="course.progress.progress_percentage"
                        :variant="course.progress.is_completed ? 'success' : 'brand'"
                        show-label
                    />
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>{{ course.progress.completed_lessons }} / {{ course.progress.total_lessons }} lessons</span>
                        <span v-if="course.progress.is_completed" class="text-emerald-600 dark:text-emerald-400 font-bold">Completed</span>
                    </div>
                </div>

                <!-- Standard Meta -->
                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-3">
                    <div class="flex items-center gap-3 font-medium">
                        <span v-if="course.duration_minutes" class="flex items-center gap-1">
                            <Clock class="w-3.5 h-3.5" />
                            {{ formattedDuration }}
                        </span>
                        <span v-if="course.enrollments_count !== undefined" class="flex items-center gap-1">
                            <Users class="w-3.5 h-3.5" />
                            {{ course.enrollments_count }}
                        </span>
                    </div>

                    <!-- Rating -->
                    <Rating
                        :model-value="course.reviews_avg_rating ?? 5.0"
                        :count="course.reviews_count ?? 0"
                        show-score
                        size="sm"
                    />
                </div>

                <!-- Footer Action & Price -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-white/5">
                    <div class="flex items-baseline gap-2">
                        <span class="text-lg font-black text-slate-900 dark:text-white">
                            {{ formattedPrice.current }}
                        </span>
                        <span v-if="formattedPrice.original" class="text-xs text-slate-400 line-through">
                            {{ formattedPrice.original }}
                        </span>
                    </div>

                    <Link
                        :href="`/courses/${course.slug}`"
                        class="inline-flex items-center gap-1 text-xs font-bold text-coral-500 dark:text-coral-400 hover:text-coral-600 transition-colors"
                    >
                        <span>View Details</span>
                        <PlayCircle class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
