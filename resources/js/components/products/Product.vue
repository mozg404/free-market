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
<div class="group relative">
  <Link :href="route('product.show', product.id)">
    <img :src="product.previewImage.url" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
  </Link>

  <div class="mt-4 flex justify-between">
    <div>
      <h3 class="text-sm text-gray-700">
          {{ product.name }}
      </h3>
      <p class="mt-1 text-sm text-gray-500">{{ product.shop.name }}</p>
    </div>
    <p class="text-sm font-medium text-gray-900">{{ product.price.current }} ₽</p>
  </div>

  <div v-if="inCart(product.id)">
    <Button disabled>
      <ShoppingCart class="w-4 h-4 mr-1" /> В корзине
    </Button>
  </div>
  <div v-else>
    <Button @click="form.post(route('cart.add', product.id))" :disabled="form.processing" class="cursor-pointer">
      <ShoppingCart class="w-4 h-4 mr-1" /> В корзину
    </Button>
  </div>


</div>
</template>

<style scoped>

</style>