<script setup>

import PageLayout from "@/layouts/PageLayout.vue";
import {Link} from "@inertiajs/vue3";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from "@/components/ui/table/index.js";
import {Button} from "@/components/ui/button/index.js";
import DateTime from "@/components/shared/DateTime.vue";
import { Key } from 'lucide-vue-next';
import TableBordered from "@/components/shared/table/TableBordered.vue";
import PriceFormatter from "@/components/shared/PriceFormatter.vue";
import ProductImage from "@/components/modules/products/ProductImage.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import UserShortInfo from "@/components/modules/users/UserShortInfo.vue";
import {Badge} from "@/components/ui/badge/index.js";
import {ModalLink} from "@inertiaui/modal-vue";
import LaravelPagination from "@/components/shared/LaravelPagination.vue";
import FeedbackControlButton from "@/components/modules/feedback/FeedbackControlButton.vue";

const props = defineProps({
  purchasedProducts: Array,
})
</script>

<template>
  <PageLayout>
    <template #title>Мои покупки</template>
    <template #counter>{{ purchasedProducts.total }}</template>

    <wrapper>
      <TableBordered>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Название</TableHead>
              <TableHead></TableHead>
              <TableHead>Продавец</TableHead>
              <TableHead class="text-right">Цена</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="product in purchasedProducts.data" :key="product.id">
              <TableCell class="whitespace-normal w-1/2">
                <div class="flex items-center">
                  <div class="w-24 shrink-0 mr-4">
                    <Link :href="route('catalog.product', product.id)" class="block">
                      <ProductImage :product="product" />
                    </Link>
                  </div>
                  <div>
                    {{product.name}}
                    <div class="mt-4 flex space-x-2">
                      <Button  variant="secondary" as-child>
                        <ModalLink :href="route('my.purchases.show', product.stock_item_id)">
                          <Key />
                          Получить
                        </ModalLink>
                      </Button>
                      <FeedbackControlButton variant="ghost" :order-item-id="product.order_item_id" :feedback="product.feedback"/>
                    </div>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <div class="flex flex-col space-y-2">
                  <Badge variant="ghost">{{ product.category.name }}</Badge>
                  <Link :href="route('my.orders.show', product.order_id)">
                    <Badge variant="ghost" class="hover:opacity-70 transition-opacity">Заказ #{{ product.order_id }}</Badge>
                  </Link>
                  <Badge variant="ghost">
                    <DateTime :value="product.purchased_at" format="D MMMM YYYY"/>
                  </Badge>
                </div>
              </TableCell>

              <TableCell>
                <UserShortInfo :user="product.seller"/>
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

      <LaravelPagination class="mt-8 justify-center" :pagination="purchasedProducts"/>

    </wrapper>
  </PageLayout>
</template>