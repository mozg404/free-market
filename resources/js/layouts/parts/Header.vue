<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import logoUrl from "../../../img/logo.svg";
import {Button} from "@/components/ui/button/index.js";
import {User2Icon, ShoppingCart, LogIn, RussianRuble} from 'lucide-vue-next'
import {useCart} from '@/composables/useCart.js'
import {useUser} from '@/composables/useUser.js'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu/index.js'

const {cart} = useCart()
const {user, isAuth} = useUser()
</script>

<template>
  <header class="border-b-1">
    <Wrapper>
      <div class="flex items-center justify-between py-5" aria-label="Global">

        <div class="flex items-center gap-x-6">
          <Link href="/public" class="-m-1.5 p-1.5">
            <span class="sr-only">Your Company</span>
            <img class="h-14 w-auto" :src="logoUrl" alt="">
          </Link>

          <div class="hidden lg:flex lg:gap-x-2">
            <Button variant="ghost" class="rounded-3xl" as-child>
              <Link href="/public">Главная</Link>
            </Button>

            <Button variant="ghost" class="rounded-3xl" as-child>
              <Link href="/catalog">Каталог</Link>
            </Button>
          </div>
        </div>

        <div class="flex lg:hidden">
          <button type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
            <span class="sr-only">Open main menu</span>
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                 aria-hidden="true" data-slot="icon">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
          </button>
        </div>

        <div class="hidden lg:flex lg:flex-1 lg:justify-end">

          <div class="flex gap-2">
            <Button variant="outline" class="rounded-3xl" v-if="isAuth" as-child>
              <Link :href="route('balance')">
                <RussianRuble class="w-4 h-4"/>
                {{ user.balance }}
              </Link>
            </Button>

            <Button variant="outline" class="rounded-3xl" as-child>
              <Link :href="route('cart.index')">
                <ShoppingCart class="w-4 h-4"/>
                {{ cart.quantity }}
              </Link>
            </Button>

            <template v-if="isAuth">
              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <Button variant="outline" size="icon" class="rounded-3xl">
                    <User2Icon class="w-4 h-4"/>
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-44">
                  <DropdownMenuLabel>
                    {{ user.name }}
                    <div class="font-normal text-muted-foreground">{{ user.email }}</div>
                  </DropdownMenuLabel>
                  <DropdownMenuSeparator/>
                  <DropdownMenuItem>
                    <Link :href="route('my.profile')" class="block w-full h-full">Профиль</Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem>
                    <Link :href="route('balance')" class="block w-full h-full">Баланс</Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem>
                    <Link :href="route('orders')" class="block w-full h-full">Покупки</Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem>
                    <Link :href="route('my.products')" class="block w-full h-full">Товары</Link>
                  </DropdownMenuItem>
                  <DropdownMenuSeparator/>
                  <DropdownMenuItem>
                    <Link :href="route('logout')" class="block w-full h-full">Выйти</Link>
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </template>
            <Button variant="outline" class="rounded-3xl" v-else as-child>
              <Link :href="route('login')">
                <LogIn class="w-4 h-4"/>
                Вход
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
