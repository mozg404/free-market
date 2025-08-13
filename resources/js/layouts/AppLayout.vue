<script setup>
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import {usePage} from "@inertiajs/vue3";
import {watch, computed} from "vue";
import PageLoader from "@/components/support/PageLoader.vue";

const page = usePage()
const toasts = computed(() => page.props.toasts)

watch(
  () => page.props.toasts,
  (toasts) => {
    if (!toasts?.length) {
      return
    }

    toasts.forEach((toastData, index) => {
      setTimeout(() => {
        const params = {
          description: toastData.description,
        }

        switch (toastData.type) {
          case 'error': toast.error(toastData.message, params); break
          case 'success': toast.success(toastData.message, params); break
          case 'warning': toast.warning(toastData.message, params); break
          case 'info': toast.info(toastData.message, params); break
          default: toast(toastData.message, params)
        }
      }, index * 300)
    })
  },
  {
    deep: true,
    immediate: true
  }
)

</script>

<template>
  <Toaster closeButton richColors position="top-right" />
  <PageLoader />
  <slot/>
</template>

<style scoped>

</style>
