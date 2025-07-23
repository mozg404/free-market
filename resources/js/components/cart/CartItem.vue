<script setup>
import { Button } from '@/components/ui/button'
import { useForm } from '@inertiajs/vue3'
import {Minus,Plus} from 'lucide-vue-next'

const props = defineProps({
  item: Object,
})
const form = useForm({})
</script>

<template>

  <div class="pb-6 mb-6 border-b-2 flex justify-between">
    <div class="flex">
      <a href="#" class="mr-3">
        <img :src="item.product.previewImage.url" class="w-[80px]"/>
      </a>
      <div>
        <div class="font-bold">{{ item.product.name }}</div>
<!--        <div class="font-light">{{ item.product.shop.name }}</div>-->
      </div>
    </div>
    <div class="flex items-center">
      <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.delete(route('cart.remove', item.product.id))" :disabled="form.processing">
        <Minus class="w-4 h-4"/>
      </Button>

      <div class="font-bold px-4">{{ item.quantity }}</div>

      <Button variant="outline" class="rounded-3xl cursor-pointer" @click="form.post(route('cart.add', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
        <Plus class="w-4 h-4"/>
      </Button>
    </div>
    <div>
      <span class="font-bold text-green-700">{{ item.product.price.current }}</span> ₽
    </div>
  </div>



<!--  <Card>-->
<!--    <CardHeader>-->
<!--      <CardTitle>{{ item.product.name }}</CardTitle>-->
<!--      <CardDescription>{{ item.product.shop.name }}</CardDescription>-->
<!--    </CardHeader>-->
<!--    <CardContent>-->
<!--      Цена: {{ item.product.price }} <br>-->
<!--      Количество: {{ item.quantity }} <br>-->
<!--    </CardContent>-->
<!--    <CardFooter class="flex justify-between px-6 pb-6">-->
<!--      <Button variant="outline" @click="form.delete(route('cart.remove', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Убрать</Button>-->
<!--      <Button variant="outline" @click="form.post(route('cart.add', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Добавить</Button>-->
<!--      <Button @click="form.delete(route('cart.delete', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Полностью удалить</Button>-->
<!--    </CardFooter>-->
<!--  </Card>-->
</template>

<style scoped>

</style>