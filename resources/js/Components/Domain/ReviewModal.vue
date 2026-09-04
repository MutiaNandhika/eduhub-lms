<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/UI/Modal.vue';
import Button from '@/Components/UI/Button.vue';
import Rating from '@/Components/UI/Rating.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import { Review, Course } from '@/Types';
import { watch } from 'vue';

interface Props {
    show: boolean;
    course: Course;
    existingReview?: Review | null;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const form = useForm({
    rating: 5,
    comment: '',
});

watch(
    () => props.existingReview,
    (rev) => {
        if (rev) {
            form.rating = rev.rating;
            form.comment = rev.comment || '';
        } else {
            form.rating = 5;
            form.comment = '';
        }
    },
    { immediate: true }
);

const submit = () => {
    if (props.existingReview) {
        form.put(`/reviews/${props.existingReview.id}`, {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(`/courses/${props.course.id}/reviews`, {
            onSuccess: () => emit('close'),
        });
    }
};
</script>

<template>
    <Modal :show="show" max-width="lg" @close="emit('close')">
        <template #title>
            {{ existingReview ? 'Edit Your Review' : 'Write a Review' }}
        </template>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                    Your Rating
                </label>
                <div class="flex items-center gap-3">
                    <Rating
                        v-model="form.rating"
                        :readonly="false"
                        size="lg"
                    />
                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">
                        {{ form.rating }} of 5 stars
                    </span>
                </div>
                <p v-if="form.errors.rating" class="text-xs text-rose-500 mt-1">
                    {{ form.errors.rating }}
                </p>
            </div>

            <Textarea
                v-model="form.comment"
                label="Your Feedback"
                placeholder="What did you think of the course content, explanations, and exercises?"
                rows="4"
                :error="form.errors.comment"
                required
            />
        </form>

        <template #footer>
            <Button variant="outline" size="sm" @click="emit('close')">
                Cancel
            </Button>
            <Button
                variant="primary"
                size="sm"
                :loading="form.processing"
                @click="submit"
            >
                {{ existingReview ? 'Update Review' : 'Submit Review' }}
            </Button>
        </template>
    </Modal>
</template>
