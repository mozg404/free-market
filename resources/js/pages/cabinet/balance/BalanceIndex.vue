<script setup>
import {PlusIcon} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import MainLayout from "@/layouts/MainLayout.vue";
import Main from "@/components/core/layout/Main.vue";
import MainTitle from "@/components/core/layout/MainTitle.vue";
import Wrapper from "@/components/core/layout/Wrapper.vue";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import {
  Card,
  CardContent,
} from '@/components/ui/card'
import DateTime from "@/components/support/DateTime.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import FormNumberInput from "@/components/core/form/FormNumberInput.vue";
import {useForm} from "@inertiajs/vue3";
import ErrorMessage from "@/components/support/ErrorMessage.vue";
import { useUser } from '@/composables/useUser'
import {Pagination} from "@/components/ui/pagination/index.js";
import LaravelPagination from "@/components/support/LaravelPagination.vue";

const { user } = useUser()
const form = useForm({
  amount: null,
})
const props = defineProps({
  pagination: Object,
})
</script>

<template>
  <MainLayout>
    <Wrapper>
      <Main>
        <MainTitle>Финансы</MainTitle>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
          <aside class="xl:col-span-3">
            <Card class="py-4 shadow-none">
              <CardContent class="px-4">
                <div><small>Баланс</small></div>
                <PriceFormatter class="font-bold text-2xl block mt-1" :value="user.balance" />
              </CardContent>
            </Card>

            <Card class="py-4 shadow-none mt-6">
              <CardContent class="px-4">
                <div><small>Пополнить</small></div>

                <form @submit.prevent="form.post(route('balance.deposit'))">
                  <FormNumberInput placeholder="Сумма" v-model="form.amount" class="mt-2 mb-4"/>
                  <ErrorMessage :message="form.errors.amount"/>

                  <Button type="submit" class="cursor-pointer">
                    <PlusIcon class="w-4 h-4" />
                    Пополнить
                  </Button>
                </form>
              </CardContent>
            </Card>
          </aside>

          <div class="xl:col-span-9 mr-2">
            <Card class="py-1 shadow-none">
              <CardContent class="px-4">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead class="w-[150px]"> Дата</TableHead>
                      <TableHead>Описание</TableHead>
                      <TableHead class="text-right">Сумма</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    <TableRow v-for="transaction in pagination.data" :key="transaction.id">
                      <TableCell class="w-[150px] pr-4">
                        <DateTime :value="transaction.created_at" format="DD-MM-YYYY в HH:mm"/>
                      </TableCell>
                      <TableCell class="font-medium">
                        <div v-if="transaction.type === 'replenishment'">Пополнение баланса</div>
                        <div v-else>Оплата заказа #{{ transaction.transactionable_id }}</div>
                      </TableCell>
                      <TableCell class="text-right">
                        <PriceFormatter v-if="transaction.type === 'replenishment'" class="text-primary" :value="transaction.amount" />
                        <PriceFormatter v-else class="text-destructive" :value="transaction.amount" />
                      </TableCell>
                    </TableRow>
                  </TableBody>
                </Table>
              </CardContent>
            </Card>

            <LaravelPagination class="mt-6" :pagination="pagination" />
          </div>
        </div>

      </Main>
    </Wrapper>
  </MainLayout>
</template>

