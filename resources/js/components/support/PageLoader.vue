<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Spinner from "@/components/support/Spinner.vue";

const isLoading = ref(false)

router.on('start', () => {
  isLoading.value = true
})

router.on('finish', () => {
  isLoading.value = false
})

router.on('error', () => {
  isLoading.value = false
})
</script>

<template>
  <Transition name="fade">
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-white/75 dark:bg-gray-900/75 z-50 flex items-center justify-center"
    >
      <Spinner />
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active {
  transition: opacity 1s ease-out; /* Медленное появление */
}

.fade-leave-active {
  transition: opacity 0.3s ease-in; /* Быстрое исчезновение */
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

