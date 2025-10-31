<template>
    <VCard>
        {{ currentEmployee.first_name }}
        <VCardTitle class="text-h5">
            {{ currentEmployee.id == 0? 'Добавление работника' : 'Редактирование работника'}}
        </VCardTitle>
        <VCardText class="pa-5">
            <VRow>
                <VCol cols="6">
                    <VTextField
                    label="Фамилия"
                    v-model="currentEmployee.last_name"
                    :error-messages="errors.last_name"
                    @blur="validateField('last_name')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="email"
                    v-model="currentEmployee.email"
                    :error-messages="errors.email"
                    @blur="validateField('email')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Имя"
                    v-model="currentEmployee.first_name"
                    :error-messages="errors.first_name"
                    @blur="validateField('first_name')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Телефон"
                    v-model="currentEmployee.phone"
                    :error-messages="errors.phone"
                    @blur="validateField('phone')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Отчество"
                    v-model="currentEmployee.middle_name"
                    :error-messages="errors.middle_name"
                    @blur="validateField('middle_name')"
                    />
                </VCol>
                <VCol cols="6">
                    <VCombobox
                    :items="positions"
                    item-title="name"
                    item-value="id"
                    v-model="currentEmployee.position_id"
                    label="Должность"
                    :return-object="false"
                    :error-messages="errors.position_id"
                    @blur="validateField('position_id')"
                    />
                </VCol>
                <VCol cols="6">
                    <VCombobox
                    :items="departments"
                    item-title="name"
                    item-value="id"
                    v-model="currentEmployee.department_id"
                    label="Отдел"
                    :return-object="false"
                    :error-messages="errors.department_id"
                    @blur="validateField('department_id')"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        :model-value="formatDate(currentEmployee.birth_date)"
                        readonly
                        @click="dateModalBirthDate = true"
                        label="Дата рождения"
                        :error-messages="errors.birth_date"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        :model-value="formatDate(currentEmployee.hire_date)"
                        readonly
                        @click="dateModalHireDate = true"
                        label="Дата Найма"
                        :error-messages="errors.hire_date"
                    />
                </VCol>
                <VCol cols="12">
                    <VTextField
                    label="Зарплата"
                    v-model="currentEmployee.salary"
                    type="number"
                    :error-messages="errors.salary"
                    @blur="validateField('salary')"
                    />
                </VCol>
                <VCol cols="12">
                    <VCombobox
                    :items="['active','fired','vacation']"
                    v-model="currentEmployee.status"
                    :error-messages="errors.status"
                    @blur="validateField('status')"
                    />
                </VCol>
            </VRow>
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
                    v-model="dateModalHireDate"
                    persistent
                    max-width="320"
                >
                    <VCard>
                        <VCardText class="pa-0">
                            <VDatePicker
                                v-model="selectedHireDate"
                                locale="ru"
                                show-adjacent-months
                            />
                        </VCardText>
                        <VCardActions>
                            <VSpacer />
                            <VBtn variant="outlined" @click="dateModalHireDate = false">Отмена</VBtn>
                            <VBtn color="primary" @click="saveHireDate">Сохранить</VBtn>
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
    employee: Object,
    positions: Array,
    departments: Array,
});
const emit = defineEmits(['apply', 'cancel']);

const currentEmployee = ref({...props.employee});

const dateModalBirthDate = ref(false)
const dateModalHireDate = ref(false)
const selectedBirthDate = ref(null)
const selectedHireDate = ref(null)

// Объект для хранения ошибок
const errors = ref({
    last_name: '',
    first_name: '',
    middle_name: '',
    email: '',
    phone: '',
    position_id: '',
    department_id: '',
    birth_date: '',
    hire_date: '',
    salary: '',
    status: ''
})

// Проверка валидности формы
const isFormValid = computed(() => {
    return !Object.values(errors.value).some(error => error !== '') &&
           currentEmployee.value.last_name &&
           currentEmployee.value.first_name &&
           currentEmployee.value.middle_name &&
           currentEmployee.value.email &&
           currentEmployee.value.phone &&
           currentEmployee.value.position_id &&
           currentEmployee.value.department_id &&
           currentEmployee.value.birth_date &&
           currentEmployee.value.hire_date &&
           currentEmployee.value.salary &&
           currentEmployee.value.status
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
    
    // Проверяем, что дата является корректной
    const birthDate = new Date(date)
    if (isNaN(birthDate.getTime())) {
        return 'Некорректная дата рождения'
    }
    
    // Проверяем, что дата не в будущем
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

const isHireDateValid = (date) => {
    if (!date) {
        return 'Дата найма обязательна для заполнения'
    }
    
    // Проверяем, что дата является корректной
    const hireDate = new Date(date)
    if (isNaN(hireDate.getTime())) {
        return 'Некорректная дата найма'
    }
    
    // Проверяем, что дата не в будущем
    const today = new Date()
    if (hireDate > today) {
        return 'Дата найма не может быть в будущем'
    }
    
    // Проверяем, что дата не слишком старая (например, не раньше 2000 года)
    const minDate = new Date(2000, 0, 1) // 1 января 2000 года
    if (hireDate < minDate) {
        return 'Дата найма не может быть раньше 2000 года'
    }
    
    return ''
}

// Валидация полей
const validateField = (fieldName) => {
    const value = currentEmployee.value[fieldName]
    
    switch (fieldName) {
        case 'last_name':
        case 'first_name':
            if (!value) {
                errors.value[fieldName] = 'Обязательное поле'
            } else if (value.length < 2) {
                errors.value[fieldName] = 'Минимум 2 символа'
            } else {
                errors.value[fieldName] = ''
            }
            break
            
        case 'middle_name':
            if (!value) {
                errors.value.middle_name = 'Обязательное поле'
            } else if (value.length < 2) {
                errors.value.middle_name = 'Минимум 2 символа'
            } else {
                errors.value.middle_name = ''
            }
            break
            
        case 'email':
            if (!value) {
                errors.value.email = 'Обязательное поле'
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                errors.value.email = 'Некорректный формат email'
            } else {
                errors.value.email = ''
            }
            break
            
        case 'phone':
            if (!value) {
                errors.value.phone = 'Обязательное поле'
            } else if (!/^[\+]?[0-9\s\-\(\)]{10,15}$/.test(value)) {
                errors.value.phone = 'Некорректный формат телефона'
            } else {
                errors.value.phone = ''
            }
            break
            
        case 'position_id':
        case 'department_id':
            if (!value) {
                errors.value[fieldName] = 'Обязательное поле'
            } else {
                errors.value[fieldName] = ''
            }
            break
            
        case 'birth_date':
            errors.value.birth_date = isBirthDateValid(value)
            break
            
        case 'hire_date':
            errors.value.hire_date = isHireDateValid(value)
            break
            
        case 'salary':
            if (!value) {
                errors.value.salary = 'Обязательное поле'
            } else {
                const salary = Number(value)
                if (isNaN(salary) || salary <= 0) {
                    errors.value.salary = 'Зарплата должна быть положительным числом'
                } else if (salary > 1000000) {
                    errors.value.salary = 'Зарплата не может превышать 1,000,000'
                } else {
                    errors.value.salary = ''
                }
            }
            break
            
        case 'status':
            if (!value) {
                errors.value.status = 'Обязательное поле'
            } else {
                errors.value.status = ''
            }
            break
    }
}

const saveHireDate = () => {
    if (selectedHireDate.value) {
        currentEmployee.value.hire_date = formatDateForMySql(selectedHireDate.value);
        validateField('hire_date')
    }
    dateModalHireDate.value = false
}

const saveBirthDate = () => {
    if (selectedBirthDate.value) {
        currentEmployee.value.birth_date = formatDateForMySql(selectedBirthDate.value);
        validateField('birth_date')
    }
    dateModalBirthDate.value = false;
}

// Метод для отправки с валидацией
const applyWithValidation = () => {
    // Валидируем все поля перед отправкой
    Object.keys(errors.value).forEach(field => {
        validateField(field)
    })
    
    if (isFormValid.value) {
        emit('apply', currentEmployee.value)
    }
}
</script>