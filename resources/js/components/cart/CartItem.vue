<script setup>
import { Link } from "@inertiajs/vue3";
import { Button } from '@/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import { useForm } from '@inertiajs/vue3'
import {AlertDialogAction} from "@/components/ui/alert-dialog/index.js";

const props = defineProps({
  item: Object,
})
const form = useForm({})
</script>

<template>

  <Card>
    <CardHeader>
      <CardTitle>{{ item.product.name }}</CardTitle>
      <CardDescription>{{ item.product.shop.name }}</CardDescription>
    </CardHeader>
    <CardContent>
      Цена: {{ item.product.price }} <br>
      Количество: {{ item.quantity }} <br>
    </CardContent>
    <CardFooter class="flex justify-between px-6 pb-6">
      <Button variant="outline" @click="form.delete(route('cart.remove', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Убрать</Button>
      <Button variant="outline" @click="form.post(route('cart.add', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Добавить</Button>
      <Button @click="form.delete(route('cart.delete', item.product.id))" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">Полностью удалить</Button>
    </CardFooter>
  </Card>
</template>

<style scoped>

</style>