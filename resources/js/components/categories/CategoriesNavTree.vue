<script setup>
import CategoriesNavTreeItem from "@/components/categories/CategoriesNavTreeItem.vue";
import { Dot } from 'lucide-vue-next';
import {Link} from "@inertiajs/vue3";
import {cn} from "@/lib/utils.js";

const props = defineProps({
  categories: {
    type: Array,
    required: true,
  },
  level: {
    type: Number,
    default: 0,
  }
})

</script>

<template>
  <div v-for="category in categories" :key="category.id">
    <template v-if="level > 0">
      <Link
        :href="route('catalog.category', category.slug)"
        :class="cn(
        'flex items-center -mx-[0.5rem] px-0 my-1 whitespace-nowrap rounded-md text-sm text-muted-foreground transition-colors hover:text-accent-foreground h-8 py-1 cursor-pointer',
                route().current('catalog.category', category.slug) ? 'text-primary hover:text-primary' : ''
          )"
      >
        <Dot :class="cn('h-8 w-8', route().current('catalog.category', category.slug) ? 'text-primary' : '')" /> {{ category.name }}
      </Link>
    </template>

    <template v-else-if="category.children?.length > 0">
      <CategoriesNavTreeItem :category="category">
        <CategoriesNavTree :categories="category.children" :level="level + 1"/>
      </CategoriesNavTreeItem>
    </template>

    <Link
      v-else
      :href="route('catalog.category', category.slug)"
      :class="cn(
        'flex items-center justify-between -mx-[1rem] my-1 whitespace-nowrap rounded-md text-sm font-medium outline-none',
        'transition-colors hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 cursor-pointer',
        route().current('catalog.category', category.slug) ? 'bg-accent text-accent-foreground' : ''
      )"
    >
      <span>{{ category.name }}</span>
    </Link>
  </div>
</template>