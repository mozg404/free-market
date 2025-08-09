<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {Button} from '@/components/ui/button/index.js'
import {ShoppingCart} from 'lucide-vue-next';
import {computed} from "vue";
import { AspectRatio } from "@/components/ui/aspect-ratio/index.js"
import ProductImage from "@/components/products/ProductImage.vue";

const props = defineProps({
  product: Object,
})

</script>

<template>
<div class="relative">
  <Link :href="route('catalog.product', product.id)">
    <ProductImage
      :src="product.preview_image"
      :alt="product.name"
    />
  </Link>

  <div class="mt-3">
    <div v-if="product.price.has_discount">
      <div class="flex items-center">
        <div class="text-xl font-bold text-red-600 line-through mr-2">{{ product.price.base }}</div>
        <div>{{ product.price.discount }} ₽</div>
      </div>
    </div>
    <div v-else class="text-lg font-bold">{{ product.price.current }} ₽</div>
  </div>

  <div class="mt-1 text-sm  text-gray-400">В наличии: {{ product.stock_items_count }}</div>
  <div class="mt-1 line-clamp-2 leading-5" :title="product.name">{{ product.name }}</div>

</div>
</template>

<style scoped>

</style>