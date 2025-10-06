<script setup lang="ts">


 const sDrawer = ref(true);

</script>


<template>

<div class="container verticalLayout">
    <div class="maxWidth px-xl-0 px-sm-5 px-0">
      <v-app-bar elevation="0" height="70" class="top-header">
        <div class="d-flex align-center justify-space-between w-100">

          <div>

            <!-- User Profile -->
            <LayoutFullVerticalHeaderProfileDD />
          </div>
        </div>
      </v-app-bar>
    </div>
  </div>
</template>