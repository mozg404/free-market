<script setup>
import MainLayout from "@/layouts/MainLayout.vue";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import {Link, useForm, usePage} from '@inertiajs/vue3'
import {computed} from "vue";
import {Button} from "@/components/ui/button/index.js";

const page = usePage()
const props = defineProps({
  cart: Object,
})
const form = useForm({})
</script>

<template>
    <MainLayout>
        <Wrapper class="mt-10">
          Оформление заказа

          <Button  class="rounded-3xl" as-child>
            <Link method="post" :href="route('checkout.store')">
              Оплатить
            </Link>
          </Button>

        </Wrapper>
    </MainLayout>
</template>
