<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

interface LinkItem {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    links: LinkItem[];
    from?: number | null;
    to?: number | null;
    total?: number;
}

withDefaults(defineProps<Props>(), {
    links: () => [],
});
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div v-if="from && to && total" class="text-xs text-slate-500 dark:text-slate-400">
            Showing <span class="font-semibold text-slate-700 dark:text-slate-200">{{ from }}</span> to
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ to }}</span> of
            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ total }}</span> results
        </div>

        <nav class="inline-flex rounded-lg shadow-sm -space-x-px" aria-label="Pagination">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="relative inline-flex items-center px-3.5 py-2 text-xs font-medium text-slate-400 dark:text-slate-600 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 select-none"
                    :class="[
                        key === 0 ? 'rounded-l-lg' : '',
                        key === links.length - 1 ? 'rounded-r-lg' : '',
                    ]"
                    v-html="link.label"
                />

                <Link
                    v-else
                    :href="link.url"
                    preserve-scroll
                    :class="[
                        'relative inline-flex items-center px-3.5 py-2 text-xs font-medium border transition-colors select-none',
                        link.active
                            ? 'z-10 bg-brand-600 border-brand-600 text-white font-bold'
                            : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800',
                        key === 0 ? 'rounded-l-lg' : '',
                        key === links.length - 1 ? 'rounded-r-lg' : '',
                    ]"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>
