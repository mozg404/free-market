<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import {Link} from "@inertiajs/vue3";
import {ModalLink} from "@inertiaui/modal-vue";
import {storageUrl} from "../../../func.js";
import Heading from "@/components/Heading.vue";

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card/index.js'
import {PlusIcon, PenIcon} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";

defineProps({
  shops: Array,
})

</script>

<template>
  <Cabinet>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Мои магазины"/>
      <div class="text-right">
        <Button variant="outline" class="rounded-3xl" as-child>
          <ModalLink :href="route('cabinet.shops.create')">
            <PlusIcon class="w-4 h-4"/> Создать магазин
          </ModalLink>
        </Button>
        <!--        <ModalLink :href="route('cabinet.shops.create')" class="block w-full p-3.5 mt-3 rounded-xl text-center font-bold text-sm bg-gray-200 transition-colors text-gray-600 hover:bg-green-400 hover:text-gray-800">Создать магазин</ModalLink>-->
      </div>
    </div>

    <template v-if="shops">
      <Card v-for="shop in shops" :key="shop.id">
        <CardHeader>
          <CardTitle>{{ shop.name }}</CardTitle>
          <CardDescription>{{ shop.description }}</CardDescription>
        </CardHeader>
        <CardFooter>
          <Button variant="outline" class="rounded-3xl" as-child>
            <ModalLink :href="route('cabinet.shops.edit', shop.id)">
              <PenIcon class="w-4 h-4"/> Изменить
            </ModalLink>
          </Button>
        </CardFooter>
      </Card>
    </template>

  </Cabinet>
</template>

<style scoped>

</style>
