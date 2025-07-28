<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "../components/core/Wrapper.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {Button} from '@/components/ui/button'
import {Badge} from '@/components/ui/badge'
import {ShoppingCart} from 'lucide-vue-next';

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
  <Wrapper>


    <div class="grid grid-cols-12 gap-10">
      <div class="col-span-6">
        <img :src="product.previewImage.url" class="aspect-square w-full rounded-md bg-gray-200 object-cover lg:aspect-auto ">
      </div>
      <div class="col-span-6">
        <h1 class="text-3xl font-bold leading-9">{{ product.name }}</h1>

        <article class="mt-3 prose prose-lg">
          <p>Ключ активации для Windows 11 Pro (Профессиональная) высылается покупателю сразу после оплаты. </p>
          <p>Он позволяет активировать ОС и использовать лицензионное программное обеспечение без каких-либо ограничений, в том числе скачивать и устанавливать обновления, регулярно выпускаемые компанией-производителем софта.</p>
          <p>По указанным клиентом контактным данным высылается электронный код активации оригинальной ОС в формате XXXXX-XXXXX-XXXXX-XXXXX-XXXXХ.</p>
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
              <ShoppingCart class="w-4 h-4 mr-1" /> В корзине
            </Button>
          </div>
          <div v-else>
            <Button @click="form.post(route('cart.add', product.id))" :disabled="form.processing" class="cursor-pointer">
              <ShoppingCart class="w-4 h-4 mr-1" /> В корзину
            </Button>
          </div>
        </div>

      </div>
    </div>


  </Wrapper>
</MainLayout>
</template>
