<script setup>
import Main from "@/layouts/Main.vue";
import Wrapper from "@/components/Wrapper.vue";
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
    <Main>
        <Wrapper class="mt-10">
          Оформление заказа

          <Button  class="rounded-3xl" as-child>
            <Link method="post" :href="route('checkout.store')">
              Оплатить
            </Link>
          </Button>

        </Wrapper>
    </Main>
</template>
