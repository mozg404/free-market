<script setup>
import { computed, useAttrs, provide } from 'vue'
import ErrorMessage from "@/components/support/ErrorMessage.vue";
import FormLabel from "@/components/core/form/FormLabel.vue";

const props = defineProps({
  label: {
    type: String,
    default: null
  },
  error: {
    type: String,
    default: ''
  },
  required: {
    type: Boolean,
    default: false
  },
  name: String // Новый пропс для связи
});

// Генерируем или используем переданный ID
const fieldId = computed(() =>
  props.name || useAttrs().id || `field-${Math.random().toString(36).slice(2, 9)}`
)

// Передаём ID в дочерние компоненты через provide
provide('fieldId', fieldId)
</script>

<template>
  <FormLabel v-if="label" :for="fieldId">{{ label }} <span v-if="required" class="text-red-600">*</span></FormLabel>
  <slot :id="fieldId" />
  <ErrorMessage v-if="error" :message="error"/>
</template>