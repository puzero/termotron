<script setup lang="ts">
import { ref, onMounted } from "vue";
import Emploee from "~/components/Layout/Emploee.vue";

</script>

<template>
        <v-locale-provider >
        <!-- <Topbar/> -->
        <v-app>
     
            <v-main>
                <Emploee/>
                <v-container fluid class="page-wrapper bg-background px-sm-5 px-4 pt-12 rounded-xl">
                    <div class="maxWidth">
                        <NuxtPage  />
                    </div>
                </v-container>
            </v-main>
        </v-app>
    </v-locale-provider>



</template>