<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import Wrapper from "./core/layout/Wrapper.vue";
import logoUrl from "./../../img/logo.svg";
import {Button} from "@/components/ui/button/index.js";
import {User2Icon,ShoppingCart,LogIn,RussianRuble} from 'lucide-vue-next'
import { useCart } from '@/composables/useCart'

const page = usePage()
const isAuth = computed(() => page.props.isAuth)
const user = computed(() => page.props.user)
const { cart } = useCart()


</script>

<template>
  <header class="border-b-1">
    <Wrapper>
      <div class="flex items-center justify-between py-5" aria-label="Global">

        <div class="flex items-center gap-x-6">
          <Link href="/" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
            <img class="h-14 w-auto" :src="logoUrl" alt="">
          </Link>

          <div class="hidden lg:flex lg:gap-x-2">
            <Button variant="ghost" class="rounded-3xl" as-child>
              <Link href="/">Главная</Link>
            </Button>

            <Button variant="ghost" class="rounded-3xl" as-child>
              <Link href="/catalog">Каталог</Link>
            </Button>
          </div>
        </div>

        <div class="flex lg:hidden">
          <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
            <span class="sr-only">Open main menu</span>
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
          </button>
        </div>

        <div class="hidden lg:flex lg:flex-1 lg:justify-end">

          <div class="flex gap-2">
            <Button variant="outline" class="rounded-3xl" v-if="isAuth" as-child>
              <Link :href="route('balance')">
                <RussianRuble class="w-4 h-4"/> {{ user.balance }}
              </Link>
            </Button>

            <Button variant="outline" class="rounded-3xl" as-child>
              <Link :href="route('cart.index')">
                <ShoppingCart class="w-4 h-4"/> {{ cart.quantity }}
              </Link>
            </Button>

            <Button variant="outline" class="rounded-3xl" v-if="isAuth" as-child>
              <Link href="/cabinet/profile">
                <User2Icon class="w-4 h-4"/> {{ user.name }}
              </Link>
            </Button>
            <Button variant="outline" class="rounded-3xl" v-else as-child>
              <Link :href="route('login')">
                <LogIn class="w-4 h-4"/> Вход
              </Link>
            </Button>
          </div>
        </div>
      </div>

    </Wrapper>
  </header>
</template>

<style scoped>

</style>
