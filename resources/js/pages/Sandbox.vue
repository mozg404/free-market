<script setup>
import Wrapper from "../components/core/Wrapper.vue";
import Headline from "@/components/core/Headline.vue";
import sandboxImage from "../../img/sandbox.jpg";
import {Link, useForm} from "@inertiajs/vue3";
import {Button} from "@/components/ui/button/index.js";
import {
  Card,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'
import PriceFormatter from "@/components/support/PriceFormatter.vue";

const props = defineProps({
  hash: String,
  amount: Number,
})

const form = useForm({
  hash: props.hash
})
</script>

<template>
  <Wrapper class="mt-10">

    <div class="flex items-center justify-center h-screen">
      <div class="p-8 text-center">
        <Headline class="pb-2">Тут должна быть касса</Headline>
        <div class="text-gray-500">Но это же демо проект ;)</div>

        <Card class="max-w-[300px] mx-auto my-10 shadow-none">
          <CardHeader>
            <CardTitle>К оплате: <PriceFormatter :value="amount"/></CardTitle>
          </CardHeader>
          <CardFooter class="flex justify-center">
            <Button @click="form.post(route('payments.success'))" class="mx-2 cursor-pointer" >Успех</Button>
            <Button @click="form.post(route('payments.fail'))" class="mx-2 cursor-pointer" variant="destructive">Успех</Button>
          </CardFooter>
        </Card>

        <div>
          <img :src="sandboxImage" alt="Просто изображение"/>
        </div>
      </div>
    </div>
  </Wrapper>
</template>

<script>
import AppLayout from "@/layouts/AppLayout.vue";

export default {
  layout: AppLayout,
}
</script>