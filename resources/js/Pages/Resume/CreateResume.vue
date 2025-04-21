<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import {ref} from "vue";

const form = useForm({
    first_name: '',
    last_name: '',
    middle_name: '',
    phone: '',
    email: '',
        education: [],
    experience: [],
    skills: [],
    additional_info: '',
});

// Модальные флаги
const showEducationModal = ref(false);
const showExperienceModal = ref(false);
const showSkillModal = ref(false);

// Модальные временные поля и индекс для редактирования
const modalData = ref({
    education: [
        {
            institution: '',
            specialty: '',
            degree: '',
            start_year: '',
            end_year: ''
        }
    ],
    experience: [
        {
            company: '',
            position: '',
            startMonth: '',
            startYear: '',
            endMonth: '',
            endYear: ''
        }
    ],
    skills: [
        {
            name: '',
            level: ''
        }
    ]
});
const editIndex = ref(null);

// Открытие модалки
const openModal = (type, data = {}, index = null) => {
    modalData.value = {...data};
    editIndex.value = index;
    if (type === 'education') showEducationModal.value = true;
    if (type === 'experience') showExperienceModal.value = true;
    if (type === 'skill') showSkillModal.value = true;
};

// Сохранение данных из модалки
const saveModalData = (type) => {
    // Проверка на существование массива
    if (!Array.isArray(form[type])) {
        console.error(`form[${type}] не является массивом`);
        return;
    }

    if (editIndex.value !== null) {
        form[type][editIndex.value] = modalData.value;
    } else {
        form[type].push({ ...modalData.value });
    }

    // Сброс
    modalData.value = {};
    editIndex.value = null;

    if (type === 'education') showEducationModal.value = false;
    if (type === 'experience') showExperienceModal.value = false;
    if (type === 'skills') showSkillModal.value = false;
};


const removeItem = (type, index) => {
    form[type].splice(index, 1);
};

const submit = () => {
    form.post('/resume');
};

const months = [
    'Январь', 'Февраль', 'Март', 'Апрель',
    'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
]

const currentYear = new Date().getFullYear()
const years = Array.from({ length: 50 }, (_, i) => currentYear - i) // последние 50 лет

</script>


<template>
    <Head title="Создание резюме"/>

    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto py-10 px-6">
            <h1 class="text-3xl font-bold text-white mb-6">Создание резюме</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- ФИО -->
                <div class="border-b border-white pb-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-2">ФИО</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-1 font-medium text-white">Имя</label>
                            <input v-model="form.first_name" type="text" class="w-full input" required/>
                        </div>
                        <div>
                            <label class="block mb-1 font-medium text-white">Фамилия</label>
                            <input v-model="form.last_name" type="text" class="w-full input" required/>
                        </div>
                        <div>
                            <label class="block mb-1 font-medium text-white">Отчество (необязательно)</label>
                            <input v-model="form.middle_name" type="text" class="w-full input"/>
                        </div>
                    </div>
                </div>

                <!-- Контакты -->
                <div class="border-b border-white pb-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-2">Контакты</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 font-medium text-white">Телефон</label>
                            <input v-model="form.phone" type="text" class="w-full input" required/>
                        </div>
                        <div>
                            <label class="block mb-1 font-medium text-white">Email</label>
                            <input v-model="form.email" type="email" class="w-full input" required/>
                        </div>
                    </div>
                </div>

                <!-- Образование -->
                <div class="border-b border-white pb-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-2 mt-6">Образование</h2>
                    <div v-for="(edu, index) in form.education" :key="index" class="mb-2 border p-3 rounded">
                        <p class="text-gray-400"><strong>{{ edu.institution }}</strong> ({{ edu.end_year }}) {{ edu.specialty }} - {{edu.degree}}</p>
                        <div class="flex gap-2 mt-2">
                            <button type="button" @click="openModal('education', edu, index)" class="btn-sm text-blue-600">Редактировать
                            </button>
                            <button type="button" @click="removeItem('education', index)" class="btn-sm text-red-600">Удалить</button>
                        </div>
                    </div>
                    <button type="button" @click="openModal('education')" class="btn-blue mt-2 text-gray-300">+ Добавить</button>
                </div>

                <!-- Опыт работы -->
                <div class="border-b border-white pb-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-2 mt-6">Опыт работы</h2>
                    <div v-for="(exp, index) in form.experience" :key="index" class="mb-2 border p-3 rounded">
                        <p class="text-gray-400"><strong>{{ exp.position }}</strong> в {{ exp.company }} ({{ exp.startYear }} - {{ exp.endYear }})</p>
                        <p class="text-sm text-white">{{ exp.responsibilities }}</p>
                        <div class="flex gap-2 mt-2">
                            <button type="button" @click="openModal('experience', exp, index)" class="btn-sm text-blue-600">
                                Редактировать
                            </button>
                            <button type="button" @click="removeItem('experience', index)" class="btn-sm text-red-600">Удалить</button>
                        </div>
                    </div>
                    <button type="button" @click="openModal('experience')" class="btn-blue mt-2 text-gray-300">+ Добавить</button>
                </div>

                <!-- Навыки -->
                <div class="border-b border-white pb-6 mb-6">
                    <h2 class="text-xl font-semibold text-white mb-2 mt-6">Навыки</h2>
                    <div v-for="(skill, index) in form.skills" :key="index" class="mb-2 border p-3 rounded">
                        <p class="text-gray-400">{{ skill.name }} — <em>{{ skill.level }}</em></p>
                        <div class="flex gap-2 mt-2">
                            <button type="button" @click="openModal('skill', skill, index)" class="btn-sm text-blue-600">Редактировать
                            </button>
                            <button type="button" @click="removeItem('skills', index)" class="btn-sm text-red-600">Удалить</button>
                        </div>
                    </div>
                    <button type="button" @click="openModal('skill')" class="btn-blue mt-2 text-gray-300">+ Добавить</button>
                </div>
                <!-- О себе -->
                <div class="border-b border-white pb-6 mb-6">
                    <label class="block mb-1 font-medium text-white">О себе</label>
                    <textarea v-model="form.additional_info" class="w-full input" rows="3"></textarea>
                </div>

                <!-- Кнопка -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow"
                        :disabled="form.processing"
                    >
                        Сохранить резюме
                    </button>
                </div>
            </form>
            <!-- Модалка Образование -->
            <div v-if="showEducationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" role="dialog" aria-labelledby="education-modal-title">
                <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                    <div class="magritte-wrapper___zH8vB_7-1-1">
                        <!-- Modal Header -->
                        <div class="magritte-title-wrapper___O--QR_7-1-1">
                            <h2 id="education-modal-title" class="text-xl font-semibold mb-4 magritte-text___gMq2l_6-1-5">
                                Учебное заведение
                            </h2>
                        </div>
                        <div class="magritte-actions___nQILV_7-1-1 magritte-actions-single___usfKu_7-1-1">
                <span class="magritte-wrapper___8Uzkk_10-2-0">
                    <!-- Close button -->
                    <button @click="showEducationModal = false" class="magritte-view___TfcUt_10-2-0 magritte-shadow-level-0___ko9ze_10-2-0 rounded-full p-2">
                        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" role="img">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10.7273 12L4 5.27279L5.27279 4L12 10.7273L18.7273 4L20 5.27279L13.2728 12L20 18.7273L18.7273 20L12 13.2728L5.27279 20L4 18.7273L10.7273 12Z" />
                        </svg>
                    </button>
                </span>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <input v-model="modalData.institution" placeholder="Учебное заведение" required class="input w-full mb-2" />

                    <input v-model="modalData.specialty" placeholder="Специальность" class="input w-full mb-4" />

                    <!-- Степень образования -->
                    <select v-model="modalData.degree" class="input w-full mb-4" required>
                        <option value='' disabled selected>Выберите степень образования</option>
                        <option value="Бакалавр">Бакалавр</option>
                        <option value="Магистр">Магистр</option>
                        <option value="Кандидат наук">Кандидат наук</option>
                        <option value="Доктор наук">Доктор наук</option>
                        <option value="Специалист">Специалист</option>
                    </select>

                    <!-- Дата начала и окончания -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input v-model="modalData.start_year" type="number" placeholder="Год начала" class="input w-full" min="1900" max="2099" required />
                        <input v-model="modalData.end_year" type="number" placeholder="Год окончания" class="input w-full" min="1900" max="2099" required />
                    </div>

                    <!-- Save and Cancel Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button @click="showEducationModal = false" class="btn">Отмена</button>
                        <button @click="saveModalData('education')" class="btn-blue">Сохранить</button>
                    </div>
                </div>
            </div>

            <!-- Модалка Опыт работы -->
            <div v-if="showExperienceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                    <h3 class="text-lg font-semibold mb-4">Опыт работы</h3>

                    <!-- Компания и Должность -->
                    <input v-model="modalData.company" placeholder="Компания" class="input w-full mb-2" />
                    <input v-model="modalData.position" placeholder="Должность" class="input w-full mb-2" />

                    <!-- Период работы -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Начало работы</label>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <div>
                                <select v-model="modalData.startMonth" class="input w-full">
                                    <option disabled value="" selected>Месяц</option>
                                    <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
                                </select>
                            </div>
                            <div>
                                <select v-model="modalData.startYear" class="input w-full">
                                    <option disabled value="">Год</option>
                                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                </select>
                            </div>
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-1">Окончание</label>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <select v-model="modalData.endMonth" class="input w-full">
                                    <option disabled value="">Месяц</option>
                                    <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
                                </select>
                            </div>
                            <div>
                                <select v-model="modalData.endYear" class="input w-full">
                                    <option disabled value="">Год</option>
                                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-2">
                        <button @click="showExperienceModal = false" class="btn">Отмена</button>
                        <button @click="saveModalData('experience')" class="btn-blue">Сохранить</button>
                    </div>
                </div>
            </div>


            <!-- Модалка Навык -->
            <div v-if="showSkillModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                    <h3 class="text-lg font-semibold mb-4">Навык</h3>
                    <input v-model="modalData.name" placeholder="Навык" class="input w-full mb-2"/>
                    <select v-model="modalData.level" class="input w-full mb-4">
                        <option value="" disabled>Уровень</option>
                        <option value="Начальный">Начальный</option>
                        <option value="Средний">Средний</option>
                        <option value="Продвинутый">Продвинутый</option>
                    </select>
                    <div class="flex justify-end space-x-2">
                        <button @click="showSkillModal = false" class="btn">Отмена</button>
                        <button @click="saveModalData('skills')" class="btn-blue">Сохранить</button>
                    </div>
                </div>
            </div>

        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.input {
    @apply border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full;
}
</style>
