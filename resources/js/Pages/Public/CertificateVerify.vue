<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import { Certificate } from '@/Types';
import { Award, CheckCircle2, XCircle, Search, ShieldCheck, Calendar, UserCheck, BookOpen } from 'lucide-vue-next';

interface Props {
    certificate?: Certificate | null;
    searchedNumber: string;
    isValid: boolean;
}

const props = defineProps<Props>();

const searchInput = ref(props.searchedNumber);

const handleSearch = () => {
    if (searchInput.value.trim()) {
        router.get(`/verify/${encodeURIComponent(searchInput.value.trim())}`);
    }
};

const formatDate = (dateStr?: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <AppLayout>
        <Head :title="`Verify Certificate ${searchedNumber} — EduHub`" />

        <div class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Header -->
            <div class="text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-950 text-brand-600 dark:text-brand-400 border border-brand-200 dark:border-brand-800 flex items-center justify-center mx-auto">
                    <Award class="w-8 h-8" />
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">
                    Certificate Verification Registry
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                    Verify the legitimacy and authenticity of any completion certificate issued by the EduHub Learning Platform.
                </p>

                <!-- Search Input Form -->
                <form @submit.prevent="handleSearch" class="mt-6 flex items-center justify-center gap-2 max-w-md mx-auto">
                    <Input
                        v-model="searchInput"
                        placeholder="e.g. EDU-2026-000124"
                    >
                        <template #prefix>
                            <Search class="w-4 h-4" />
                        </template>
                    </Input>
                    <Button type="submit" variant="primary" size="md">
                        Verify
                    </Button>
                </form>
            </div>

            <!-- Valid Certificate Card -->
            <div
                v-if="isValid && certificate"
                class="rounded-3xl border-2 border-emerald-500/40 bg-white dark:bg-slate-900 p-8 sm:p-12 shadow-2xl space-y-8 relative overflow-hidden"
            >
                <div class="absolute top-0 right-0 left-0 h-2 bg-gradient-to-r from-brand-600 via-emerald-500 to-indigo-600" />

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 flex items-center justify-center">
                            <ShieldCheck class="w-7 h-7" />
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider flex items-center gap-1">
                                <CheckCircle2 class="w-3.5 h-3.5" />
                                Authenticated Credential
                            </span>
                            <div class="font-mono text-lg font-black text-slate-900 dark:text-white">
                                {{ certificate.certificate_number }}
                            </div>
                        </div>
                    </div>

                    <Badge variant="success" size="lg">
                        Official Record Valid
                    </Badge>
                </div>

                <div class="space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Awarded To</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                            {{ certificate.user?.name }}
                        </h2>
                    </div>

                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">For Successfully Completing</span>
                        <h3 class="text-xl sm:text-2xl font-bold text-brand-600 dark:text-brand-400 mt-1">
                            {{ certificate.course?.title }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-sm">
                        <div class="flex items-center gap-3">
                            <Avatar :src="certificate.course?.instructor?.avatar" :name="certificate.course?.instructor?.name" size="md" />
                            <div>
                                <span class="text-xs text-slate-400 block">Lead Instructor</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ certificate.course?.instructor?.name }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500">
                                <Calendar class="w-5 h-5" />
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Date Issued</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ formatDate(certificate.issued_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                    <div>Issued securely by EduHub Digital Certification Engine</div>
                    <Link
                        v-if="certificate.course"
                        :href="`/courses/${certificate.course.slug}`"
                        class="text-brand-600 dark:text-brand-400 font-bold hover:underline"
                    >
                        View Course Syllabus →
                    </Link>
                </div>
            </div>

            <!-- Invalid State -->
            <div
                v-else
                class="p-10 rounded-3xl border border-rose-200 dark:border-rose-900 bg-rose-50/50 dark:bg-rose-950/20 text-center space-y-4"
            >
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto">
                    <XCircle class="w-7 h-7" />
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">
                    Certificate Number Not Found
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 max-w-md mx-auto">
                    We could not find any official certificate registered with the identifier <code class="font-bold text-rose-600">{{ searchedNumber }}</code>. Please verify the code and try again.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
