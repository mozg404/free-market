<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import MainLayout from "./MainLayout.vue";
import {Button} from '@/components/ui/button'
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import Icon from "@/components/shared/Icon.vue";

const page = usePage();
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
const items = [
  {
    name: 'Профиль',
    url: route('profile.show'),
    isCurrent: route().current('profile.show'),
    icon: 'UserPen',
  },
  // {
  //   name: 'Мой баланс',
  //   url: route('cabinet.balance'),
  //   isCurrent: route().current('cabinet.balance'),
  //   icon: 'Wallet',
  // },
  // {
  //   name: 'Мои заказы',
  //   url: route('cabinet.orders'),
  //   isCurrent: route().current('cabinet.orders'),
  //   icon: 'ShoppingCart',
  // },
  {
    name: 'Мои покупки',
    url: route('cabinet.purchases'),
    isCurrent: route().current('cabinet.purchases'),
    icon: 'UserPen',
  },
  {
    name: 'Мои товары',
    url: route('cabinet.products'),
    isCurrent: route().current('cabinet.products'),
    icon: 'ShoppingBasket',
  },
  {
    name: 'Мои продажи',
    url: route('cabinet.sales'),
    isCurrent: route().current('cabinet.sales'),
    icon: 'UserPen',
  },
]
</script>

<template>
  <MainLayout>

    <Wrapper>
      <div class="flex mt-10">
        <aside class="w-full max-w-[200px] mr-14">
          <nav class="flex flex-col gap-1 space-x-0 space-y-1">

            <template v-for="item in items">

              <template v-if="item.isCurrent">
                <div class="flex items-center py-3 px-4 text-sm rounded-3xl bg-green-600 text-white transition-colors">
                  <Icon stroke-width="2" :name="item.icon" class="mr-3 font-black" />
                  <span class="">{{ item.name }}</span>
                </div>
              </template>
              <template v-else>
                <Link :href="item.url" class="flex items-center text-sm py-3 px-4 rounded-3xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                  <Icon stroke-width="2" :name="item.icon" class="mr-3" />
                  <span class="">{{ item.name }}</span>
                </Link>
              </template>
            </template>

          </nav>
        </aside>

        <div class="space-y-12 w-full">
          <slot />
        </div>
      </div>
    </Wrapper>

  </MainLayout>
</template>

