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

const form = useForm({
  name: null,
  address: null,
  inn: '',
  phone: '',
  description: '',
})
const modalRef = ref(null)
const submit = () => form.post(route('cabinet.shops.store'), {
  onSuccess: () => modalRef.value.close(),
  onError: (errors) => {console.log(errors)}
})

</script>

<template>
  <Modal max-width="xl" ref="modalRef">
    <h2 class="mb-6 text-lg font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">Новый магазин</h2>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="name">Название <span class="text-red-600">*</span></Label>
          <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" v-model="form.name"/>
          <InputError :message="form.errors.name"/>
        </div>

        <div class="grid gap-2">
          <Label for="inn">ИНН <span class="text-red-600">*</span></Label>
          <Input id="inn" type="text" autofocus :tabindex="2" autocomplete="inn" v-model="form.inn"/>
          <InputError :message="form.errors.inn"/>
        </div>

        <div class="grid gap-2">
          <Label for="address">Адрес</Label>
          <Input id="address" type="text" autofocus :tabindex="3" autocomplete="address" v-model="form.address"/>
          <InputError :message="form.errors.address"/>
        </div>

        <div class="grid gap-2">
          <Label for="phone">Контактный телефон</Label>
          <Input id="phone" type="tel" autofocus :tabindex="4" autocomplete="phone" v-model="form.phone"/>
          <InputError :message="form.errors.phone"/>
        </div>

        <div class="grid gap-2">
          <Label for="description">Описание</Label>
          <Textarea id="description" placeholder="Начните ввод" :tabindex="5">{{ form.errors.description }}</Textarea>
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

  </Modal>
</template>

<style scoped>
</style>