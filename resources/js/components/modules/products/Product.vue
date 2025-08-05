<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {Button} from '@/components/ui/button/index.js'
import {ShoppingCart} from 'lucide-vue-next';
import {computed} from "vue";
import { AspectRatio } from "@/components/ui/aspect-ratio"
import ProductPreviewImage from "@/components/core/media/ProductPreviewImage.vue";

const page = usePage()
const cart = computed(() => page.props.cart)
const props = defineProps({
  product: Object,
})
const form = useForm({})
const inCart = (id) => Object.keys(page.props.cart.items).some(key => page.props.cart.items[key].product.id === id)
</script>

<template>
<div class="relative">
  <Link :href="route('product.show', product.id)">
    <ProductPreviewImage
      :src="product.previewImage.url"
      :is-exist="product.previewImage.isExists"
      :alt="product.name"
    />
  </Link>

  <div class="mt-3">
    <div v-if="product.price.isDiscount">
      <div class="flex items-center">
        <div class="text-xl font-bold text-red-600 line-through mr-2">{{ product.price.base }}</div>
        <div>{{ product.price.discount }} ₽</div>
      </div>
    </div>
    <div v-else class="text-lg font-bold">{{ product.price.current }} ₽</div>
  </div>

  <div class="mt-1 text-sm  text-gray-400">В наличии: {{ product.stockItemsCount }}</div>
  <div class="mt-1 line-clamp-2 leading-5" :title="product.name">{{ product.name }}</div>

</div>
</template>

<style scoped>

</style>