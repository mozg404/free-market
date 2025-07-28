<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import MainLayout from "./MainLayout.vue";
import {Button} from '@/components/ui/button'
import Wrapper from "@/components/core/Wrapper.vue";

const page = usePage();
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
const items = [
  {
    name: 'Профиль',
    url: route('profile.show'),
    isCurrent: route().current('profile.show'),
  },
  {
    name: 'Мои заказы',
    url: route('cabinet.orders'),
    isCurrent: route().current('cabinet.orders'),
  },
  {
    name: 'Мои покупки',
    url: route('cabinet.purchases'),
    isCurrent: route().current('cabinet.purchases'),
  },
  {
    name: 'Мои товары',
    url: route('cabinet.products'),
    isCurrent: route().current('cabinet.products'),
  },
  {
    name: 'Мои продажи',
    url: route('cabinet.sales'),
    isCurrent: route().current('cabinet.sales'),
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

  </MainLayout>
</template>

<style scoped>

</style>
