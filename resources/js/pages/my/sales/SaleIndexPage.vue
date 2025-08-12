<script setup>

import PageLayout from "@/layouts/PageLayout.vue";
import {Link} from "@inertiajs/vue3";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from "@/components/ui/table/index.js";
import {Button} from "@/components/ui/button/index.js";
import DateTime from "@/components/support/DateTime.vue";
import { Key } from 'lucide-vue-next';
import TableBordered from "@/components/shared/table/TableBordered.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import ProductImage from "@/components/products/ProductImage.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import UserShortInfo from "@/components/users/UserShortInfo.vue";
import {Badge} from "@/components/ui/badge/index.js";
import {ModalLink} from "@inertiaui/modal-vue";
import LaravelPagination from "@/components/shared/LaravelPagination.vue";

const props = defineProps({
  products: Array,
})
</script>

<template>
  <PageLayout>
    <template #title>Мои продажи</template>
    <template #counter>{{ products.total }}</template>

    <wrapper>
      <TableBordered>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Название</TableHead>
              <TableHead>Дата продажи</TableHead>
              <TableHead>Покупатель</TableHead>
              <TableHead class="text-right">Цена</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="product in products.data" :key="product.id">
              <TableCell class="whitespace-normal w-1/2">
                <div class="flex items-center">
                  <div class="w-16 shrink-0 mr-4">
                    <Link :href="route('catalog.product', product.id)" class="block">
                      <ProductImage :src="product.preview_image" :alt="product.name"/>
                    </Link>
                  </div>
                  <div>
                    <div class="mb-2">{{product.name}}</div>
                    <div class="space-x-2">
                      <Link :href="route('my.products.show', product.id)">
                        <Badge variant="ghost" class="hover:opacity-70 transition-opacity">Товар #{{ product.id }}</Badge>
                      </Link>
                      <Badge variant="ghost">Позиция #{{ product.stock_item_id }}</Badge>
                    </div>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <DateTime :value="product.sold_at" format="D MMMM YYYY в HH:MM"/>
              </TableCell>

              <TableCell>
                <UserShortInfo :user="product.buyer"/>
              </TableCell>

              <TableCell class="text-right">
                <template v-if="product.price.has_discount">
                  <PriceFormatter :value="product.price.discount" class="font-semibold text-destructive block"/>
                  <PriceFormatter :value="product.price.base" class="text-xs text-muted-foreground line-through block"/>
                </template>
                <PriceFormatter v-else :value="product.price.current" class="font-semibold"/>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </TableBordered>

      <LaravelPagination class="mt-8 justify-center" :pagination="products"/>

    </wrapper>
  </PageLayout>
</template>