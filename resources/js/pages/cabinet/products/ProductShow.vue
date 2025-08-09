<script setup>
import {Card, CardContent, CardFooter, CardHeader, CardTitle} from "@/components/ui/card/index.js";
import DateTime from "@/components/support/DateTime.vue";
import MainLayout from "@/layouts/MainLayout.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import Main from "@/components/shared/layout/Main.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import ProductImage from "@/components/products/ProductImage.vue";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from "@/components/ui/table/index.js";
import {Settings, PlusIcon, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import {Badge} from "@/components/ui/badge/index.js";
import {Input} from "@/components/ui/input/index.js";
import TableEmbed from "@/components/shared/table/TableEmbed.vue";
import { Separator } from "@/components/ui/separator"
import LaravelPagination from "@/components/support/LaravelPagination.vue";
import { ModalLink } from '@inertiaui/modal-vue'

const props = defineProps({
  product: Object,
  itemsPaginated: Array,
  availableItemsCount: Number,
  soldItemsCount: Number,
  reservedItemsCount: Number,
})
</script>

<template>
  <MainLayout>
    <Wrapper>
      <Main>
        <div class="flex items-center mb-12">

          <div class="flex-1">
            <div class="flex space-x-3 mb-2">
              <Badge>{{product.category.name}}</Badge>
              <Badge variant="ghost">#{{product.id}}</Badge>
              <Badge variant="ghost">
                <DateTime :value="product.created_at "/>
              </Badge>
            </div>

            <MainTitle class="mb-8">{{ product.name }}</MainTitle>

            <div class="flex items-center space-x-4">
              <Card class="py-2 border-none bg-muted min-w-40">
                <CardContent class="px-4">
                  <small>Цена</small>
                  <PriceFormatter class="block font-bold text-xl" :value="product.price.current"/>
                </CardContent>
              </Card>

              <Card class="py-2 border-none bg-muted min-w-40">
                <CardContent class="px-4">
                  <small>Скидка</small>
                  <PriceFormatter class="block font-bold text-xl" :value="product.price.discount_amount"/>
                </CardContent>
              </Card>
            </div>
          </div>
          <div class="w-44 shrink-0 ml-6">
            <ProductImage :src="product.preview_image" :alt="product.name"/>
          </div>
        </div>

        <Card>
          <CardHeader>
            <div class="flex justify-between items-center">
              <CardTitle>Позиции</CardTitle>

              <div class="flex h-5 items-center space-x-6 text-sm text-muted-foreground">
                <div><Badge class="mr-1">{{ availableItemsCount }}</Badge> Доступно </div>
                <div><Badge class="mr-1" variant="destructive">{{ soldItemsCount }}</Badge> Продано </div>
                <div><Badge class="mr-1" variant="secondary">{{ reservedItemsCount }}</Badge> Зарезервировано </div>
              </div>
            </div>
          </CardHeader>

          <Separator />

          <CardContent>
            <div class="flex items-center justify-between mb-4">
              <div class="relative w-full max-w-sm items-center">
                <Input id="search" type="text" placeholder="Search..." class="pl-8" v-model="search" />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground" />
              </span>
              </div>
              <div class="text-right">
                <Button as-child>
                  <ModalLink :href="route('my.products.stock-items.create', product.id)">
                    <PlusIcon class="w-4 h-4"/> Добавить
                  </ModalLink>
                </Button>
              </div>
            </div>

            <TableEmbed>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>#</TableHead>
                    <TableHead>Содержимое</TableHead>
                    <TableHead>Статус</TableHead>
                    <TableHead>Дата</TableHead>
                    <TableHead></TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  <TableRow v-for="item in itemsPaginated.data" :key="item.id">
                    <TableCell>{{item.id}}</TableCell>
                    <TableCell>{{item.content}}</TableCell>
                    <TableCell>
                      <Badge variant="destructive" v-if="item.status === 'sold'">Продан</Badge>
                      <Badge variant="reserved" v-else-if="item.status === 'reserved'">Зарезервирован</Badge>
                      <Badge v-else>Доступен</Badge>
                    </TableCell>
                    <TableCell><DateTime :value="item.created_at"/></TableCell>
                    <TableCell class="text-right">
                      <Button variant="ghost" :as="ModalLink" :href="route('my.products.stock-items.edit', [product.id, item.id])" size="icon">
                          <Settings />
                      </Button>
                    </TableCell>
                  </TableRow>
                </TableBody>
              </Table>
            </TableEmbed>
          </CardContent>

          <CardFooter v-if="itemsPaginated.last_page > 1">
            <LaravelPagination :pagination="itemsPaginated"/>
          </CardFooter>
        </Card>

      </Main>
    </Wrapper>
  </MainLayout>
</template>