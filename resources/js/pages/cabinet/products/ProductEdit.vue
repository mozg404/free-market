<script setup>
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import {LoaderCircle} from 'lucide-vue-next';
import Input from "@/components/ui/input/Input.vue";
import {Button} from '@/components/ui/button/index.js'
import {useForm} from "@inertiajs/vue3";
import FilePondImage from "@/components/support/FilePondImage.vue";
import QuillEditor from "@/components/support/QuillEditor.vue";
import { ref, computed } from 'vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'

const props = defineProps({
  isEdit: {
    type: Boolean,
    default: false,
  },
  id: Number,
  data: {
    type: Object,
    default: () => ({}),
  },
  categories: Array,
})

const form = useForm({
  name: props.data.name,
  categoryId: props.data.categoryId ?? props.categories[0].id,
  priceBase: props.data.priceBase,
  priceDiscount: props.data.priceDiscount,
  description: props.data.description,
  previewImage: props.data.previewImage,
  features: props.data.features,
})

// Доступные характеристики для выбранной категории
const currentFeatures = computed(() => {
  if (!form.categoryId) return []
  return props.categories.find(c => c.id === form.categoryId)?.features || []
})

// Генерация полей для характеристик

const featureFields = computed(() => {
  return currentFeatures.value.map(feature => ({
    ...feature,
    fieldName: `features[${feature.id}]`,
    fieldValue: form.features[feature.id] || ''
  }))
})

const submit = () => {
  if (props.isEdit) {
    form.put(route('cabinet.products.update', props.id))
  } else {
    form.post(route('cabinet.products.store'))
  }
}
</script>

<template>
<!--  <pre>-->
<!--    {{featureFields}}-->
<!--  </pre>-->

  <h2 class="mb-6 text-lg font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">Новый товар</h2>

  <form @submit.prevent="submit" class="flex flex-col gap-6">
    <div class="grid gap-6">
      <div class="grid gap-2">
        <Label for="categoryId">Категория <span class="text-red-600">*</span></Label>
        <Select v-model="form.categoryId">
          <SelectTrigger>
            <SelectValue placeholder="Выбрать категорию" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem v-for="category in props.categories" :key="category.id" :value="category.id">{{ category.name }}</SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
        <InputError :message="form.errors.categoryId"/>
      </div>

      <div class="grid gap-2">
        <Label for="name">Название <span class="text-red-600">*</span></Label>
        <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" v-model="form.name"/>
        <InputError :message="form.errors.name"/>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="grid gap-2">
          <Label for="priceBase">Цена <span class="text-red-600">*</span></Label>
          <Input id="priceBase" type="text" autofocus :tabindex="1" autocomplete="priceBase" v-model="form.priceBase"/>
          <InputError :message="form.errors.priceBase"/>
        </div>

        <div class="grid gap-2">
          <Label for="priceDiscount">Цена по скидке <span class="text-red-600">*</span></Label>
          <Input id="priceDiscount" type="text" autofocus :tabindex="1" autocomplete="priceDiscount" v-model="form.priceDiscount"/>
          <InputError :message="form.errors.priceDiscount"/>
        </div>
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
    </div>


    <div class="grid gap-6" v-if="form.categoryId">

      <div v-if="featureFields" v-for="field in featureFields" :key="field.id" class="grid gap-2">
        <Label :for="field.key">{{ field.name }} <span v-if="field.is_required" class="text-red-600">*</span></Label>

        <!-- Текстовое поле -->
        <Input v-if="field.type === 'text'" :id="field.key" type="text" autofocus />

        <!-- Выпадающий список -->
        <Select v-else-if="field.type === 'select'" v-model="form.features[field.id]">
          <SelectTrigger>
            <SelectValue placeholder="Выбрать категорию" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem v-for="(option, optionKey) in field.options" :key="optionKey" :value="optionKey">{{ option }}</SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>

        <!-- Числовое поле -->
        <Input v-if="field.type === 'number'" :id="field.key" type="number" />

        <!-- Чекбокс -->
        <div v-else-if="field.type === 'checkbox'" class="flex items-center gap-2">
          <input
            v-model="form.features[field.id]"
            type="checkbox"
            :required="field.is_required"
            class="checkbox"
          >
          <span>Да</span>
        </div>
      </div>
    </div>

    <div>
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