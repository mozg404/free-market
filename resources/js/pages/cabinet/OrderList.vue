<script setup>
import CabinetLayout from "@/layouts/CabinetLayout.vue";
import Heading from "@/components/Heading.vue";
import DateTime from "@/components/support/DateTime.vue";
import {Badge} from "@/components/ui/badge/index.js";

const props = defineProps({
  items: Array,
})
</script>

<template>
  <CabinetLayout>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Заказы"/>
    </div>

    <div>
      <div class="p-4 border-2 mb-4" v-for="item in items" :key="item.id">
        ID заказа: {{item.id}} <br>
        Цена: {{item.price}} <br>
        Позиций: {{item.itemsCount}}шт <br>
        Статус:
        <Badge v-if="item.isNew" variant="secondary">Новый</Badge>
        <Badge v-else-if="item.isPaid">Оплачен</Badge><br>
        Дата: <DateTime :value="item.purchasedAt" format="DD-MM-YYYY"/><br>
        Время: <DateTime :value="item.purchasedAt" format="HH:MM"/>
      </div>
    </div>

  </CabinetLayout>
</template>

<style scoped>

</style>
