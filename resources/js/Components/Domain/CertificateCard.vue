<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Certificate } from '@/Types';
import { Award, CheckCircle2, ArrowUpRight, ExternalLink } from 'lucide-vue-next';

interface Props {
    certificate: Certificate;
}

const props = defineProps<Props>();

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <div class="relative group rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden">
        <!-- Background subtle watermark decoration -->
        <div class="absolute -right-8 -bottom-8 opacity-5 dark:opacity-10 pointer-events-none">
            <Award class="w-48 h-48 text-brand-600" />
        </div>

        <div>
            <div class="flex items-center justify-between gap-2 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/60 dark:border-amber-800/60 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <Award class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="text-[10px] font-bold tracking-wider uppercase text-slate-400">Official Certificate</span>
                        <div class="text-xs font-mono font-bold text-slate-700 dark:text-slate-300">
                            {{ certificate.certificate_number }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-full">
                    <CheckCircle2 class="w-3.5 h-3.5" />
                    Verified
                </div>
            </div>

            <h3 class="font-bold text-base text-slate-900 dark:text-white line-clamp-2 mt-2">
                {{ certificate.course?.title || 'Course Certificate' }}
            </h3>

            <div class="mt-4 space-y-1 text-xs text-slate-500 dark:text-slate-400">
                <div v-if="certificate.user" class="flex justify-between">
                    <span>Recipient:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ certificate.user.name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Issued Date:</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ formatDate(certificate.issued_at) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <Link
                :href="`/verify/${certificate.certificate_number}`"
                target="_blank"
                class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors"
            >
                <span>Public Verification</span>
                <ExternalLink class="w-3.5 h-3.5" />
            </Link>

            <Link
                :href="`/certificates/${certificate.id}`"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 dark:text-brand-400 hover:text-brand-700 dark:hover:text-brand-300 transition-colors"
            >
                <span>View Certificate</span>
                <ArrowUpRight class="w-3.5 h-3.5" />
            </Link>
        </div>
    </div>
</template>
