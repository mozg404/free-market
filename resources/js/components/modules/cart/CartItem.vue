<script setup>
import { Button } from '@/components/ui/button/index.js'
import { useForm } from '@inertiajs/vue3'
import {Minus,Plus,Trash} from 'lucide-vue-next'
import {Card, CardContent} from "@/components/ui/card/index.js";
import PriceFormatter from "@/components/support/PriceFormatter.vue";

const props = defineProps({
  item: Object,
})
const form = useForm({})
</script>

<template>


  <Card class="py-4 mb-6 shadow-none">
    <CardContent class="px-4">
      <div class="grid grid-cols-12 items-center">
        <div class="col-span-10">
          <div class="flex items-center min-w-0">
            <a href="#" class="block mr-4 shrink-0">
              <img :src="item.product.previewImage.url" class="w-[80px]" alt=""/>
            </a>
            <div>
              <div>{{ item.product.name }}</div>

              <div class="flex items-center gap-3 mt-4">
                <Button
                  variant="outline"
                  size="icon"
                  class="rounded-3xl cursor-pointer hover:bg-destructive hover:text-destructive-foreground hover:border-destructive"
                  :disabled="form.processing"
                  @click="form.delete(route('cart.delete', item.product.id))"
                >
                  <Trash class="w-4 h-4" />
                </Button>

                <div class="bg-gray-100 rounded-3xl flex items-center gap-3">
                  <Button
                    variant="outline"
                    class="rounded-3xl cursor-pointer hover:bg-destructive hover:text-destructive-foreground hover:border-destructive"
                    size="icon"
                    :disabled="form.processing || item.quantity < 2"
                    @click="form.delete(route('cart.remove', item.product.id))"
                  >
                    <Minus class="w-4 h-4"/>
                  </Button>

                  <div>{{ item.quantity }}</div>

                  <Button
                    variant="outline"
                    class="rounded-3xl cursor-pointer hover:bg-primary hover:text-primary-foreground hover:border-primary"
                    size="icon"
                    @click="form.post(route('cart.add', item.product.id))"
                    :disabled="form.processing || item.quantity >= item.product.stockItemsCount"
                  >
                    <Plus class="w-4 h-4"/>
                  </Button>
                </div>

                <span class="text-muted-foreground text-sm">В наличии: {{ item.product.stockItemsCount }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-span-2 text-right">
          <small v-if="item.quantity > 1" class="text-muted-foreground mb-1">
            <PriceFormatter :value="item.product.price.current"/> за 1шт
          </small>
          <template v-if="item.product.price.isDiscount">
            <div class="text-2xl text-destructive"><PriceFormatter :value="item.amount.discount"/></div>
            <div class="text-muted-foreground line-through"><PriceFormatter :value="item.amount.base"/></div>
          </template>
          <template v-else>
            <div class="text-2xl"><PriceFormatter :value="item.amount.current"/></div>
          </template>
        </div>

      </div>
    </CardContent>
  </Card>

</template>

<style scoped>

</style>