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
import FormField from "@/components/core/form/FormField.vue";
import FormNumberInput from "@/components/core/form/FormNumberInput.vue";
import FormSelect from "@/components/core/form/FormSelect.vue";
import FormTextInput from "@/components/core/form/FormTextInput.vue";
import {forEach} from "lodash";
import FormSwitch from "@/components/core/form/FormSwitch.vue";
import {Switch} from "@/components/ui/switch/index.js";
import FormMultipleCheckbox from "@/components/core/form/FormMultipleCheckbox.vue";
import FormMultipleCheckboxList from "@/components/core/form/FormMultipleCheckboxList.vue";
import {normalizeKeyValuePairs} from "@/lib/support.js";

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
  testChecks: Array,
})

const form = useForm({
  name: props.data.name,
  categoryId: props.data.categoryId ?? props.categories[0].id,
  priceBase: props.data.priceBase,
  priceDiscount: props.data.priceDiscount,
  description: props.data.description,
  previewImage: props.data.previewImage,
  features: props.data.features ?? [],

  test: ['on1', 'on3'],
  test2: ['on2'],
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

const selectValuesFormatted = (values) => {
  let result = []

  for (let key in values) {
    result.push({
      id: key,
      name: values[key],
    })
  }

  return result
}

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
        <FormField label="Категория" :required="true" :error="form.errors.categoryId">
          <FormSelect :options="categories" v-model="form.categoryId" />
        </FormField>
      </div>

      <div class="grid gap-2">
        <FormField label="Название" :required="true" :error="form.errors.name">
          <FormTextInput v-model="form.name" />
        </FormField>
      </div>


      <div class="grid gap-2">
        <FormField label="Тест чекбокса">
          <FormMultipleCheckbox v-model="form.test" value="on1" label="Вариант чекбокса 1" />
          <FormMultipleCheckbox v-model="form.test" value="on2" label="Вариант чекбокса 2" />
          <FormMultipleCheckbox v-model="form.test" value="on3" label="Вариант чекбокса 3" />
          <FormMultipleCheckbox v-model="form.test" value="on4" label="Вариант чекбокса 4" />
        </FormField>
      </div>

      <div class="grid gap-2">
        <FormField label="Тест списка чекбоксов">
          <FormMultipleCheckboxList v-model="form.test2" :options="normalizeKeyValuePairs(testChecks)" class="mt-1" />
        </FormField>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="grid gap-2">
          <FormField label="Цена" :required="true" :error="form.errors.priceBase">
            <FormNumberInput  v-model="form.priceBase" />
          </FormField>
        </div>
        <div class="grid gap-2">
          <FormField label="Цена по скидке" :error="form.errors.priceDiscount">
            <FormNumberInput v-model="form.priceDiscount" />
          </FormField>
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
        <template v-if="field.type === 'text'">
          <FormField :label="field.name">
            <FormTextInput v-model="form.features[field.id]" />
          </FormField>
        </template>

        <template v-else-if="field.type === 'select'">
          <FormField :label="field.name">
            <FormSelect :empty-option="true" :options="selectValuesFormatted(field.options)" v-model="form.features[field.id]" />
          </FormField>
        </template>

        <template v-else-if="field.type === 'number'">
          <FormField :label="field.name">
            <FormNumberInput v-model="form.features[field.id]" />
          </FormField>
        </template>

        <template v-else-if="field.type === 'check'">
          <FormSwitch v-model="form.features[field.id]" :label="field.name" />
        </template>
      </div>
    </div>

    <div>
      <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
        Создать
      </Button>
    </div>
  </form>
</template>

<script>
import CabinetLayout from "@/layouts/CabinetLayout.vue";

export default {
  layout: CabinetLayout,
}
</script>