<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/Domain/ThemeToggle.vue';
import Toast from '@/Components/UI/Toast.vue';
import Logo from '@/Components/UI/Logo.vue';
import { CheckCircle2, ShieldCheck, Award } from 'lucide-vue-next';

interface Props {
    title?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-[#f8faf9] dark:bg-[#08131a] text-slate-800 dark:text-slate-100 transition-colors">
        <Toast />

        <!-- Left Column: Marketing / Showcase -->
        <div class="hidden lg:flex flex-col justify-between p-12 bg-gradient-to-br from-midnight-950 via-midnight-900 to-brand-950 text-white relative overflow-hidden border-r border-white/5">
            <!-- Background circles -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-brand-600/15 blur-3xl pointer-events-none" />
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-coral-500/10 blur-3xl pointer-events-none" />

            <div class="relative z-10 flex items-center justify-between">
                <Logo size="lg" variant="light" />
            </div>

            <div class="relative z-10 my-auto max-w-lg space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-bold text-brand-300">
                    <ShieldCheck class="w-3.5 h-3.5 text-brand-400" />
                    <span>Enterprise-Grade LMS Platform</span>
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight leading-tight">
                    Master modern engineering skills with hands-on practice.
                </h1>

                <p class="text-slate-300 text-sm leading-relaxed">
                    Join thousands of passionate learners, developers, and industry experts. Access high-definition video modules, comprehensive articles, interactive quizzes, and verified certification.
                </p>

                <div class="pt-4 space-y-3 text-xs font-medium text-slate-300">
                    <div class="flex items-center gap-2.5">
                        <CheckCircle2 class="w-4 h-4 text-emerald-400" />
                        <span>Interactive coding & quiz assessments</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <CheckCircle2 class="w-4 h-4 text-emerald-400" />
                        <span>Verifiable certificates of completion</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <CheckCircle2 class="w-4 h-4 text-emerald-400" />
                        <span>Curated career tracks taught by leading engineers</span>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-xs text-slate-400">
                &copy; 2026 EduHub LMS. All rights reserved.
            </div>
        </div>

        <!-- Right Column: Form Container -->
        <div class="flex flex-col justify-between p-6 sm:p-12 lg:p-16">
            <div class="flex items-center justify-between">
                <div class="lg:hidden">
                    <Logo size="md" />
                </div>

                <div class="ml-auto">
                    <ThemeToggle />
                </div>
            </div>

            <div class="w-full max-w-md mx-auto my-auto py-8">
                <slot />
            </div>

            <div class="text-center text-xs text-slate-400">
                <Link href="/" class="hover:text-brand-600 dark:hover:text-brand-400 transition-colors">
                    ← Back to EduHub Homepage
                </Link>
            </div>
        </div>
    </div>
</template>
