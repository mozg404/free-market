<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {Link} from "@inertiajs/vue3";
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
import {Card, CardContent} from "@/components/ui/card/index.js";
import UserAvatar from "@/components/users/UserAvatar.vue";

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
        <div class="flex flex-col lg:flex-row justify-between gap-8">

          <!-- Правый блок (контент) -->
          <div class="flex-1">

            <div class="mb-12">
              <Badge variant="secondary">{{ product.category.name }}</Badge>

              <MainTitle class="mt-3">{{ product.name }}</MainTitle>
            </div>

            <Section v-if="product.features" class="mt-6">
              <DescriptionList>
                <DescriptionItem v-for="feature in product.features" :key="feature.id">
                  <DescriptionTitle>{{ feature.name }}</DescriptionTitle>
                  <DescriptionSeparator />
                  <DescriptionValue>{{ feature.value }}</DescriptionValue>
                </DescriptionItem>
              </DescriptionList>
            </Section>


            <Section v-if="product.description" class="mt-12">
              <SectionTitle>Описание</SectionTitle>
              <article class="mt-2 prose prose-md max-w-full">
                <div v-html="product.description"></div>
              </article>
            </Section>

            <Section v-if="product.instruction">
              <SectionTitle>Инструкция по активации</SectionTitle>

              <article class="mt-2 prose prose-md">
                <div v-html="product.instruction"></div>
              </article>
            </Section>
          </div>


          <!-- Правый (плавающий) блок -->
          <div class="lg:sticky lg:top-6 lg:self-start lg:w-[300px]">

            <ProductImage :src="product.preview_image" :alt="product.name" class="mb-4"/>

            <Card class="py-4 mb-4">
              <CardContent class="px-4">

                <div class="text-muted-foreground text-sm pb-2">В наличие: {{product.available_stock_items_count}}</div>
                <div class="mb-4">
                  <div class="flex items-center space-x-2" v-if="product.price.has_discount">
                    <div class="text-3xl font-bold">
                      <PriceFormatter :value="product.price.discount"/>
                    </div>
                    <div class="text-muted-foreground line-through">
                      <PriceFormatter :value="product.price.base"/>
                    </div>
                  </div>
                  <div v-else class="text-3xl font-bold">
                    <PriceFormatter :value="product.price.current"/>
                  </div>
                </div>

                <template v-if="product.is_available">
                  <div v-if="inCart(product.id)">
                    <div class="flex space-x-4 items-center">
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
                      В корзине
                      <Button :as="Link" :href="route('cart.index')" class="rounded-3xl cursor-pointer hidden">
                        В корзине
                      </Button>
                    </div>
                  </div>
                  <Button
                    v-else
                    size="lg"
                    class="w-full cursor-pointer "
                    @click="addToCart(product.id)"
                    :disabled="form.processing"
                  >
                    <ShoppingCart class="w-4 h-4"/>
                    В корзину
                  </Button>
                </template>

                <template v-else>
                  <Button class="w-full cursor-pointer py-6" disabled>
                    Недоступно для покупки
                  </Button>
                </template>
              </CardContent>
            </Card>

            <Card class="py-4 mb-4">
              <CardContent class="px-4 flex items-center">
                <UserAvatar class="mr-3" :src="product.user.avatar"/>
                <div>
                  <div class="text-muted-foreground text-xs mb-[-4px]">Продавец</div>
                  <Link :href="route('users.show', product.user.id)" class="font-semibold text-sm hover:text-primary">{{product.user.name}}</Link>
                </div>
              </CardContent>
            </Card>
          </div>

        </div>
      </Wrapper>
    </Main>
  </MainLayout>
</template>
