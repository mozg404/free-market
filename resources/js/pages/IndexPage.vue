<script setup>
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import ProductList from "@/components/products/ProductList.vue";
import { Calendar, Home, Inbox, Search, Settings } from "lucide-vue-next"
import PageLayout from "@/layouts/PageLayout.vue";

defineProps({
  products: Array,
})

</script>

<template>
  <PageLayout>
    <template #title>Последние товары</template>

    <Wrapper class="mt-10">
      <ProductList :products="products" />
    </Wrapper>
  </PageLayout>
</template>