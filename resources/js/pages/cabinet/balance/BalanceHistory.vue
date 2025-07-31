<script setup>
import CabinetLayout from "@/layouts/CabinetLayout.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, Settings, Trash2, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";

const props = defineProps({
  transactions: Array,
})
</script>

<template>
  <CabinetLayout>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Баланс счета"/>

      <div class="text-right">
        <Button variant="outline" class="rounded-3xl" as-child>
          <ModalLink :href="route('cabinet.balance.deposit')">
            <PlusIcon class="w-4 h-4"/> Пополнить
          </ModalLink>
        </Button>
      </div>
    </div>

    <div class="p-4 border-2" v-for="transaction in transactions" :key="transaction.id">
      id: {{transaction.id}} <br>
      Тип: {{transaction.type}} <br>
      Сумма: {{transaction.amount}}
    </div>
  </CabinetLayout>
</template>

<style scoped>

</style>
