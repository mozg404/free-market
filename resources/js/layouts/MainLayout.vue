<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import Header from "../components/Header.vue";
</script>

<template>
  <AppLayout>
    <Header/>
    <slot/>
  </AppLayout>
</template>

<script>
import AppLayout from "@/layouts/AppLayout.vue";

export default {
  layout: AppLayout,
}
</script>
