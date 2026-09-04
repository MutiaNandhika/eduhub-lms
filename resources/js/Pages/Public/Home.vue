<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CourseCard from '@/Components/Domain/CourseCard.vue';
import Rating from '@/Components/UI/Rating.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import { Course, Category, Review, User } from '@/Types';
import {
    Sparkles,
    ArrowRight,
    PlayCircle,
    CheckCircle2,
    ShieldCheck,
    Award,
    Code2,
    Database,
    Palette,
    Cpu,
    BookOpen,
    Layers,
    Users,
    ChevronRight,
    TrendingUp,
    Check,
    Star,
} from 'lucide-vue-next';

interface Props {
    featuredCourses: Course[];
    categories: Category[];
    stats: {
        students: number;
        courses: number;
        lessons: number;
        certificates: number;
    };
    instructors: User[];
    testimonials: Review[];
}

defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="Learn. Practice. Grow." />

        <!-- 1. Hero Section -->
        <section class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden">
            <!-- Ambient Glows -->
            <div class="absolute top-12 left-1/2 -translate-x-1/2 w-[700px] h-[380px] bg-brand-500/10 dark:bg-brand-500/15 blur-[140px] rounded-full pointer-events-none -z-10" />
            <div class="absolute top-48 right-10 w-[400px] h-[300px] bg-coral-500/10 blur-[120px] rounded-full pointer-events-none -z-10" />

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Top Hero Center Copy -->
                <div class="text-center max-w-3xl mx-auto space-y-6">
                    <!-- Main Headline -->
                    <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.08]">
                        Learn. Practice. <span class="text-brand-600 dark:text-brand-400">Grow.</span>
                    </h1>

                    <!-- Supporting Text -->
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl mx-auto">
                        Elevate your technical career with structured multi-module courses, automated server-evaluated quizzes, and cryptographically verifiable certificates.
                    </p>

                    <!-- CTAs (Pill-style buttons matching reference image) -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                        <Link
                            href="/courses"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full text-sm font-bold bg-coral-500 hover:bg-coral-600 text-white shadow-lg shadow-coral-500/25 active:scale-95 transition-all"
                        >
                            <span>Explore Courses</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>

                        <Link
                            href="/register"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-full text-sm font-bold border border-slate-300 dark:border-white/10 hover:bg-slate-100 dark:hover:bg-midnight-800 text-slate-700 dark:text-slate-200 active:scale-95 transition-all"
                        >
                            <span>Start Learning Free</span>
                        </Link>
                    </div>

                    <!-- Key Value Pills -->
                    <div class="pt-4 flex flex-wrap items-center justify-center gap-4 sm:gap-8 text-xs font-semibold text-slate-500 dark:text-slate-400">
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="w-4 h-4 text-brand-600 dark:text-brand-400" />
                            <span>Interactive Assessments</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="w-4 h-4 text-brand-600 dark:text-brand-400" />
                            <span>Verified Certificates</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <CheckCircle2 class="w-4 h-4 text-brand-600 dark:text-brand-400" />
                            <span>Live Progress Sync</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Graphic Showcase (Dark Slate Card aesthetic from Image 1) -->
                <div class="mt-14 relative max-w-5xl mx-auto">
                    <!-- Outer Glowing Ring -->
                    <div class="absolute -inset-1.5 bg-gradient-to-r from-brand-600/30 via-coral-500/20 to-midnight-800/40 rounded-3xl blur-lg -z-10 opacity-70" />

                    <!-- Main Showcase Container -->
                    <div class="glass-dark-card rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                        <!-- Top Bar Mockup -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-6 mb-8">
                            <div class="flex items-center gap-3">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-rose-500/80" />
                                    <div class="w-3 h-3 rounded-full bg-amber-500/80" />
                                    <div class="w-3 h-3 rounded-full bg-emerald-500/80" />
                                </div>
                                <div class="hidden sm:block text-xs font-mono text-slate-400 pl-4 border-l border-white/10">
                                    app.eduhub.test/learning-track
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-300 border border-brand-500/30 text-xs font-bold">
                                    Live Session
                                </span>
                            </div>
                        </div>

                        <!-- Grid Inside Mockup -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                            <!-- Left: Course In-Progress Card -->
                            <div class="md:col-span-2 bg-midnight-950/70 border border-white/10 rounded-2xl p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-brand-400 uppercase tracking-wider">Active Track</span>
                                    <span class="text-xs font-mono text-slate-400">Lesson 6 of 8</span>
                                </div>
                                <h3 class="text-xl font-extrabold text-white">
                                    Fullstack Next.js & Laravel Architecture
                                </h3>
                                <p class="text-xs text-slate-300 leading-relaxed">
                                    Implementing atomic state transitions, PostgreSQL pooler transactions, and secure JWT authentication.
                                </p>
                                <!-- Progress Bar -->
                                <div class="space-y-1.5 pt-2">
                                    <div class="flex justify-between text-xs font-bold">
                                        <span class="text-slate-300">Curriculum Progress</span>
                                        <span class="text-coral-400">75% Complete</span>
                                    </div>
                                    <div class="w-full h-2.5 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-brand-500 to-coral-500 rounded-full w-3/4" />
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Stat / Quiz Score Pill Widget -->
                            <div class="space-y-4">
                                <div class="bg-midnight-950/70 border border-white/10 rounded-2xl p-5 text-center">
                                    <div class="inline-flex p-3 rounded-2xl bg-coral-500/20 text-coral-400 mb-2">
                                        <Award class="w-6 h-6" />
                                    </div>
                                    <div class="text-2xl font-black text-white">98.5%</div>
                                    <div class="text-xs text-slate-400 mt-0.5">Quiz Mastery Score</div>
                                </div>

                                <div class="bg-midnight-950/70 border border-white/10 rounded-2xl p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">
                                            ✓
                                        </div>
                                        <div class="text-left">
                                            <div class="text-xs font-bold text-white">Certificate</div>
                                            <div class="text-[10px] text-slate-400">Ready for export</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-brand-400">Verified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Platform Statistics (Pill Theme Container) -->
        <section class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="theme-card p-8 sm:p-10">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100 dark:divide-white/5">
                        <div class="pt-4 md:pt-0">
                            <div class="text-3xl sm:text-4xl font-black text-brand-600 dark:text-brand-400">
                                {{ stats.students > 0 ? stats.students + '+' : '50+' }}
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">
                                Active Students
                            </div>
                        </div>
                        <div class="pt-4 md:pt-0">
                            <div class="text-3xl sm:text-4xl font-black text-brand-600 dark:text-brand-400">
                                {{ stats.courses > 0 ? stats.courses + '+' : '20+' }}
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">
                                Published Courses
                            </div>
                        </div>
                        <div class="pt-4 md:pt-0">
                            <div class="text-3xl sm:text-4xl font-black text-brand-600 dark:text-brand-400">
                                {{ stats.lessons > 0 ? stats.lessons + '+' : '120+' }}
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">
                                Practical Lessons
                            </div>
                        </div>
                        <div class="pt-4 md:pt-0">
                            <div class="text-3xl sm:text-4xl font-black text-brand-600 dark:text-brand-400">
                                {{ stats.certificates > 0 ? stats.certificates + '+' : '30+' }}
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">
                                Certificates Issued
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Featured Courses Section -->
        <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-12">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-coral-500 dark:text-coral-400">
                        Top Rated Curriculum
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                        Featured Learning Tracks
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Curated step-by-step masterclasses designed to take you from fundamentals to production mastery.
                    </p>
                </div>

                <Link
                    href="/courses"
                    class="pill-btn-outline"
                >
                    <span>View all courses</span>
                    <ArrowRight class="w-3.5 h-3.5 ml-1.5" />
                </Link>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <CourseCard
                    v-for="course in featuredCourses"
                    :key="course.id"
                    :course="course"
                />
            </div>
        </section>

        <!-- 4. Categories Grid -->
        <section class="py-20 bg-slate-100/50 dark:bg-midnight-950/40 border-y border-slate-200/80 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-coral-500 dark:text-coral-400">
                        Explore Topics
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                        Top Engineering Disciplines
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Browse by your preferred stack or career specialization.
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <Link
                        v-for="cat in categories"
                        :key="cat.id"
                        :href="`/courses?category=${cat.slug}`"
                        class="p-6 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 hover:border-brand-500/50 dark:hover:border-brand-500/50 hover:shadow-lg transition-all group flex flex-col justify-between"
                    >
                        <div>
                            <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-950/60 border border-brand-200/60 dark:border-brand-800/60 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <Code2 class="w-6 h-6" />
                            </div>
                            <h3 class="font-bold text-sm text-slate-900 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 transition-colors">
                                {{ cat.name }}
                            </h3>
                            <p v-if="cat.description" class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">
                                {{ cat.description }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ cat.courses_count || 0 }} courses</span>
                            <ChevronRight class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform text-brand-600" />
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- 5. Why EduHub Feature Highlights -->
        <section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-coral-500 dark:text-coral-400">
                    Why EduHub
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                    Engineered for Practical Competence
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                    Traditional video-only tutorials leave knowledge gaps. EduHub integrates structured modules, hands-on tests, and verifiable milestones.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-6">
                        <PlayCircle class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        Dual Format Learning
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Switch seamlessly between high-definition video walkthroughs and clean, distraction-free markdown articles with syntax highlighting.
                    </p>
                </div>

                <div class="p-8 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-coral-50 dark:bg-coral-950/60 text-coral-600 dark:text-coral-400 flex items-center justify-center mb-6">
                        <ShieldCheck class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        Secure Server-Side Quizzes
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Test your comprehension with automated multiple-choice tests evaluated securely with deep rationale and score thresholds.
                    </p>
                </div>

                <div class="p-8 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-6">
                        <Award class="w-6 h-6" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                        Verifiable Certificates
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Earn unique authenticated certificates for completed tracks. Anyone or any employer can verify your credentials online instantly.
                    </p>
                </div>
            </div>
        </section>

        <!-- 6. Popular Instructors -->
        <section v-if="instructors.length > 0" class="py-20 bg-slate-100/50 dark:bg-midnight-950/40 border-t border-slate-200/80 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-bold uppercase tracking-wider text-coral-500 dark:text-coral-400">
                        Learn From The Best
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                        Featured Instructors
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="inst in instructors"
                        :key="inst.id"
                        class="p-6 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 text-center shadow-sm flex flex-col items-center justify-between"
                    >
                        <Avatar :src="inst.avatar" :name="inst.name" size="xl" />
                        <h3 class="font-bold text-base text-slate-900 dark:text-white mt-4">
                            {{ inst.name }}
                        </h3>
                        <p v-if="inst.bio" class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            {{ inst.bio }}
                        </p>
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 text-xs text-brand-600 dark:text-brand-400 font-bold">
                            {{ inst.courses_count || 0 }} published courses
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. Testimonials -->
        <section v-if="testimonials.length > 0" class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-coral-500 dark:text-coral-400">
                    Student Reviews
                </span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                    Loved by Learners Globally
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div
                    v-for="test in testimonials"
                    :key="test.id"
                    class="p-8 rounded-3xl border border-slate-200/80 dark:border-white/5 bg-white dark:bg-midnight-900 shadow-sm flex flex-col justify-between"
                >
                    <div>
                        <Rating :model-value="test.rating" size="sm" class="mb-4" />
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic">
                            "{{ test.comment }}"
                        </p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center gap-3">
                        <Avatar :src="test.user?.avatar" :name="test.user?.name" size="sm" />
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white">
                                {{ test.user?.name }}
                            </div>
                            <div v-if="test.course" class="text-[10px] text-slate-400 truncate max-w-[180px]">
                                {{ test.course.title }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. Final CTA Banner (Midnight + Terracotta Coral Accent) -->
        <section class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-midnight-950 via-midnight-900 to-brand-950 rounded-4xl p-10 sm:p-16 text-white text-center relative overflow-hidden border border-white/10 shadow-2xl">
                <!-- Glowing accents -->
                <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-brand-500/15 blur-3xl pointer-events-none" />
                <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-coral-500/20 blur-3xl pointer-events-none" />

                <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-brand-300 border border-white/10 text-xs font-bold uppercase tracking-wider">
                        Start Your Learning Journey
                    </span>

                    <h2 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                        Ready to accelerate your engineering career?
                    </h2>

                    <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto">
                        Get instant access to curated developer courses, real-time progress tracking, and verifiable certificates today.
                    </p>

                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <Link
                            href="/register"
                            class="pill-btn-coral px-8 py-3.5 text-sm"
                        >
                            Create Free Account
                        </Link>
                        <Link
                            href="/courses"
                            class="pill-btn-outline px-8 py-3.5 text-sm text-white border-white/20 hover:bg-white/10"
                        >
                            Browse Catalog
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
