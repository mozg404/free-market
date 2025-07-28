<script setup>
import dayjs from 'dayjs';
import { computed } from 'vue';

const props = defineProps({
  value: [String, Date],
  format: {
    type: String,
    default: 'DD.MM.YYYY HH:mm',
  },
});

const formatted = computed(() => dayjs(props.value).format(props.format));
</script>

<template>
  <time :datetime="value">{{ formatted }}</time>
</template>

<style scoped>
</style>