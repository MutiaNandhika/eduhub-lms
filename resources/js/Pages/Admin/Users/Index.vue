<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Avatar from '@/Components/UI/Avatar.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Modal from '@/Components/UI/Modal.vue';
import ConfirmDialog from '@/Components/UI/ConfirmDialog.vue';
import { User, PaginatedData } from '@/Types';
import { Search, UserCheck, Shield, Trash2, Edit3 } from 'lucide-vue-next';

interface Props {
    users: PaginatedData<User>;
    filters: {
        search?: string;
        role?: string;
    };
}

const props = defineProps<Props>();

const filterState = reactive({
    search: props.filters.search || '',
    role: props.filters.role || 'all',
});

const applyFilters = () => {
    router.get(
        '/admin/users',
        {
            search: filterState.search || undefined,
            role: filterState.role !== 'all' ? filterState.role : undefined,
        },
        { preserveState: true, replace: true }
    );
};

// Role Edit Modal
const roleModalOpen = ref(false);
const editingUser = ref<User | null>(null);
const selectedRole = ref<'admin' | 'instructor' | 'student'>('student');

const openEditRole = (u: User) => {
    editingUser.value = u;
    selectedRole.value = u.role;
    roleModalOpen.value = true;
};

const saveRole = () => {
    if (editingUser.value) {
        router.put(`/admin/users/${editingUser.value.id}/role`, {
            role: selectedRole.value,
        }, {
            onSuccess: () => (roleModalOpen.value = false),
        });
    }
};

// Delete User Dialog
const deleteDialogOpen = ref(false);
const userToDelete = ref<User | null>(null);

const confirmDelete = (u: User) => {
    userToDelete.value = u;
    deleteDialogOpen.value = true;
};

const executeDelete = () => {
    if (userToDelete.value) {
        router.delete(`/admin/users/${userToDelete.value.id}`, {
            onSuccess: () => (deleteDialogOpen.value = false),
        });
    }
};

const formatDate = (dateStr?: string) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};
</script>

<template>
    <AdminLayout title="User Management">
        <Head title="Manage Users — Admin Portal" />

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                        User Directory
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Manage platform accounts, role privileges, and student enrollments.
                    </p>
                </div>
            </div>

            <!-- Filters Bar -->
            <Card>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex-1 w-full">
                        <Input
                            v-model="filterState.search"
                            placeholder="Search by name or email..."
                            @keydown.enter="applyFilters"
                        >
                            <template #prefix>
                                <Search class="w-4 h-4" />
                            </template>
                        </Input>
                    </div>

                    <div class="w-full sm:w-48">
                        <Select
                            v-model="filterState.role"
                            :options="[
                                { value: 'all', label: 'All Roles' },
                                { value: 'admin', label: 'Admin' },
                                { value: 'instructor', label: 'Instructor' },
                                { value: 'student', label: 'Student' },
                            ]"
                            @change="applyFilters"
                        />
                    </div>

                    <Button variant="primary" size="md" @click="applyFilters">
                        Filter
                    </Button>
                </div>
            </Card>

            <!-- Users Table -->
            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">User</th>
                                <th class="px-4 py-3.5">Role</th>
                                <th class="px-4 py-3.5">Courses Created</th>
                                <th class="px-4 py-3.5">Enrollments</th>
                                <th class="px-4 py-3.5">Joined Date</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <Avatar :src="user.avatar" :name="user.name" size="sm" />
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ user.name }}</div>
                                            <div class="text-[11px] text-slate-400">{{ user.email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <Badge
                                        :variant="user.role === 'admin' ? 'brand' : user.role === 'instructor' ? 'warning' : 'neutral'"
                                        size="sm"
                                        class="capitalize"
                                    >
                                        {{ user.role }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ (user as any).courses_count || 0 }}
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ (user as any).enrollments_count || 0 }}
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ formatDate(user.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEditRole(user)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-950 transition-colors"
                                            title="Change Role"
                                        >
                                            <Shield class="w-4 h-4" />
                                        </button>

                                        <button
                                            v-if="user.id !== $page.props.auth.user?.id"
                                            type="button"
                                            @click="confirmDelete(user)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition-colors"
                                            title="Delete User"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                    <Pagination
                        :links="users.links"
                        :from="users.from"
                        :to="users.to"
                        :total="users.total"
                    />
                </div>
            </Card>
        </div>

        <!-- Role Edit Modal -->
        <Modal :show="roleModalOpen" max-width="md" @close="roleModalOpen = false">
            <template #title>
                Change Role for {{ editingUser?.name }}
            </template>
            <div class="space-y-4">
                <Select
                    v-model="selectedRole"
                    label="Assign Role"
                    :options="[
                        { value: 'admin', label: 'Super Admin' },
                        { value: 'instructor', label: 'Instructor' },
                        { value: 'student', label: 'Student' },
                    ]"
                />
            </div>
            <template #footer>
                <Button variant="outline" size="sm" @click="roleModalOpen = false">Cancel</Button>
                <Button variant="primary" size="sm" @click="saveRole">Update Role</Button>
            </template>
        </Modal>

        <!-- Delete User Dialog -->
        <ConfirmDialog
            :show="deleteDialogOpen"
            title="Delete User Account"
            :message="`Are you sure you want to permanently delete the account for '${userToDelete?.name}'?`"
            confirm-text="Delete User"
            variant="danger"
            @close="deleteDialogOpen = false"
            @confirm="executeDelete"
        />
    </AdminLayout>
</template>
