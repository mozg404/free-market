<script setup>
import {Modal} from '@inertiaui/modal-vue'
import {ref} from "vue";
import InputError from "@/components/ui/input/InputError.vue";
import {Label} from "@/components/ui/label/index.js";
import {LoaderCircle} from 'lucide-vue-next';
import {Button} from '@/components/ui/button/index.js'
import {Textarea} from '@/components/ui/textarea/index.js'
import {useForm} from "@inertiajs/vue3";
import ProductCard from "@/components/products/ProductCard.vue";

const props = defineProps({
  id: Number,
  content: String,
})
const form = useForm({
  content: props.content,
})
const modalRef = ref(null)
const submit = () => form.put(route('cabinet.stock.update', props.id), {
  onSuccess: () => modalRef.value.close(),
  onError: (errors) => {
    console.log(errors)
  }
})

</script>

<template>
  <Modal max-width="md" ref="modalRef">
    <h2 class="mb-6 text-lg font-semibold tracking-tight text-pretty text-gray-900 sm:text-3xl">Новая позиция</h2>

    <form @submit.prevent="submit" class="flex flex-col gap-6">
      <div class="grid gap-6">

        <div class="grid gap-2">
          <Label for="content">Содержимое</Label>
          <Textarea id="content" v-model="form.content" />
          <InputError :message="form.errors.content"/>
        </div>

        <div>
          <Button type="submit" tabindex="6" :disabled="form.processing" class="cursor-pointer">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin"/>
            Создать
          </Button>
        </div>
      </div>
    </form>

  </Modal>
</template>

<style scoped>
</style>