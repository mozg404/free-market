<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import AppHeader from "./parts/AppHeader.vue";
import AppBreadcrumbs from "@/layouts/parts/AppBreadcrumbs.vue";

const props = defineProps({
  withBreadcrumbs: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <AppLayout>
    <AppHeader/>
    <AppBreadcrumbs v-if="withBreadcrumbs" />
    <slot/>
  </AppLayout>
</template>