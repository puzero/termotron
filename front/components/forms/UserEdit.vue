<template>
    <VCard>
        <VCardTitle class="text-h5">
            Редактировать пользователя
        </VCardTitle>
        <VCardText class="pa-5">
            <VRow>
                <VCol cols="12">
                    <VTextField
                        prepend-inner-icon="ri-user-line"
                        v-model="currentUser.name"
                        label="Имя"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        prepend-inner-icon="ri-mail-line"
                        v-model="currentUser.email"
                        label="Email"
                    />
                </VCol>
                <VCol cols="6">
                    <VTextField
                        v-model="currentUser.phone"
                        prepend-inner-icon="ri-phone-line"
                        v-mask="'+7(###) ###-##-##'"
                        type="tel"
                        label="Телефон"
                    />
                </VCol>
            </VRow>
            
            <div class="text-h5 mt-10 mb-5">Безопасность</div>
            <VRow>
                <VCol cols="6">
                    <VTextField
                        :id="useId()"
                        v-model="currentUser.password"
                        :type="isPasswordVisible ? 'text' : 'password'"
                        :append-inner-icon="isPasswordVisible ? 'ri-eye-off-line' : 'ri-eye-line'"
                        label="Новый пароль"
                        @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    />
                </VCol>
            </VRow>
        </VCardText>
        <VCardActions class="pa-4">
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
                @click="$emit('apply', currentUser)"
            >
                Да
            </v-btn>
        </VCardActions>
    </VCard>
</template>

<script setup>
    const props = defineProps({
        user: Object
    });
    defineEmits(['apply', 'cancel']);

    const currentUser = ref({...props.user});
    currentUser.value.password = '';
    currentUser.value.passwordConfirm = '';

    const isPasswordVisible = ref(false);
    const isPasswordConfirmVisible = ref(false);
</script>