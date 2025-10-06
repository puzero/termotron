<script setup lang="ts">
/*-For Set Blank Layout-*/
definePageMeta({
  layout: "blank",
});
</script>
<template>
    <div class="authentication">
        <v-container fluid class="pa-3">
            <v-row class="h-100vh d-flex justify-center align-center">
                <v-col cols="12" class="d-flex align-center">
                    <div class="boxed-auth-wrap">
                        <v-card rounded="xl" elevation="10" class="px-sm-1 px-0  mx-auto index-2" max-width="450">
                            <v-card-item class="pa-sm-8">
                                <div class="d-flex justify-center mb-5">
                                   <h2>Регистрация пользователя</h2>
                                </div>
                                <div class="d-flex align-center text-center mb-6">
                                    <div class="text-h6 w-100 px-5 font-weight-regular auth-divider position-relative">
                                    </div>
                                </div>
                                <AuthRegisterForm />
                            </v-card-item>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div></template>
