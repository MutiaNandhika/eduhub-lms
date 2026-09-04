<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Rating from '@/Components/UI/Rating.vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { Review, PaginatedData } from '@/Types';
import { Search, Trash2, MessageSquare } from 'lucide-vue-next';

interface Props {
    reviews: PaginatedData<Review>;
    filters: {
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search || '');

const applyFilter = () => {
    router.get('/admin/reviews', { search: search.value || undefined }, { preserveState: true, replace: true });
};

const deleteReview = (rev: Review) => {
    if (confirm(`Remove this review by ${rev.user?.name}?`)) {
        router.delete(`/admin/reviews/${rev.id}`);
    }
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <AdminLayout title="Review Moderation">
        <Head title="Moderate Reviews — Admin Portal" />

        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                    Review Moderation
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Monitor course feedback, manage inappropriate comments, and maintain community guidelines.
                </p>
            </div>

            <Card>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <Input
                            v-model="search"
                            placeholder="Search by student name, course title, or comment text..."
                            @keydown.enter="applyFilter"
                        >
                            <template #prefix>
                                <Search class="w-4 h-4" />
                            </template>
                        </Input>
                    </div>
                    <Button variant="primary" size="md" @click="applyFilter">
                        Search
                    </Button>
                </div>
            </Card>

            <Card :padded="false" v-if="reviews.data.length > 0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Student</th>
                                <th class="px-4 py-3.5">Course</th>
                                <th class="px-4 py-3.5">Rating</th>
                                <th class="px-6 py-3.5">Feedback Comment</th>
                                <th class="px-4 py-3.5">Date</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="rev in reviews.data" :key="rev.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <Avatar :src="rev.user?.avatar" :name="rev.user?.name" size="xs" />
                                        <span class="font-bold text-slate-900 dark:text-white">{{ rev.user?.name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-brand-600 dark:text-brand-400">
                                    {{ rev.course?.title }}
                                </td>
                                <td class="px-4 py-4">
                                    <Rating :model-value="rev.rating" size="sm" />
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300 max-w-sm line-clamp-2">
                                    {{ rev.comment }}
                                </td>
                                <td class="px-4 py-4 text-slate-400">
                                    {{ formatDate(rev.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        type="button"
                                        @click="deleteReview(rev)"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950"
                                        title="Delete Review"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                    <Pagination
                        :links="reviews.links"
                        :from="reviews.from"
                        :to="reviews.to"
                        :total="reviews.total"
                    />
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>
