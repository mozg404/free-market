<script setup>
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import ProductCard from "@/components/modules/products/ProductCard.vue";
import {Link, useForm, router} from "@inertiajs/vue3";
import Input from "@/components/ui/input/Input.vue";
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import { Switch } from '@/components/ui/switch'
import {LoaderCircle} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import FormField from "@/components/shared/form/FormField.vue";
import FormMultipleCheckboxList from "@/components/shared/form/FormMultipleCheckboxList.vue";
import {normalizeKeyValuePairs} from "@/lib/support.js";
import LaravelPagination from "@/components/shared/LaravelPagination.vue";
import PageLayout from "@/layouts/PageLayout.vue";
import {Card, CardContent, CardHeader, CardTitle} from "@/components/ui/card/index.js";
import SidebarLayout from "@/components/shared/SidebarLayout.vue";
import CategoriesNavTree from "@/components/modules/catalog/category-selector/CategoriesNavTree.vue";
import {cn} from "@/lib/utils.js";

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
  products: Object,
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
  <PageLayout :with-breadcrumbs="isCategory">
    <template #title>{{ isCategory ? category.title : 'Каталог товаров'}}</template>
    <template #counter>{{ products.total }}</template>

    <Wrapper>
      <SidebarLayout>
        <template #sidebar_left>
          <Card>
            <CardHeader>
              <CardTitle>Категории</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="-my-[0.5rem]">

                <Link
                  :href="route('catalog')"
                  :class="cn(
        'flex items-center justify-between -mx-[1rem] my-1 whitespace-nowrap rounded-md text-sm font-medium outline-none',
        'transition-colors hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 cursor-pointer',
        route().current('catalog') ? 'bg-accent text-accent-foreground' : ''
      )"
                >
                  Все
                </Link>


                <CategoriesNavTree :categories="categories"/>
              </div>
            </CardContent>
          </Card>

          <Card class="mt-6">
            <CardHeader>
              <CardTitle>Фильтры</CardTitle>
            </CardHeader>
            <CardContent>
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
            </CardContent>
          </Card>
        </template>

        <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:gap-x-8 w-full">
          <ProductCard v-for="product in products.data" :key="product.id" :product="product" />
        </div>

        <LaravelPagination class="mt-8" :pagination="products"/>
      </SidebarLayout>
    </Wrapper>
  </PageLayout>
</template>