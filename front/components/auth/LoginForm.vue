
<template>
    <div class="d-flex align-center text-center mb-6">
        <div class="text-h6 w-100 px-5 font-weight-regular auth-divider position-relative">

        </div>
    </div>
    {{ errorMessage }}
    <div>
        <v-row class="mb-3">
            <v-col cols="12">
                <v-label class="font-weight-medium mb-1">Почта</v-label>
                <v-text-field variant="outlined" class="pwdInput" hide-details color="primary" v-model="form.email"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-label class="font-weight-medium mb-1">Пароль</v-label>
                <v-text-field variant="outlined" class="border-borderColor" type="password" hide-details
                    color="primary" v-model="form.password"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-btn size="large" rounded="pill" color="primary" class="rounded-pill" block type="submit" flat @click="singIn">Войти</v-btn>
            </v-col>
        </v-row>
    </div>
</template>


<script setup lang="ts">
import { ref } from 'vue';
const checkbox = ref(false);


const form = ref({
    email: '',
    password: '',
  })
const user = ref({});
const errorMessage = ref('');

const { setUserData } = useUserData();

const singIn = async() =>{
    console.log();
    const { data } = await useApi('/api/login' , {
        method: 'POST',
        body: form
    });

    if(data.value.status === 'success') {
        user.value = data.value;

        console.log(user.value);

        setUserData({
            id: user.value.id,
            name: user.value.name,
            email: user.value.email,
            isLoggedIn: true,
        });
    }   else {
        errorMessage.value = data.value.data;
    }
}
</script>
