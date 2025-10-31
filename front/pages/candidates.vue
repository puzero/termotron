<template>
    <v-card>
        <!-- Диалог удаления -->
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="400"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить кандидата ${candidateToDelete.last_name} ${candidateToDelete.first_name}?`"
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
            <forms-candidate-edit
                :candidate="candidateToEdit"
                @cancel="cancelEdit"
                @apply="applyEdit"
            />
        </v-dialog>

        <!-- Панель управления отчетом -->
        <v-card-text>
            <v-row>
                <v-col cols="12" md="4">
                    <v-text-field
                        v-model="searchQuery"
                        label="Поиск по ФИО или контактам"
                        placeholder="Введите фамилию, имя, email или телефон"
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        clearable
                        @input="handleSearch"
                        @click:clear="clearSearch"
                    />
                </v-col>
                <v-col cols="12" md="3">
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
            <v-alert v-if="filteredCandidates.length > 0" type="info" density="compact" class="mb-4">
                <div class="d-flex justify-space-between align-center flex-wrap">
                    <span>Показано: {{ filteredCandidates.length }} из {{ candidates.length }} кандидатов</span>
                </div>
            </v-alert>
        </v-card-text>

        <!-- Таблица отчета -->
        <v-data-table 
            :headers="headers"
            :items="filteredCandidates"
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
                    </v-list>
                </v-menu>
            </template>

            <!-- ФИО -->
            <template v-slot:item.full_name="{ item }">
                <div class="d-flex align-center gap-x-3">
                    <v-avatar size="36" color="primary" class="text-white">
                        {{ getInitials(item) }}
                    </v-avatar>
                    <div class="d-flex flex-column">
                        <strong class="text-body-1">
                            {{ item.last_name }} {{ item.first_name }} {{ item.middle_name || '' }}
                        </strong>
                        <span class="text-caption text-medium-emphasis">
                            ID: {{ item.id }}
                        </span>
                    </div>
                </div>
            </template>

            <!-- Контакты -->
            <template v-slot:item.contacts="{ item }">
                <div class="d-flex flex-column">
                    <div v-if="item.email" class="d-flex align-center gap-x-1 mb-1">
                        <v-icon size="16" color="primary">mdi-email</v-icon>
                        <a :href="`mailto:${item.email}`" class="text-caption text-decoration-none">
                            {{ item.email }}
                        </a>
                    </div>
                    <div v-if="item.phone" class="d-flex align-center gap-x-1">
                        <v-icon size="16" color="primary">mdi-phone</v-icon>
                        <a :href="`tel:${item.phone}`" class="text-caption text-decoration-none">
                            {{ formatPhone(item.phone) }}
                        </a>
                    </div>
                    <div v-if="!item.email && !item.phone" class="text-caption text-medium-emphasis">
                        Контакты не указаны
                    </div>
                </div>
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

            <!-- Дата добавления -->
            <template v-slot:item.created_at="{ item }">
                <div class="d-flex flex-column">
                    <span class="text-caption font-weight-medium">
                        {{ formatDate(item.created_at) }}
                    </span>
                    <span v-if="item.created_at" class="text-caption text-medium-emphasis">
                        {{ formatTimeAgo(item.created_at) }}
                    </span>
                </div>
            </template>

            <template #bottom>
                <div class="pa-4 text-caption text-medium-emphasis d-flex justify-space-between align-center">
                    <span>Всего кандидатов: {{ filteredCandidates.length }}</span>
                    <v-btn
                        variant="text"
                        color="success"
                        size="small"
                        @click="exportToExcel"
                        :loading="exportLoading"
                        prepend-icon="mdi-file-export"
                    >
                        Экспорт отчета
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
                prepend-icon="mdi-account-plus"
                @click="handleNew"
                class="rounded-lg"
            >
                Новый кандидат
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">
import type { Value } from 'sass';



// Реактивные переменные
const deleteDialogIsOpened = ref(false);
const editDialogIsOpened = ref(false);
const candidateToDelete = ref({});
const candidateToEdit = ref({});
const candidates = ref([]);

// Фильтры и поиск
const searchQuery = ref('');
const statusFilter = ref(null);
const loading = ref(false);
const exportLoading = ref(false);
const filteredCandidates = ref([]);

// Заголовки таблицы
const headers = [
    { title: '', value: 'actions', width: 60, sortable: false },
    { title: 'ФИО', value: 'full_name', width: 250 },
    { title: 'Контакты', value: 'contacts', width: 250 },
    { title: 'Статус', value: 'status', width: 150 },
    { title: 'Дата добавления', value: 'created_at', width: 150 },
]

// Опции статусов
const statusOptions = [
    { title: 'Новый', value: 'new' },
    { title: 'Собеседование', value: 'interview' },
    { title: 'Отклонен', value: 'Rejected' },
    { title: 'Принят', value: 'Accepted' }
];

// Загрузка данных
const loadCandidates = async() => {
    loading.value = true;
    try {
        const { data } = await useApi('/api/candidates');
        candidates.value = data.value.data;
        applyFilters();
    } catch (error) {
        console.error('Ошибка загрузки кандидатов:', error);
    } finally {
        loading.value = false;
    }
}

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
    statusFilter.value = null;
    applyFilters();
}

const applyFilters = () => {
    let result = [...candidates.value];

    // Поиск по ФИО и контактам
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase().trim();
        result = result.filter(candidate => {
            const fullName = `${candidate.last_name || ''} ${candidate.first_name || ''} ${candidate.middle_name || ''}`
                .toLowerCase()
                .trim();
            return fullName.includes(query) || 
                   candidate.email?.toLowerCase().includes(query) ||
                   candidate.phone?.includes(query);
        });
    }

    // Фильтр по статусу
    if (statusFilter.value) {
        result = result.filter(candidate => candidate.status === statusFilter.value);
    }

    filteredCandidates.value = result;
}

// Экспорт в CSV/Excel
const exportToExcel = async () => {
    exportLoading.value = true;
    try {
        // Подготавливаем данные для экспорта
        const exportData = filteredCandidates.value.map(candidate => ({
            'ID': candidate.id,
            'Фамилия': candidate.last_name || '',
            'Имя': candidate.first_name || '',
            'Отчество': candidate.middle_name || '',
            'Email': candidate.email || '',
            'Телефон': candidate.phone || '',
            'Статус': getStatusText(candidate.status),
            'Дата добавления': formatDate(candidate.created_at),
            'Полное ФИО': `${candidate.last_name || ''} ${candidate.first_name || ''} ${candidate.middle_name || ''}`.trim()
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
        const fileName = `candidates_report_${date}.csv`;
        
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
const activeCandidatesCount = computed(() => {
    return filteredCandidates.value.filter(c => 
        ['new', 'review', 'interview', 'test', 'offer'].includes(c.status)
    ).length;
});

const inReviewCount = computed(() => {
    return filteredCandidates.value.filter(c => 
        ['review', 'interview', 'test'].includes(c.status)
    ).length;
});

const archivedCount = computed(() => {
    return filteredCandidates.value.filter(c => 
        ['rejected', 'archived'].includes(c.status)
    ).length;
});

// Вспомогательные функции
const getInitials = (candidate) => {
    const first = candidate.first_name?.[0] || '';
    const last = candidate.last_name?.[0] || '';
    return (first + last).toUpperCase();
}

const formatPhone = (phone) => {
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length === 11) {
        return `+${cleaned[0]} (${cleaned.slice(1,4)}) ${cleaned.slice(4,7)}-${cleaned.slice(7,9)}-${cleaned.slice(9)}`;
    }
    return phone;
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

const formatTimeAgo = (dateString) => {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return 'сегодня';
    if (diffDays === 2) return 'вчера';
    if (diffDays < 7) return `${diffDays - 1} дня назад`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} недели назад`;
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} месяца назад`;
    return `${Math.floor(diffDays / 365)} года назад`;
}

const getStatusColor = (status) => {
    const colors = {
        new: 'blue',
        review: 'light-blue',
        interview: 'orange',
        test: 'deep-orange',
        offer: 'green',
        hired: 'success',
        rejected: 'error',
        archived: 'grey'
    };
    return colors[status] || 'default';
}

const getStatusIcon = (status) => {
    const icons = {
        new: 'mdi-account-plus',
        review: 'mdi-eye',
        interview: 'mdi-account-voice',
        test: 'mdi-file-document',
        offer: 'mdi-handshake',
        hired: 'mdi-check-circle',
        rejected: 'mdi-close-circle',
        archived: 'mdi-archive'
    };
    return icons[status] || 'mdi-help-circle';
}

const getStatusText = (status) => {
    const texts = {
        new: 'Новый',
        review: 'На рассмотрении',
        interview: 'Собеседование',
        test: 'Тестовое задание',
        offer: 'Оффер',
        hired: 'Принят',
        rejected: 'Отказ',
        archived: 'Архив'
    };
    return texts[status] || status;
}

const viewDetails = (candidate) => {
    console.log('Просмотр деталей кандидата:', candidate.id);
    // Логика просмотра детальной информации
}

// Управление кандидатами
const handleDelete = (candidate) => {
    deleteDialogIsOpened.value = true;
    candidateToDelete.value = candidate;
};

const cancelDelete = () => {
    deleteDialogIsOpened.value = false;
    candidateToDelete.value = {};
};

const applyDelete = async() => {
    const id = candidateToDelete.value.id;
    try {
        await useApi(`/api/candidates/${id}`, {
            method: 'DELETE'
        });
        deleteDialogIsOpened.value = false;
        await loadCandidates();
    } catch (error) {
        console.error('Ошибка удаления кандидата:', error);
    }
}

const handleNew = () => {
    editDialogIsOpened.value = true;
    candidateToEdit.value = {
        id: 0,
        status: 'new'
    };
}

const handleEdit = (candidate) => {
    editDialogIsOpened.value = true;
    candidateToEdit.value = { ...candidate };
}

const cancelEdit = () => {
    editDialogIsOpened.value = false;
    candidateToEdit.value = {};
};

const applyEdit = async(payload) => {
    try {
        const id = payload.id;
        await useApi(`/api/candidates/${id}`, {
            method: 'POST',
            body: payload
        });
        editDialogIsOpened.value = false;
        await loadCandidates();
    } catch (error) {
        console.error('Ошибка сохранения кандидата:', error);
    }
}

// Инициализация
onMounted(() => {
    loadCandidates();
});
</script>

<style scoped>
.gap-x-1 {
    gap: 0.25rem;
}
.gap-x-3 {
    gap: 0.75rem;
}
.text-decoration-none {
    text-decoration: none;
}
</style>