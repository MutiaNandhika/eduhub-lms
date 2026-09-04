<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import InstructorLayout from '@/Layouts/InstructorLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import Button from '@/Components/UI/Button.vue';
import { Category } from '@/Types';
import { ArrowLeft, Sparkles, PlusCircle } from 'lucide-vue-next';

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

const categoryOptions = props.categories.map((c) => ({
    value: c.id,
    label: c.name,
}));

const levelOptions = [
    { value: 'beginner', label: 'Beginner' },
    { value: 'intermediate', label: 'Intermediate' },
    { value: 'advanced', label: 'Advanced' },
];

const form = useForm({
    title: '',
    category_id: props.categories[0]?.id || '',
    level: 'beginner',
    language: 'English',
    short_description: '',
    description: '',
    thumbnail: '',
    preview_video: '',
    price: 0,
    discount_price: null,
    duration_minutes: 60,
});

const submit = () => {
    form.post('/instructor/courses');
};
</script>

<template>
    <InstructorLayout title="Create Course">
        <Head title="Create New Course — Instructor Studio" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <Link
                    href="/instructor/courses"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span>Back to Courses</span>
                </Link>
            </div>

            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                    Create New Course
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Step 1: Set up your basic course information, difficulty, and media.
                </p>
            </div>

            <Card>
                <form @submit.prevent="submit" class="space-y-6">
                    <Input
                        v-model="form.title"
                        label="Course Title"
                        placeholder="e.g. Master Modern Vue 3 & TypeScript from Scratch"
                        :error="form.errors.title"
                        required
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <Select
                            v-model="form.category_id"
                            label="Category"
                            :options="categoryOptions"
                            :error="form.errors.category_id"
                            required
                        />

                        <Select
                            v-model="form.level"
                            label="Difficulty Level"
                            :options="levelOptions"
                            :error="form.errors.level"
                            required
                        />

                        <Input
                            v-model="form.language"
                            label="Language"
                            placeholder="English"
                            :error="form.errors.language"
                            required
                        />
                    </div>

                    <Textarea
                        v-model="form.short_description"
                        label="Short Description / Tagline"
                        placeholder="A concise 1-2 sentence pitch of what students will achieve in this course."
                        rows="2"
                        :error="form.errors.short_description"
                        required
                    />

                    <Textarea
                        v-model="form.description"
                        label="Full Course Overview & Syllabus Description"
                        placeholder="Detailed course description, prerequisites, and learning objectives..."
                        rows="5"
                        :error="form.errors.description"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input
                            v-model="form.thumbnail"
                            label="Thumbnail Image URL (Optional)"
                            placeholder="https://images.unsplash.com/photo-..."
                            :error="form.errors.thumbnail"
                            hint="A crisp 16:9 cover image for the course card."
                        />

                        <Input
                            v-model="form.preview_video"
                            label="Preview / Intro Video URL (Optional)"
                            placeholder="https://www.youtube.com/watch?v=..."
                            :error="form.errors.preview_video"
                            hint="A public YouTube or Vimeo introductory video."
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <Input
                            v-model="form.price"
                            type="number"
                            label="Standard Price ($)"
                            placeholder="0 for Free"
                            :error="form.errors.price"
                            required
                        />

                        <Input
                            v-model="form.discount_price"
                            type="number"
                            label="Discount Price ($) (Optional)"
                            placeholder="e.g. 19.99"
                            :error="form.errors.discount_price"
                        />

                        <Input
                            v-model="form.duration_minutes"
                            type="number"
                            label="Estimated Total Minutes"
                            placeholder="120"
                            :error="form.errors.duration_minutes"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <Link
                            href="/instructor/courses"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-700"
                        >
                            Cancel
                        </Link>
                        <Button
                            type="submit"
                            variant="primary"
                            size="md"
                            :loading="form.processing"
                        >
                            <span>Save & Proceed to Curriculum Builder</span>
                            <Sparkles class="w-4 h-4 ml-1.5" />
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </InstructorLayout>
</template>
