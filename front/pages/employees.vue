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
                :text="`Удалить работника ${employeeToDelete.last_name} ${employeeToDelete.first_name}?`"
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
            <forms-empployee-edit
                :employee="employeeToEdit"
                :departments="departments"
                :positions="positions"
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
                        label="Поиск по ФИО"
                        placeholder="Введите фамилию, имя или отчество"
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
                        v-model="positionFilter"
                        :items="positionOptions"
                        label="Фильтр по должности"
                        clearable
                        variant="outlined"
                        @update:model-value="applyFilters"
                    />
                </v-col>
                <v-col cols="12" md="2">
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
            <v-alert v-if="filteredEmployees.length > 0" type="info" density="compact" class="mb-4">
                <div class="d-flex justify-space-between align-center flex-wrap">
                    <span>Показано: {{ filteredEmployees.length }} из {{ employees.length }} сотрудников</span>
                </div>
            </v-alert>
        </v-card-text>

        <!-- Таблица отчета -->
        <v-data-table 
            :headers="headers"
            :items="filteredEmployees"
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
                    <div v-if="item.phone" class="d-flex align-center gap-x-1 mb-1">
                        <v-icon size="16" color="primary">mdi-phone</v-icon>
                        <span class="text-caption">{{ formatPhone(item.phone) }}</span>
                    </div>
                    <div v-if="item.email" class="d-flex align-center gap-x-1">
                        <v-icon size="16" color="primary">mdi-email</v-icon>
                        <span class="text-caption">{{ item.email }}</span>
                    </div>
                </div>
            </template>

            <!-- Дата найма -->
            <template v-slot:item.hire_date="{ item }">
                <span v-if="item.hire_date">
                    {{ formatDate(item.hire_date) }}
                </span>
                <span v-else class="text-medium-emphasis">—</span>
            </template>

            <!-- Статус -->
            <template v-slot:item.status="{ item }">
                <v-chip 
                    :color="getStatusColor(item.status)" 
                    size="small"
                    variant="flat"
                >
                    {{ getStatusText(item.status) }}
                </v-chip>
            </template>

            <!-- Отдел -->
            <template v-slot:item.department="{ item }">
                <v-chip 
                    v-if="item.department"
                    variant="outlined"
                    size="small"
                    color="primary"
                >
                    {{ item.department.name }}
                </v-chip>
                <span v-else class="text-medium-emphasis">—</span>
            </template>

            <!-- Должность -->
            <template v-slot:item.position="{ item }">
                <span v-if="item.position">
                    {{ item.position.name }}
                </span>
                <span v-else class="text-medium-emphasis">—</span>
            </template>

            <template #bottom>
                <div class="pa-4 text-caption text-medium-emphasis d-flex justify-space-between align-center">
                    <span>Всего записей: {{ filteredEmployees.length }}</span>
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
                prepend-icon="mdi-account-plus"
                @click="handleNew"
                class="rounded-lg"
            >
                Новый сотрудник
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">

// Реактивные переменные
const deleteDialogIsOpened = ref(false);
const editDialogIsOpened = ref(false);
const employeeToDelete = ref({});
const employeeToEdit = ref({});
const employees = ref([]);
const positions = ref([]);
const departments = ref([]);

// Фильтры и поиск
const searchQuery = ref('');
const departmentFilter = ref(null);
const positionFilter = ref(null);
const loading = ref(false);
const exportLoading = ref(false);
const filteredEmployees = ref([]);

// Заголовки таблицы
const headers = [
    { title: '', value: 'actions', width: 60, sortable: false },
    { title: 'ФИО', value: 'full_name', width: 250 },
    { title: 'Должность', value: 'position.name' },
    { title: 'Отдел', value: 'department.name' },
    { title: 'Дата найма', value: 'hire_date', width: 120 },
    { title: 'Контакты', value: 'contacts', width: 200 },
    { title: 'Статус', value: 'status', width: 120 },
]

// Загрузка данных
const loadEmployees = async() => {
    loading.value = true;
    try {
        const { data } = await useApi('/api/employees');
        employees.value = data.value.data;
        applyFilters(); // Применяем фильтры после загрузки
    } catch (error) {
        console.error('Ошибка загрузки сотрудников:', error);
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

const positionOptions = computed(() => {
    return positions.value.map(pos => ({
        title: pos.name,
        value: pos.id
    }));
});

// Функции фильтрации и поиска
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
    positionFilter.value = null;
    applyFilters();
}

const applyFilters = () => {
    let result = [...employees.value];

    // Поиск по ФИО
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase().trim();
        result = result.filter(employee => {
            const fullName = `${employee.last_name || ''} ${employee.first_name || ''} ${employee.middle_name || ''}`
                .toLowerCase()
                .trim();
            return fullName.includes(query);
        });
    }

    // Фильтр по отделу
    if (departmentFilter.value) {
        result = result.filter(employee => 
            employee.department && employee.department.id === departmentFilter.value
        );
    }

    // Фильтр по должности
    if (positionFilter.value) {
        result = result.filter(employee => 
            employee.position && employee.position.id === positionFilter.value
        );
    }

    filteredEmployees.value = result;
}

// Экспорт в Excel
const exportToExcel = async () => {
    exportLoading.value = true;
    try {
        // Подготавливаем данные для экспорта
        const exportData = filteredEmployees.value.map(employee => ({
            'ID': employee.id,
            'Фамилия': employee.last_name || '',
            'Имя': employee.first_name || '',
            'Отчество': employee.middle_name || '',
            'Полное ФИО': `${employee.last_name || ''} ${employee.first_name || ''} ${employee.middle_name || ''}`.trim(),
            'Должность': employee.position?.name || '',
            'Отдел': employee.department?.name || '',
            'Дата найма': formatDate(employee.hire_date),
            'Email': employee.email || '',
            'Телефон': employee.phone || '',
            'Статус': getStatusText(employee.status),
            'Дата найма (оригинал)': employee.hire_date || ''
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
        const fileName = `employees_report_${date}.csv`;
        
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

// Вспомогательные функции
const getInitials = (employee) => {
    const first = employee.first_name?.[0] || '';
    const last = employee.last_name?.[0] || '';
    return (first + last).toUpperCase();
}

const formatPhone = (phone) => {
    // Простой форматирование телефона
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

const getStatusColor = (status) => {
    const colors = {
        active: 'success',
        inactive: 'error',
        vacation: 'warning',
        fired: 'error'
    };
    return colors[status] || 'default';
}

const getStatusText = (status) => {
    const texts = {
        active: 'Активен',
        inactive: 'Неактивен',
        vacation: 'Отпуск',
        fired: 'Уволен'
    };
    return texts[status] || status;
}

// Управление сотрудниками
const handleDelete = (employee) => {
    deleteDialogIsOpened.value = true;
    employeeToDelete.value = employee;
};

const cancelDelete = () => {
    deleteDialogIsOpened.value = false;
    employeeToDelete.value = {};
};

const applyDelete = async() => {
    const id = employeeToDelete.value.id;
    try {
        await useApi(`/api/employees/${id}`, {
            method: 'DELETE'
        });
        deleteDialogIsOpened.value = false;
        await loadEmployees(); // Перезагружаем данные
    } catch (error) {
        console.error('Ошибка удаления сотрудника:', error);
    }
}

const handleNew = () => {
    editDialogIsOpened.value = true;
    employeeToEdit.value = {
        id: 0,
        status: 'active'
    };
}

const handleEdit = (employee) => {
    editDialogIsOpened.value = true;
    employeeToEdit.value = { ...employee }; // Создаем копию для редактирования
}

const cancelEdit = () => {
    editDialogIsOpened.value = false;
    employeeToEdit.value = {};
};

const applyEdit = async(payload) => {
    try {
        const id = payload.id;
        await useApi(`/api/employees/${id}`, {
            method: 'POST',
            body: payload
        });
        editDialogIsOpened.value = false;
        await loadEmployees(); // Перезагружаем данные
    } catch (error) {
        console.error('Ошибка сохранения сотрудника:', error);
    }
}

// Инициализация
onMounted(() => {
    loadEmployees();
    loadPositions();
    loadDepartments();
});
</script>

<style scoped>
.user-list-name {
    line-height: 1.2;
}
.gap-x-3 {
    gap: 0.75rem;
}
.gap-x-1 {
    gap: 0.25rem;
}
</style>