<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Textarea from '@/Components/UI/Textarea.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Button from '@/Components/UI/Button.vue';
import Modal from '@/Components/UI/Modal.vue';
import ConfirmDialog from '@/Components/UI/ConfirmDialog.vue';
import { Category } from '@/Types';
import { PlusCircle, Edit3, Trash2, FolderTree } from 'lucide-vue-next';

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

const categoryModalOpen = ref(false);
const editingCategory = ref<Category | null>(null);

const form = useForm({
    name: '',
    description: '',
    is_active: true,
});

const openAdd = () => {
    editingCategory.value = null;
    form.name = '';
    form.description = '';
    form.is_active = true;
    categoryModalOpen.value = true;
};

const openEdit = (cat: Category) => {
    editingCategory.value = cat;
    form.name = cat.name;
    form.description = cat.description || '';
    form.is_active = cat.is_active;
    categoryModalOpen.value = true;
};

const submit = () => {
    if (editingCategory.value) {
        form.put(`/admin/categories/${editingCategory.value.id}`, {
            onSuccess: () => (categoryModalOpen.value = false),
        });
    } else {
        form.post('/admin/categories', {
            onSuccess: () => (categoryModalOpen.value = false),
        });
    }
};

const deleteCategory = (cat: Category) => {
    if (cat.courses_count && cat.courses_count > 0) {
        alert('Cannot delete category with active courses assigned.');
        return;
    }
    if (confirm(`Delete category '${cat.name}'?`)) {
        router.delete(`/admin/categories/${cat.id}`);
    }
};
</script>

<template>
    <AdminLayout title="Category Management">
        <Head title="Manage Categories — Admin Portal" />

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                        Course Categories
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                        Manage technical fields, disciplines, and learning specializations.
                    </p>
                </div>

                <Button variant="primary" size="md" @click="openAdd">
                    <PlusCircle class="w-4 h-4" />
                    <span>Create Category</span>
                </Button>
            </div>

            <!-- Categories Table -->
            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Category Name</th>
                                <th class="px-4 py-3.5">Slug</th>
                                <th class="px-4 py-3.5">Description</th>
                                <th class="px-4 py-3.5">Courses Count</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            <tr v-for="cat in categories" :key="cat.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40">
                                <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                                    {{ cat.name }}
                                </td>
                                <td class="px-4 py-4 font-mono text-slate-500">
                                    {{ cat.slug }}
                                </td>
                                <td class="px-4 py-4 text-slate-500 max-w-xs line-clamp-1">
                                    {{ cat.description || '—' }}
                                </td>
                                <td class="px-4 py-4 font-bold text-slate-800 dark:text-slate-200">
                                    {{ cat.courses_count || 0 }}
                                </td>
                                <td class="px-4 py-4">
                                    <Badge :variant="cat.is_active ? 'success' : 'neutral'" size="sm">
                                        {{ cat.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="openEdit(cat)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-950 transition-colors"
                                        >
                                            <Edit3 class="w-4 h-4" />
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteCategory(cat)"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950 transition-colors"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>

        <!-- Add/Edit Category Modal -->
        <Modal :show="categoryModalOpen" max-width="md" @close="categoryModalOpen = false">
            <template #title>
                {{ editingCategory ? 'Edit Category' : 'Create Category' }}
            </template>
            <form @submit.prevent="submit" class="space-y-4">
                <Input v-model="form.name" label="Category Name" placeholder="e.g. Cloud & DevOps" required :error="form.errors.name" />
                <Textarea v-model="form.description" label="Description (Optional)" rows="3" :error="form.errors.description" />
                <Checkbox v-model="form.is_active" label="Active Category" description="Visible on catalog filters and exploration cards." />
            </form>
            <template #footer>
                <Button variant="outline" size="sm" @click="categoryModalOpen = false">Cancel</Button>
                <Button variant="primary" size="sm" :loading="form.processing" @click="submit">Save Category</Button>
            </template>
        </Modal>
    </AdminLayout>
</template>
