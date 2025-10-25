<template>
    <v-card>
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="320"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить работника ${employeeToDelete.last_name + employeeToDelete.first_name}?`"
                @cancel="cancelDelete"
                @apply="applyDelete"
            />
        </v-dialog>

        <v-dialog
            v-model="editDialogIsOpened"
            width="600"
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

        <v-data-table 
            :headers="headers"
            :items="employees"
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
                            {{ item.last_name }}
                        </h6>
                    </div>
                </div>
            </template>
            <template v-slot:item.role="{ item }">
                {{ item }}
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
const employeeToDelete = ref({});   // пользователь, которого собираемся удалить
const employeeToEdit = ref({});
const employeeToShow = ref({});
const employees = ref([]);
const positions = ref([]);
const departments = ref([]);

const headers = [
    {title: '', value: 'actions', width: 50},
    {title: 'ID', value: 'id'},
    {title: 'Фамилия', value: 'name'},
    {title: 'Должность', value: 'position.name'},
    {title: 'Отдел', value: 'department.name'},
]


const loadEmployees = async() =>{

//   const { data }  = await useFetch('http://127.0.0.1:8000/api/users');
  const { data }  = await useApi('/api/employees');
 
  employees.value = data.value.data;

  console.log(employees.value[0], "EMPLOYEE")

}

const loadPositions = async() =>{
    const {data} = await useApi('/api/positions');

    positions.value = data.value.data;
}

const loadDepartments = async() =>{
    const {data} = await useApi('/api/departments');

    departments.value = data.value.data;
}


loadEmployees();
loadPositions();
loadDepartments();


 /////////////// Удаление
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
        await useApi(`/api/employees/${id}`, {
            method: 'DELETE'
            });
        
        deleteDialogIsOpened.value = false;

        loadEmployees();
    }

 //////////////// Добавление и Редактировние

 const handleNew = () => {
        editDialogIsOpened.value = true;
        employeeToEdit.value = {
            id: 0,
            status: 'active'
        };
    }

    const handleEdit = (employee) => {
        editDialogIsOpened.value = true;
        employeeToEdit.value = employee;
    }

    const cancelEdit = () => {
        editDialogIsOpened.value = false;
        employeeToEdit.value = {};
    };

    const applyEdit = async(payload) => {
        console.log(payload.birth_date);
        const id = payload.id;
      await useApi(`/api/employees/${id}`, {
      method: 'POST',
      body: payload
    });

    editDialogIsOpened.value = false;
    loadEmployees();
    }
</script>