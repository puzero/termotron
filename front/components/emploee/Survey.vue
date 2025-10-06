<script setup lang="ts">
import { Title } from '#components';




  const surveys = [
  {
    title: "Тестирование 1",
    completed: false,
    number: 1,
    questions: [{
      number: 1,
      text: "Вопрос 1?"
    },
    {
      number: 2,
      text: "Вопрос 2?"
    },
    {
      number: 3,
      text: "Вопрос 3?"
    }
    ]
  },
  {
    title: "Тестирование 2",
    completed: false,
    number: 2
  },
  {
    title: "Тестирование 3",
    completed: false,
    number: 3
  },
  {
    title: "Тестирование 4",
    completed: false,
    number: 4
  },
  ]
</script>


<template>

<v-container>
      <v-expansion-panels v-for="item in surveys" class="pa-3">
        <v-expansion-panel
          :disabled="item.completed"
          class="pa-4 rounded-lg"
        >
        <v-expansion-panel-title>
                <h2>{{ item.title }}</h2>
            </v-expansion-panel-title>
            <v-expansion-panel-text>
              Это тестировние ля-ля-ля ля-ля-ля
                <div class="d-flex justify-end">
                    <v-btn 
                    :to="'/survey-'+item.number"
                    class="rounded-lg">К тестированию</v-btn>
                </div>
            </v-expansion-panel-text>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-container>

</template>