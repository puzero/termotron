<script setup lang="ts">
</script>


<template>
        <v-locale-provider >
        <!-- <Topbar/> -->
        <v-app>
     
            <v-main>
                <v-container fluid class="page-wrapper bg-background px-sm-5 px-4 pt-12 rounded-xl">
                    <div class="maxWidth">
                        <NuxtPage  />
                    </div>
                </v-container>
            </v-main>
        </v-app>
    </v-locale-provider>
</template>