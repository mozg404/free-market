<script setup>
import AppLayout from "@/layouts/AppLayout.vue";
import AppHeader from "../components/modules/app/AppHeader.vue";
import AppBreadcrumbs from "@/components/modules/app/AppBreadcrumbs.vue";

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