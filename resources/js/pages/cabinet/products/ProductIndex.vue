<script setup>
import {PlusIcon, Menu, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import {Badge} from '@/components/ui/badge'
import { ref, watch } from 'vue';
import {Link, router} from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import Input from "@/components/ui/input/Input.vue";
import TableBordered from "@/components/shared/table/TableBordered.vue";
import DateTime from "@/components/support/DateTime.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import ProductImage from "@/components/products/ProductImage.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from "@/components/ui/dropdown-menu/index.js";
import Main from "@/components/shared/layout/Main.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";

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
  <MainLayout :with-breadcrumbs="false">
    <Wrapper>
      <Main>
        <MainTitle>Мои товары</MainTitle>

          <div class="flex items-center justify-between mb-6">
            <div class="relative w-full max-w-sm items-center">
              <Input id="search" type="text" placeholder="Search..." class="pl-8" v-model="search" />
              <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground" />
              </span>
            </div>
            <div class="text-right">
              <Button variant="outline" as-child>
                <Link :href="route('my.products.create')">
                  <PlusIcon class="w-4 h-4"/> Создать товар
                </Link>
              </Button>
            </div>
          </div>

          <TableBordered>
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Название</TableHead>
                  <TableHead>Дата</TableHead>
                  <TableHead>Цена</TableHead>
                  <TableHead>Статус</TableHead>
                  <TableHead>Позиций</TableHead>
                  <TableHead></TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="product in products" :key="product.id">
                  <TableCell class="whitespace-normal w-1/2">
                    <div class="flex items-center">
                      <div class="w-14 shrink-0 mr-4">
                        <Link :href="route('catalog.product', product.id)" class="block">
                          <ProductImage :src="product.preview_image" :alt="product.name"/>
                        </Link>
                      </div>
                      <div class="text-sm">
                        {{product.name}}
                        <div class="text-muted-foreground text-xs mt-2">
                          #{{product.id}}
                        </div>
                      </div>
                    </div>
                  </TableCell>
                  <TableCell>
                    <div class="text-muted-foreground text-xs mt-2">
                      <DateTime :value="product.created_at" format="DD.MM.YYYY HH:mm"/>
                    </div>
                  </TableCell>

                  <TableCell>
                    <template v-if="product.price.has_discount">
                      <PriceFormatter :value="product.price.discount" class="font-semibold text-destructive block"/>
                      <PriceFormatter :value="product.price.base" class="text-xs text-muted-foreground line-through block"/>
                    </template>
                    <PriceFormatter v-else :value="product.price.current" class="font-semibold"/>
                  </TableCell>
                  <TableCell>Доступен</TableCell>

                  <TableCell>
                    <span class="mr-1">{{ product.stock_items_count }}</span>
                    <span title="Доступных позиций">({{ product.available_stock_items_count }})</span>
                  </TableCell>

                  <TableCell class="text-right">
                    <DropdownMenu>
                      <DropdownMenuTrigger as-child>
                        <Button variant="outline" size="icon" class="rounded-3xl">
                          <Menu class="w-4 h-4"/>
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent class="w-44">
                        <DropdownMenuItem class="cursor-pointer h-full w-full" :as="Link" :href="route('my.products.show', product.id)">Подробнее</DropdownMenuItem>
                        <DropdownMenuItem class="cursor-pointer h-full w-full" :as="Link" :href="route('my.products.edit', product.id)">Изменить</DropdownMenuItem>
                        <DropdownMenuSeparator/>
                        <DropdownMenuItem class="cursor-pointer h-full w-full" :as="Link" :href="route('catalog.product', product.id)">На сайте</DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </TableBordered>

      </Main>
    </Wrapper>
  </MainLayout>
</template>