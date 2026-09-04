<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CertificateCard from '@/Components/Domain/CertificateCard.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import { Certificate } from '@/Types';
import { Award, BookOpen } from 'lucide-vue-next';

interface Props {
    certificates: Certificate[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="My Issued Certificates — EduHub" />

        <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    My Certificates
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Official digital credentials earned upon 100% completion of structured courses and assessments.
                </p>
            </div>

            <!-- Certificates Grid -->
            <div v-if="certificates.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <CertificateCard
                    v-for="cert in certificates"
                    :key="cert.id"
                    :certificate="cert"
                />
            </div>

            <div v-else>
                <EmptyState
                    title="No certificates earned yet"
                    description="Complete all lessons and pass required quizzes in any course to unlock your first verifiable certificate."
                >
                    <template #icon>
                        <Award class="w-6 h-6" />
                    </template>
                    <template #action>
                        <Link
                            href="/courses"
                            class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition-colors"
                        >
                            Explore Courses
                        </Link>
                    </template>
                </EmptyState>
            </div>
        </div>
    </AppLayout>
</template>
