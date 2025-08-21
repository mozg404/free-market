<script setup>
import { AspectRatio } from '@/components/ui/aspect-ratio/index.js';
import { ImageOff } from 'lucide-vue-next';

defineProps({
  product: {
    type: Object,
    required: true,
  }
});
</script>

<template>
  <AspectRatio :ratio="3 / 4" class="bg-muted rounded-md overflow-hidden relative">
    <img
      v-if="product?.image_url"
      :src="product?.image_url"
      :alt="product?.name ?? 'Изображение товара'"
      class="object-cover rounded-md w-full h-full transition-transform duration-300 ease-in-out hover:scale-105"
    />
    <div v-else class="flex items-center justify-center h-full text-muted-foreground rounded-md">
      <ImageOff class="h-6 w-6" />
    </div>
  </AspectRatio>
</template>