<template>
    <VCard>
        {{ currentEmployee.birth_date }}
        <VCardTitle class="text-h5">
            {{ currentEmployee.id == 0? 'Добавление работника' : 'Редактирование работника'}}
        </VCardTitle>
        <VCardText class="pa-5">
            <VRow>
                <VCol cols="6">
                    <VTextField
                    label="Фамилия"
                    v-model="currentEmployee.last_name"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="email"
                    v-model="currentEmployee.email"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Имя"
                    v-model="currentEmployee.first_name"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Телефон"
                    v-model="currentEmployee.phone"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                    label="Отчество"
                    v-model="currentEmployee.middle_name"
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
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        :model-value="formatDate(currentEmployee.birth_date)"
                        readonly
                        @click="dateModalBirthDate = true"
                        label="Дата рождения"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        :model-value="formatDate(currentEmployee.hire_date)"
                        readonly
                        @click="dateModalHireDate = true"
                        label="Дата Найма"
                    />
                </VCol>
                <VCol cols="12">
                    <VTextField
                    label="Зарплата"
                    v-model="currentEmployee.salary"
                    />
                </VCol>
                <VCol cols="12">
                    <VCombobox
                    :items="['active','fired','vacation']"
                    v-model="currentEmployee.status"
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
                @click="$emit('apply', currentEmployee)"
            >
                Да
            </v-btn>
        </VCardActions>
    </VCard>
</template>

<script setup>



    const props = defineProps({
        employee: Object,
        positions: Array,
        departments: Array,
    });
    defineEmits(['apply', 'cancel']);

    const currentEmployee = ref({...props.employee});

    const dateModalBirthDate = ref(false)
    const dateModalHireDate = ref(false)
    const selectedBirthDate = ref(null)
    const selectedHireDate = ref(null)

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
    const year = date.value.getFullYear()
    const month = String(date.value.getMonth() + 1).padStart(2, '0')
    const day = String(date.value.getDate()).padStart(2, '0')

    return `${year}-${month}-${day}`
}

const saveHireDate = () => {
    currentEmployee.value.hire_date = formatDateForMySql(selectedHireDate);
    dateModalHireDate.value = false
}
const saveBirthDate = () =>{
    currentEmployee.value.birth_date = formatDateForMySql(selectedBirthDate);
    dateModalBirthDate.value = false;
}


</script>