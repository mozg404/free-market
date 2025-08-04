<script>
export default {
  inheritAttrs: false
}
</script>

<script setup>
import { Checkbox } from '@/components/ui/checkbox'
import FormLabel from '@/components/core/form/FormLabel.vue'
import { computed, useAttrs, ref, watch } from 'vue'
import {cn} from "@/lib/utils.js";
import {generateUniqueId} from "@/lib/support.js";

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => []
  },
  value: {
    type: String,
    required: true
  },
  label: String,
  id: {
    type: String,
    default: null
  },
  class: {
    type: String,
  }
})

const emit = defineEmits(['update:modelValue'])
const localId = computed(() => props.id || generateUniqueId('checkbox'))
const internalValue = computed({
  get: () => props.modelValue.includes(props.value),
  set: (val) => {
    const newArr = val
      ? [...props.modelValue, props.value]
      : props.modelValue.filter(v => v !== props.value);
    emit('update:modelValue', newArr);
  }
});
</script>

<template>
  <div :class="cn('flex items-center space-x-2', props.class)">
    <Checkbox
      v-model="internalValue"
      :id="localId"
      class="cursor-pointer"
    />
    <FormLabel v-if="label" :for="localId" class="cursor-pointer text-xs">
      {{ label }}
    </FormLabel>
  </div>
</template>