<script setup>
import Product from "@/components/modules/products/Product.vue";

const props = defineProps({
  products: Array,
})
</script>

<template>
  <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-5 xl:gap-x-8">
    <Product v-for="product in products" :key="product.id" :product="product" />
  </div>
</template>

<style scoped>

</style>