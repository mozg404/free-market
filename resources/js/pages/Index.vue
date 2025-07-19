<script setup>
import Main from "@/layouts/Main.vue";
import { Link } from "@inertiajs/vue3";
import Wrapper from "../components/Wrapper.vue";
import { Button } from '@/components/ui/button'

defineProps({
    products: Array,
})
</script>

<template>
    <Main>
        <Wrapper class="mt-10">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Customers also purchased</h2>

            <Button type="button" class="rounded-xl">Кнопка</Button>

            <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">

                <div class="group relative" v-for="product in products" :key="product.id">
                    <img :src="product.imageUrl" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
                    <div class="mt-4 flex justify-between">
                        <div>
                            <h3 class="text-sm text-gray-700">
                                <Link :href="product.url">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ product.name }}
                                </Link>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Black</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">${{ product.price }}</p>
                    </div>
                </div>

            </div>
        </Wrapper>

    </Main>
</template>
