<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import Heading from "@/components/Heading.vue";
import DateTime from "@/components/DateTime.vue";

const props = defineProps({
  items: Array,
})
</script>

<template>
  <Cabinet>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Купленные товары"/>
    </div>


    <div>
      <div class="p-4 border-2 mb-4" v-for="item in items" :key="item.id">
        <img :src="item.image.url" alt="" class="w-[50px]" /><br>
        ID заказа: {{item.orderId}} <br>
        Название: {{item.name}} <br>
        Цена: {{item.price}} <br>
        Продавец: {{item.seller.name}} (#{{item.seller.id}}) <br>
        Дата: <DateTime :value="item.purchasedAt" format="DD-MM-YYYY"/><br>
        Время: <DateTime :value="item.purchasedAt" format="HH:MM"/>
      </div>
    </div>

  </Cabinet>
</template>

<style scoped>

</style>
