<script setup>
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import {LoaderCircle} from 'lucide-vue-next';
import {Button} from '@/components/ui/button/index.js'
import {useForm} from "@inertiajs/vue3";
import QuillEditor from "@/components/shared/form/QuillEditor.vue";
import {computed} from 'vue'
import FormField from "@/components/shared/form/FormField.vue";
import FormNumberInput from "@/components/shared/form/FormNumberInput.vue";
import FormSelect from "@/components/shared/form/FormSelect.vue";
import FormTextInput from "@/components/shared/form/FormTextInput.vue";
import FormSwitch from "@/components/shared/form/FormSwitch.vue";
import ImageUploader from "@/components/shared/form/ImageUploader.vue";
import PageLayout from "@/layouts/PageLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";

const props = defineProps({
  isEdit: {
    type: Boolean,
    default: false,
  },
  id: {
    type: Number,
    default: 0,
  },
  data: {
    type: Object,
    default: () => ({}),
  },
  categories: Array,
})

const form = useForm({
  name: props.data.name,
  category_id: props.data.category_id ?? props.categories[0].id,
  price_base: props.data.price_base,
  price_discount: props.data.price_discount,
  description: props.data.description,
  instruction: props.data.instruction,
  preview_image: props.data.preview_image,
  features: props.data.features ?? [],
  _method: props.isEdit ? 'PUT' : 'GET', // Method spoofing
})

// Доступные характеристики для выбранной категории
const currentFeatures = computed(() => {
  if (!form.category_id) return []
  return props.categories.find(c => c.id === form.category_id)?.features || []
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
    form.post(route('my.products.update', props.id), {
      forceFormData: true,
    })
  } else {
    form.post(route('my.products.store'))
  }
}
</script>

<template>
  <PageLayout>
    <template #title>{{ isEdit ? 'Редактирование товара №' + id : 'Новый товар' }}</template>

    <Wrapper>
      <form @submit.prevent="submit" class="flex flex-col gap-6 max-w-[800px]">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <FormField label="Категория" :required="true" :error="form.errors.category_id">
              <FormSelect :options="categories" v-model="form.category_id" />
            </FormField>
          </div>

          <div class="grid gap-2">
            <FormField label="Название" :required="true" :error="form.errors.name">
              <FormTextInput v-model="form.name" />
            </FormField>
          </div>

          <div class="grid grid-cols-2 gap-6">
            <div class="grid gap-2">
              <FormField label="Цена" :required="true" :error="form.errors.price_base">
                <FormNumberInput  v-model="form.price_base" />
              </FormField>
            </div>
            <div class="grid gap-2">
              <FormField label="Цена по скидке" :error="form.errors.price_discount">
                <FormNumberInput v-model="form.price_discount" />
              </FormField>
            </div>
          </div>

          <div class="grid gap-2">
            <Label>Превью</Label>
            <div class="max-w-[300px]">
              <ImageUploader v-model="form.preview_image" :aspect-ratio="3/4"/>
            </div>
            <InputError :message="form.errors.preview_image"/>
          </div>

          <div class="grid gap-2">
            <Label>Описание</Label>
            <QuillEditor v-model="form.description"/>
            <InputError :message="form.errors.description"/>
          </div>

          <div class="grid gap-2">
            <Label>Инструкция</Label>
            <QuillEditor v-model="form.instruction"/>
            <InputError :message="form.errors.instruction"/>
          </div>
        </div>

        <div class="grid gap-6" v-if="form.category_id">
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
            {{ isEdit ? 'Сохранить' : 'Создать' }}
          </Button>
        </div>
      </form>
    </Wrapper>

  </PageLayout>
</template>