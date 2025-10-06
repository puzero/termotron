<template>
    <VCard>
        <VCardTitle class="text-h5">
            {{ surveyName }}
        </VCardTitle>
        <VCardText class="pa-5">
            <VRow>
                    <!-- <VCol cols="6" v-for="item in questions">{{ item }}</VCol>
                    <VCol cols="6" v-for="item in answers">{{ item }}</VCol> -->

                    <v-list>
                        <v-list-item v-for="item in questions">{{ item.text }}</v-list-item>
                    </v-list>

                    <v-list>
                        <v-list-item v-for="item in answers">{{ item.text }} </v-list-item>
                    </v-list>
            </VRow>
        </VCardText>
        <VCardActions class="pa-4">
            <VSpacer />
            <VBtn
                color="primary darken-1"
                variant="text"
                @click="$emit('cancel')"
            >
                Закрыть
            </VBtn>
        </VCardActions>
    </VCard>
</template>

<script setup>
    const props = defineProps({
        user: Object,
        answers: Array,
        questions: Array,
        surveyName: String,
    });
    defineEmits([ 'cancel']);

    const currentUser = ref({...props.user});

</script>