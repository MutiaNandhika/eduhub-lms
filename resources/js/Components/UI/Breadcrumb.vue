<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight, Home } from 'lucide-vue-next';

interface BreadcrumbItem {
    label: string;
    href?: string;
}

interface Props {
    items: BreadcrumbItem[];
}

defineProps<Props>();
</script>

<template>
    <nav class="flex items-center text-xs sm:text-sm text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1.5 md:space-x-2">
            <li class="inline-flex items-center">
                <Link
                    href="/"
                    class="inline-flex items-center text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 transition-colors"
                >
                    <Home class="w-3.5 h-3.5 mr-1" />
                    <span>Home</span>
                </Link>
            </li>

            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
                <ChevronRight class="w-3.5 h-3.5 text-slate-400 dark:text-slate-600 mx-1" />
                <Link
                    v-if="item.href && index < items.length - 1"
                    :href="item.href"
                    class="text-slate-500 hover:text-brand-600 dark:text-slate-400 dark:hover:text-brand-400 transition-colors"
                >
                    {{ item.label }}
                </Link>
                <span v-else class="font-medium text-slate-800 dark:text-slate-200">
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>
