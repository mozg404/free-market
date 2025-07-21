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
      <h2 class="text-2xl font-bold tracking-tight text-gray-900">Customers also purchased</h2>
      <Button type="button" class="rounded-xl">Кнопка</Button>
      <ProductList :products="products" />
    </Wrapper>
  </Main>
</template>
