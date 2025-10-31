<template>
    <VCard>
        <VCardTitle class="text-h5">
            {{ currentCandidate.id == 0 ? 'Добавление кандидата' : 'Редактирование кандидата' }}
        </VCardTitle>
        <VCardText class="pa-5">
            <VForm>
                <VRow>
                    <VCol cols="6">
                        <VTextField
                            label="Фамилия"
                            v-model="currentCandidate.last_name"
                            :error="!!errors.last_name && touched.last_name"
                            :error-messages="touched.last_name ? errors.last_name : ''"
                            @blur="markTouched('last_name'); validateField('last_name')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            label="email"
                            v-model="currentCandidate.email"
                            :error="!!errors.email && touched.email"
                            :error-messages="touched.email ? errors.email : ''"
                            @blur="markTouched('email'); validateField('email')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            label="Имя"
                            v-model="currentCandidate.first_name"
                            :error="!!errors.first_name && touched.first_name"
                            :error-messages="touched.first_name ? errors.first_name : ''"
                            @blur="markTouched('first_name'); validateField('first_name')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            label="Телефон"
                            v-model="currentCandidate.phone"
                            :error="!!errors.phone && touched.phone"
                            :error-messages="touched.phone ? errors.phone : ''"
                            @blur="markTouched('phone'); validateField('phone')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            label="Отчество"
                            v-model="currentCandidate.middle_name"
                            :error="!!errors.middle_name && touched.middle_name"
                            :error-messages="touched.middle_name ? errors.middle_name : ''"
                            @blur="markTouched('middle_name'); validateField('middle_name')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VSelect
                            :items="statuses"
                            item-title="title"
                            item-value="value"
                            v-model="currentCandidate.status"
                            label="Статус"
                            :error="!!errors.status && touched.status"
                            :error-messages="touched.status ? errors.status : ''"
                            @blur="markTouched('status'); validateField('status')"
                        />
                    </VCol>
                    <VCol cols="12">
                        <VTextarea
                            label="Резюме"
                            v-model="currentCandidate.resume_text"
                            :error="!!errors.resume_text && touched.resume_text"
                            :error-messages="touched.resume_text ? errors.resume_text : ''"
                            @blur="markTouched('resume_text'); validateField('resume_text')"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            :model-value="formatDate(currentCandidate.birth_date)"
                            readonly
                            @click="dateModalBirthDate = true"
                            label="Дата рождения"
                            :error="!!errors.birth_date && touched.birth_date"
                            :error-messages="touched.birth_date ? errors.birth_date : ''"
                        />
                    </VCol>
                    <VCol cols="6">
                        <VTextField
                            :model-value="formatDate(currentCandidate.application_date)"
                            readonly
                            @click="dateModalApplicationDate = true"
                            label="Дата подачи заявки"
                            :error="!!errors.application_date && touched.application_date"
                            :error-messages="touched.application_date ? errors.application_date : ''"
                        />
                    </VCol>
                </VRow>
            </VForm>

            <VDialog
                v-model="dateModalBirthDate"
                persistent
                max-width="320"
            >
                <VCard>
                    <VCardText class="pa-0">
                        <VDatePicker
                            v-model="selectedBirthDate"
                            locale="ru"
                            show-adjacent-months
                        />
                    </VCardText>
                    <VCardActions>
                        <VSpacer />
                        <VBtn variant="outlined" @click="dateModalBirthDate = false">Отмена</VBtn>
                        <VBtn color="primary" @click="saveBirthDate">Сохранить</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>

            <VDialog
                v-model="dateModalApplicationDate"
                persistent
                max-width="320"
            >
                <VCard>
                    <VCardText class="pa-0">
                        <VDatePicker
                            v-model="selectedApplicationDate"
                            locale="ru"
                            show-adjacent-months
                        />
                    </VCardText>
                    <VCardActions>
                        <VSpacer />
                        <VBtn variant="outlined" @click="dateModalApplicationDate = false">Отмена</VBtn>
                        <VBtn color="primary" @click="saveApplicationDate">Сохранить</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>
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
    candidate: Object,
});
const emit = defineEmits(['apply', 'cancel']);

// Статусы с русскими labels
const statuses = [
    { title: 'Новый', value: 'new' },
    { title: 'Собеседование', value: 'interview' },
    { title: 'Отклонен', value: 'Rejected' },
    { title: 'Принят', value: 'Accepted' }
]

// Правильная инициализация currentCandidate
const currentCandidate = ref({
    id: props.candidate?.id || 0,
    last_name: props.candidate?.last_name || '',
    first_name: props.candidate?.first_name || '',
    middle_name: props.candidate?.middle_name || '',
    email: props.candidate?.email || '',
    phone: props.candidate?.phone || '',
    status: props.candidate?.status || 'Новый',
    resume_text: props.candidate?.resume_text || '',
    birth_date: props.candidate?.birth_date || null,
    application_date: props.candidate?.application_date || null
})

const dateModalBirthDate = ref(false)
const dateModalApplicationDate = ref(false)
const selectedBirthDate = ref(currentCandidate.value.birth_date ? new Date(currentCandidate.value.birth_date) : null)
const selectedApplicationDate = ref(currentCandidate.value.application_date ? new Date(currentCandidate.value.application_date) : null)

// Объект для хранения ошибок
const errors = ref({
    last_name: '',
    first_name: '',
    middle_name: '',
    email: '',
    phone: '',
    status: '',
    resume_text: '',
    birth_date: '',
    application_date: ''
})

// Объект для отслеживания touched полей
const touched = ref({
    last_name: false,
    first_name: false,
    middle_name: false,
    email: false,
    phone: false,
    status: false,
    resume_text: false,
    birth_date: false,
    application_date: false
})

// Функция для отметки поля как touched
const markTouched = (fieldName) => {
    touched.value[fieldName] = true
}

// Проверка валидности формы
const isFormValid = computed(() => {
    return !Object.values(errors.value).some(error => error !== '') &&
           currentCandidate.value.last_name?.trim() &&
           currentCandidate.value.first_name?.trim() &&
           currentCandidate.value.middle_name?.trim() &&
           currentCandidate.value.email?.trim() &&
           currentCandidate.value.phone?.trim() &&
           currentCandidate.value.status &&
           currentCandidate.value.resume_text?.trim() &&
           currentCandidate.value.birth_date &&
           currentCandidate.value.application_date
})

const formatDate = (date) => {
    if (!date) return ''
    if (typeof date === 'string' && date.includes(' ')) {
        const [datePart] = date.split(' ') 
        const [year, month, day] = datePart.split('-')
        return `${day}.${month}.${year}` 
    }

    const d = new Date(date)
    return d.toLocaleDateString('ru-RU')
}

const formatDateForMySql = (date) => {
    if (!date) return null
    const d = new Date(date)
    const year = d.getFullYear()
    const month = String(d.getMonth() + 1).padStart(2, '0')
    const day = String(d.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

// Функции для проверки дат
const isBirthDateValid = (date) => {
    if (!date) {
        return 'Дата рождения обязательна для заполнения'
    }
    
    const birthDate = new Date(date)
    if (isNaN(birthDate.getTime())) {
        return 'Некорректная дата рождения'
    }
    
    const today = new Date()
    if (birthDate > today) {
        return 'Дата рождения не может быть в будущем'
    }
    
    // Проверяем возраст (от 18 до 80 лет)
    const age = today.getFullYear() - birthDate.getFullYear()
    const monthDiff = today.getMonth() - birthDate.getMonth()
    const dayDiff = today.getDate() - birthDate.getDate()
    
    const actualAge = monthDiff < 0 || (monthDiff === 0 && dayDiff < 0) ? age - 1 : age
    
    if (actualAge < 18) {
        return 'Возраст должен быть не менее 18 лет'
    }
    
    if (actualAge > 80) {
        return 'Возраст должен быть не более 80 лет'
    }
    
    return ''
}

const isApplicationDateValid = (date) => {
    if (!date) {
        return 'Дата подачи заявки обязательна для заполнения'
    }
    
    const applicationDate = new Date(date)
    if (isNaN(applicationDate.getTime())) {
        return 'Некорректная дата подачи заявки'
    }
    
    const today = new Date()
    if (applicationDate > today) {
        return 'Дата подачи заявки не может быть в будущем'
    }
    
    // Проверяем, что дата не слишком старая (например, не раньше 2020 года)
    const minDate = new Date(2020, 0, 1)
    if (applicationDate < minDate) {
        return 'Дата подачи заявки не может быть раньше 2020 года'
    }
    
    return ''
}

// Валидация полей
const validateField = (fieldName) => {
    const value = currentCandidate.value[fieldName]
    
    switch (fieldName) {
        case 'last_name':
        case 'first_name':
            if (!value || !value.trim()) {
                errors.value[fieldName] = 'Обязательное поле'
            } else if (value.trim().length < 2) {
                errors.value[fieldName] = 'Минимум 2 символа'
            } else if (!/^[A-Za-zА-Яа-яЁё\s-]+$/.test(value.trim())) {
                errors.value[fieldName] = 'Только буквы, пробелы и дефисы разрешены'
            } else {
                errors.value[fieldName] = ''
            }
            break
            
        case 'middle_name':
            if (!value || !value.trim()) {
                errors.value.middle_name = 'Обязательное поле'
            } else if (value.trim().length < 2) {
                errors.value.middle_name = 'Минимум 2 символа'
            } else if (!/^[A-Za-zА-Яа-яЁё\s-]+$/.test(value.trim())) {
                errors.value.middle_name = 'Только буквы, пробелы и дефисы разрешены'
            } else {
                errors.value.middle_name = ''
            }
            break
            
        case 'email':
            if (!value || !value.trim()) {
                errors.value.email = 'Обязательное поле'
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim())) {
                errors.value.email = 'Некорректный формат email'
            } else {
                errors.value.email = ''
            }
            break
            
        case 'phone':
            if (!value || !value.trim()) {
                errors.value.phone = 'Обязательное поле'
            } else if (!/^[\+]?[0-9\s\-\(\)]{10,15}$/.test(value.trim())) {
                errors.value.phone = 'Некорректный формат телефона (10-15 цифр)'
            } else {
                errors.value.phone = ''
            }
            break
            
        case 'status':
            if (!value) {
                errors.value.status = 'Обязательное поле'
            } else {
                errors.value.status = ''
            }
            break
            
        case 'resume_text':
            if (!value || !value.trim()) {
                errors.value.resume_text = 'Резюме обязательно для заполнения'
            } else if (value.trim().length < 10) {
                errors.value.resume_text = 'Резюме должно содержать минимум 10 символов'
            } else {
                errors.value.resume_text = ''
            }
            break
            
        case 'birth_date':
            errors.value.birth_date = isBirthDateValid(value)
            break
            
        case 'application_date':
            errors.value.application_date = isApplicationDateValid(value)
            break
    }
}

const saveApplicationDate = () => {
    if (selectedApplicationDate.value) {
        currentCandidate.value.application_date = formatDateForMySql(selectedApplicationDate.value);
        markTouched('application_date')
        validateField('application_date')
    }
    dateModalApplicationDate.value = false
}

const saveBirthDate = () => {
    if (selectedBirthDate.value) {
        currentCandidate.value.birth_date = formatDateForMySql(selectedBirthDate.value);
        markTouched('birth_date')
        validateField('birth_date')
    }
    dateModalBirthDate.value = false;
}

// Метод для отправки с валидацией
const applyWithValidation = () => {
    // Помечаем все поля как touched перед валидацией
    Object.keys(touched.value).forEach(field => {
        touched.value[field] = true
    })
    
    // Валидируем все поля перед отправкой
    Object.keys(errors.value).forEach(field => {
        validateField(field)
    })
    
    // Очищаем пробелы в текстовых полях
    const cleanedCandidate = {
        ...currentCandidate.value,
        last_name: currentCandidate.value.last_name?.trim() || '',
        first_name: currentCandidate.value.first_name?.trim() || '',
        middle_name: currentCandidate.value.middle_name?.trim() || '',
        email: currentCandidate.value.email?.trim() || '',
        phone: currentCandidate.value.phone?.trim() || '',
        resume_text: currentCandidate.value.resume_text?.trim() || ''
    }
    
    if (isFormValid.value) {
        emit('apply', cleanedCandidate)
    } else {
        console.log('Форма содержит ошибки, отправка отменена')
    }
}

// Watchers для автоматической валидации при изменении дат
watch(selectedBirthDate, (newVal) => {
    if (newVal) {
        currentCandidate.value.birth_date = formatDateForMySql(newVal)
        markTouched('birth_date')
        validateField('birth_date')
    }
})

watch(selectedApplicationDate, (newVal) => {
    if (newVal) {
        currentCandidate.value.application_date = formatDateForMySql(newVal)
        markTouched('application_date')
        validateField('application_date')
    }
})
</script>