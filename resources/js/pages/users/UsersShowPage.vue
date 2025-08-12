<script setup>
import PageLayout from "@/layouts/PageLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import LaravelPagination from "@/components/shared/LaravelPagination.vue";
import ProductCard from "@/components/products/ProductCard.vue";
import PageTitle from "@/components/shared/layout/PageTitle.vue";
import AccountAge from "@/components/support/AccountAge.vue";
import Section from "@/components/shared/layout/Section.vue";
import SectionTitle from "@/components/shared/layout/SectionTitle.vue";

const props = defineProps({
  concreateUser: Array,
  products: Array,
})
</script>

<template>
  <PageLayout>
    <Wrapper>

      <div class="mb-12 flex items-center">
        <div class="w-30 shrink-0 mr-4">
          <AspectRatio :ratio="1" class="bg-muted rounded-full overflow-hidden relative">
            <img :src="concreateUser.avatar" class="object-cover rounded-full w-full" :alt="concreateUser.avatar"/>
          </AspectRatio>
        </div>
        <div>
          <PageTitle>{{ concreateUser.name }}</PageTitle>
          <div class="mt-1">На сайте: <AccountAge :registration-date="concreateUser.created_at"/></div>
        </div>
      </div>

      <Section>
        <SectionTitle>Товары</SectionTitle>
        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8 w-full">
          <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
        </div>
        <LaravelPagination class="mt-8 justify-center" :pagination="products"/>
      </Section>

    </Wrapper>
  </PageLayout>
</template>