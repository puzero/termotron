<script setup lang="ts">



 const answer = ref('');

 const props = defineProps({
        question: Object,
    });
    defineEmits(['cancel', 'apply']);
</script>

<template>
<v-card height="200">
    <v-card-title>{{ question.text }}</v-card-title>
    <v-text-field
        class="position-absolute bottom-0 w-100"
        v-model="question.answer_text"
        :rules="[() => !!answer || 'This field is required']"
        label="Ответ"
        required
        ></v-text-field>
</v-card>
</template>