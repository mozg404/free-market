<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, Settings, Trash2} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import { Badge } from '@/components/ui/badge'
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {Link} from "@inertiajs/vue3";
import Pagination from "@/components/Pagination.vue";
import ConfirmDeleteDialog from "@/components/ConfirmDeleteDialog.vue";

const props = defineProps({
  products: Array,
  links: Array,
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
          <TableHead>Статус</TableHead>
          <TableHead>Цена</TableHead>
          <TableHead></TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="product in products" :key="product.id">
          <TableCell class="font-medium">{{ product.id }}</TableCell>
          <TableCell><img :src="product.imageUrl" class="w-[60px]"/></TableCell>
          <TableCell>{{ product.name }}</TableCell>
          <TableCell>{{ product.shop.name }}</TableCell>
          <TableCell>
            <div v-if="product.isAvailable"><Badge>В наличие</Badge></div>
            <div v-else><Badge variant="destructive">Закончился</Badge></div>
          </TableCell>
          <TableCell>{{ product.price }}</TableCell>
          <TableCell class="text-end">
            <div class="flex justify-around">
              <ModalLink :href="route('cabinet.products.edit', product.id)">
                <Settings class="w-4 h-4"/>
              </ModalLink>

              <ConfirmDeleteDialog
                :route="route('cabinet.products.delete', product.id)"
                title="Удалить товар?"
                description="Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить."
              >
                <Trash2 class="w-4 h-4"/>
              </ConfirmDeleteDialog>
            </div>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <div class="pb-6">
      <Pagination :links="props.links"/>
    </div>

  </Cabinet>
</template>

<style scoped>

</style>
