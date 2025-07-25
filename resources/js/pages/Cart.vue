<script setup>
import Main from "@/layouts/Main.vue";
import Wrapper from "../components/Wrapper.vue";
import CartItem from "@/components/cart/CartItem.vue";
import {Link, useForm, usePage} from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import {computed} from "vue";
import {ShoppingCart} from "lucide-vue-next";

const page = usePage()
const cart = computed(() => page.props.cart)
const form = useForm({})

</script>

<template>
    <Main>
        <Wrapper class="mt-10">
          <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
            <main class="xl:col-span-9 p-4">
              <h2 class="text-xl font-bold tracking-tight text-gray-900 pb-8">Корзина</h2>
              <CartItem v-for="item in cart.items" :key="item.product.id"  :item="item" />
              <Button variant="destructive" @click="form.delete(route('cart.clean'))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Очистить корзину</Button>
            </main>

            <aside class="xl:col-span-3 bg-gray-50 p-4">
              <h2 class="text-xl font-bold tracking-tight text-gray-900 pb-8">Оформление</h2>

              <ul class="space-y-2">
                <li>Всего товаров: {{cart.totalCount}}</li>
                <li>Общая цена: {{cart.totalPrice}}</li>
              </ul>

              <Button  class="rounded-3xl" as-child>
                <Link :href="route('checkout')">
                  Оформить заказ
                </Link>
              </Button>

            </aside>
          </div>

        </Wrapper>
    </Main>
</template>
