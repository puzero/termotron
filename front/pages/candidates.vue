<template>
    <v-card>
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="320"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить Кандидата ${candidateToDelete.last_name}?`"
                @cancel="cancelDelete"
                @apply="applyDelete"
            />
        </v-dialog>

        <v-dialog
            v-model="editDialogIsOpened"
            width="600"
            persistent
        >
            <forms-candidate-edit
                :candidate="candidateToEdit"
                @cancel="cancelEdit"
                @apply="applyEdit"
            />
        </v-dialog>

        <v-data-table 
            :headers="headers"
            :items="candidates"
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
import { Value } from 'sass';


const deleteDialogIsOpened = ref(false);
const editDialogIsOpened = ref(false);
const candidateToDelete = ref({});   // пользователь, которого собираемся удалить
const candidateToEdit = ref({});
const employeeToShow = ref({});
const candidates = ref([]);
const positions = ref([]);
const departments = ref([]);

const headers = [
    {title: '', value: 'actions', width: 50},
    {title: 'ID', value: 'id'},
    {title: 'Имя', value: 'last_name'},
    {title: 'Email', value: 'email'},
    {title: 'Телефон', value: 'phone'},
    {title: 'Статус', value: 'status'},
]


const loadCandidates = async() =>{

//   const { data }  = await useFetch('http://127.0.0.1:8000/api/users');
  const { data }  = await useApi('/api/candidates');
 
  candidates.value = data.value.data;

}

const loadPositions = async() =>{
    const {data} = await useApi('/api/positions');

    positions.value = data.value.data;
}

const loadDepartments = async() =>{
    const {data} = await useApi('/api/departments');

    departments.value = data.value.data;
}


loadCandidates();
loadPositions();
loadDepartments();


 /////////////// Удаление
    const handleDelete = (vacancy) => {
        deleteDialogIsOpened.value = true;
        candidateToDelete.value = vacancy;

    };

    const cancelDelete = () => {
        deleteDialogIsOpened.value = false;
        candidateToDelete.value = {};
    };

    const applyDelete = async() => {
        const id = candidateToDelete.value.id;
        await useApi(`/api/candidates/${id}`, {
            method: 'DELETE'
            });
        
        deleteDialogIsOpened.value = false;

        loadCandidates();
    }

 //////////////// Добавление и Редактировние

    const handleNew = () => {
        editDialogIsOpened.value = true;
        candidateToEdit.value = {
            id: 0,
            status: 'active'
        };
    }

    const handleEdit = (candidate) => {
        editDialogIsOpened.value = true;
        candidateToEdit.value = candidate;
    }

    const cancelEdit = () => {
        editDialogIsOpened.value = false;
        candidateToEdit.value = {};
    };

    const applyEdit = async(payload) => {
        console.log(payload)
        const id = payload.id;
      await useApi(`/api/candidates/${id}`, {
      method: 'POST',
      body: payload
    });

    editDialogIsOpened.value = false;
    loadCandidates();
    }
</script>