<template>
    <VCard>
        <VCardTitle class="text-h5">
            {{ title }}
        </VCardTitle>
        <VCardText>
            {{ text }}
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
                color="primary"
                variant="text"
                @click="$emit('apply')"
            >
                Да
            </v-btn>
        </VCardActions>
    </VCard>
</template>

<script setup>
    const props = defineProps({
        title: String,
        text: String,
    });

    defineEmits(['cancel', 'apply']);
</script>