<script setup>
import Cabinet from "@/layouts/Cabinet.vue";
import {ModalLink} from "@inertiaui/modal-vue";
import Heading from "@/components/Heading.vue";
import {PlusIcon, Settings, Trash2, Search} from "lucide-vue-next";
import {Button} from "@/components/ui/button/index.js";
import { ref, watch } from 'vue';
import {Badge} from '@/components/ui/badge'
import ConfirmDeleteDialog from "@/components/ConfirmDeleteDialog.vue";

const props = defineProps({
  product: Object,
  stockItems: Array,
})
</script>

<template>
  <Cabinet>
    <div class="flex align-middle justify-between m-0">
      <Heading title="Позиции товара"/>

      <div class="text-right">
        <Button variant="outline" class="rounded-3xl" as-child>
          <ModalLink :href="route('cabinet.stock.create', product.id)">
            <PlusIcon class="w-4 h-4"/> Добавить позицию
          </ModalLink>
        </Button>
      </div>
    </div>

    <div>{{product.name}}</div>

    <div class="p-4 border-2" v-for="item in stockItems" :key="item.id">
      id: {{item.id}} <br>

      Статус:
      <Badge v-if="item.isAvailable">Доступен</Badge>
      <Badge v-else-if="item.isReserved" variant="secondary">Зарезервирован</Badge>
      <Badge v-else variant="destructive">Куплен</Badge>
      <br>

      Содержимое: {{item.content}}<br><br>

      <div class="grid grid-cols-2 gap-2 w-[50px]">
        <ModalLink :href="route('cabinet.stock.edit', item.id)">
          <Settings class="w-4 h-4"/>
        </ModalLink>
        <ConfirmDeleteDialog
          :route="route('cabinet.stock.destroy', item.id)"
          title="Удалить позицию?"
          description="Вы уверены, что хотите удалить эту позицию? Это действие нельзя отменить."
        >
          <Trash2 class="w-4 h-4 cursor-pointer"/>
        </ConfirmDeleteDialog>
      </div>
    </div>

  </Cabinet>
</template>

<style scoped>

</style>
