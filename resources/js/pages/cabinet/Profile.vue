<script setup>
import {Link} from '@inertiajs/vue3'
import Cabinet from "@/layouts/Cabinet.vue";
import Heading from "@/components/Heading.vue";

defineProps({
  id: String,
  name: String,
  email: String,
  createdAt: String,
  updatedAt: String,
})
</script>

<template>
  <Cabinet>
    <Heading title="Профиль" />

        <div class="flow-root">
            <dl class="-my-3 divide-y divide-gray-200 text-sm">
                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">ID</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ id }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">Имя</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ name }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">Email</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ email }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">Создан</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ createdAt }}</dd>
                </div>

                <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-3 sm:gap-4">
                    <dt class="font-medium text-gray-900">Обновлен</dt>
                    <dd class="text-gray-700 sm:col-span-2">{{ updatedAt }}</dd>
                </div>
            </dl>
        </div>

        <div><Link href="/logout">Выйти</Link></div>
    </Cabinet>
</template>
