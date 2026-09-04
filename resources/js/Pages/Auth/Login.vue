<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import { Mail, Lock, UserCheck, Shield, Sparkles } from 'lucide-vue-next';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};

const fillDemo = (email: string) => {
    form.email = email;
    form.password = 'password';
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign In to EduHub" />

        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Welcome back
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Enter your credentials to continue your learning journey.
                </p>
            </div>

            <!-- Quick 1-Click Demo Logins -->
            <div class="p-3.5 rounded-2xl bg-brand-50/70 dark:bg-brand-950/40 border border-brand-200/70 dark:border-brand-900/60 space-y-2">
                <div class="flex items-center gap-1.5 text-xs font-bold text-brand-700 dark:text-brand-300">
                    <Sparkles class="w-3.5 h-3.5" />
                    <span>Quick Demo Switcher (1-Click Fill)</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button
                        type="button"
                        @click="fillDemo('student@eduhub.test')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition-colors cursor-pointer"
                    >
                        Student
                    </button>
                    <button
                        type="button"
                        @click="fillDemo('instructor@eduhub.test')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition-colors cursor-pointer"
                    >
                        Instructor
                    </button>
                    <button
                        type="button"
                        @click="fillDemo('admin@eduhub.test')"
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-brand-500 hover:text-brand-600 transition-colors cursor-pointer"
                    >
                        Admin
                    </button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <Input
                    v-model="form.email"
                    type="email"
                    label="Email Address"
                    placeholder="you@example.com"
                    :error="form.errors.email"
                    required
                    autocomplete="username"
                >
                    <template #prefix>
                        <Mail class="w-4 h-4" />
                    </template>
                </Input>

                <Input
                    v-model="form.password"
                    type="password"
                    label="Password"
                    placeholder="••••••••"
                    :error="form.errors.password"
                    required
                    autocomplete="current-password"
                >
                    <template #prefix>
                        <Lock class="w-4 h-4" />
                    </template>
                </Input>

                <div class="flex items-center justify-between">
                    <Checkbox
                        v-model="form.remember"
                        label="Remember me"
                    />
                </div>

                <Button
                    type="submit"
                    variant="primary"
                    :loading="form.processing"
                    full-width
                    size="lg"
                >
                    Sign In
                </Button>
            </form>

            <div class="text-center text-xs text-slate-500 dark:text-slate-400">
                Don't have an account?
                <Link href="/register" class="font-bold text-brand-600 dark:text-brand-400 hover:underline ml-1">
                    Create one now
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
