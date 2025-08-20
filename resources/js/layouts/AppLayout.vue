<script setup>
import {Toaster, toast} from 'vue-sonner'
import 'vue-sonner/style.css'
import {Head, usePage} from "@inertiajs/vue3";
import {watch} from "vue";
import PageLoaderWrapper from "@/components/support/PageLoaderWrapper.vue";

const {seo, toasts} = usePage().props;

watch(
  () => toasts,
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
          case 'error':
            toast.error(toastData.message, params);
            break
          case 'success':
            toast.success(toastData.message, params);
            break
          case 'warning':
            toast.warning(toastData.message, params);
            break
          case 'info':
            toast.info(toastData.message, params);
            break
          default:
            toast(toastData.message, params)
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
  <Head>
    <title>{{ seo.title }}</title>
    <meta name="description" :content="seo.description">
    
    <!-- Open Graph -->
    <meta property="og:title" :content="seo.openGraph.title" />
    <meta property="og:description" :content="seo.openGraph.description" />
    <meta property="og:image" :content="seo.openGraph.image" />
    <meta property="og:url" :content="seo.openGraph.url" />
    <meta property="og:type" :content="seo.openGraph.type" />
    <meta property="og:site_name" :content="seo.openGraph.site_name" />

    <!-- Twitter Card -->
    <meta name="twitter:card" :content="seo.twitterCard.card" />
    <meta name="twitter:title" :content="seo.twitterCard.title" />
    <meta name="twitter:description" :content="seo.twitterCard.description" />
    <meta name="twitter:image" :content="seo.twitterCard.image" />
    <meta
      v-if="seo.twitterCard.site"
      name="twitter:site"
      :content="seo.twitterCard.site"
    />
  </Head>

  <Toaster closeButton richColors position="top-right"/>

  <PageLoaderWrapper>
    <div class="min-h-screen">
      <slot/>
    </div>
  </PageLoaderWrapper>
</template>