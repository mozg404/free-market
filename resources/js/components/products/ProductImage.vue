<script setup>
import { AspectRatio } from '@/components/ui/aspect-ratio/index.js';
import { ImageOff } from 'lucide-vue-next';

defineProps({
  src: [String, null],
  alt: {
    type: String,
    default: 'Изображение товара',
  },
});
</script>

<template>
  <AspectRatio :ratio="3 / 4" class="bg-muted rounded-md overflow-hidden relative">
    <img
      v-if="src"
      :src="src"
      :alt="alt"
      class="object-cover rounded-md w-full h-full transition-transform duration-300 ease-in-out hover:scale-105"
    />
    <div v-else class="flex items-center justify-center h-full text-muted-foreground rounded-md">
      <ImageOff class="h-6 w-6" />
    </div>
  </AspectRatio>
</template>