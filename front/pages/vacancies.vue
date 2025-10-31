<template>
    <v-card>
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="320"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить Вакансию ${vacancyToDelete.name}?`"
                @cancel="cancelDelete"
                @apply="applyDelete"
            />
        </v-dialog>

        <v-dialog
            v-model="editDialogIsOpened"
            width="600"
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

        <v-data-table 
            :headers="headers"
            :items="vacancies"
            items-per-page="20"
        >
            <template v-slot:item.actions="item"> 
                <v-menu>
                    
                    <template v-slot:activator="{ props }">
                    <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props"/>
                    </template>

                    <v-list>
                    <v-list-item>
                            <v-btn
                                class="justify-start"
                                block
                                variant="flat"
                                color="red"
                                @click="handleEdit(item.item)"
                                prepend-icon="ri-file-edit-line"
                            >
                            Редактировать
                            </v-btn>
                    </v-list-item>
                    <v-list-item>
                            <v-btn
                                class="justify-start"
                                block
                                color="red"
                                variant="flat"
                                @click="handleDelete(item.item)"
                                prepend-icon="ri-delete-bin-7-line"
                            >
                            Удалить
                            </v-btn>
                    </v-list-item>
                </v-list>
            </v-menu>
            </template>

            <template v-slot:item.name="{ item }">
                <div class="d-flex align-center gap-x-4">
                    <div class="d-flex flex-column">
                        <h6 class="text-h6 font-weight-medium user-list-name">
                            {{ item.name }}
                        </h6>
                    </div>
                </div>
            </template>

            <template #bottom />
        </v-data-table>
        <v-card-actions class="ma-2 mt-10">
            <v-spacer />
            <v-btn
                variant="elevated"
                prepend-icon="mdi-account-circle"
                @click="handleNew"
                class="rounded-lg"
            >
                Новый пользователь
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup lang="ts">

const deleteDialogIsOpened = ref(false);
const editDialogIsOpened = ref(false);
const vacancyToDelete = ref({});   // пользователь, которого собираемся удалить
const vacancyToEdit = ref({});
const employeeToShow = ref({});
const vacancies = ref([]);
const positions = ref([]);
const departments = ref([]);

const headers = [
    {title: '', value: 'actions', width: 50},
    {title: 'ID', value: 'id'},
    {title: 'Название', value: 'name'},
    {title: 'Отдел', value: 'department.name'},
    {title: 'Позиция', value: 'position.name'},
    {title: 'Требования', value: 'responsibilities'}
]


const loadVacancies = async() =>{

//   const { data }  = await useFetch('http://127.0.0.1:8000/api/users');
  const { data }  = await useApi('/api/vacancies');
 
  vacancies.value = data.value.data;

  console.log(vacancies.value[0], "EMPLOYEE")

}

const loadPositions = async() =>{
    const {data} = await useApi('/api/positions');

    positions.value = data.value.data;
}

const loadDepartments = async() =>{
    const {data} = await useApi('/api/departments');

    departments.value = data.value.data;
}


loadVacancies();
loadPositions();
loadDepartments();


 /////////////// Удаление
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
        await useApi(`/api/vacancies/${id}`, {
            method: 'DELETE'
            });
        
        deleteDialogIsOpened.value = false;

        loadVacancies();
    }

 //////////////// Добавление и Редактировние

    const handleNew = () => {
        editDialogIsOpened.value = true;
        vacancyToEdit.value = {
            id: 0,
            status: 'active'
        };
    }

    const handleEdit = (vacancy) => {
        editDialogIsOpened.value = true;
        vacancyToEdit.value = vacancy;
    }

    const cancelEdit = () => {
        editDialogIsOpened.value = false;
        vacancyToEdit.value = {};
    };

    const applyEdit = async(payload) => {
        const id = payload.id;
      await useApi(`/api/vacancies/${id}`, {
      method: 'POST',
      body: payload
    });

    editDialogIsOpened.value = false;
    loadVacancies();
    }
</script>