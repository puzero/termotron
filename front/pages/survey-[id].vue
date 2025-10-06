<template>
  {{ questions }}
      <emploee-ending-form v-if="currentStep > questions.length " @cancel="currentStep--" @pushAnswers="pushAnswers"/>
  <v-stepper v-model="currentStep" v-else>
    <v-stepper-header>
      <v-stepper-item
        v-for="(question, index) in questions"
        :value="index + 1"

        :complete="currentStep > index + 1"
        editable></v-stepper-item>
    </v-stepper-header>
    <v-stepper-window>
      <v-stepper-window-item
        v-for="(question, index) in questions"
        :key="index + 1"
        :value="index + 1"
      >
          <emploee-answer :question="question"/>
      </v-stepper-window-item>
    </v-stepper-window>
    <v-card-actions class="px-4 pb-4 justify-end">
      <v-btn 
        color="secondary" 
        @click="prev"
        :disabled="currentStep === 0"
        class="mr-2"
      >
        Назад
      </v-btn>
      <v-btn 
        color="primary" 
        @click="next"
      >
        {{ currentStep === questions.length ? 'Завершить' : 'Далее' }}
      </v-btn>
    </v-card-actions>
  </v-stepper>
    
</template>

<script setup lang="ts">



definePageMeta({
  layout: "survey",
});


const questions = ref([]);

const endFormIsOpened = ref(false);

const currentStep = ref(0); 

// const steps = ref([{title: 'шаг 1', value: 0,complete:false},{title: 'шаг 2', value: 1,complete:false},{title: 'шаг 3', value: 2,compleete:false}])

const next = () =>{
  // console.log(step.value, questions.value.length);
  // if(currentStep.value < questions.value.length){
  currentStep.value++;

  // }
}
const prev = () =>{
  if(currentStep.value > 0){
  currentStep.value--;
  }
}


const route = useRoute();
const surveyId = route.params.id;


const loadQuestions = async () => {

  const { data } = await useApi(`/api/questions/survey/${surveyId}`);
  questions.value = data.value;

  questions.value.forEach(element => (element.answer_text = ''));

  console.log(questions.value.length);
  
  }


loadQuestions();
 
 const endSurvey = () =>{
  questions.value.forEach(element => {
    if(element.answer_text == ""){

    }
    
  });

    endFormIsOpened.value = true;

 }

 const pushAnswers = async() => {

  const answers = questions.value.map(element => {
    return {
      question_id: element.id,
      text: element.answer_text,
    };
  });

  console.log(answers);

   await useApi('/api/answers', {
    method: 'POST',
    body: {
      answers,
    }
  });

 };


</script>

