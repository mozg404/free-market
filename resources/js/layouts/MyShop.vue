<script setup>

import Cabinet from "@/layouts/Cabinet.vue";
import Heading from "@/components/Heading.vue";

defineProps({
  shop: Object,
})
</script>

<template>
  <Cabinet>
    <div class="flex align-middle justify-between m-0">
      <Heading :title="shop.name"/>
    </div>
    <slot />
  </Cabinet>
</template>

<style scoped>

</style>