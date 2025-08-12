<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import Header from "./parts/Header.vue";
import Breadcrumbs from "@/layouts/parts/Breadcrumbs.vue";

const props = defineProps({
  withBreadcrumbs: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <AppLayout>
    <Header/>
    <Breadcrumbs v-if="withBreadcrumbs" />
    <slot/>
  </AppLayout>
</template>