<script setup>
import Wrapper from "../components/core/Wrapper.vue";
import ProductList from "@/components/modules/products/ProductList.vue";

defineProps({
  products: Array,
})
</script>

<template>
  <Wrapper class="mt-10">
    <ProductList :products="products" />
  </Wrapper>
</template>

<script>
import MainLayout from "@/layouts/MainLayout.vue";

export default {
  layout: MainLayout,
}
</script>