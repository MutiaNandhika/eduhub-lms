<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Input from '@/Components/UI/Input.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import Button from '@/Components/UI/Button.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Card from '@/Components/UI/Card.vue';
import { User } from '@/Types';
import { User as UserIcon, Mail, Lock, Sparkles } from 'lucide-vue-next';

interface Props {
    user: User;
}

const props = defineProps<Props>();

const profileForm = useForm({
    name: props.user.name,
    email: props.user.email,
    bio: props.user.bio || '',
    avatar: props.user.avatar || '',
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updateProfile = () => {
    profileForm.put('/profile');
};

const updatePassword = () => {
    passwordForm.put('/profile/password', {
        onSuccess: () => passwordForm.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Account Settings — EduHub" />

        <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
                    Account Settings
                </h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Manage your personal profile information, bio, and security credentials.
                </p>
            </div>

            <!-- Profile Info Card -->
            <Card>
                <template #header>
                    <div class="flex items-center gap-3">
                        <Avatar :src="profileForm.avatar || user.avatar" :name="profileForm.name || user.name" size="lg" />
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Profile Details</h2>
                            <p class="text-xs text-slate-500">Update your public student or instructor information.</p>
                        </div>
                    </div>
                </template>

                <form @submit.prevent="updateProfile" class="space-y-4">
                    <Input
                        v-model="profileForm.name"
                        label="Full Name"
                        :error="profileForm.errors.name"
                        required
                    />

                    <Input
                        v-model="profileForm.email"
                        type="email"
                        label="Email Address"
                        :error="profileForm.errors.email"
                        required
                    />

                    <Input
                        v-model="profileForm.avatar"
                        label="Avatar Image URL (Optional)"
                        placeholder="https://example.com/avatar.jpg"
                        :error="profileForm.errors.avatar"
                        hint="Provide a public image link for your profile picture."
                    />

                    <Textarea
                        v-model="profileForm.bio"
                        label="Bio / About Me"
                        placeholder="Tell students and fellow developers a bit about yourself..."
                        rows="3"
                        :error="profileForm.errors.bio"
                    />

                    <div class="flex justify-end pt-2">
                        <Button
                            type="submit"
                            variant="primary"
                            :loading="profileForm.processing"
                        >
                            Save Profile Changes
                        </Button>
                    </div>
                </form>
            </Card>

            <!-- Password Card -->
            <Card>
                <template #header>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Update Password</h2>
                    <p class="text-xs text-slate-500">Ensure your account uses a strong and unique password.</p>
                </template>

                <form @submit.prevent="updatePassword" class="space-y-4">
                    <Input
                        v-model="passwordForm.current_password"
                        type="password"
                        label="Current Password"
                        :error="passwordForm.errors.current_password"
                        required
                    />

                    <Input
                        v-model="passwordForm.password"
                        type="password"
                        label="New Password"
                        :error="passwordForm.errors.password"
                        required
                    />

                    <Input
                        v-model="passwordForm.password_confirmation"
                        type="password"
                        label="Confirm New Password"
                        :error="passwordForm.errors.password_confirmation"
                        required
                    />

                    <div class="flex justify-end pt-2">
                        <Button
                            type="submit"
                            variant="primary"
                            :loading="passwordForm.processing"
                        >
                            Update Password
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
