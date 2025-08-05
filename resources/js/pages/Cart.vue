<script setup>
import Wrapper from "../components/core/layout/Wrapper.vue";
import CartItem from "@/components/modules/cart/CartItem.vue";
import {Link, useForm, usePage} from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {computed} from "vue";
import MainTitle from "@/components/core/layout/MainTitle.vue";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import {Trash,ShoppingBasket} from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'

const page = usePage()
const cart = computed(() => page.props.cart)
const form = useForm({})
</script>

<template>
  <Wrapper>

    <template v-if="cart.count > 0">

      <MainTitle class="py-10 flex items-center">
        Корзина
        <Badge variant="secondary" class="ml-4 py-1.5 px-2.5">{{ cart.count }}</Badge>
      </MainTitle>

      <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
        <div class="xl:col-span-9 mr-2">
          <CartItem v-for="item in cart.items" :key="item.product.id"  :item="item" />
          <Button
            variant="destructive"
            class="cursor-pointer"
            @click="form.delete(route('cart.clean'))"
            :disabled="form.processing"
            :class="{ 'opacity-50': form.processing }"
          >
            <Trash class="w-4 h-4" />
            Очистить корзину
          </Button>
        </div>

        <aside class="xl:col-span-3">
          <Card class="shadow-none" >
            <CardHeader>
              <CardTitle class="flex justify-between mb-3">
                <span>К оплате:</span>
                <span><PriceFormatter :value="cart.amount?.current ?? 0 "/></span>
              </CardTitle>
              <CardDescription class="flex justify-between">
                <span>Товаров:</span>
                <span>{{cart.quantity ?? 0}}шт.</span>
              </CardDescription>
              <CardDescription class="flex justify-between">
                <span>Цена:</span>
                <span><PriceFormatter :value="cart.amount.base"/></span>
              </CardDescription>
              <CardDescription class="flex justify-between">
                <span>Скидка:</span>
                <span><PriceFormatter :value="cart.amount.benefit"/> ₽</span>
              </CardDescription>
              <CardDescription class="flex justify-between">
                <span>Экономия:</span>
                <span>{{cart.amount?.discountPercent ?? 0}}%</span>
              </CardDescription>
            </CardHeader>
            <CardFooter class="border-t-1 pt-5">
              <Button class="w-full" as-child>
                <Link method="post" class="cursor-pointer" :href="route('order_checkout.store')">
                  <ShoppingBasket class="w-4 h-4" />
                  Оформить заказ
                </Link>
              </Button>
            </CardFooter>
          </Card>
        </aside>
      </div>
    </template>

    <template v-else>
      <div class="text-center pt-10">
        <MainTitle class="pb-5">Корзина пустая</MainTitle>
        <Button :as="Link" :href="route('catalog')">К покупкам!</Button>
      </div>
    </template>

  </Wrapper>
</template>

<script>
import MainLayout from "@/layouts/MainLayout.vue";

export default {
  layout: MainLayout,
}
</script>