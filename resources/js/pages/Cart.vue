<script setup>
import Wrapper from "../components/core/Wrapper.vue";
import CartItem from "@/components/modules/cart/CartItem.vue";
import {Link, useForm, usePage} from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {computed} from "vue";
import Headline from "@/components/core/Headline.vue";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import PriceFormatter from "@/components/support/PriceFormatter.vue";

const page = usePage()
const cart = computed(() => page.props.cart)
const form = useForm({})
</script>

<template>
  <Wrapper class="mt-10">
    <Headline class="pb-10">Корзина</Headline>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
      <div class="xl:col-span-9 mr-2">
        <CartItem v-for="item in cart.items" :key="item.product.id"  :item="item" />
        <Button variant="destructive" @click="form.delete(route('cart.clean'))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Очистить корзину</Button>
      </div>

      <aside class="xl:col-span-3">
        <Card class="shadow-none">
          <CardHeader>
            <CardTitle class="flex justify-between mb-3">
              <span>К оплате:</span>
              <span><PriceFormatter :value="cart.amount.current"/> ₽</span>
            </CardTitle>
            <CardDescription class="flex justify-between">
              <span>Товаров:</span>
              <span>{{cart.quantity}}шт.</span>
            </CardDescription>
            <CardDescription class="flex justify-between">
              <span>Цена:</span>
              <span><PriceFormatter :value="cart.amount.base"/> ₽</span>
            </CardDescription>
            <CardDescription class="flex justify-between">
              <span>Скидка:</span>
              <span><PriceFormatter :value="cart.amount.benefit"/> ₽</span>
            </CardDescription>
            <CardDescription class="flex justify-between">
              <span>Экономия:</span>
              <span>{{cart.amount.discountPercent}}%</span>
            </CardDescription>
          </CardHeader>
          <CardFooter class="border-t-1 pt-5">
            <Button class="w-full " as-child>
              <Link method="post" :href="route('order_checkout.store')">
                Оформить заказ
              </Link>
            </Button>
          </CardFooter>
        </Card>
      </aside>

    </div>

  </Wrapper>
</template>

<script>
import MainLayout from "@/layouts/MainLayout.vue";

export default {
  layout: MainLayout,
}
</script>