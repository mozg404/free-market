<script setup>
import { useForm } from '@inertiajs/vue3';
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import ImageUploader from "@/components/shared/ImageUploader.vue";

const form = useForm({
  image: null
});
</script>

<template>
  <Wrapper class="mt-10 flex relative">
    <div>
      <ImageUploader v-model="form.image" upload-label="Выбрать аватар" :aspect-ratio="3/4" />
    </div>

    <pre>
      {{form.image}}
    </pre>

  </Wrapper>
</template>
