<template>
    <VCard>
        <VCardTitle class="text-h5">
            {{ currentVacancy.id == 0 ? 'Добавление вакансии' : 'Редактирование вакансии' }}
        </VCardTitle>
        <VCardText class="pa-5">
            <VRow>
                <VCol cols="6">
                    <VTextField
                    label="Название"
                    v-model="currentVacancy.name"
                    :error="!!errors.name"
                    :error-messages="errors.name"
                    @blur="validateField('name')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Обязанности"
                    v-model="currentVacancy.responsibilities"
                    :error="!!errors.responsibilities"
                    :error-messages="errors.responsibilities"
                    @blur="validateField('responsibilities')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Опыт"
                    v-model.number="currentVacancy.experience"
                    type="number"
                    :error="!!errors.experience"
                    :error-messages="errors.experience"
                    @blur="validateField('experience')"
                    />
                </VCol>
                <VCol cols="6">
                    <VCombobox
                    :items="positions"
                    item-title="name"
                    item-value="id"
                    v-model="currentVacancy.position_id"
                    label="Должность"
                    :return-object="false"
                    :error="!!errors.position_id"
                    :error-messages="errors.position_id"
                    @blur="validateField('position_id')"
                    @update:model-value="handlePositionChange"
                    />
                </VCol>
                <VCol cols="6">
                    <VCombobox
                    :items="departments"
                    item-title="name"
                    item-value="id"
                    v-model="currentVacancy.department_id"
                    label="Отдел"
                    :return-object="false"
                    :error="!!errors.department_id"
                    :error-messages="errors.department_id"
                    @blur="validateField('department_id')"
                    @update:model-value="handleDepartmentChange"
                    />
                </VCol>
                <VCol cols="6">
                    <VSelect
                    :items="statuses"
                    item-title="title"
                    item-value="value"
                    v-model="currentVacancy.status"
                    label="Статус"
                    :error="!!errors.status"
                    :error-messages="errors.status"
                    @blur="validateField('status')"
                    @update:model-value="handleStatusChange"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextarea
                    label="Описание"
                    v-model="currentVacancy.description"
                    :error="!!errors.description"
                    :error-messages="errors.description"
                    @blur="validateField('description')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextarea
                    label="Требования"
                    v-model="currentVacancy.requirements"
                    :error="!!errors.requirements"
                    :error-messages="errors.requirements"
                    @blur="validateField('requirements')"
                    />
                </VCol>
                <VCol cols="12">
                    <VTextField
                    label="Количество кандидатов"
                    v-model="currentVacancy.candidate_count"
                    type="number"
                    :error="!!errors.candidate_count"
                    :error-messages="errors.candidate_count"
                    @blur="validateField('candidate_count')"
                    />
                </VCol>
            </VRow>
        </VCardText>
        <VCardActions class="pa-4">
            <VSpacer />
            <VBtn
                color="primary darken-1"
                variant="text"
                @click="$emit('cancel')"
            >
                Нет
            </VBtn>
            <v-btn
                color="primary"
                variant="text"
                @click="applyWithValidation"
                :disabled="!isFormValid"
            >
                Да
            </v-btn>
        </VCardActions>
    </VCard>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    vacancy: Object,
    positions: Array,
    departments: Array,
});
const emit = defineEmits(['apply', 'cancel']);

// Статусы с русскими labels
const statuses = computed(() => [
    { title: 'Открыта', value: 'open' },
    { title: 'Закрыта', value: 'closed' },
    { title: 'Приостановлена', value: 'paused' },
    { title: 'Черновик', value: 'draft' },
])

// Правильная инициализация currentVacancy
const currentVacancy = ref({
    id: props.vacancy?.id || 0,
    name: props.vacancy?.name || '',
    responsibilities: props.vacancy?.responsibilities || '',
    experience: props.vacancy?.experience || 0,
    position_id: props.vacancy?.position_id || null,
    department_id: props.vacancy?.department_id || null,
    status: props.vacancy?.status || 'draft',
    description: props.vacancy?.description || '',
    requirements: props.vacancy?.requirements || '',
    candidate_count: props.vacancy?.candidate_count || 1
})

// Объект для хранения ошибок
const errors = ref({
    name: '',
    responsibilities: '',
    experience: '',
    position_id: '',
    department_id: '',
    status: '',
    description: '',
    requirements: '',
    candidate_count: ''
})

// Проверка валидности формы
const isFormValid = computed(() => {
    return !Object.values(errors.value).some(error => error !== '') &&
           currentVacancy.value.name?.trim() &&
           currentVacancy.value.responsibilities?.trim() &&
           currentVacancy.value.experience !== null &&
           currentVacancy.value.experience !== undefined &&
           currentVacancy.value.position_id &&
           currentVacancy.value.department_id &&
           currentVacancy.value.status &&
           currentVacancy.value.description?.trim() &&
           currentVacancy.value.requirements?.trim() &&
           currentVacancy.value.candidate_count !== null &&
           currentVacancy.value.candidate_count !== undefined
})

// Обработчики для combobox чтобы гарантировать строковые значения
const handleStatusChange = (value) => {
    // Если значение массив - берем первый элемент, иначе используем как есть
    if (Array.isArray(value)) {
        currentVacancy.value.status = value[0] || 'draft'
    } else {
        currentVacancy.value.status = value || 'draft'
    }
    validateField('status')
}

const handlePositionChange = (value) => {
    if (Array.isArray(value)) {
        currentVacancy.value.position_id = value[0] || null
    } else {
        currentVacancy.value.position_id = value
    }
    validateField('position_id')
}

const handleDepartmentChange = (value) => {
    if (Array.isArray(value)) {
        currentVacancy.value.department_id = value[0] || null
    } else {
        currentVacancy.value.department_id = value
    }
    validateField('department_id')
}

// Валидация полей
const validateField = (fieldName) => {
    const value = currentVacancy.value[fieldName]
    
    switch (fieldName) {
        case 'name':
            if (!value || !value.trim()) {
                errors.value.name = 'Название обязательно для заполнения'
            } else if (value.trim().length < 2) {
                errors.value.name = 'Название должно содержать минимум 2 символа'
            } else {
                errors.value.name = ''
            }
            break
            
        case 'responsibilities':
            if (!value || !value.trim()) {
                errors.value.responsibilities = 'Обязанности обязательны для заполнения'
            } else if (value.trim().length < 10) {
                errors.value.responsibilities = 'Описание обязанностей должно содержать минимум 10 символов'
            } else {
                errors.value.responsibilities = ''
            }
            break
            
        case 'experience':
            if (value === null || value === undefined || value === '') {
                errors.value.experience = 'Опыт обязателен для заполнения'
            } else {
                const experience = Number(value)
                if (isNaN(experience)) {
                    errors.value.experience = 'Опыт должен быть числом'
                } else if (experience < 0) {
                    errors.value.experience = 'Опыт не может быть отрицательным'
                } else if (experience > 50) {
                    errors.value.experience = 'Опыт не может превышать 50 лет'
                } else {
                    errors.value.experience = ''
                }
            }
            break
            
        case 'position_id':
            if (!value) {
                errors.value.position_id = 'Должность обязательна для выбора'
            } else {
                errors.value.position_id = ''
            }
            break
            
        case 'department_id':
            if (!value) {
                errors.value.department_id = 'Отдел обязателен для выбора'
            } else {
                errors.value.department_id = ''
            }
            break
            
        case 'status':
            if (!value) {
                errors.value.status = 'Статус обязателен для выбора'
            } else {
                errors.value.status = ''
            }
            break
            
        case 'description':
            if (!value || !value.trim()) {
                errors.value.description = 'Описание обязательно для заполнения'
            } else if (value.trim().length < 10) {
                errors.value.description = 'Описание должно содержать минимум 10 символов'
            } else {
                errors.value.description = ''
            }
            break
            
        case 'requirements':
            if (!value || !value.trim()) {
                errors.value.requirements = 'Требования обязательны для заполнения'
            } else if (value.trim().length < 10) {
                errors.value.requirements = 'Требования должны содержать минимум 10 символов'
            } else {
                errors.value.requirements = ''
            }
            break
            
        case 'candidate_count':
            if (value === null || value === undefined || value === '') {
                errors.value.candidate_count = 'Количество кандидатов обязательно для заполнения'
            } else {
                const count = Number(value)
                if (isNaN(count)) {
                    errors.value.candidate_count = 'Количество кандидатов должно быть числом'
                } else if (count <= 0) {
                    errors.value.candidate_count = 'Количество кандидатов должно быть положительным числом'
                } else if (count > 100) {
                    errors.value.candidate_count = 'Количество кандидатов не может превышать 100'
                } else {
                    errors.value.candidate_count = ''
                }
            }
            break
    }
}

// Метод для отправки с валидацией
const applyWithValidation = () => {
    // Валидируем все поля перед отправкой
    Object.keys(errors.value).forEach(field => {
        validateField(field)
    })
    
    // Очищаем пробелы в текстовых полях и преобразуем числа
    // Гарантируем что статус - строка, а не массив
    const cleanedVacancy = {
        ...currentVacancy.value,
        name: currentVacancy.value.name?.trim() || '',
        responsibilities: currentVacancy.value.responsibilities?.trim() || '',
        description: currentVacancy.value.description?.trim() || '',
        requirements: currentVacancy.value.requirements?.trim() || '',
        experience: currentVacancy.value.experience ? Number(currentVacancy.value.experience) : 0,
        candidate_count: currentVacancy.value.candidate_count ? Number(currentVacancy.value.candidate_count) : 1,
        // Гарантируем что статус - строка
        status: Array.isArray(currentVacancy.value.status) 
            ? (currentVacancy.value.status[0] || 'draft')
            : (currentVacancy.value.status || 'draft')
    }
    
    if (isFormValid.value) {
        emit('apply', cleanedVacancy)
    } else {
        console.log('Форма содержит ошибки, отправка отменена')
    }
}
</script>