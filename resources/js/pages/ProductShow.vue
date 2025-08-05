<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "../components/core/layout/Wrapper.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {Button} from '@/components/ui/button'
import {Badge} from '@/components/ui/badge'
import {ShoppingBasket, ShoppingCart} from 'lucide-vue-next';
import ProductImage from "@/components/products/ProductImage.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import MainTitle from "@/components/core/layout/MainTitle.vue";
import Main from "@/components/core/layout/Main.vue";
import {
  DescriptionList,
  DescriptionItem,
  DescriptionTitle,
  DescriptionValue,
  DescriptionSeparator
} from '@/components/core/description'
import Section from "@/components/core/layout/Section.vue";
import SectionTitle from "@/components/core/layout/SectionTitle.vue";

const page = usePage()
const cart = computed(() => page.props.cart)
const props = defineProps({
  product: Object,
})

const form = useForm({})
const inCart = (id) => Object.keys(page.props.cart.items).some(key => page.props.cart.items[key].product.id === id)
</script>

<template>
  <MainLayout>
    <Main>
      <Wrapper>

        <!-- Основная обёртка -->
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Левый (плавающий) блок -->
          <div class="lg:sticky lg:top-6 lg:self-start lg:w-1/3">
            <ProductImage :image="product.previewImage" :alt="product.name"/>

            <div class="my-4">
              <div class="flex items-center space-x-2" v-if="product.price.isDiscount">
                <div class="text-2xl font-bold">
                  <PriceFormatter :value="product.price.discount"/>
                </div>
                <div class="text-muted-foreground line-through">
                  <PriceFormatter :value="product.price.base"/>
                </div>
              </div>
              <div v-else class="text-2xl font-bold">
                <PriceFormatter :value="product.price.current"/>
              </div>
            </div>

            <Button
              class="w-full cursor-pointer py-6"
              @click="form.post(route('cart.add', product.id))"
              :disabled="form.processing"
            >
              <ShoppingCart class="w-4 h-4"/>
              В корзину
            </Button>
          </div>

          <!-- Правый блок (контент) -->
          <div class="lg:w-2/3">
            <MainTitle class="mb-4">{{ product.name }}</MainTitle>

            <Section v-if="product.features">
              <DescriptionList>
                <DescriptionItem v-for="feature in product.features" :key="feature.id">
                  <DescriptionTitle>{{ feature.name }}</DescriptionTitle>
                  <DescriptionSeparator />
                  <DescriptionValue>{{ feature.value }}</DescriptionValue>
                </DescriptionItem>
              </DescriptionList>
            </Section>

            <Section class="mt-6" v-if="product.description">
              <SectionTitle>Описание</SectionTitle>
              <article class="mt-2 prose prose-md">
                <div v-html="product.description"></div>
              </article>
            </Section>

          </div>
        </div>


        <br><br><br><br><br>


        <div class="grid grid-cols-12 gap-10" v-if="false">
          <div class="col-span-3">
            <img :src="product.previewImage.url"
                 class="aspect-square w-full rounded-md bg-gray-200 object-cover lg:aspect-auto ">
          </div>
          <div class="col-span-9">
            <h1 class="text-3xl font-bold leading-9">{{ product.name }}</h1>

            <article class="mt-3 prose prose-lg">
              <p>Ключ активации для Windows 11 Pro (Профессиональная) высылается покупателю сразу после оплаты. </p>
              <p>Он позволяет активировать ОС и использовать лицензионное программное обеспечение без каких-либо
                ограничений, в том числе скачивать и устанавливать обновления, регулярно выпускаемые
                компанией-производителем софта.</p>
              <p>По указанным клиентом контактным данным высылается электронный код активации оригинальной ОС в формате
                XXXXX-XXXXX-XXXXX-XXXXX-XXXXХ.</p>
            </article>

            <div class="mt-3">
              <div v-if="product.price.isDiscount">
                <div class="flex items-center">
                  <div class="text-xl font-bold text-red-600 line-through">{{ product.price.base }}</div>
                  <div class="mx-4">{{ product.price.discount }} ₽</div>
                  <Badge variant="destructive">-{{ product.price.discountPercent }}%</Badge>
                </div>
              </div>
              <div v-else class="text-lg font-bold">{{ product.price.current }} ₽</div>
            </div>

            <div class="mt-3">
              <div v-if="inCart(product.id)">
                <Button disabled>
                  <ShoppingCart class="w-4 h-4 mr-1"/>
                  В корзине
                </Button>
              </div>
              <div v-else>
                <Button @click="form.post(route('cart.add', product.id))" :disabled="form.processing"
                        class="cursor-pointer">
                  <ShoppingCart class="w-4 h-4 mr-1"/>
                  В корзину
                </Button>
              </div>
            </div>

          </div>
        </div>


      </Wrapper>
    </Main>
  </MainLayout>
</template>
