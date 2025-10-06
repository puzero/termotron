
<template>
    <v-row>
        <v-col>
          <v-container>
      <v-expansion-panels v-for="item in surveys" class="pa-3">
        <v-expansion-panel
          :disabled="item.completed"
          class="pa-4 rounded-lg"
        >
        <v-expansion-panel-title>
                <h2>{{ item.name }}</h2>
            </v-expansion-panel-title>
            <v-expansion-panel-text>
              Это тестировние ля-ля-ля ля-ля-ля
                <div class="d-flex justify-end">
                    <v-btn 
                    :to="'/survey-'+item.id"
                    class="rounded-lg">К тестированию</v-btn>
                </div>
            </v-expansion-panel-text>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-container>
        </v-col>
    </v-row>
</template>


<script setup lang="ts">
import { ref } from "vue";
import ProfitExpanse from "@/components/dashboards/ProfitExpense.vue";
import TrafficDistribution from "@/components/dashboards/TrafficDistribution.vue";
import ProductSales from "@/components/dashboards/ProductSales.vue";
import UpcommingSchedule from "@/components/dashboards/UpcommingSchedule.vue";
import TopPayingClients from "~/components/dashboards/AllUsers.vue";
import ProductCards from "@/components/dashboards/ProductCards.vue";


definePageMeta({
  layout: "emploee",
});
const disabled = ref(false);


  const surveys = ref([]);


  const loadSurveys = async() =>{

  const { data } = await useApi('/api/surveys');

  surveys.value = data.value;
  }

  loadSurveys();
</script>