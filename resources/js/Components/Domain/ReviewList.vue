<script setup lang="ts">
import { Review } from '@/Types';
import Rating from '@/Components/UI/Rating.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { MessageSquare } from 'lucide-vue-next';

interface Props {
    reviews: Review[];
    averageRating?: number;
    canReview?: boolean;
    userReview?: Review | null;
}

defineProps<Props>();

const emit = defineEmits<{
    (e: 'write-review'): void;
    (e: 'edit-review', review: Review): void;
    (e: 'delete-review', review: Review): void;
}>();

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span>Student Reviews</span>
                    <span class="text-sm font-semibold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                        {{ reviews.length }}
                    </span>
                </h3>
            </div>

            <button
                v-if="canReview && !userReview"
                type="button"
                @click="emit('write-review')"
                class="px-4 py-2 text-xs font-bold rounded-lg bg-brand-600 hover:bg-brand-700 text-white shadow-sm transition-colors cursor-pointer"
            >
                Write a Review
            </button>
        </div>

        <div v-if="reviews.length === 0">
            <EmptyState
                title="No reviews yet"
                description="Be the first enrolled student to review this course after learning!"
            >
                <template #icon>
                    <MessageSquare class="w-6 h-6" />
                </template>
            </EmptyState>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="rev in reviews"
                :key="rev.id"
                class="p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm transition-all"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <Avatar :src="rev.user?.avatar" :name="rev.user?.name" size="sm" />
                        <div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white">
                                {{ rev.user?.name || 'Anonymous Student' }}
                            </h4>
                            <span class="text-xs text-slate-400 dark:text-slate-500">
                                {{ formatDate(rev.created_at) }}
                            </span>
                        </div>
                    </div>

                    <Rating :model-value="rev.rating" size="sm" />
                </div>

                <p v-if="rev.comment" class="mt-3.5 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    {{ rev.comment }}
                </p>

                <!-- If user owns this review -->
                <div v-if="userReview && userReview.id === rev.id" class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-3 text-xs">
                    <button
                        type="button"
                        @click="emit('edit-review', rev)"
                        class="text-brand-600 dark:text-brand-400 hover:underline font-semibold"
                    >
                        Edit
                    </button>
                    <button
                        type="button"
                        @click="emit('delete-review', rev)"
                        class="text-rose-600 hover:underline font-semibold"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
