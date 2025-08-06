<script setup>
import CabinetLayout from "@/layouts/CabinetLayout.vue";
import Heading from "@/components/Heading.vue";
import DateTime from "@/components/support/DateTime.vue";
import {Badge} from "@/components/ui/badge/index.js";
import OrderCart from "@/components/orders/OrderCart.vue";

const props = defineProps({
  orders: Array,
})
</script>

<template>
  <CabinetLayout>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Заказы"/>
    </div>

    <div>
      <OrderCart v-for="order in orders" :key="order.id" :order="order" />
    </div>
  </CabinetLayout>
</template>

<style scoped>

</style>
