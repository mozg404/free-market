<script setup>
import { Link } from '@inertiajs/vue3'
import {cn} from "@/lib/utils.js";

const props = defineProps({
  pagination: Object,
  class: {
    type: String,
    default: ''
  }
})
</script>

<template>
  <nav v-if="pagination.last_page > 1" :class="cn('relative flex justify-center', props.class)">
    <template v-for="link in pagination.links" :key="link.label">
      <Link
        preserve-scroll
        :href="link.url ?? ''"
        v-html="link.label"
        class="flex items-center justify-center px-3 py-2 text-sm rounded-lg text-gray-600"
        :class="{ 'bg-gray-200': link.active, '!text-gray-300': !link.url }"
      />
    </template>
  </nav>
</template>

<style scoped>
</style>