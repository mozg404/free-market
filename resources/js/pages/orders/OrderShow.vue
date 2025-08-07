<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Main from "@/components/shared/layout/Main.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {Card, CardContent} from "@/components/ui/card/index.js";
import OrderItemCart from "@/components/orders/OrderItemCart.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import {
  DescriptionItem,
  DescriptionSeparator,
  DescriptionTitle,
  DescriptionValue
} from "@/components/shared/description/index.js";
import DateTime from "@/components/support/DateTime.vue";

const props = defineProps({
  order: Object,
  items: Array,
  totalAmount: Object,
  totalCount: Number,
})
</script>

<template>
  <MainLayout>
    <Wrapper>

      <Main>
        <MainTitle>Заказ #{{ order.id }}</MainTitle>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
          <div class="xl:col-span-9 mr-2">
            <div class="space-y-4">
              <OrderItemCart v-for="item in items" :key="item.product.id" :item="item" />
            </div>
          </div>

          <aside class="xl:col-span-3">
            <Card v-if="order.status === 'paid'" class="border-0 bg-primary text-primary-foreground text-center mb-4">
              <CardContent class="flex justify-center">Заказ оплачен</CardContent>
            </Card>
            <Card v-else class="border-0 bg-destructive text-destructive-foreground text-center mb-4">
              <CardContent class="flex justify-center">Ожидает оплату</CardContent>
            </Card>

            <Card>
              <CardContent>
                <div class="space-y-2">
                  <DescriptionItem>
                    <DescriptionTitle class="font-semibold text-card-foreground">Стоимость</DescriptionTitle>
                    <DescriptionValue class="text-sm font-semibold text-card-foreground"><PriceFormatter :value="totalAmount.current ?? 0 "/></DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Товаров</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm">{{ totalCount }}шт</DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Дата</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm">
                      <DateTime :value="order.created_at" format="DD.MM.YYYY"/>
                    </DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Время</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm">
                      <DateTime :value="order.created_at" format="HH:mm"/>
                    </DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Цена</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm"><PriceFormatter :value="totalAmount.base"/></DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Скидка</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm"><PriceFormatter :value="totalAmount.discount_amount ?? 0"/></DescriptionValue>
                  </DescriptionItem>

                  <DescriptionItem>
                    <DescriptionTitle class="text-sm">Выгода</DescriptionTitle>
                    <DescriptionSeparator/>
                    <DescriptionValue class="text-sm"><PriceFormatter :value="totalAmount.discount_percent ?? 0" currency="%"/></DescriptionValue>
                  </DescriptionItem>
                </div>
              </CardContent>
            </Card>


          </aside>
        </div>

      </Main>
    </Wrapper>

  </MainLayout>
</template>
