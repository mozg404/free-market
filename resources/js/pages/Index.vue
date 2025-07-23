<script setup>
import Main from "@/layouts/Main.vue";
import { Link } from "@inertiajs/vue3";
import Wrapper from "../components/Wrapper.vue";
import { Button } from '@/components/ui/button'
import Product from "@/components/products/Product.vue";
import ProductList from "@/components/products/ProductList.vue";

defineProps({
    products: Array,
})
</script>

<template>
  <Main>

    <Wrapper class="mt-10">
      <ProductList :products="products" />
    </Wrapper>

  </Main>
</template>
