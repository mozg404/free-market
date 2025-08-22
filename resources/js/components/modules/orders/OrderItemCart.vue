<script setup>
import {Link} from "@inertiajs/vue3";
import PriceFormatter from "@/components/shared/PriceFormatter.vue";
import ProductImage from "@/components/modules/products/ProductImage.vue";
import {Card, CardContent} from "@/components/ui/card/index.js";
import UserAvatar from "@/components/modules/users/UserAvatar.vue";
import FeedbackControlButton from "@/components/modules/feedback/FeedbackControlButton.vue";

const props = defineProps({
  item: Object,
})
</script>

<template>
  <Card class="py-4 gap-4">
    <CardContent class="px-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center min-w-0">
          <div class="w-20 shrink-0 mr-4">
            <Link :href="route('catalog.product', item.product.id)" class="block">
              <ProductImage :product="item.product" />
            </Link>
          </div>
          <div>
            <div class="line-clamp-2">{{ item.product.name }}</div>

          </div>
        </div>

        <div class="ml-6 text-right text-nowrap">
          <template v-if="item.price.has_discount">
            <div class="text-destructive "><PriceFormatter :value="item.price.discount"/></div>
            <div class="text-sm text-muted-foreground line-through"><PriceFormatter :value="item.price.base"/></div>
          </template>
          <template v-else>
            <PriceFormatter :value="item.price.current"/>
          </template>
        </div>
      </div>
    </CardContent>
    <CardContent class="px-4">
      <div class="flex items-center justify-between space-x-4">

        <div class="flex items-center">
          <UserAvatar class="mr-2" :src="item.seller.avatar_url"/>
          <div>
            <div class="text-muted-foreground text-xs -mb-[4px]">Продавец</div>
            <Link :href="route('users.show', item.seller.id)" class="font-semibold text-sm hover:text-primary">{{item.seller.name}}</Link>
          </div>
        </div>

        <FeedbackControlButton :order-item-id="item.id" :feedback="item.feedback" />
      </div>
    </CardContent>
  </Card>
</template>