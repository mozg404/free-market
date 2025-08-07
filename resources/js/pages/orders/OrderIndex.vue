<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Main from "@/components/shared/layout/Main.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import TableBordered from "@/components/shared/table/TableBordered.vue";
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from "@/components/ui/table/index.js";
import LaravelPagination from "@/components/support/LaravelPagination.vue";
import DateTime from "@/components/support/DateTime.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import {Badge} from "@/components/ui/badge/index.js";
import {Button} from "@/components/ui/button/index.js";
import {ChevronRight} from "lucide-vue-next";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  pagination: Array,
})
</script>

<template>
  <MainLayout>
    <Wrapper>

      <Main>
        <MainTitle>Заказы</MainTitle>

        <TableBordered>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Номер</TableHead>
                <TableHead>Дата</TableHead>
                <TableHead>Позиций</TableHead>
                <TableHead>Статус</TableHead>
                <TableHead>Сумма</TableHead>
                <TableHead></TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="order in pagination.data" :key="order.id" class="align-middle">
                <TableCell class="font-medium">Заказ #{{ order.id }}</TableCell>
                <TableCell>
                  <DateTime :value="order.created_at" format="DD-MM-YYYY в HH:mm"/>
                </TableCell>
                <TableCell>{{ order.items_count }}</TableCell>
                <TableCell>
                  <Badge v-if="order.status === 'paid'">Оплачен</Badge>
                  <Badge v-else-if="order.status === 'new'" variant="destructive">Ожидает оплаты</Badge>
                  <Badge v-else-if="order.status === 'canceled'" variant="secondary">Отменен</Badge>
                  <Badge v-else variant="destructive">НЕИЗВЕСТНО</Badge>
                </TableCell>
                <TableCell>
                  <PriceFormatter class="font-bold" :value="order.amount"/>
                </TableCell>
                <TableCell class="text-right">
                  <Button :as="Link" :href="route('orders.show', order.id)" variant="secondary" size="icon" class="w-8 h-8">
                    <ChevronRight/>
                  </Button>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </TableBordered>

        <LaravelPagination class="mt-6" :pagination="pagination"/>
      </Main>
    </Wrapper>

  </MainLayout>
</template>
