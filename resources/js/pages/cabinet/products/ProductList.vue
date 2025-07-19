<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, PenIcon} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import FilePondImage from "../../../components/FilePondImage.vue";
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
defineProps({
  products: Array,
})

</script>

<template>
  <Cabinet>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Мои товары"/>
      <div class="text-right">
        <Button variant="outline" class="rounded-3xl" as-child>
          <ModalLink :href="route('cabinet.products.create')">
            <PlusIcon class="w-4 h-4"/> Создать товар
          </ModalLink>
        </Button>
      </div>
    </div>

    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>ID</TableHead>
          <TableHead>Название</TableHead>
          <TableHead>Изображение</TableHead>
          <TableHead>Магазин</TableHead>
          <TableHead>Цена</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="product in products" :key="product.id">
          <TableCell class="font-medium">{{ product.id }}</TableCell>
          <TableCell><img :src="product.imageUrl" class="w-[60px]"/></TableCell>
          <TableCell>{{ product.name }}</TableCell>
          <TableCell>{{ product.shop.name }}</TableCell>
          <TableCell>{{ product.price }}</TableCell>
        </TableRow>
      </TableBody>
    </Table>

  </Cabinet>
</template>

<style scoped>

</style>
