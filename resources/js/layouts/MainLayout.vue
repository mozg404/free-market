<script setup>
import Header from "../components/Header.vue";
</script>

<template>
  <Header/>
  <slot/>
</template>

<script>
import AppLayout from "@/layouts/AppLayout.vue";

export default {
  layout: AppLayout,
}
</script>
