<script setup>
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import ProductCard from "@/components/products/ProductCard.vue";
import {Link, useForm, router} from "@inertiajs/vue3";
import Input from "@/components/ui/input/Input.vue";
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import { Separator } from '@/components/ui/separator'
import { Switch } from '@/components/ui/switch'
import {LoaderCircle} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import FormField from "@/components/shared/form/FormField.vue";
import FormMultipleCheckboxList from "@/components/shared/form/FormMultipleCheckboxList.vue";
import {normalizeKeyValuePairs} from "@/lib/support.js";
import NavArrowLink from "@/components/shared/navigation/NavArrowLink.vue";
import LaravelPagination from "@/components/support/LaravelPagination.vue";
import MainLayout from "@/layouts/MainLayout.vue";

const props = defineProps({
  isCategory: {
    type: Boolean,
    default: false
  },
  category: Object,
  categories: Array,
  features: {
    type: [Array, null],
    default: null,
  },
  productsPaginate: Array,
  filters: Object,
})

const form = useForm({
  priceMin: props.filters.priceMin ?? null,
  priceMax: props.filters.priceMax ?? null,
  onlyDiscounted: props.filters.onlyDiscounted ?? false,
  features: {
    // Для select-полей: берем значение из filters или инициализируем []
    ...Object.fromEntries(
      (props.features || [])
        .filter(f => f.type === 'select')
        .map(f => [f.id, props.filters.features?.[f.id] || []])
    ),
    // Для остальных полей берем как есть из filters
    ...(props.filters.features || {})
  },
})

const filtersApply = () => {
  const params = new URLSearchParams();

  // Рекурсивная функция для добавления параметров
  const addToParams = (data, prefix = '') => {
    Object.entries(data).forEach(([key, value]) => {
      // Пропускаем пустые значения
      if (value === null || value === undefined || value === '') return;

      const paramKey = prefix ? `${prefix}[${key}]` : key;

      if (Array.isArray(value)) {
        // Обработка массивов (например features)
        value.forEach(item => {
          if (item) params.append(paramKey + '[]', item);
        });
      } else if (typeof value === 'object') {
        // Рекурсия для вложенных объектов
        addToParams(value, paramKey);
      } else {
        // Простые значения
        params.append(paramKey, value);
      }
    });
  };

  // Обрабатываем все данные формы
  addToParams(form.data());

  // Формируем URL
  const baseUrl = props.isCategory
    ? route('catalog.category', props.category.slug)
    : route('catalog');

  router.get(baseUrl + '?' + params)
}
</script>

<template>
  <MainLayout :with-breadcrumbs="isCategory">
    <Wrapper>
      <div class="flex">
        <aside class="w-64 py-6 pr-6 border-r-1 bg-white top-0 h-screen">

          <div class="font-semibold mb-4">Категории:</div>
          <nav>
            <NavArrowLink :as="Link" :is-active="route().current('catalog')" :href="route('catalog')">Все</NavArrowLink>
            <NavArrowLink
              v-for="categoryItem in categories"
              :key="categoryItem.id"
              :as="Link"
              :is-active="route().current('catalog.category', categoryItem.slug)"
              :href="route('catalog.category', categoryItem.slug)"
              class="mt-2"
            >{{ categoryItem.name }}</NavArrowLink>
          </nav>

          <Separator class="my-6" />

          <form @submit.prevent="filtersApply">
            <div class="flex items-center space-x-2 mb-6">
              <Switch id="onlyDiscounted" v-model="form.onlyDiscounted" />
              <Label for="onlyDiscounted" class="font-normal">Только со скидками</Label>
            </div>

            <div class="flex mb-6">
              <div class="grid gap-2 mr-4">
                <Label for="priceMin" class="font-normal">Мин. цена</Label>
                <Input id="priceMin" type="text" autofocus autocomplete="priceMin" v-model="form.priceMin"/>
                <InputError :message="form.errors.priceMin"/>
              </div>

              <div class="grid gap-2">
                <Label for="priceMax" class="font-normal">Макс. цена</Label>
                <Input id="priceMax" type="text" autofocus autocomplete="priceMin" v-model="form.priceMax"/>
                <InputError :message="form.errors.priceMax"/>
              </div>
            </div>

            <template v-if="features">
              <template v-for="feature in features" :key="feature">
                <div class="grid gap-2 mb-6" v-if="feature.type === 'select'">
                  <FormField :label="feature.name">
                    <FormMultipleCheckboxList v-model="form.features[feature.id]" :options="normalizeKeyValuePairs(feature.options)"  class="mt-1" />
                  </FormField>
                </div>
              </template>
            </template>

            <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
              Применить
            </Button>
          </form>
        </aside>

        <main class="flex-1 py-6 ml-6">
          <MainTitle v-if="isCategory" class="pb-6">{{ category.name }}</MainTitle>
          <MainTitle v-else class="pb-6">Товары</MainTitle>

          <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <ProductCard v-for="product in productsPaginate.data" :key="product.id" :product="product" />
          </div>

          <LaravelPagination :pagination="productsPaginate"/>

        </main>
      </div>
    </Wrapper>
  </MainLayout>
</template>