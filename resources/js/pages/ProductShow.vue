<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {Link, useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {Button} from '@/components/ui/button'
import {Badge} from '@/components/ui/badge'
import {Minus, Plus, ShoppingBasket, ShoppingCart} from 'lucide-vue-next';
import ProductImage from "@/components/products/ProductImage.vue";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import MainTitle from "@/components/shared/layout/MainTitle.vue";
import Main from "@/components/shared/layout/Main.vue";
import {
  DescriptionList,
  DescriptionItem,
  DescriptionTitle,
  DescriptionValue,
  DescriptionSeparator
} from '@/components/shared/description'
import Section from "@/components/shared/layout/Section.vue";
import SectionTitle from "@/components/shared/layout/SectionTitle.vue";
import { useCart } from '@/composables/useCart'

const props = defineProps({
  product: Object,
})
const { inCart, addToCart, decreaseQuantity, getCartItemQuantity, form } = useCart()
</script>

<template>
  <MainLayout>
    <Main>
      <Wrapper>

        <!-- Основная обёртка -->
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Левый (плавающий) блок -->
          <div class="lg:sticky lg:top-6 lg:self-start lg:w-1/3">
            <ProductImage :image="product.preview_image" :alt="product.name"/>

            <div class="my-4 text-center">
              <div class="flex items-center justify-center space-x-2" v-if="product.price.has_discount">
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

            <div v-if="inCart(product.id)">
              <div class="flex justify-center space-x-4">
                <div class="bg-gray-100 rounded-3xl flex items-center gap-3">
                  <Button
                    variant="outline"
                    class="rounded-3xl cursor-pointer hover:bg-destructive hover:text-destructive-foreground hover:border-destructive"
                    size="icon"
                    :disabled="form.processing"
                    @click="decreaseQuantity(product.id)"
                  >
                    <Minus class="w-4 h-4"/>
                  </Button>

                  <div>{{getCartItemQuantity(product.id)}}</div>

                  <Button
                    variant="outline"
                    class="rounded-3xl cursor-pointer hover:bg-primary hover:text-primary-foreground hover:border-primary"
                    size="icon"
                    @click="addToCart(product.id)"
                    :disabled="form.processing"
                  >
                    <Plus class="w-4 h-4"/>
                  </Button>
                </div>
                <Button :as="Link" :href="route('cart.index')" class="rounded-3xl cursor-pointer">
                  <ShoppingCart class="w-4 h-4"/>
                  Перейти в корзину
                </Button>
              </div>
            </div>
            <Button
              v-else
              class="w-full cursor-pointer py-6"
              @click="addToCart(product.id)"
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

      </Wrapper>
    </Main>
  </MainLayout>
</template>
