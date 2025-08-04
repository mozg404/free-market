<script setup>
import { ChevronRight } from "lucide-vue-next"
import {cn} from "@/lib/utils.js";
import {computed} from "vue";

const props = defineProps({
  as: {
    type: [String, Object],
    default: 'a'
  },
  isActive: {
    type: Boolean,
    default: false
  },
  class: {
    type: String,
  }
})

const changedClasses = computed(() => {
  if (props.isActive) {
    return 'text-primary font-semibold'
  } else {
    return 'text-foreground hover:text-primary transition-colors'
  }
})
</script>

<template>
  <component
    :is="as"
    :class="cn('flex items-center ' + changedClasses, props.class)"
    v-bind="$attrs"
  >
    <ChevronRight class="w-4 h-4 mr-1" />
    <span>
      <slot />
    </span>
  </component>
</template>

