<script setup>
  import {Minus, Plus, Trash} from "lucide-vue-next";
  import {Link} from "@inertiajs/vue3";
  import {Button} from "@/components/ui/button/index.js";
  import {Separator} from "@/components/ui/separator";
  import PriceFormatter from "@/components/support/PriceFormatter.vue";
  import ProductImage from "@/components/products/ProductImage.vue";
  import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
  } from '@/components/ui/card'
  import OrderItemCart from "@/components/orders/OrderItemCart.vue";

  const props = defineProps({
    order: Object,
  })
</script>

<template>
  <Card class="py-4 mb-6 shadow-none gap-4">
    <CardHeader class="px-4 gap-0">
      <div class="flex items-center justify-between">
        <div class="gap-1.5">
          <CardTitle>Заказ #{{ order.id }}</CardTitle>
          <CardDescription>2025.08.06 в 16:14</CardDescription>
        </div>
        <div>
          <div class="text-xl"><PriceFormatter :value="order.amount"/></div>
        </div>
      </div>
    </CardHeader>

    <Separator/>

    <CardContent class="px-4">
      <div class="space-y-4">
        <OrderItemCart v-for="item in order.items" :key="item.id" :item="item" />
      </div>
    </CardContent>
  </Card>
</template>

<style scoped>

</style>