<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, Settings, Trash2, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import {Badge} from '@/components/ui/badge'
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import Pagination from "@/components/Pagination.vue";
import ConfirmDeleteDialog from "@/components/ConfirmDeleteDialog.vue";
import Input from "@/components/ui/input/Input.vue";

const props = defineProps({
  products: Array,
  shops: Array,
  links: Array,
  filters: Object,
})
const search = ref(props.filters.search);
const shopId = '';

// Общая функция для обработки фильтров
const applyFilters = debounce(() => {
  router.get(route('cabinet.products'), {
    search: search.value,
    shop_id: shopId.value
  }, {
    preserveState: true,
    replace: true,
  });
}, 300);

// Наблюдаем за изменениями обоих фильтров
watch([search, shopId], applyFilters);
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

    <div class="py-5">
      <div class="relative w-full max-w-sm items-center">
        <Input id="search" type="text" placeholder="Search..." class="pl-8" v-model="search" />
        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
          <Search class="size-4 text-muted-foreground" />
        </span>
      </div>
    </div>

    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>ID</TableHead>
          <TableHead>Изображение</TableHead>
          <TableHead>Название</TableHead>
          <TableHead>В наличие</TableHead>
          <TableHead>Статус</TableHead>
          <TableHead>Цена</TableHead>
          <TableHead>Скидка</TableHead>
          <TableHead></TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="product in products" :key="product.id">
          <TableCell class="font-medium">{{ product.id }}</TableCell>
          <TableCell><img :src="product.previewImage.url" class="w-[60px]"/></TableCell>
          <TableCell>{{ product.name }}</TableCell>
          <TableCell>14</TableCell>
          <TableCell>
            <div v-if="product.isAvailable"><Badge>В наличие</Badge></div>
            <div v-else><Badge variant="destructive">Закончился</Badge></div>
          </TableCell>
          <TableCell>{{ product.price.base }}</TableCell>
          <TableCell>{{ product.price.discount ?? '-' }}</TableCell>
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
