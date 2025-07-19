<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import Heading from "@/components/Heading.vue";
import MyShop from "@/layouts/MyShop.vue";

defineProps({
  shop: Object,
})
</script>

<template>
  <MyShop :shop="shop">
    Контент
  </MyShop>
</template>