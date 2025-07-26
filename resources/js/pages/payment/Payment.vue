<script setup>
import Main from "@/layouts/Main.vue";
import Wrapper from "@/components/Wrapper.vue";
import {Link, useForm, usePage} from '@inertiajs/vue3'
import {computed} from "vue";
import {Button} from "@/components/ui/button/index.js";

const props = defineProps({
  order: Object,
})
const form = useForm({})
</script>

<template>
    <Main>
        <Wrapper class="mt-10">
          <div>Тут должен быть функционал пополнения счета</div>

          <Button  class="rounded-3xl" as-child>
            <Link method="post" :href="route('order_checkout.pay', order.id)">
              Оплатить
            </Link>
          </Button>

        </Wrapper>
    </Main>
</template>
