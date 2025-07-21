<script setup>
import Main from "@/layouts/Main.vue";
import Wrapper from "../components/Wrapper.vue";
import CartItem from "@/components/cart/CartItem.vue";
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  cart: Object,
})
const form = useForm({})
</script>

<template>
    <Main>
        <Wrapper class="mt-10">

          <h2 class="text-2xl font-bold tracking-tight text-gray-900">Корзина</h2>

          <CartItem v-for="item in cart.items" :key="item.product.id"  :item="item" />

          Всего товаров: {{cart.totalCount}} <br>
          Общая цена: {{cart.totalPrice}} <br><br><br>

          <Button @click="form.delete(route('cart.clean'))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Полностью удалить</Button>

        </Wrapper>

    </Main>
</template>
