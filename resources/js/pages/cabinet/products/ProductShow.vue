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
import {Settings, PlusIcon, Search, Pencil, ArrowBigDown, ArrowBigUp, X, Check, Eye} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import {Badge} from "@/components/ui/badge/index.js";
import {Input} from "@/components/ui/input/index.js";
import TableEmbed from "@/components/shared/table/TableEmbed.vue";
import {Separator} from "@/components/ui/separator"
import LaravelPagination from "@/components/support/LaravelPagination.vue";
import {ModalLink} from '@inertiaui/modal-vue'
import {Link, router, useForm} from "@inertiajs/vue3";
import {
  DescriptionItem,
  DescriptionSeparator,
  DescriptionTitle,
  DescriptionValue
} from "@/components/shared/description/index.js";

const props = defineProps({
  product: Object,
  itemsPaginated: Array,
  availableItemsCount: Number,
  soldItemsCount: Number,
  reservedItemsCount: Number,
})
const form = useForm()
</script>

<template>
  <MainLayout>
    <Wrapper>
      <Main>
        <div class="flex items-center mb-12">

          <div class="flex-1">
            <div class="flex space-x-3 mb-2">
              <Badge>{{ product.category.name }}</Badge>
              <Badge variant="ghost">#{{ product.id }}</Badge>
              <Badge variant="ghost">
                <DateTime :value="product.created_at "/>
              </Badge>
            </div>

            <MainTitle class="mb-8">{{ product.name }}</MainTitle>


            <div class="max-w-3/4 space-y-2">
              <DescriptionItem>
                <DescriptionTitle>ID</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>{{ product.id }}</DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Дата создания</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <DateTime :value="product.created_at "/>
                </DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Последнее обновление</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <DateTime :value="product.updated_at "/>
                </DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Статус публикации</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <Badge v-if="product.is_published">Опубликовано</Badge>
                  <Badge v-else variant="destructive">Не опубликовано</Badge>
                </DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Статус продажи</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <Badge v-if="product.is_available">Доступно для продажи</Badge>
                  <Badge v-else variant="destructive">Не доступно для продажи</Badge>
                </DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Текущая цена</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <PriceFormatter class="block font-semibold" :value="product.price.current"/>
                </DescriptionValue>
              </DescriptionItem>

              <DescriptionItem>
                <DescriptionTitle>Скидка</DescriptionTitle>
                <DescriptionSeparator/>
                <DescriptionValue>
                  <PriceFormatter class="block font-semibold" :value="product.price.discount_amount"/>
                </DescriptionValue>
              </DescriptionItem>

              <div class="flex mt-6 space-x-4">
                <Button :as="Link" :href="route('my.products.edit', product.id)" class="cursor-pointer"
                        variant="secondary">
                  <Pencil/>
                  Изменить
                </Button>

                <Button v-if="product.is_available"
                        @click="router.patch(route('my.products.mark-unavailable', product.id))" variant="secondary">
                  <X/>
                  Снять с продажи
                </Button>
                <Button v-else @click="router.patch(route('my.products.mark-available', product.id))"
                        variant="secondary">
                  <Check/>
                  Поставить на продажу
                </Button>

                <Button v-if="product.is_published" @click="router.patch(route('my.products.unpublish', product.id))"
                        variant="secondary">
                  <ArrowBigDown/>
                  Снять с публикации
                </Button>
                <Button v-else @click="router.patch(route('my.products.publish', product.id))" variant="secondary">
                  <ArrowBigUp/>
                  Опубликовать
                </Button>

                <Button
                  v-if="product.is_published"
                  :as="Link"
                  :href="route('catalog.product', product.id)" class="cursor-pointer"
                  variant="secondary"
                >
                  <Eye/>
                  Посмотреть
                </Button>
              </div>
            </div>

          </div>
          <div class="w-70 shrink-0 ml-6">
            <ProductImage :src="product.preview_image" :alt="product.name"/>
          </div>
        </div>

        <Card>
          <CardHeader>
            <div class="flex justify-between items-center">
              <CardTitle>Позиции</CardTitle>

              <div class="flex h-5 items-center space-x-6 text-sm text-muted-foreground">
                <div>
                  <Badge class="mr-1">{{ availableItemsCount }}</Badge>
                  Доступно
                </div>
                <div>
                  <Badge class="mr-1" variant="destructive">{{ soldItemsCount }}</Badge>
                  Продано
                </div>
                <div>
                  <Badge class="mr-1" variant="secondary">{{ reservedItemsCount }}</Badge>
                  Зарезервировано
                </div>
              </div>
            </div>
          </CardHeader>

          <Separator/>

          <CardContent>
            <div class="flex items-center justify-between mb-4">
              <div class="relative w-full max-w-sm items-center">
                <Input id="search" type="text" placeholder="Search..." class="pl-8" v-model="search"/>
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground"/>
              </span>
              </div>
              <div class="text-right">
                <Button as-child>
                  <ModalLink :href="route('my.products.stock-items.create', product.id)">
                    <PlusIcon class="w-4 h-4"/>
                    Добавить
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
                    <TableCell>{{ item.id }}</TableCell>
                    <TableCell>{{ item.content }}</TableCell>
                    <TableCell>
                      <Badge variant="destructive" v-if="item.status === 'sold'">Продан</Badge>
                      <Badge variant="reserved" v-else-if="item.status === 'reserved'">Зарезервирован</Badge>
                      <Badge v-else>Доступен</Badge>
                    </TableCell>
                    <TableCell>
                      <DateTime :value="item.created_at"/>
                    </TableCell>
                    <TableCell class="text-right">
                      <Button variant="ghost" :as="ModalLink"
                              :href="route('my.products.stock-items.edit', [product.id, item.id])" size="icon">
                        <Settings/>
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