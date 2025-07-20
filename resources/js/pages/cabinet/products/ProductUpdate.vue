<script setup>
import {Modal} from '@inertiaui/modal-vue'
import {ref} from "vue";
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import {LoaderCircle} from 'lucide-vue-next';
import Input from "@/components/ui/input/Input.vue";
import {Button} from '@/components/ui/button/index.js'
import {Textarea} from '@/components/ui/textarea/index.js'
import {useForm} from "@inertiajs/vue3";
import FilePondImage from "@/components/FilePondImage.vue";
import { Switch } from '@/components/ui/switch'

const props = defineProps({
  id: Number,
  name: String,
  slug: String,
  price: Number,
  priceDiscount: Number,
  image: String,
  isAvailable: Boolean,
})

const form = useForm({
  name: props.name,
  slug: props.slug,
  price: props.price,
  priceDiscount: props.priceDiscount,
  image: props.image,
  isAvailable: props.isAvailable,
})
const modalRef = ref(null)
const submit = () => form.post(route('cabinet.products.update', props.id), {
  onSuccess: () => modalRef.value.close(),
  onError: (errors) => {
    console.log(errors)
  }
})

</script>

<template>
  <Modal max-width="xl" ref="modalRef">
    <h2 class="mb-6 text-lg font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">Редактировать товар #{{ props.id }}</h2>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="name">Название <span class="text-red-600">*</span></Label>
          <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" v-model="form.name"/>
          <InputError :message="form.errors.name"/>
        </div>

        <div class="grid gap-2">
          <Label for="slug">URI <span class="text-red-600">*</span></Label>
          <Input id="slug" type="text" autofocus :tabindex="1" autocomplete="slug" v-model="form.slug"/>
          <InputError :message="form.errors.slug"/>
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
          <FilePondImage v-model="form.image"/>
          <InputError :message="form.errors.image"/>
        </div>

        <div>
          <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
            Создать
          </Button>
        </div>
      </div>
    </form>

  </Modal>
</template>

<style scoped>
</style>