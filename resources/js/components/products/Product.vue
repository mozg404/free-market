<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  product: Object,
})
</script>

<template>
<div class="group relative">
  <img :src="product.previewImage.url" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
  <div class="mt-4 flex justify-between">
    <div>
      <h3 class="text-sm text-gray-700">
        <Link :href="route('product.show', product.id)">
            <span aria-hidden="true" class="absolute inset-0"></span>
            {{ product.name }}
        </Link>
      </h3>
      <p class="mt-1 text-sm text-gray-500">Black</p>
    </div>
    <p class="text-sm font-medium text-gray-900">{{ product.price.current }} ₽</p>
  </div>
</div>
</template>

<style scoped>

</style>