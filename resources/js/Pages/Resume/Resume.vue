<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const resumes = ref([]);

const fetchResumes = async () => {
    const response = await axios.get('/api/resumes');
    resumes.value = response.data;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('ru-RU', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

onMounted(fetchResumes);
</script>

<template>
    <Head title="Мои резюме" />

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-white">Мои резюме</h1>
                <Link
                    href="/resume/create"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-decoration-none text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow"
                >
                    Создать резюме
                </Link>
            </div>

            <div v-if="resumes.length" class="space-y-6">
                <div
                    v-for="resume in resumes"
                    :key="resume.id"
                    class="p-6 bg-white border border-gray-200 rounded-xl shadow-sm transition hover:shadow-md"
                >
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ resume.title }}</h2>
                            <p class="text-sm text-gray-500">Обновлено: {{ formatDate(resume.updated_at) }}</p>
                        </div>
                        <Link
                            :href="`/resume/${resume.id}/edit`"
                            class="text-blue-600 hover:underline text-sm font-medium"
                        >
                            Редактировать
                        </Link>
                    </div>

                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center gap-1">
                            👁️ <span class="font-medium">{{ resume.stats?.views ?? 0 }}</span> показов
                        </div>
                        <div class="flex items-center gap-1">
                            🔍 <span class="font-medium">{{ resume.stats?.clicks ?? 0 }}</span> просмотров
                        </div>
                        <div class="flex items-center gap-1">
                            ✉️ <span class="font-medium">{{ resume.stats?.replies ?? 0 }}</span> откликов
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-400 mt-12 text-lg">
                У вас пока нет резюме.
            </div>
        </div>
    </AuthenticatedLayout>
</template>
