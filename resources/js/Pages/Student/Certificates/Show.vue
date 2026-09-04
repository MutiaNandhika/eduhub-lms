<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Logo from '@/Components/UI/Logo.vue';
import { Certificate } from '@/Types';
import { Award, Printer, ArrowLeft, ExternalLink, ShieldCheck, CheckCircle2 } from 'lucide-vue-next';

interface Props {
    certificate: Certificate;
}

const props = defineProps<Props>();

const printCertificate = () => {
    window.print();
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <AppLayout>
        <Head :title="`Certificate: ${certificate.course?.title} — ${certificate.user?.name}`" />

        <div class="py-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Top Controls (Hidden when printing) -->
            <div class="flex items-center justify-between print:hidden">
                <Link
                    href="/certificates"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition-colors"
                >
                    <ArrowLeft class="w-4 h-4" />
                    <span>Back to Certificates</span>
                </Link>

                <div class="flex items-center gap-3">
                    <Link
                        :href="`/verify/${certificate.certificate_number}`"
                        target="_blank"
                        class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-brand-600 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900"
                    >
                        <span>Public Verification</span>
                        <ExternalLink class="w-3.5 h-3.5" />
                    </Link>

                    <Button
                        variant="primary"
                        size="sm"
                        @click="printCertificate"
                    >
                        <Printer class="w-4 h-4" />
                        <span>Print / Save PDF</span>
                    </Button>
                </div>
            </div>

            <!-- Certificate Art Frame -->
            <div class="p-4 sm:p-10 rounded-3xl bg-midnight-950 shadow-2xl border-4 border-amber-500/30">
                <div class="bg-gradient-to-b from-midnight-950 to-midnight-900 text-white rounded-2xl border-2 border-amber-500/50 p-8 sm:p-16 text-center space-y-8 relative overflow-hidden">
                    <!-- Corner Ornaments -->
                    <div class="absolute top-4 left-4 w-12 h-12 border-t-2 border-l-2 border-amber-500/60" />
                    <div class="absolute top-4 right-4 w-12 h-12 border-t-2 border-r-2 border-amber-500/60" />
                    <div class="absolute bottom-4 left-4 w-12 h-12 border-b-2 border-l-2 border-amber-500/60" />
                    <div class="absolute bottom-4 right-4 w-12 h-12 border-b-2 border-r-2 border-amber-500/60" />

                    <!-- Header -->
                    <div class="space-y-4">
                        <div class="flex justify-center">
                            <Logo size="lg" variant="light" :href="''" />
                        </div>
                        <div class="pt-2">
                            <span class="text-xs sm:text-sm font-black tracking-[0.25em] uppercase text-amber-400">
                                Certificate of Completion
                            </span>
                            <p class="text-xs text-slate-400 mt-1">
                                EduHub Online Learning Management System
                            </p>
                        </div>
                    </div>

                    <!-- Recipient -->
                    <div class="space-y-2 py-4">
                        <p class="text-xs uppercase tracking-widest text-slate-400 font-semibold">
                            This is to officially certify that
                        </p>
                        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight font-serif italic text-amber-100">
                            {{ certificate.user?.name }}
                        </h1>
                        <p class="text-xs text-slate-400 max-w-lg mx-auto pt-1 leading-relaxed">
                            has successfully completed all required curriculum lessons, hands-on programming projects, and scored above passing thresholds in:
                        </p>
                    </div>

                    <!-- Course Title -->
                    <div class="py-2">
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-brand-400 tracking-tight">
                            {{ certificate.course?.title }}
                        </h2>
                    </div>

                    <!-- Signatures & Verification Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pt-8 border-t border-slate-800/80 text-xs items-end">
                        <!-- Instructor Signature -->
                        <div class="space-y-1 text-center">
                            <div class="font-serif italic text-base text-slate-200 border-b border-slate-700 pb-1 mx-auto max-w-[160px]">
                                {{ certificate.course?.instructor?.name || 'Lead Instructor' }}
                            </div>
                            <span class="text-slate-400 text-[11px] block">Instructor Signature</span>
                        </div>

                        <!-- Official Seal Badge -->
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full border-2 border-dashed border-amber-500/70 flex items-center justify-center text-amber-400">
                                <ShieldCheck class="w-7 h-7" />
                            </div>
                            <span class="text-[10px] font-bold text-amber-400 mt-1 uppercase tracking-wider">Verified Credential</span>
                        </div>

                        <!-- Date & Serial -->
                        <div class="space-y-1 text-center">
                            <div class="text-slate-200 font-bold border-b border-slate-700 pb-1 mx-auto max-w-[160px]">
                                {{ formatDate(certificate.issued_at) }}
                            </div>
                            <span class="text-slate-400 text-[11px] block font-mono">
                                {{ certificate.certificate_number }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
