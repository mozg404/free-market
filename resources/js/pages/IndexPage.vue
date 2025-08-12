<script setup>
import Wrapper from "@/components/shared/layout/Wrapper.vue";
import ProductList from "@/components/products/ProductList.vue";
import { Calendar, Home, Inbox, Search, Settings } from "lucide-vue-next"

import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb'
import { Separator } from '@/components/ui/separator'
import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar'

defineProps({
  products: Array,
})

const items = [
  {
    title: "Home",
    url: "#",
    icon: Home,
  },
  {
    title: "Inbox",
    url: "#",
    icon: Inbox,
  },
  {
    title: "Calendar",
    url: "#",
    icon: Calendar,
  },
  {
    title: "Search",
    url: "#",
    icon: Search,
  },
  {
    title: "Settings",
    url: "#",
    icon: Settings,
  },
];
</script>

<template>
  <Wrapper class="mt-10">
    <ProductList :products="products" />
  </Wrapper>
</template>

<script>
import MainLayout from "@/layouts/MainLayout.vue";
export const iframeHeight = "800px"
export const description = "A sidebar with submenus."
export default {
  layout: MainLayout,
}
</script>