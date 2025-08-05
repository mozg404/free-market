<script setup>
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { ImageOff } from 'lucide-vue-next';

defineProps({
  src: {
    type: String,
    default: '',
  },
  isExist: {
    type: Boolean,
    default: false,
  },
  alt: {
    type: String,
    default: 'Product image',
  },
});
</script>

<template>
  <AspectRatio :ratio="3 / 4" class="bg-muted rounded-md">
    <img
      v-if="isExist && src"
      :src="src"
      :alt="alt"
      class="object-cover rounded-md w-full h-full"
    />
    <div v-else class="flex items-center justify-center h-full text-muted-foreground rounded-md">
      <ImageOff class="h-6 w-6" />
    </div>
  </AspectRatio>
</template>