<template>
    <v-card>
        <!-- Диалог удаления -->
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="320"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить вакансию «${vacancyToDelete.name}»?`"
                @cancel="cancelDelete"
                @apply="applyDelete"
            />
        </v-dialog>

        <!-- Диалог редактирования -->
        <v-dialog
            v-model="editDialogIsOpened"
            width="800"
            persistent
        >
            <forms-vacancy-edit
                :vacancy="vacancyToEdit"
                :departments="departments"
                :positions="positions"
                @cancel="cancelEdit"
                @apply="applyEdit"
            />
        </v-dialog>

        <!-- Шапка отчета -->
        <v-card-text>
            <v-row>
                <v-col cols="12" md="3">
                    <v-text-field
                        v-model="searchQuery"
                        label="Поиск по названию"
                        placeholder="Введите название вакансии"
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        clearable
                        @input="handleSearch"
                        @click:clear="clearSearch"
                    />
                </v-col>
                <v-col cols="12" md="2">
                    <v-select
                        v-model="departmentFilter"
                        :items="departmentOptions"
                        label="Фильтр по отделу"
                        clearable
                        variant="outlined"
                        @update:model-value="applyFilters"
                    />
                </v-col>
                <v-col cols="12" md="2">
                    <v-select
                        v-model="statusFilter"
                        :items="statusOptions"
                        label="Фильтр по статусу"
                        clearable
                        variant="outlined"
                        @update:model-value="applyFilters"
                    />
                </v-col>
                <v-col cols="12" md="3">
                    <v-btn
                        variant="outlined"
                        prepend-icon="mdi-filter-remove"
                        @click="clearAllFilters"
                        class="mt-3"
                        block
                    >
                        Сбросить
                    </v-btn>
                </v-col>
                <v-col cols="12" md="2">
                    <v-btn
                        variant="elevated"
                        color="success"
                        prepend-icon="mdi-file-excel"
                        @click="exportToExcel"
                        class="mt-3"
                        block
                        :loading="exportLoading"
                    >
                        Экспорт
                    </v-btn>
                </v-col>
            </v-row>

            <!-- Статистика -->
            <v-alert v-if="filteredVacancies.length > 0" type="info" density="compact" class="mb-4">
                <div class="d-flex justify-space-between align-center">
                    <span>Показано: {{ filteredVacancies.length }} из {{ vacancies.length }} вакансий</span>
                    <div class="d-flex align-center gap-4">
                        <span class="font-weight-bold">Всего кандидатов: {{ totalCandidates }}</span>
                    </div>
                </div>
            </v-alert>
        </v-card-text>

        <!-- Таблица отчета -->
        <v-data-table 
            :headers="headers"
            :items="filteredVacancies"
            :loading="loading"
            :items-per-page="20"
            class="elevation-1"
        >
            <!-- Действия -->
            <template v-slot:item.actions="{ item }"> 
                <v-menu>
                    <template v-slot:activator="{ props }">
                        <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props"/>
                    </template>
                    <v-list density="compact">
                        <v-list-item>
                            <v-btn
                                class="justify-start"
                                block
                                variant="text"
                                color="primary"
                                @click="handleEdit(item)"
                                prepend-icon="mdi-pencil"
                                size="small"
                            >
                                Редактировать
                            </v-btn>
                        </v-list-item>
                        <v-list-item>
                            <v-btn
                                class="justify-start"
                                block
                                color="error"
                                variant="text"
                                @click="handleDelete(item)"
                                prepend-icon="mdi-delete"
                                size="small"
                            >
                                Удалить
                            </v-btn>
                        </v-list-item>
                        <v-divider />
                        <v-list-item>
                            <v-btn
                                class="justify-start"
                                block
                                variant="text"
                                color="success"
                                @click="viewCandidates(item)"
                                prepend-icon="mdi-account-group"
                                size="small"
                            >
                                Кандидаты ({{ item.candidate_count || 0 }})
                            </v-btn>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>

            <!-- Название вакансии -->
            <template v-slot:item.name="{ item }">
                <div class="d-flex align-center gap-x-3">
                    <v-avatar size="36" color="blue-lighten-5" class="text-blue">
                        <v-icon>mdi-briefcase</v-icon>
                    </v-avatar>
                    <div class="d-flex flex-column">
                        <strong class="text-body-1">
                            {{ item.name }}
                        </strong>
                        <div class="d-flex gap-x-2 mt-1">
                            <v-chip v-if="item.position" size="x-small" variant="outlined" color="primary">
                                {{ item.position.name }}
                            </v-chip>
                            <v-chip v-if="item.department" size="x-small" variant="outlined" color="secondary">
                                {{ item.department.name }}
                            </v-chip>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Описание -->
            <template v-slot:item.description="{ item }">
                <div class="text-caption line-clamp-2" :title="item.description">
                    {{ truncateText(item.description, 80) }}
                </div>
            </template>

            <!-- Требования -->
            <template v-slot:item.requirements="{ item }">
                <div class="text-caption line-clamp-2" :title="item.requirements">
                    {{ truncateText(item.requirements, 80) }}
                </div>
            </template>

            <!-- Обязанности -->
            <template v-slot:item.responsibilities="{ item }">
                <div class="text-caption line-clamp-2" :title="item.responsibilities">
                    {{ truncateText(item.responsibilities, 80) }}
                </div>
            </template>

            <!-- Количество кандидатов -->
            <template v-slot:item.candidate_count="{ item }">
                <v-badge
                    :content="item.candidate_count || 0"
                    :color="getCandidatesColor(item.candidate_count)"
                    inline
                >
                    <v-btn
                        variant="text"
                        size="small"
                        color="primary"
                        @click="viewCandidates(item)"
                        prepend-icon="mdi-account-multiple"
                    >
                        Кандидаты
                    </v-btn>
                </v-badge>
            </template>

            <!-- Статус -->
            <template v-slot:item.status="{ item }">
                <v-chip 
                    :color="getStatusColor(item.status)" 
                    size="small"
                    variant="flat"
                    :prepend-icon="getStatusIcon(item.status)"
                >
                    {{ getStatusText(item.status) }}
                </v-chip>
            </template>

            <!-- Дата создания -->
            <template v-slot:item.created_at="{ item }">
                <span v-if="item.created_at">
                    {{ formatDate(item.created_at) }}
                </span>
                <span v-else class="text-medium-emphasis">—</span>
            </template>

            <!-- Зарплата -->
            <template v-slot:item.salary="{ item }">
                <span v-if="item.salary_from || item.salary_to" class="font-weight-medium">
                    <template v-if="item.salary_from && item.salary_to">
                        {{ formatSalary(item.salary_from) }} - {{ formatSalary(item.salary_to) }}
                    </template>
                    <template v-else-if="item.salary_from">
                        от {{ formatSalary(item.salary_from) }}
                    </template>
                    <template v-else-if="item.salary_to">
                        до {{ formatSalary(item.salary_to) }}
                    </template>
                </span>
                <span v-else class="text-medium-emphasis">—</span>
            </template>

            <template #bottom>
                <div class="pa-4 text-caption text-medium-emphasis d-flex justify-space-between align-center">
                    <span>
                        Всего вакансий: {{ filteredVacancies.length }} • 
                        Активных: {{ activeVacanciesCount }} • 
                        Закрытых: {{ closedVacanciesCount }}
                    </span>
                    <v-btn
                        variant="text"
                        color="success"
                        size="small"
                        @click="exportToExcel"
                        :loading="exportLoading"
                        prepend-icon="mdi-file-export"
                    >
                        Экспорт в Excel
                    </v-btn>
                </div>
            </template>
        </v-data-table>

        <!-- Кнопка добавления -->
        <v-card-actions class="ma-2 mt-4">
            <v-spacer />
            <v-btn
                variant="elevated"
                color="primary"
                prepend-icon="mdi-plus-circle"
                @click="handleNew"
                class="rounded-lg"
            >
                Новая вакансия
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">

// Реактивные переменные
const deleteDialogIsOpened = ref(false);
const editDialogIsOpened = ref(false);
const vacancyToDelete = ref({});
const vacancyToEdit = ref({});
const vacancies = ref([]);
const positions = ref([]);
const departments = ref([]);

// Фильтры и поиск
const searchQuery = ref('');
const departmentFilter = ref(null);
const statusFilter = ref(null);
const loading = ref(false);
const exportLoading = ref(false);
const filteredVacancies = ref([]);

// Заголовки таблицы
const headers = [
    { title: '', value: 'actions', width: 60, sortable: false },
    { title: 'Название вакансии', value: 'name', width: 250 },
    { title: 'Описание', value: 'description', width: 200 },
    { title: 'Требования', value: 'requirements', width: 200 },
    { title: 'Обязанности', value: 'responsibilities', width: 200 },
    { title: 'Кандидаты', value: 'candidate_count', width: 120, align: 'center' },
    { title: 'Статус', value: 'status', width: 130 },
    { title: 'Дата создания', value: 'created_at', width: 120 },
]

// Загрузка данных
const loadVacancies = async() => {
    loading.value = true;
    try {
        const { data } = await useApi('/api/vacancies');
        vacancies.value = data.value.data;
        applyFilters();
    } catch (error) {
        console.error('Ошибка загрузки вакансий:', error);
    } finally {
        loading.value = false;
    }
}

const loadPositions = async() => {
    try {
        const { data } = await useApi('/api/positions');
        positions.value = data.value.data;
    } catch (error) {
        console.error('Ошибка загрузки должностей:', error);
    }
}

const loadDepartments = async() => {
    try {
        const { data } = await useApi('/api/departments');
        departments.value = data.value.data;
    } catch (error) {
        console.error('Ошибка загрузки отделов:', error);
    }
}

// Опции для фильтров
const departmentOptions = computed(() => {
    return departments.value.map(dept => ({
        title: dept.name,
        value: dept.id
    }));
});

const statusOptions = [
    { title: 'Открыта', value: 'open' },
    { title: 'Закрыта', value: 'closed' },
    { title: 'Приостановлена', value: 'paused' },
    { title: 'Черновик', value: 'draft' },
];

// Функции фильтрации
const handleSearch = () => {
    applyFilters();
}

const clearSearch = () => {
    searchQuery.value = '';
    applyFilters();
}

const clearAllFilters = () => {
    searchQuery.value = '';
    departmentFilter.value = null;
    statusFilter.value = null;
    applyFilters();
}

const applyFilters = () => {
    let result = [...vacancies.value];

    // Поиск по названию
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase().trim();
        result = result.filter(vacancy => 
            vacancy.name?.toLowerCase().includes(query) ||
            vacancy.description?.toLowerCase().includes(query)
        );
    }

    // Фильтр по отделу
    if (departmentFilter.value) {
        result = result.filter(vacancy => 
            vacancy.department && vacancy.department.id === departmentFilter.value
        );
    }

    // Фильтр по статусу
    if (statusFilter.value) {
        result = result.filter(vacancy => vacancy.status === statusFilter.value);
    }

    filteredVacancies.value = result;
}

// Экспорт в Excel
const exportToExcel = async () => {
    exportLoading.value = true;
    try {
        // Подготавливаем данные для экспорта
        const exportData = filteredVacancies.value.map(vacancy => ({
            'ID': vacancy.id,
            'Название вакансии': vacancy.name || '',
            'Описание': vacancy.description || '',
            'Требования': vacancy.requirements || '',
            'Обязанности': vacancy.responsibilities || '',
            'Отдел': vacancy.department?.name || '',
            'Должность': vacancy.position?.name || '',
            'Количество кандидатов': vacancy.candidate_count || 0,
            'Статус': getStatusText(vacancy.status),
            'Зарплата от': vacancy.salary_from ? formatSalary(vacancy.salary_from) : '',
            'Зарплата до': vacancy.salary_to ? formatSalary(vacancy.salary_to) : '',
            'Дата создания': formatDate(vacancy.created_at),
            'Дата создания (оригинал)': vacancy.created_at || ''
        }));

        // Создаем CSV содержимое
        const headers = Object.keys(exportData[0]).join(';');
        const rows = exportData.map(row => 
            Object.values(row).map(value => `"${String(value).replace(/"/g, '""')}"`).join(';')
        );
        const csvContent = [headers, ...rows].join('\n');
        
        // Создаем blob и ссылку для скачивания
        const blob = new Blob(['\uFEFF' + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        // Создаем имя файла с датой
        const date = new Date().toISOString().split('T')[0];
        const fileName = `vacancies_report_${date}.csv`;
        
        link.setAttribute('href', url);
        link.setAttribute('download', fileName);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Освобождаем память
        setTimeout(() => URL.revokeObjectURL(url), 100);
        
        console.log('Файл успешно экспортирован:', fileName);
        
    } catch (error) {
        console.error('Ошибка при экспорте:', error);
        
        // Показываем сообщение об ошибке
        alert('Произошла ошибка при экспорте данных. Попробуйте еще раз.');
    } finally {
        exportLoading.value = false;
    }
}

// Статистика
const totalCandidates = computed(() => {
    return filteredVacancies.value.reduce((sum, vacancy) => sum + (vacancy.candidate_count || 0), 0);
});

const activeVacanciesCount = computed(() => {
    return filteredVacancies.value.filter(v => v.status === 'open').length;
});

const closedVacanciesCount = computed(() => {
    return filteredVacancies.value.filter(v => v.status === 'closed').length;
});

// Вспомогательные функции
const truncateText = (text, length) => {
    if (!text) return '—';
    return text.length > length ? text.substring(0, length) + '...' : text;
}

const formatDate = (dateString) => {
    if (!dateString) return '—';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('ru-RU');
    } catch {
        return dateString;
    }
}

const formatSalary = (salary) => {
    if (!salary) return '';
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0
    }).format(salary);
}

const getStatusColor = (status) => {
    const colors = {
        open: 'success',
        closed: 'error',
        paused: 'warning',
        draft: 'default'
    };
    return colors[status] || 'default';
}

const getStatusIcon = (status) => {
    const icons = {
        open: 'mdi-check-circle',
        closed: 'mdi-close-circle',
        paused: 'mdi-pause-circle',
        draft: 'mdi-file-document'
    };
    return icons[status] || 'mdi-help-circle';
}

const getStatusText = (status) => {
    const texts = {
        open: 'Открыта',
        closed: 'Закрыта',
        paused: 'Приостановлена',
        draft: 'Черновик'
    };
    return texts[status] || status;
}

const getCandidatesColor = (count) => {
    if (!count || count === 0) return 'grey';
    if (count < 5) return 'orange';
    if (count < 10) return 'blue';
    return 'green';
}

const viewCandidates = (vacancy) => {
    // Навигация к кандидатам вакансии
    console.log('Просмотр кандидатов для вакансии:', vacancy.id);
    // router.push(`/candidates?vacancy_id=${vacancy.id}`);
}

// Управление вакансиями
const handleDelete = (vacancy) => {
    deleteDialogIsOpened.value = true;
    vacancyToDelete.value = vacancy;
};

const cancelDelete = () => {
    deleteDialogIsOpened.value = false;
    vacancyToDelete.value = {};
};

const applyDelete = async() => {
    const id = vacancyToDelete.value.id;
    try {
        await useApi(`/api/vacancies/${id}`, {
            method: 'DELETE'
        });
        deleteDialogIsOpened.value = false;
        await loadVacancies();
    } catch (error) {
        console.error('Ошибка удаления вакансии:', error);
    }
}

const handleNew = () => {
    editDialogIsOpened.value = true;
    vacancyToEdit.value = {
        id: 0,
        status: 'draft'
    };
}

const handleEdit = (vacancy) => {
    editDialogIsOpened.value = true;
    vacancyToEdit.value = { ...vacancy };
}

const cancelEdit = () => {
    editDialogIsOpened.value = false;
    vacancyToEdit.value = {};
};

const applyEdit = async(payload) => {
    try {
        const id = payload.id;
        await useApi(`/api/vacancies/${id}`, {
            method: 'POST',
            body: payload
        });
        editDialogIsOpened.value = false;
        await loadVacancies();
    } catch (error) {
        console.error('Ошибка сохранения вакансии:', error);
    }
}

// Инициализация
onMounted(() => {
    loadVacancies();
    loadPositions();
    loadDepartments();
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.gap-x-2 {
    gap: 0.5rem;
}
.gap-x-3 {
    gap: 0.75rem;
}
</style>