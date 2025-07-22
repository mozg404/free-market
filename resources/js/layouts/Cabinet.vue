<script setup>

import {Link, usePage} from "@inertiajs/vue3";
import Main from "./Main.vue";
import {Button} from '@/components/ui/button'
import Wrapper from "@/components/Wrapper.vue";

const page = usePage();
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';


const items = [
  {
    name: 'Профиль',
    url: route('profile.show'),
    isCurrent: route().current('profile.show'),
  },
  {
    name: 'Мои товары',
    url: route('cabinet.products'),
    isCurrent: route().current('cabinet.products'),
  },
]


</script>

<template>
  <Main>

    <Wrapper>
      <div class="flex mt-10">
        <aside class="w-full max-w-[200px] mr-14">
          <nav class="flex flex-col gap-1 space-x-0 space-y-1">

            <template v-for="item in items">
              <Button :variant="item.isCurrent ? '' : 'ghost'" class="justify-start" as-child>
                <Link :href="item.url">{{ item.name }}</Link>
              </Button>
            </template>

          </nav>
        </aside>

        <section class="space-y-12 w-full">
          <slot />
        </section>
      </div>
    </Wrapper>


<!--    <div class="container mx-auto p-6">-->
<!--      <div class="flex align-top">-->
<!--        <div class="w-1/4 pr-8">-->
<!--          <div class="flex flex-col">-->
<!--            <Link href="/cabinet/profile"-->
<!--                  class="inline-flex align-middle items-center group whitespace-nowrap px-2.5 py-1.5 rounded-md transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600">-->
<!--              <span>Профиль</span>-->
<!--            </Link>-->
<!--            <Link href="/cabinet/profile"-->
<!--                  class="inline-flex align-middle items-center group whitespace-nowrap px-2.5 py-1.5 rounded-md transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600">-->
<!--              <span>Мои заказы</span>-->
<!--            </Link>-->

<!--            <div class="font-bold text-xs text-gray-400 mt-4">Магазины</div>-->
<!--          </div>-->
<!--        </div>-->

<!--        <div class="w-full">-->
<!--          <slot/>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

  </Main>
</template>

<style scoped>

</style>
