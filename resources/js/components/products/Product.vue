<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {Button} from '@/components/ui/button'
import {ShoppingCart} from 'lucide-vue-next';
import {computed} from "vue";

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
    <img :src="product.previewImage.url" class="aspect-square w-full rounded-md bg-gray-200 object-cover lg:aspect-auto ">
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

<!--  <div class="mt-1">-->
<!--    <div v-if="inCart(product.id)">-->
<!--      <Button disabled>-->
<!--        <ShoppingCart class="w-4 h-4 mr-1" /> В корзине-->
<!--      </Button>-->
<!--    </div>-->
<!--    <div v-else>-->
<!--      <Button @click="form.post(route('cart.add', product.id))" :disabled="form.processing" class="cursor-pointer">-->
<!--        <ShoppingCart class="w-4 h-4 mr-1" /> В корзину-->
<!--      </Button>-->
<!--    </div>-->
<!--  </div>-->


</div>
</template>

<style scoped>

</style>