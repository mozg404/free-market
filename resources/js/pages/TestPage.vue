<script setup>
import {
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
} from "@/components/ui/sidebar";

import { Calendar, Home, Inbox, Search, Settings } from "lucide-vue-next"
import Wrapper from "@/components/core/Wrapper.vue";

// Menu items.
const items = [
  {
    title: "Главная",
    url: "#",
    icon: Home,
  },
  {
    title: "Мои продажи",
    url: "#",
    icon: Inbox,
  },
  {
    title: "Мои покупки",
    url: "#",
    icon: Calendar,
  },
  {
    title: "Поиск по истекшим товарам",
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

  <Wrapper class="mt-10 flex relative">




    <SidebarProvider>
      <Sidebar class="sticky top-0 h-screen w-64 bg-sidebar z-10">
        <SidebarContent>
          <SidebarGroup>
            <SidebarGroupLabel>Покупатель</SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                  <SidebarMenuButton asChild>
                    <a :href="item.url">
                      <component :is="item.icon" />
                      <span>{{item.title}}</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>

          <SidebarGroup>
            <SidebarGroupLabel>Продажник</SidebarGroupLabel>
            <SidebarGroupContent>
              <SidebarMenu>
                <SidebarMenuItem v-for="item in items" :key="item.title">
                  <SidebarMenuButton asChild>
                    <a :href="item.url">
                      <component :is="item.icon" />
                      <span>{{item.title}}</span>
                    </a>
                  </SidebarMenuButton>
                </SidebarMenuItem>
              </SidebarMenu>
            </SidebarGroupContent>
          </SidebarGroup>
        </SidebarContent>
      </Sidebar>
      <main>
        <SidebarTrigger />
        тут вот контент
      </main>
    </SidebarProvider>






  </Wrapper>
</template>

<script>
import MainLayout from "@/layouts/MainLayout.vue";

export default {
  layout: MainLayout,
}
</script>
