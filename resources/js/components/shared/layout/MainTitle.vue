<script setup>
import { cn } from '@/lib/utils'

const props = defineProps({
  class: { type: String, default: '' }
})
</script>

<template>
  <div :class="cn('text-4xl font-semibold tracking-tight mb-12', props.class)">
    <slot/>
  </div>
</template>

<style scoped>

</style>