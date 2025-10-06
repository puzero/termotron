<script setup lang="ts">
import { ref } from "vue";
import ProfitExpanse from "@/components/dashboards/ProfitExpense.vue";
import TrafficDistribution from "@/components/dashboards/TrafficDistribution.vue";
import ProductSales from "@/components/dashboards/ProductSales.vue";
import UpcommingSchedule from "@/components/dashboards/UpcommingSchedule.vue";
import TopPayingClients from "~/components/dashboards/AllUsers.vue";
import ProductCards from "@/components/dashboards/ProductCards.vue";

import UiChildCard from '@/components/shared/UiChildCard.vue';
import AllUsers from "~/components/dashboards/AllUsers.vue";
const desserts = ref([
    {
        name: "Frozen Yogurt",
        calories: 159,
    },
    {
        name: "Ice cream sandwich",
        calories: 237,
    },
    {
        name: "Eclair",
        calories: 262,
    },
    {
        name: "Cupcake",
        calories: 305,
    },
    {
        name: "Gingerbread",
        calories: 356,
    },
    {
        name: "Jelly bean",
        calories: 375,
    },
    {
        name: "Lollipop",
        calories: 392,
    },
    {
        name: "Honeycomb",
        calories: 408,
    },
    {
        name: "Donut",
        calories: 452,
    },
    {
        name: "KitKat",
        calories: 518,
    },
]);
</script>

<template>
    <v-row>
        <v-col cols="12" sm="12">
            <AllUsers/>
        </v-col>
        <v-col cols="12" sm="12" >
            <UiChildCard title="General Table">
                
                <v-table>
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Calories</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in desserts" :key="item.name">
                            <td>{{ item.name }}</td>
                            <td>{{ item.calories }}</td>
                            <td>{{ item.calories }}</td>
                        </tr>
                    </tbody>
                </v-table>

            </UiChildCard>
        </v-col>
        <v-col cols="12" sm="12" lg="8">
            <TopPayingClients/>
        </v-col>
        <v-col cols="12" sm="12" lg="8">
            <ProfitExpanse />
        </v-col>
        <v-col cols="12" sm="12" lg="4">
            <div class="mb-6">
                <TrafficDistribution/>
            </div>
            <div>
                <ProductSales/>
            </div>
        </v-col>
        <v-col cols="12" sm="12" lg="4">
            <UpcommingSchedule/>
        </v-col>
        <v-col cols="12" sm="12" lg="8">
            <TopPayingClients/>
        </v-col>
        <v-col cols="12" sm="12" lg="12">
            <ProductCards/>
        </v-col>
        <v-col class="text-center mt-2">
            <p class="text-muted">Design and Developed by <a flat variant="text" href="https://www.wrappixel.com/" target="_blank" class="pl-1 text-primary">Max Kuznetsov</a></p>
        </v-col>
    </v-row>
</template>