<script setup>
import Header from "../components/Header.vue";
import App from "@/layouts/App.vue";
</script>

<template>
  <App>
    <Header/>
    <slot/>
  </App>
</template>
