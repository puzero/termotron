<script setup lang="ts">

    defineEmits(['cancel', 'pushAnswers']);

</script>

<template>
    <VCard>
        <VCardTitle class="text-h5">
             title 
        </VCardTitle>
        <VCardText>
            Вы уверны, что хотите закончить
        </VCardText>
        <VCardActions>
            <VSpacer />
            <VBtn
                color="primary darken-1"
                variant="text"
                @click="$emit('cancel')"
            >
                Нет
            </VBtn>
            <v-btn
            to="/"
                color="primary"
                variant="text"
                @click="$emit('pushAnswers')"
            >
                Да
            </v-btn>
        </VCardActions>
    </VCard>
</template>
