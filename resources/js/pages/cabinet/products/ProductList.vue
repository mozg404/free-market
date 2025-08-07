<script setup>
import CabinetLayout from "@/layouts/CabinetLayout.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, Settings, Trash2, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import {Badge} from '@/components/ui/badge'
import { ref, watch } from 'vue';
import {Link, router} from '@inertiajs/vue3';
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
import LaravelPagination from "@/components/support/LaravelPagination.vue";
import ConfirmDialog from "@/components/support/ConfirmDialog.vue";
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
  <CabinetLayout>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Мои товары"/>
      <div class="text-right">
        <Button variant="outline" class="rounded-3xl" as-child>
          <Link :href="route('cabinet.products.create')">
            <PlusIcon class="w-4 h-4"/> Создать товар
          </Link>
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


    <div class="p-4 border-2" v-for="product in products" :key="product.id">

      <div class="grid grid-cols-12">
        <div class="col-span-2">
          <img :src="product.previewImage.url" class="w-[60px]" alt=""/>
        </div>
        <div class="col-span-10">
          id: {{product.id}} <br>
          Название: {{product.name}} <br>
          Статус:
          <Badge v-if="product.isAvailable">Доступен</Badge>
          <Badge v-else variant="destructive">Недоступен</Badge>
          <br>
          Цена: {{product.price.base}} <br>
          Цена по скидке: {{product.price.discount ?? '-'}} <br>

          <br>
          <Link :href="route('cabinet.stock.index', product.id)">Позиции</Link>

          <br><br>
          <div class="grid grid-cols-2 gap-2 w-[50px]">
            <Link :href="route('cabinet.products.edit', product.id)">
              <Settings class="w-4 h-4"/>
            </Link>

            <ConfirmDialog
              :route="route('cabinet.products.delete', product.id)"
              title="Удалить товар?"
              description="Вы уверены, что хотите удалить этот товар? Это действие нельзя отменить."
            >
              <Trash2 class="w-4 h-4"/>
            </ConfirmDialog>
          </div>
        </div>

      </div>
    </div>


    <div class="pb-6">
      <LaravelPagination :links="props.links"/>
    </div>

  </CabinetLayout>
</template>

<style scoped>

</style>
