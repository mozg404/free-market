<script setup>
import { AspectRatio } from '@/components/ui/aspect-ratio/index.js';
import { ImageOff } from 'lucide-vue-next';

defineProps({
  image: {
    type: Object,
    required: true ,
  },
  alt: {
    type: String,
    default: 'Изображение товара',
  },
});
</script>

<template>
  <AspectRatio :ratio="3 / 4" class="bg-muted rounded-md">
    <img
      v-if="image.isExists"
      :src="image.url"
      :alt="alt"
      class="object-cover rounded-md w-full h-full"
    />
    <div v-else class="flex items-center justify-center h-full text-muted-foreground rounded-md">
      <ImageOff class="h-6 w-6" />
    </div>
  </AspectRatio>
</template>