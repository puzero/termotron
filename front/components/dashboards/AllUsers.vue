<script setup lang="ts">
import { ref } from 'vue';
import {productPerformance} from '@/data/dashboard/dashboardData';
const items = ref([
    { title: "Зарегистрировать" },
    { title: "Удалить" },
    { title: "Изменить" },
]);
</script>
<template>
    <v-card elevation="10" class="pb-5">
        <v-card-item class="pa-6">
            <div class="d-flex align-center justify-space-between">
                <div>
                    <h5 class="text-h5 mb-1 font-weight-semibold">Работники</h5>
                </div>
                <div>
                    <v-menu bottom left>
                        <template v-slot:activator="{ props }">
                            <v-btn icon color="inherit" v-bind="props" flat>
                                <DotsVerticalIcon stroke-width="1.5" size="24" class="text-grey100" />
                            </v-btn>
                        </template>
                        <v-list density="compact">
                            <v-list-item v-for="(item, i) in items" :key="i" :value="i">
                                <v-list-item-title>{{ item.title }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </div>
            </div>
        <v-table class="month-table" fixed-header height="300px">
            <thead>
                <tr>
                    <th class="text-subtitle-1 font-weight-bold">Id</th>
                    <th class="text-subtitle-1 font-weight-bold">Имя</th>
                    <th class="text-subtitle-1 font-weight-bold">Фамилия</th>
                    <th class="text-subtitle-1 font-weight-bold">Отчество</th>
                    <th class="text-subtitle-1 font-weight-bold">Email</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in productPerformance" :key="item.name" class="month-item">
                    <td>
                        <p class="text-15 font-weight-medium">{{ item.id }}</p>
                    </td>
                    <td>
                        <div class="">
                                <h6 class="text-subtitle-1 font-weight-bold">{{ item.name }}</h6>
                                <div class="text-subtitle-2 mt-1 text-muted">{{ item.post }}</div>
                        </div>
                    </td>
                    <td>
                        <h6 class="text-body-1 text-muted">{{ item.pname }}</h6>
                    </td>
                    <td>
                        <h6 class="text-body-1 text-muted">{{ item.lastName }}</h6>
                    </td>
                    <td>
                        <h6 class="text-h6 text-body-1">{{ item.email }}</h6>
                    </td>
                </tr>
            </tbody>
        </v-table>
        </v-card-item>
    </v-card>
</template>
