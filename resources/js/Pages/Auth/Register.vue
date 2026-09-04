<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';
import Radio from '@/Components/UI/Radio.vue';
import { User as UserIcon, Mail, Lock, GraduationCap, PenTool } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'student',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Create Your EduHub Account" />

        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    Start Learning Today
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Create your free EduHub account to unlock structured courses and verified certification.
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Role Selector -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">
                        I want to join as a:
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <Radio
                            v-model="form.role"
                            value="student"
                            name="account_role"
                            label="Student"
                            description="Take courses & earn certs"
                        />
                        <Radio
                            v-model="form.role"
                            value="instructor"
                            name="account_role"
                            label="Instructor"
                            description="Teach & publish courses"
                        />
                    </div>
                </div>

                <Input
                    v-model="form.name"
                    label="Full Name"
                    placeholder="John Doe"
                    :error="form.errors.name"
                    required
                    autocomplete="name"
                >
                    <template #prefix>
                        <UserIcon class="w-4 h-4" />
                    </template>
                </Input>

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
                    placeholder="At least 8 characters"
                    :error="form.errors.password"
                    required
                    autocomplete="new-password"
                >
                    <template #prefix>
                        <Lock class="w-4 h-4" />
                    </template>
                </Input>

                <Input
                    v-model="form.password_confirmation"
                    type="password"
                    label="Confirm Password"
                    placeholder="Re-enter password"
                    :error="form.errors.password_confirmation"
                    required
                    autocomplete="new-password"
                >
                    <template #prefix>
                        <Lock class="w-4 h-4" />
                    </template>
                </Input>

                <Button
                    type="submit"
                    variant="primary"
                    :loading="form.processing"
                    full-width
                    size="lg"
                >
                    Create Account
                </Button>
            </form>

            <div class="text-center text-xs text-slate-500 dark:text-slate-400">
                Already have an account?
                <Link href="/login" class="font-bold text-brand-600 dark:text-brand-400 hover:underline ml-1">
                    Sign in here
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
