<script setup>


import {Minus, Plus, Trash} from "lucide-vue-next";
import {Link} from "@inertiajs/vue3";
import {Button} from "@/components/ui/button/index.js";
import PriceFormatter from "@/components/support/PriceFormatter.vue";
import ProductImage from "@/components/products/ProductImage.vue";

const props = defineProps({
    item: Object,
  })
</script>

<template>

  <div class="grid grid-cols-12 items-center">
    <div class="col-span-10">
      <div class="flex items-center min-w-0">
        <div class="w-10 shrink-0 mr-4">
          <Link :href="route('catalog.product', item.product.id)" class="block">
            <ProductImage
              :image="item.product.preview_image"
              :alt="item.product.name"
            />
          </Link>
        </div>
        <div class="text-sm line-clamp-1">{{ item.product.name }}</div>
      </div>
    </div>

    <div class="col-span-2 text-right">
      <template v-if="item.price.has_discount">
        <div class="text-destructive"><PriceFormatter :value="item.price.discount"/></div>
        <div class="text-sm text-muted-foreground line-through"><PriceFormatter :value="item.price.base"/></div>
      </template>
      <template v-else>
        <PriceFormatter :value="item.price.current"/>
      </template>
    </div>

  </div>
</template>

<style scoped>

</style>