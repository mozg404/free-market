<script setup>
import MainLayout from "../../layouts/MainLayout.vue";
import Wrapper from "../../components/core/Wrapper.vue";
import Headline from "@/components/core/Headline.vue";
import Product from "@/components/modules/products/Product.vue";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import {Link, useForm} from "@inertiajs/vue3";
import Input from "@/components/ui/input/Input.vue";
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import { Separator } from '@/components/ui/separator'
import { Switch } from '@/components/ui/switch'
import {LoaderCircle} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";

const props = defineProps({
  isCategory: {
    type: Boolean,
    default: false
  },
  category: Object,
  categories: Array,
  products: Array,
  filters: Object,
})

const form = useForm({
  priceMin: props.filters.priceMin ?? null,
  priceMax: props.filters.priceMax ?? null,
  onlyDiscounted: props.filters.onlyDiscounted ?? false,
})

const filtersApply = () => {
  if (props.isCategory) {
    form.get(route('catalog.category', props.category.slug))
  } else {
    form.get(route('catalog'))
  }
}
</script>

<template>
  <MainLayout>
    <Wrapper>

      <div class="flex">
        <aside class="w-64 py-6 pr-6 border-r-1 bg-white sticky top-0 h-screen">

          <div class="font-semibold mb-4">Категории:</div>
          <nav>
            <ul>
              <li><Link :href="route('catalog')">Все</Link></li>
              <li v-for="categoryItem in categories" :key="categoryItem.id">
                <Link :href="route('catalog.category', categoryItem.slug)">
                  {{ categoryItem.name }}
                </Link>
              </li>
            </ul>
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

            <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
              Применить
            </Button>
          </form>
        </aside>

        <main class="flex-1 py-6 ml-6">
          <Headline v-if="isCategory" class="pb-6">{{ category.name }}</Headline>
          <Headline v-else class="pb-6">Товары</Headline>

          <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            <Product v-for="product in products" :key="product.id" :product="product" />
          </div>
        </main>
      </div>
    </Wrapper>
  </MainLayout>
</template>


