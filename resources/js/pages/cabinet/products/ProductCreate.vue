<script setup>
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import {LoaderCircle} from 'lucide-vue-next';
import Input from "@/components/ui/input/Input.vue";
import {Button} from '@/components/ui/button/index.js'
import {useForm} from "@inertiajs/vue3";
import FilePondImage from "@/components/support/FilePondImage.vue";
import { Switch } from '@/components/ui/switch'
import QuillEditor from "@/components/support/QuillEditor.vue";

const form = useForm({
  name: null,
  price: '',
  priceDiscount: '',
  description: '',
  previewImage: null,
  isAvailable: false,
})
</script>

<template>
  <h2 class="mb-6 text-lg font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">Новый товар</h2>

  <form @submit.prevent="form.post(route('cabinet.products.store'))" class="flex flex-col gap-6">
    <div class="grid gap-6">
      <div class="grid gap-2">
        <Label for="name">Название <span class="text-red-600">*</span></Label>
        <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" v-model="form.name"/>
        <InputError :message="form.errors.name"/>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="grid gap-2">
          <Label for="price">Цена <span class="text-red-600">*</span></Label>
          <Input id="price" type="text" autofocus :tabindex="1" autocomplete="price" v-model="form.price"/>
          <InputError :message="form.errors.price"/>
        </div>

        <div class="grid gap-2">
          <Label for="priceDiscount">Цена по скидке <span class="text-red-600">*</span></Label>
          <Input id="priceDiscount" type="text" autofocus :tabindex="1" autocomplete="priceDiscount" v-model="form.priceDiscount"/>
          <InputError :message="form.errors.priceDiscount"/>
        </div>
      </div>

      <div class="grid gap-2">
        <div class="flex items-center space-x-2">
          <Switch id="airplane-mode" v-model="form.isAvailable" />
          <Label for="airplane-mode">В наличие</Label>
        </div>
        <InputError :message="form.errors.isAvailable"/>
      </div>

      <div class="grid gap-2">
        <Label>Изображение <span class="text-red-600">*</span></Label>
        <FilePondImage v-model="form.previewImage"/>
        <InputError :message="form.errors.previewImage"/>
      </div>

      <div class="grid gap-2">
        <Label>Описание <span class="text-red-600">*</span></Label>
        <QuillEditor v-model="form.description" placeholder="Начните писать"/>
        <InputError :message="form.errors.description"/>
      </div>

      <div>
        <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
          <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
          Создать
        </Button>
      </div>
    </div>
  </form>
</template>

<script>
import CabinetLayout from "@/layouts/CabinetLayout.vue";

export default {
  layout: CabinetLayout,
}
</script>