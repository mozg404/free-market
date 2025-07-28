<script setup>
import { Button } from '@/components/ui/button/index.js'
import { useForm } from '@inertiajs/vue3'
import {Minus,Plus} from 'lucide-vue-next'
import {Card, CardContent} from "@/components/ui/card/index.js";

const props = defineProps({
  item: Object,
})
const form = useForm({})
</script>

<template>


  <Card class="py-4 mb-6">
    <CardContent class="px-4">
      <div class="grid grid-cols-12 items-center">
        <div class="col-span-7">
          <div class="flex items-center min-w-0">
            <a href="#" class="block mr-4 shrink-0">
              <img :src="item.product.previewImage.url" class="w-[80px]" alt=""/>
            </a>
            <div>{{ item.product.name }}</div>
          </div>
        </div>

        <div class="col-span-3">
          <div class="flex items-center justify-center">
            <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.delete(route('cart.remove', item.product.id))" :disabled="form.processing">
              <Minus class="w-4 h-4"/>
            </Button>

            <div class="font-bold px-4">{{ item.quantity }}</div>

            <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.post(route('cart.add', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
              <Plus class="w-4 h-4"/>
            </Button>
          </div>
        </div>

        <div class="col-span-2 text-right">
          <template v-if="item.product.price.isDiscount">
            <div class="text-2xl text-red-600">{{ item.product.price.current }} ₽</div>
            <div class="text-gray-400 line-through">{{ item.product.price.current }} ₽</div>
          </template>
          <template v-else>
            <div class="text-2xl">{{ item.product.price.current }} ₽</div>
          </template>
        </div>

      </div>
    </CardContent>
  </Card>




<!--  <div class="pb-6 mb-6 border-b-2 flex justify-between">-->
<!--    <div class="flex">-->
<!--      <a href="#" class="mr-3">-->
<!--        <img :src="item.product.previewImage.url" class="w-[80px]"/>-->
<!--      </a>-->
<!--      <div>-->
<!--        <div class="font-bold">{{ item.product.name }}</div>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="flex items-center">-->
<!--      <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.delete(route('cart.remove', item.product.id))" :disabled="form.processing">-->
<!--        <Minus class="w-4 h-4"/>-->
<!--      </Button>-->

<!--      <div class="font-bold px-4">{{ item.quantity }}</div>-->

<!--      <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.post(route('cart.add', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">-->
<!--        <Plus class="w-4 h-4"/>-->
<!--      </Button>-->
<!--    </div>-->
<!--    <div>-->
<!--      <span class="font-bold text-green-700">{{ item.product.price.current }}</span> ₽-->
<!--    </div>-->
<!--  </div>-->




</template>

<style scoped>

</style>