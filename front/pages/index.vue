<template>
    <v-card>
        <v-dialog
            v-model="deleteDialogIsOpened"
            persistent
            max-width="320"
        >
            <forms-confirm
                title="Внимание"
                :text="`Удалить пользователя ${userToDelete.name}?`"
                @cancel="cancelDelete"
                @apply="applyDelete"
            />
        </v-dialog>

        <v-dialog
            v-model="editDialogIsOpened"
            width="600"
            persistent
        >
            <forms-user-edit
                :user="userToEdit"
                @cancel="cancelEdit"
                @apply="applyEdit"
            />
        </v-dialog>

        <v-dialog
            v-model="surveyShowIsOpened"
            width="800"
            persistent
        >
            <manager-survey-show
            :surveyName ="surveyName"
            :answers="answers"
            :questions="questions"
            @cancel="cancelShow"
            />

        </v-dialog>

        <v-data-table 
            :headers="headers"
            :items="users"
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
                    <v-list-item>
                        <v-menu>
                            <template v-slot:activator="{ props }">
                                <v-btn
                                prepend-icon="ri-delete-bin-7-line"
                                class="justify-start"
                                block
                                color="red"
                                variant="text"
                                v-bind="props"
                                @click="userToShow = item"
                                >Опросы</v-btn>
                            </template>

                            <v-list>
                                <v-list-item v-for="item in surveys">
                                <v-btn
                                prepend-icon="ri-delete-bin-7-line"
                                class="justify-start"
                                block
                                color="red"
                                variant="text"
                                @click="loadAnswersQuestions(item.id, userToShow.item.id, item.name)"
                                > {{ item.name }}</v-btn>
                                </v-list-item>
                            </v-list>
                        </v-menu>
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

                        <span class="text-sm text-medium-emphasis">{{ item.position }}</span>
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
const surveyShowIsOpened = ref(false);
const userToDelete = ref({});   // пользователь, которого собираемся удалить
const userToEdit = ref({});
const userToShow = ref({});
const users = ref([]);
const surveys = ref([]);
const answers = ref([]);
const questions = ref([]);
const surveyName = ref('');

const headers = [
    {title: '', value: 'actions', width: 50},
    {title: 'ID', value: 'id'},
    {title: 'Имя/фамилия', value: 'name'},
    {title: 'Email', value: 'email'},
]

const loadSurveys = async() =>{

    const { data } = await useApi('/api/surveys');

    surveys.value = data.value;
}

const loadUsers = async() =>{

//   const { data }  = await useFetch('http://127.0.0.1:8000/api/users');
  const { data }  = await useApi('/api/users');
 
  users.value = data.value.data;

}

loadUsers();

loadSurveys();

//////////////// Просмотр Опросов

   const cancelShow = () =>{
    surveyShowIsOpened.value = false;
    userToShow.value = {};
   }

 /////////////// Удаление
    const handleDelete = (user) => {
        deleteDialogIsOpened.value = true;
        userToDelete.value = user;

    };

    const cancelDelete = () => {
        deleteDialogIsOpened.value = false;
        userToDelete.value = {};
    };

    const applyDelete = async() => {
        const id = userToDelete.value.id;
        await useApi(`/api/users/${id}`, {
            method: 'DELETE'
            });
        
        deleteDialogIsOpened.value = false;

        loadUsers();
    }

 //////////////// Добавление и Редактировние

 const handleNew = () => {
        editDialogIsOpened.value = true;
        userToEdit.value = {
            id: 0,
            name: '',
            email: '',
            // phone: '',
            // password: ''
        };
    }

    const handleEdit = (user) => {
        editDialogIsOpened.value = true;
        userToEdit.value = user;
    }

    const cancelEdit = () => {
        editDialogIsOpened.value = false;
        userToEdit.value = {};
    };

    const applyEdit = async(payload) => {
        console.log(payload);
        const id = payload.id;
      await useApi(`/api/users/${id}`, {
      method: 'POST',
      body: payload
    });

    editDialogIsOpened.value = false;
    loadUsers();
    }
    const loadAnswersQuestions = async(surveyId, userId, survey_name) => {

        surveyShowIsOpened.value = true;

        surveyName.value = survey_name;

        console.log("ID", surveyId, userId);

        const { data }  = await useApi(`/api/surveys/${surveyId}/users/${userId}`);
 
        answers.value = data.value.answers;
        questions.value = data.value.questions;

    }
</script>